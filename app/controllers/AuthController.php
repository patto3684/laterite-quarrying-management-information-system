<?php
class AuthController extends Controller {
    public function login(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $userModel = new User();
            $user = $userModel->authenticate($email, $password);
            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['full_name'];
                $_SESSION['role'] = $user['role'];
                $this->redirect('dashboard');
            }
            $error = 'Invalid credentials';
            $this->view('auth/login', ['error' => $error, 'use_layout' => false]);
            return;
        }
        $this->view('auth/login', ['use_layout' => false]);
    }

    public function register(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel = new User();
            $userModel->create([
                'full_name' => trim($_POST['full_name'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'password' => password_hash(trim($_POST['password'] ?? ''), PASSWORD_DEFAULT),
                'role' => $_POST['role'] ?? DEFAULT_ROLE,
            ]);
            $this->redirect('login');
        }
        $this->view('auth/register', ['use_layout' => false]);
    }

    public function logout(): void {
        session_destroy();
        $this->redirect('login');
    }
}
