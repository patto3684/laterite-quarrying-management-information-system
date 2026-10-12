<?php
session_start();
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/Model.php';
require_once __DIR__ . '/core/RolePermissions.php';
require_once __DIR__ . '/app/controllers/AuthController.php';
require_once __DIR__ . '/app/controllers/DashboardController.php';
require_once __DIR__ . '/app/controllers/UserController.php';
require_once __DIR__ . '/app/controllers/QuarryController.php';
require_once __DIR__ . '/app/controllers/ProductionController.php';
require_once __DIR__ . '/app/controllers/StockController.php';
require_once __DIR__ . '/app/controllers/EquipmentController.php';
require_once __DIR__ . '/app/controllers/FinanceController.php';
require_once __DIR__ . '/app/controllers/InspectionController.php';
require_once __DIR__ . '/app/controllers/CustomerController.php';
require_once __DIR__ . '/app/controllers/ReportController.php';
require_once __DIR__ . '/app/controllers/SubmissionController.php';
require_once __DIR__ . '/app/controllers/SettingsController.php';
require_once __DIR__ . '/app/models/User.php';
require_once __DIR__ . '/app/models/Quarry.php';
require_once __DIR__ . '/app/models/Production.php';
require_once __DIR__ . '/app/models/Stock.php';
require_once __DIR__ . '/app/models/Equipment.php';
require_once __DIR__ . '/app/models/Finance.php';
require_once __DIR__ . '/app/models/Report.php';
require_once __DIR__ . '/app/models/Inspection.php';
require_once __DIR__ . '/app/models/Customer.php';
require_once __DIR__ . '/app/models/Activity.php';
require_once __DIR__ . '/app/models/RolePermission.php';

getConnection();
$router = $_GET['route'] ?? '';
if ($router === '' && !empty($_SERVER['REQUEST_URI'])) {
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $path = trim(str_replace('/lqsmis', '', $path), '/');
    $router = $path === '' ? 'login' : $path;
}
$router = trim($router, '/');

if ($router === '' || $router === 'login') {
    $controller = new AuthController();
    $controller->login();
    exit;
}

if ($router === 'logout') {
    $controller = new AuthController();
    $controller->logout();
    exit;
}

if ($router === 'register') {
    $controller = new AuthController();
    $controller->register();
    exit;
}

if ($router === 'dashboard') {
    $controller = new DashboardController();
    $controller->index();
    exit;
}

if ($router === 'users') {
    $controller = new UserController();
    $controller->index();
    exit;
}

if ($router === 'users/create') {
    $controller = new UserController();
    $controller->create();
    exit;
}

if ($router === 'users/edit') {
    $controller = new UserController();
    $controller->edit((int)($_GET['id'] ?? 0));
    exit;
}

if ($router === 'users/update') {
    $controller = new UserController();
    $controller->update();
    exit;
}

if ($router === 'users/delete') {
    $controller = new UserController();
    $controller->delete();
    exit;
}

if ($router === 'quarries') {
    $controller = new QuarryController();
    $controller->index();
    exit;
}

if ($router === 'quarries/create') {
    $controller = new QuarryController();
    $controller->create();
    exit;
}

if ($router === 'quarries/delete') {
    $controller = new QuarryController();
    $controller->delete();
    exit;
}

if ($router === 'productions') {
    $controller = new ProductionController();
    $controller->index();
    exit;
}

if ($router === 'productions/create') {
    $controller = new ProductionController();
    $controller->create();
    exit;
}

if ($router === 'productions/edit') {
    $controller = new ProductionController();
    $controller->edit((int)($_GET['id'] ?? 0));
    exit;
}

if ($router === 'productions/update') {
    $controller = new ProductionController();
    $controller->update();
    exit;
}

if ($router === 'productions/delete') {
    $controller = new ProductionController();
    $controller->delete();
    exit;
}

if ($router === 'stocks') {
    $controller = new StockController();
    $controller->index();
    exit;
}

if ($router === 'stocks/create') {
    $controller = new StockController();
    $controller->create();
    exit;
}

if ($router === 'stocks/edit') {
    $controller = new StockController();
    $controller->edit();
    exit;
}

if ($router === 'stocks/update') {
    $controller = new StockController();
    $controller->update();
    exit;
}

if ($router === 'stocks/delete') {
    $controller = new StockController();
    $controller->delete();
    exit;
}

