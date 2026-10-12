<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body { background: #f4f7fb; }
        .sidebar { min-height: 100vh; background: #0f172a; color: white; }
        .sidebar a { color: #cbd5e1; text-decoration: none; }
        .sidebar a:hover { color: white; }
        .card-stat { border-left: 4px solid #2563eb; }
        .print-toolbar { display: block; }
        .print-page {
            min-width: 120px;
        }
        @media print {
            .sidebar, .print-toolbar, .btn, .nav-link { display: none !important; }
            body { background: white !important; }
            .card { box-shadow: none !important; border: none !important; }
            .container-fluid, .row, .col-md-9 { width: auto !important; }
            .page-title { font-size: 24px !important; margin-bottom: 16px !important; }
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <?php if (!empty($_SESSION['user_id'])): ?>
        <?php
            // Detect whether we're on the settings route to render a simplified sidebar
            $requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $trimmed = trim(str_replace('/lqsmis', '', $requestPath), '/');
            $isSettingsRoute = $trimmed === 'settings' || strpos($trimmed, 'settings/') === 0;
        ?>
        <nav class="col-md-3 col-lg-2 d-md-block sidebar p-3">
            <h4 class="mb-4">LQMS</h4>
            <ul class="nav flex-column">
                <?php if ($isSettingsRoute): ?>
                    <li class="nav-item mb-2"><a href="<?= APP_URL ?>/settings">System Settings</a></li>
                    <li class="nav-item mb-2"><a href="<?= APP_URL ?>/settings/users">Users Management</a></li>
                    <li class="nav-item mb-2"><a href="<?= APP_URL ?>/settings/audit">Audit Logs</a></li>
                    <li class="nav-item mt-4"><a href="<?= APP_URL ?>/logout">Logout</a></li>
                <?php else: ?>
                    <?php
                        $role = strtolower($_SESSION['role'] ?? '');
                        if ($role === 'administrator'):
                    ?>
                        <li class="nav-item mb-2"><a href="<?= APP_URL ?>/dashboard">Dashboard</a></li>
                        <li class="nav-item mb-2"><a href="<?= APP_URL ?>/settings">Settings</a></li>
                        <li class="nav-item mb-2"><a href="<?= APP_URL ?>/settings/users">Users</a></li>
                        <li class="nav-item mt-4"><a href="<?= APP_URL ?>/logout">Logout</a></li>
                    <?php else:
                        $menuItems = RolePermissions::getMenuItems($role);
                        foreach ($menuItems as $page => $item):
                    ?>
                        <li class="nav-item mb-2"><a href="<?= APP_URL . $item['url'] ?>"><?= htmlspecialchars($item['label']) ?></a></li>
                    <?php endforeach; ?>
                    <li class="nav-item mt-4"><a href="<?= APP_URL ?>/logout">Logout</a></li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
        </nav>
        <?php endif; ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <?php if (!empty($_SESSION['user_id'])): ?>
                <div class="d-flex justify-content-end align-items-center mb-3 print-toolbar">
                    <button class="print-page btn btn-outline-secondary btn-sm me-2">Print Page</button>
                </div>
            <?php endif; ?>
            <?php if (!empty($_SESSION['user_id'])): ?>
                <?php include __DIR__ . '/print_instructions.php'; ?>
            <?php endif; ?>
            <?= $content ?>
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var buttons = document.querySelectorAll('.print-page');
        buttons.forEach(function(btn) {
            btn.addEventListener('click', function () {
                window.print();
            });
        });
    });
</script>
</body>
</html>
