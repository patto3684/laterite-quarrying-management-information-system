<?php
class UserController extends Controller {
    public function index(): void {
        $this->requireAccess('users');
        $userModel = new User();
        $this->view('users/index', ['users' => $userModel->all()]);
    }

    public function create(): void {
        $this->requireAccess('users');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel = new User();
            $userModel->create([
                'full_name' => trim($_POST['full_name'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'password' => password_hash(trim($_POST['password'] ?? ''), PASSWORD_DEFAULT),
                'role' => $_POST['role'] ?? DEFAULT_ROLE,
            ]);
            $this->redirect('users');
        }
        $this->view('users/create');
    }

    public function edit(int $id): void {
        $this->requireAccess('users');
        $userModel = new User();
        $user = $userModel->find($id);
        if (!$user) {
            $this->redirect('users');
        }
        $this->view('users/edit', ['user' => $user]);
    }

    public function update(): void {
        $this->requireAccess('users');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel = new User();
            $userModel->update((int)($_POST['id'] ?? 0), [
                'full_name' => $_POST['full_name'] ?? '',
                'email' => $_POST['email'] ?? '',
                'password' => $_POST['password'] ?? '',
                'role' => $_POST['role'] ?? DEFAULT_ROLE,
            ]);
        }
        $this->redirect('users');
    }

    public function delete(): void {
        $this->requireAccess('users');
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $userModel = new User();
            $userModel->delete($id);
        }
        $this->redirect('users');
    }
}
