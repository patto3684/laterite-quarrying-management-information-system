<?php
class SettingsController extends Controller {
    public function index(): void {
        $this->requireAccess('settings');

        $userModel = new User();
        $activity = new Activity();

        // Ensure support tables exist for system settings, api keys, and audit logs
        $pdo = getConnection();
        $pdo->exec("CREATE TABLE IF NOT EXISTS system_settings (
            `k` VARCHAR(100) PRIMARY KEY,
            `v` TEXT DEFAULT NULL,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        $pdo->exec("CREATE TABLE IF NOT EXISTS api_keys (
            id INT AUTO_INCREMENT PRIMARY KEY,
            service VARCHAR(150) NOT NULL,
            endpoint VARCHAR(255) DEFAULT NULL,
            api_key TEXT DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        $pdo->exec("CREATE TABLE IF NOT EXISTS audit_logs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT DEFAULT NULL,
            action VARCHAR(100) NOT NULL,
            target_table VARCHAR(100) DEFAULT NULL,
            details TEXT DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        try {
            $pdo->exec("ALTER TABLE users ADD COLUMN is_active TINYINT(1) NOT NULL DEFAULT 1");
        } catch (Exception $e) {
            // if column exists, ignore
        }

        // Load users and audit logs
        $users = $userModel->allActive();
        $audits = [];
        $tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
        if (in_array('audit_logs', $tables, true)) {
            $audits = $pdo->query(
                'SELECT logs.*, COALESCE(u.full_name, u.role, u.email, logs.user_id) AS user_name, u.role AS user_role '
                . 'FROM audit_logs AS logs '
                . 'LEFT JOIN users AS u ON logs.user_id = u.id '
                . 'ORDER BY logs.created_at DESC LIMIT 200'
            )->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $stmt = $pdo->query('SELECT id, title AS action, detail AS details, created_at, NULL AS target_table, NULL AS user_name, NULL AS user_role FROM activities ORDER BY created_at DESC LIMIT 200');
            $audits = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // load settings into associative array
        $settingsStmt = $pdo->query('SELECT k, v FROM system_settings');
        $settingsRaw = $settingsStmt->fetchAll(PDO::FETCH_ASSOC);
        $settings = [];
        foreach ($settingsRaw as $s) { $settings[$s['k']] = $s['v']; }

        // load api keys
        $apiStmt = $pdo->query('SELECT id, service, endpoint, api_key, created_at FROM api_keys ORDER BY created_at DESC');
        $apiKeys = $apiStmt->fetchAll(PDO::FETCH_ASSOC);

        $this->view('settings/index', [
            'users' => $users,
            'audits' => $audits,
            'settings' => $settings,
            'api_keys' => $apiKeys
        ]);
    }

    // Create a new user from the settings UI
    public function createUser(): void {
        $this->requireAccess('users');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $full = trim($_POST['full_name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $role = trim($_POST['role'] ?? 'quarry_officer');
            $userModel = new User();

            if (!$full || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['notice'] = 'Please provide a valid name and email address.';
                $this->redirect('settings');
            }

            if ($userModel->findByEmail($email)) {
                $_SESSION['notice'] = 'Email already exists. Please use a different address.';
                $this->redirect('settings');
            }

            $password = bin2hex(random_bytes(4));
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $userModel->create(['full_name' => $full, 'email' => $email, 'password' => $hashed, 'role' => $role]);
            $_SESSION['notice'] = 'User created successfully. Temporary password: ' . $password;
        }
        $this->redirect('settings');
    }

    // Soft-deactivate user
    public function deactivateUser(): void {
        $this->requireAccess('users');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0) {
                $pdo = getConnection();
                try {
                    $pdo->exec("ALTER TABLE users ADD COLUMN is_active TINYINT(1) NOT NULL DEFAULT 1");
                } catch (Exception $e) {
                    // column likely exists; ignore
                }
                $userModel = new User();
                $userModel->deactivate($id);
                $_SESSION['notice'] = 'User deactivated.';
            }
        }
        $this->redirect('settings');
    }

    // Save core configuration settings
    public function saveSettings(): void {
        $this->requireAccess('settings');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $defaultCurrency = trim($_POST['default_currency'] ?? '');
            $baseUnit = trim($_POST['base_unit_weight'] ?? '');
            $defaultVat = trim($_POST['default_vat'] ?? '');
            $sessionTimeout = trim($_POST['session_timeout'] ?? '');
            $backupSchedule = trim($_POST['backup_schedule'] ?? '');
            $backupPath = trim($_POST['backup_path'] ?? '');

            // Server-side validation: mirror client constraints
            $errors = [];

            // Currency: letters only, 1-3 chars
            $defaultCurrency = strtoupper($defaultCurrency);
            if (!preg_match('/^[A-Z]{1,3}$/', $defaultCurrency)) {
                $errors['default_currency'] = 'Currency must be 1 to 3 letters (A-Z).';
            }

            // Weight unit: letters only, max 10
            if (!preg_match('/^[A-Za-z]{1,10}$/', $baseUnit)) {
                $errors['base_unit_weight'] = 'Weight unit must contain only letters, maximum 10 characters.';
            }

            // VAT: integer only and strictly between 0 and 100
            if (!ctype_digit((string)$defaultVat) || !((int)$defaultVat > 0 && (int)$defaultVat < 100)) {
                $errors['default_vat'] = 'VAT must be an integer greater than 0 and less than 100.';
            }

            // Session timeout: integer between 5 and 1440
            if (!ctype_digit((string)$sessionTimeout) || !((int)$sessionTimeout >= 5 && (int)$sessionTimeout <= 1440)) {
                $errors['session_timeout'] = 'Session timeout must be a whole number between 5 and 1440.';
            }

            // Backup path: disallow invalid chars
            if ($backupPath !== '' && preg_match('/[<>|?*]/', $backupPath)) {
                $errors['backup_path'] = 'Backup path contains invalid characters: < > | ? *';
            }

            if (!empty($errors)) {
                $_SESSION['form_errors'] = $errors;
                $_SESSION['form_old'] = [
                    'default_currency' => $defaultCurrency,
                    'base_unit_weight' => $baseUnit,
                    'default_vat' => $defaultVat,
                    'session_timeout' => $sessionTimeout,
                    'backup_schedule' => $backupSchedule,
                    'backup_path' => $backupPath,
                ];
                $this->redirect('settings');
            }

            $allowedSchedules = ['daily', 'weekly', 'monthly'];
            if (!in_array($backupSchedule, $allowedSchedules, true)) {
                $_SESSION['notice'] = 'Invalid backup schedule selected.';
                $this->redirect('settings');
            }

            $pdo = getConnection();
            $upsert = $pdo->prepare('REPLACE INTO system_settings (k, v) VALUES (:k, :v)');
            $upsert->execute([':k' => 'default_currency', ':v' => $defaultCurrency]);
            $upsert->execute([':k' => 'base_unit_weight', ':v' => $baseUnit]);
            $upsert->execute([':k' => 'default_vat', ':v' => $defaultVat]);
            $upsert->execute([':k' => 'session_timeout', ':v' => $sessionTimeout]);
            $upsert->execute([':k' => 'backup_schedule', ':v' => $backupSchedule]);
            $upsert->execute([':k' => 'backup_path', ':v' => $backupPath]);
            $_SESSION['notice'] = 'Settings saved successfully.';
        }
        $this->redirect('settings');
    }

    // Save API key record
    public function saveApiKey(): void {
        $this->requireAccess('settings');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $service = trim($_POST['service'] ?? '');
            $endpoint = trim($_POST['endpoint'] ?? '');
            $api_key = trim($_POST['api_key'] ?? '');
            if ($service) {
                $pdo = getConnection();
                $stmt = $pdo->prepare('INSERT INTO api_keys (service, endpoint, api_key) VALUES (:service, :endpoint, :api_key)');
                $stmt->execute([':service' => $service, ':endpoint' => $endpoint, ':api_key' => $api_key]);
                $_SESSION['notice'] = 'API key saved.';
            }
        }
        $this->redirect('settings');
    }

    public function users(): void {
        $this->requireAccess('users');
        $userModel = new User();
        $this->view('settings/users', ['users' => $userModel->all()]);
    }

    public function resetPassword(): void {
        $this->requireAccess('users');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            $newPassword = trim($_POST['new_password'] ?? '');
            if ($id > 0) {
                if ($newPassword === '') {
                    $newPassword = bin2hex(random_bytes(4));
                    $message = 'Temporary password: ' . $newPassword;
                } else {
                    $message = 'Password updated successfully.';
                }
                $userModel = new User();
                $userModel->update($id, ['password' => $newPassword]);
                $_SESSION['notice'] = 'Password reset. ' . $message;
            }
        }
        $this->redirect('settings');
    }