if ($router === 'equipment') {
    $controller = new EquipmentController();
    $controller->index();
    exit;
}

if ($router === 'equipment/create') {
    $controller = new EquipmentController();
    $controller->create();
    exit;
}

if ($router === 'equipment/edit') {
    $controller = new EquipmentController();
    $controller->edit((int)($_GET['id'] ?? 0));
    exit;
}

if ($router === 'equipment/update') {
    $controller = new EquipmentController();
    $controller->update();
    exit;
}

if ($router === 'equipment/delete') {
    $controller = new EquipmentController();
    $controller->delete();
    exit;
}

if ($router === 'finance') {
    $controller = new FinanceController();
    $controller->index();
    exit;
}

if ($router === 'finance/create') {
    $controller = new FinanceController();
    $controller->create();
    exit;
}

if ($router === 'finance/edit') {
    $controller = new FinanceController();
    $controller->edit((int)($_GET['id'] ?? 0));
    exit;
}

if ($router === 'finance/update') {
    $controller = new FinanceController();
    $controller->update();
    exit;
}

if ($router === 'finance/delete') {
    $controller = new FinanceController();
    $controller->delete();
    exit;
}

if ($router === 'inspections') {
    $controller = new InspectionController();
    $controller->index();
    exit;
}

if ($router === 'inspections/create') {
    $controller = new InspectionController();
    $controller->create();
    exit;
}

if ($router === 'inspections/edit') {
    $controller = new InspectionController();
    $controller->edit((int)($_GET['id'] ?? 0));
    exit;
}

if ($router === 'inspections/update') {
    $controller = new InspectionController();
    $controller->update();
    exit;
}

if ($router === 'inspections/delete') {
    $controller = new InspectionController();
    $controller->delete();
    exit;
}

if ($router === 'customers') {
    $controller = new CustomerController();
    $controller->index();
    exit;
}

if ($router === 'customers/create') {
    $controller = new CustomerController();
    $controller->create();
    exit;
}

if ($router === 'customers/edit') {
    $controller = new CustomerController();
    $controller->edit((int)($_GET['id'] ?? 0));
    exit;
}

if ($router === 'customers/update') {
    $controller = new CustomerController();
    $controller->update();
    exit;
}

if ($router === 'customers/delete') {
    $controller = new CustomerController();
    $controller->delete();
    exit;
}

if ($router === 'reports') {
    $controller = new ReportController();
    $controller->index();
    exit;
}

if ($router === 'reports/production') {
    $controller = new ReportController();
    $controller->production();
    exit;
}

if ($router === 'reports/finance') {
    $controller = new ReportController();
    $controller->finance();
    exit;
}

if ($router === 'reports/logistics') {
    $controller = new ReportController();
    $controller->logistics();
    exit;
}

if ($router === 'reports/safety') {
    $controller = new ReportController();
    $controller->safety();
    exit;
}

if ($router === 'reports/filter') {
    $controller = new ReportController();
    $controller->filter();
    exit;
}

if ($router === 'submissions') {
    $controller = new SubmissionController();
    $controller->index();
    exit;
}

if ($router === 'settings') {
    $controller = new SettingsController();
    $controller->index();
    exit;
}

if ($router === 'settings/users') {
    $controller = new SettingsController();
    $controller->users();
    exit;
}

if ($router === 'settings/create_user') {
    $controller = new SettingsController();
    $controller->createUser();
    exit;
}

if ($router === 'settings/deactivate_user') {
    $controller = new SettingsController();
    $controller->deactivateUser();
    exit;
}

if ($router === 'settings/save_settings') {
    $controller = new SettingsController();
    $controller->saveSettings();
    exit;
}

if ($router === 'settings/save_api_key') {
    $controller = new SettingsController();
    $controller->saveApiKey();
    exit;
}

if ($router === 'settings/reset_password') {
    $controller = new SettingsController();
    $controller->resetPassword();
    exit;
}

if ($router === 'settings/audit') {
    $controller = new SettingsController();
    $controller->audit();
    exit;
}

if ($router === 'settings/export') {
    $controller = new SettingsController();
    $controller->export();
    exit;
}

if ($router === 'settings/backup') {
    $controller = new SettingsController();
    $controller->backup();
    exit;
}

header('HTTP/1.0 404 Not Found');
echo 'Page not found';
