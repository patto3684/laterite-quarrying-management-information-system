<?php
class DashboardController extends Controller {
    public function index(): void {
        $this->requireAccess('dashboard');
        $userModel = new User();
        $quarryModel = new Quarry();
        $productionModel = new Production();
        $stockModel = new Stock();
        $financeModel = new Finance();
        $inspectionModel = new Inspection();
        $activityModel = new Activity();

        $role = strtolower($_SESSION['role'] ?? 'quarry_officer');

        if ($role === 'administrator') {
            $pdo = getConnection();

            // Users active vs inactive
            try {
                // Ensure is_active column exists; if not, treat all as active
                $hasIsActive = false;
                $cols = $pdo->query("SHOW COLUMNS FROM users")->fetchAll(PDO::FETCH_COLUMN);
                foreach ($cols as $c) { if ($c === 'is_active') { $hasIsActive = true; break; } }
                if ($hasIsActive) {
                    $activeCount = (int)$pdo->query('SELECT COUNT(*) FROM users WHERE is_active = 1')->fetchColumn();
                    $inactiveCount = (int)$pdo->query('SELECT COUNT(*) FROM users WHERE is_active = 0')->fetchColumn();
                } else {
                    $activeCount = (int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
                    $inactiveCount = 0;
                }
            } catch (Exception $e) {
                $activeCount = $userModel->count();
                $inactiveCount = 0;
            }

            // System health: DB connection ok, uptime, last backup
            $dbOk = true;
            $uptime = null;
            $lastBackup = null;
            try {
                $row = $pdo->query("SHOW GLOBAL STATUS LIKE 'Uptime'")->fetch(PDO::FETCH_ASSOC);
                if ($row) { $uptime = (int)$row['Value']; }
            } catch (Exception $e) {
                $dbOk = false;
            }

            $backupDir = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'backups';
            $backupSize = 0;
            $lastBackupTime = null;
            if (is_dir($backupDir)) {
                $files = array_filter(scandir($backupDir), function($f){ return $f[0] !== '.'; });
                foreach ($files as $f) {
                    $path = $backupDir . DIRECTORY_SEPARATOR . $f;
                    if (is_file($path)) {
                        $backupSize += filesize($path);
                        $mtime = filemtime($path);
                        if ($lastBackupTime === null || $mtime > $lastBackupTime) {
                            $lastBackupTime = $mtime;
                        }
                    }
                }
                if ($lastBackupTime) { $lastBackup = date('c', $lastBackupTime); }
            }

            // Daily system activity count (from activities or audit_logs)
            $todayCount = 0;
            try {
                // prefer audit_logs table if exists
                $tableExists = false;
                $tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
                foreach ($tables as $t) { if ($t === 'audit_logs') { $tableExists = true; break; } }
                if ($tableExists) {
                    $stmt = $pdo->prepare('SELECT COUNT(*) FROM audit_logs WHERE DATE(created_at) = CURDATE()');
                    $stmt->execute();
                    $todayCount = (int)$stmt->fetchColumn();

                    $columns = $pdo->query('SHOW COLUMNS FROM audit_logs')->fetchAll(PDO::FETCH_COLUMN);
                    $hasUserId = in_array('user_id', $columns, true);
                    if ($hasUserId) {
                        $recentActivities = $pdo->query(
                            'SELECT logs.*, COALESCE(u.full_name, u.role, u.email, logs.user_id) AS operator_name '
                            . 'FROM audit_logs logs '
                            . 'LEFT JOIN users u ON logs.user_id = u.id '
                            . 'ORDER BY logs.created_at DESC LIMIT 50'
                        )->fetchAll(PDO::FETCH_ASSOC);
                    } else {
                        $recentActivities = $pdo->query('SELECT *, NULL AS operator_name FROM audit_logs ORDER BY created_at DESC LIMIT 50')->fetchAll(PDO::FETCH_ASSOC);
                    }
                } else {
                    $stmt = $pdo->prepare('SELECT COUNT(*) FROM activities WHERE DATE(created_at) = CURDATE()');
                    $stmt->execute();
                    $todayCount = (int)$stmt->fetchColumn();
                    $recentActivities = $activityModel->latest();
                }
            } catch (Exception $e) {
                $todayCount = 0;
                $recentActivities = $activityModel->latest();
            }

            // Quick metrics: active sessions, failed login attempts, pending activations
            $activeSessions = 0;
            $failedLogins = 0;
            $pendingActivations = 0;
            try {
                // count PHP session files modified in last 30 minutes
                $sessPath = ini_get('session.save_path') ?: sys_get_temp_dir();
                if (is_dir($sessPath)) {
                    $files = glob(rtrim($sessPath, '\\/') . DIRECTORY_SEPARATOR . 'sess_*');
                    $cutoff = time() - (30 * 60);
                    foreach ($files as $f) {
                        if (filemtime($f) >= $cutoff) $activeSessions++;
                    }
                }
                // failed logins from activities
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM activities WHERE (title LIKE '%fail%' OR detail LIKE '%fail%') AND DATE(created_at) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)");
                $stmt->execute();
                $failedLogins = (int)$stmt->fetchColumn();

                // pending activations
                if ($hasIsActive) {
                    $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE is_active = 0');
                    $stmt->execute();
                    $pendingActivations = (int)$stmt->fetchColumn();
                }
            } catch (Exception $e) {
                // ignore
            }

            $data = [
                'role' => $_SESSION['role'] ?? 'administrator',
                'adminStats' => [
                    'total_users_active' => $activeCount,
                    'total_users_inactive' => $inactiveCount,
                    'db_ok' => $dbOk,
                    'uptime_seconds' => $uptime,
                    'last_backup' => $lastBackup,
                    'backup_volume_bytes' => $backupSize,
                    'daily_activity_count' => $todayCount,
                ],
                'recentActivities' => $recentActivities,
                'quickMetrics' => [
                    'active_sessions' => $activeSessions,
                    'failed_logins' => $failedLogins,
                    'pending_activations' => $pendingActivations,
                ]
            ];

            $this->view('dashboard/index', $data);
            return;
        }

        $data = [
            'stats' => [
                'quarries' => $quarryModel->count(),
                'production' => $productionModel->totalProduced(),
                'stock' => $stockModel->availableStock(),
                'revenue' => $financeModel->totalRevenue(),
                'expenses' => $financeModel->totalExpenses(),
                'pending_inspections' => $inspectionModel->pendingCount(),
                'completed_inspections' => $inspectionModel->completedCount(),
                'users' => $userModel->count(),
            ],
            'activities' => $activityModel->latest(),
            'role' => $_SESSION['role'] ?? 'quarry_officer',
        ];
        $this->view('dashboard/index', $data);
    }
}
