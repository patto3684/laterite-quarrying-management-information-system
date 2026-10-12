<?php
class Controller {
    protected function view(string $view, array $data = []): void {
        extract($data);
        $viewFile = __DIR__ . '/../app/views/' . $view . '.php';
        if (!file_exists($viewFile)) {
            throw new Exception('View not found: ' . $view);
        }

        if (!isset($use_layout) || $use_layout) {
            ob_start();
            require $viewFile;
            $content = ob_get_clean();
            require __DIR__ . '/../app/views/layout.php';
            return;
        }

        require $viewFile;
    }

    protected function redirect(string $path): void {
        header('Location: ' . APP_URL . '/' . ltrim($path, '/'));
        exit;
    }

    protected function requireAuth(): void {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }
    }

    protected function requireRole(array $roles): void {
        $this->requireAuth();
        if (!in_array($_SESSION['role'] ?? '', $roles, true)) {
            $this->redirect('dashboard');
        }
    }

    protected function logActivity(string $title, string $detail): void {
        $activity = new Activity();
        $activity->create([
            'title' => $title,
            'detail' => $detail,
        ]);
    }

    protected function requireAccess(string $page): void {
        $this->requireAuth();
        $role = $_SESSION['role'] ?? '';
        if (!RolePermissions::hasAccess($role, $page)) {
            http_response_code(403);
            die('Access Denied');
        }
    }
}