    public function audit(): void {
        $this->requireAccess('settings');
        $activity = new Activity();
        $this->view('settings/audit', ['activities' => $activity->latest()]);
    }

    public function export(): void {
        $this->requireAccess('settings');
        // Simple CSV export for key tables (users and activities) for admins
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="lqsmis_export_' . date('Ymd') . '.csv"');
        $out = fopen('php://output', 'w');
        // users
        fputcsv($out, ['-- USERS --']);
        $userModel = new User();
        $users = $userModel->all();
        fputcsv($out, array_keys($users[0] ?? ['id','full_name','email','role','created_at']));
        foreach ($users as $u) {
            fputcsv($out, $u);
        }
        // activities
        fputcsv($out, ['']);
        fputcsv($out, ['-- ACTIVITIES --']);
        $activity = new Activity();
        $acts = $activity->latest();
        fputcsv($out, array_keys($acts[0] ?? ['id','title','detail','created_at']));
        foreach ($acts as $a) {
            fputcsv($out, $a);
        }
        fclose($out);
        exit;
    }

    public function backup(): void {
        $this->requireAccess('settings');
        $filenameBase = 'lqsmis_backup_' . date('Ymd_His');
        $filenameSql = $filenameBase . '.sql';
        $filenameZip = $filenameBase . '.zip';

        $projectRoot = dirname(__DIR__, 2);
        $backupDir = $projectRoot . DIRECTORY_SEPARATOR . 'backups';
        if (!is_dir($backupDir)) {
            @mkdir($backupDir, 0755, true);
        }
        $filePath = $backupDir . DIRECTORY_SEPARATOR . $filenameSql;

        // Try mysqldump first for a full consistent dump
        $mysqldump = trim(@shell_exec('where mysqldump 2>NUL')) ?: trim(@shell_exec('which mysqldump 2>/dev/null'));
        if ($mysqldump) {
            $user = 'root';
            $password = '';
            $host = '127.0.0.1';
            $port = '3306';
            $cmd = escapeshellcmd($mysqldump) . " -h " . escapeshellarg($host) . " -P " . escapeshellarg($port) . " -u " . escapeshellarg($user);
            if ($password !== '') {
                $cmd .= " -p" . escapeshellarg($password);
            }
            $cmd .= " lqsmis";
            // direct dump to file
            $cmd .= " > " . escapeshellarg($filePath);
            @shell_exec($cmd);
        } else {
            // Fallback: generate a simple SQL dump via PDO into file
            $pdo = getConnection();
            $out = fopen($filePath, 'w');
            fwrite($out, "-- LQSMIS backup generated on " . date('c') . "\n\n");
            $tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
            foreach ($tables as $table) {
                $row = $pdo->query("SHOW CREATE TABLE `{$table}`")->fetch(PDO::FETCH_ASSOC);
                if (!empty($row['Create Table'])) {
                    fwrite($out, "-- Table structure for `{$table}`\n");
                    fwrite($out, $row['Create Table'] . ";\n\n");
                }
                $rows = $pdo->query("SELECT * FROM `{$table}`")->fetchAll(PDO::FETCH_ASSOC);
                if ($rows) {
                    fwrite($out, "-- Data for table `{$table}`\n");
                    foreach ($rows as $r) {
                        $cols = array_map(function($c){ return "`" . str_replace('`','``',$c) . "`"; }, array_keys($r));
                        $vals = array_map(function($v) use ($pdo) { return $v === null ? 'NULL' : $pdo->quote($v); }, array_values($r));
                        fwrite($out, "INSERT INTO `{$table}` (" . implode(',', $cols) . ") VALUES (" . implode(',', $vals) . ");\n");
                    }
                    fwrite($out, "\n");
                }
            }
            fclose($out);
        }

        // Create ZIP with SQL and assets folder if ZipArchive available
        $zipPath = $backupDir . DIRECTORY_SEPARATOR . $filenameZip;
        if (class_exists('ZipArchive')) {
            $zip = new ZipArchive();
            if ($zip->open($zipPath, ZipArchive::CREATE) === true) {
                $zip->addFile($filePath, $filenameSql);
                $assetsDir = $projectRoot . DIRECTORY_SEPARATOR . 'assets';
                if (is_dir($assetsDir)) {
                    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($assetsDir));
                    foreach ($files as $f) {
                        if (!$f->isDir()) {
                            $localName = 'assets' . substr($f->getPathname(), strlen($projectRoot));
                            $zip->addFile($f->getPathname(), ltrim(str_replace('\\', '/', substr($f->getPathname(), strlen($projectRoot) + 1)), '/'));
                        }
                    }
                }
                $zip->close();
            }
        }

        // Record result for UI
        if (!empty($_SESSION)) {
            $_SESSION['notice'] = 'Backup created: ' . basename($zipPath ?? $filePath);
        }
        $this->redirect('settings');
    }
}
