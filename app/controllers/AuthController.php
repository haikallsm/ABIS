<?php
/**
 * Auth Controller
 * Handles authentication (login, register, logout)
 * ABIS - Aplikasi Desa Digital
 */

require_once MODELS_DIR . '/User.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    /**
     * Show login form
     */
    public function login() {
        redirectIfAuthenticated();
        $this->renderView('auth/login', [
            'title' => 'Login - ' . APP_NAME
        ]);
    }

    /**
     * Process login form
     */
    public function processLogin() {
        redirectIfAuthenticated();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        // Get and sanitize input
        $username = sanitize($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validate input
        $errors = [];
        if (empty($username)) {
            $errors['username'] = 'Username wajib diisi';
        }
        if (empty($password)) {
            $errors['password'] = 'Password wajib diisi';
        }

        if (!empty($errors)) {
            $this->renderView('auth/login', [
                'title' => 'Login - ' . APP_NAME,
                'errors' => $errors,
                'old' => ['username' => $username]
            ]);
            return;
        }

        // Find user
        $user = $this->userModel->findByUsername($username);
        if (!$user || !$this->userModel->verifyPassword($password, $user['password'])) {
            $errors['general'] = ERROR_INVALID_CREDENTIALS;
            $this->renderView('auth/login', [
                'title' => 'Login - ' . APP_NAME,
                'errors' => $errors,
                'old' => ['username' => $username]
            ]);
            return;
        }

        // Set user session
        setUserSession($user);

        // Redirect based on role
        $redirectUrl = $user['role'] === ROLE_ADMIN ? '/admin/dashboard' : '/dashboard';
        header('Location: ' . BASE_URL . $redirectUrl);
        exit;
    }

    /**
     * Show register form
     */
    public function register() {
        redirectIfAuthenticated();
        $this->renderView('auth/register', [
            'title' => 'Register - ' . APP_NAME
        ]);
    }

    /**
     * Process register form
     */
    public function processRegister() {
        redirectIfAuthenticated();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/register');
            exit;
        }

        // Get and sanitize input
        $username = sanitize($_POST['username'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $fullName = sanitize($_POST['full_name'] ?? '');
        $phone = sanitize($_POST['phone'] ?? '');
        $address = sanitize($_POST['address'] ?? '');

        // Validate input
        $errors = [];
        if (empty($username)) {
            $errors['username'] = 'Username wajib diisi';
        } elseif (strlen($username) < 3) {
            $errors['username'] = 'Username minimal 3 karakter';
        } elseif ($this->userModel->usernameExists($username)) {
            $errors['username'] = 'Username sudah digunakan';
        }

        if (empty($email)) {
            $errors['email'] = 'Email wajib diisi';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Format email tidak valid';
        } elseif ($this->userModel->emailExists($email)) {
            $errors['email'] = 'Email sudah digunakan';
        }

        if (empty($password)) {
            $errors['password'] = 'Password wajib diisi';
        } elseif (strlen($password) < PASSWORD_MIN_LENGTH) {
            $errors['password'] = 'Password minimal ' . PASSWORD_MIN_LENGTH . ' karakter';
        }

        if (empty($confirmPassword)) {
            $errors['confirm_password'] = 'Konfirmasi password wajib diisi';
        } elseif ($password !== $confirmPassword) {
            $errors['confirm_password'] = 'Konfirmasi password tidak cocok';
        }

        if (empty($fullName)) {
            $errors['full_name'] = 'Nama lengkap wajib diisi';
        }

        if (!empty($errors)) {
            $this->renderView('auth/register', [
                'title' => 'Register - ' . APP_NAME,
                'errors' => $errors,
                'old' => [
                    'username' => $username,
                    'email' => $email,
                    'full_name' => $fullName,
                    'phone' => $phone,
                    'address' => $address
                ]
            ]);
            return;
        }

        // Create user
        $userData = [
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'full_name' => $fullName,
            'phone' => $phone,
            'address' => $address,
            'role' => ROLE_USER
        ];

        if ($this->userModel->create($userData)) {
            // Set success message
            $_SESSION['success'] = SUCCESS_REGISTER;

            // Redirect to login
            header('Location: ' . BASE_URL . '/login');
            exit;
        } else {
            $errors['general'] = 'Terjadi kesalahan saat mendaftar. Silakan coba lagi.';
            $this->renderView('auth/register', [
                'title' => 'Register - ' . APP_NAME,
                'errors' => $errors,
                'old' => [
                    'username' => $username,
                    'email' => $email,
                    'full_name' => $fullName,
                    'phone' => $phone,
                    'address' => $address
                ]
            ]);
        }
    }

    /**
     * Logout user
     */
    public function logout() {
        clearUserSession();
        header('Location: ' . BASE_URL . '/login');
        exit;
    }

    /**
     * Render view with layout
     * @param string $view
     * @param array $data
     */
    private function renderView($view, $data = []) {
        // Extract data to make variables available in view
        extract($data);

        // Include layout
        require VIEWS_DIR . '/layouts/auth.php';
    }
}
