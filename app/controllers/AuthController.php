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

        // Check if AJAX request
        $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        if (!empty($errors)) {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $errors
                ]);
                exit;
            } else {
                $this->renderView('auth/login', [
                    'title' => 'Login - ' . APP_NAME,
                    'errors' => $errors,
                    'old' => ['username' => $username]
                ]);
                return;
            }
        }

        // Find user
        $user = $this->userModel->findByUsername($username);
        if (!$user || !$this->userModel->verifyPassword($password, $user['password'])) {
            $errorMsg = ERROR_INVALID_CREDENTIALS;

            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => $errorMsg,
                    'errors' => ['general' => $errorMsg]
                ]);
                exit;
            } else {
                $errors['general'] = $errorMsg;
                $this->renderView('auth/login', [
                    'title' => 'Login - ' . APP_NAME,
                    'errors' => $errors,
                    'old' => ['username' => $username]
                ]);
                return;
            }
        }

        // Set user session
        setUserSession($user);

        // Redirect based on role
        if ($user['role'] === ROLE_ADMIN) {
            $redirectUrl = '/admin/dashboard';
        } else {
            // For regular users, redirect to user dashboard (as requested)
            $redirectUrl = '/dashboard';
        }

        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'message' => 'Login berhasil',
                'redirect' => BASE_URL . $redirectUrl
            ]);
            exit;
        } else {
            header('Location: ' . BASE_URL . $redirectUrl);
            exit;
        }
    }

    /**
     * Show register form
     */
    public function register() {
        redirectIfAuthenticated();
        header('Location: ' . BASE_URL . '/login');
        exit;
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

        // Check if AJAX request
        $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        // Get and sanitize input (support both AJAX and regular form)
        $nik = sanitize($_POST['nik'] ?? $_POST['username'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['password_confirmation'] ?? $_POST['confirm_password'] ?? '';
        $fullName = sanitize($_POST['nama'] ?? $_POST['full_name'] ?? '');
        $telegramChatId = sanitize($_POST['telegram_chat_id'] ?? '');
        $phone = sanitize($_POST['phone'] ?? ''); // Phone can be empty during registration
        $address = sanitize($_POST['address'] ?? '');

        // Validate input
        $errors = [];
        if (empty($nik)) {
            $errors['nik'] = 'NIK wajib diisi';
        } elseif (strlen($nik) !== 16) {
            $errors['nik'] = 'NIK harus 16 digit';
        } elseif (!is_numeric($nik)) {
            $errors['nik'] = 'NIK harus berupa angka';
        } elseif ($this->userModel->nikExists($nik)) {
            $errors['nik'] = 'NIK sudah terdaftar';
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
            $errors['password_confirmation'] = 'Konfirmasi password wajib diisi';
        } elseif ($password !== $confirmPassword) {
            $errors['password_confirmation'] = 'Konfirmasi password tidak cocok';
        }

        if (empty($fullName)) {
            $errors['nama'] = 'Nama lengkap wajib diisi';
        }

        if (empty($telegramChatId)) {
            $errors['telegram_chat_id'] = 'ID Telegram wajib diisi';
        } elseif (!is_numeric($telegramChatId)) {
            $errors['telegram_chat_id'] = 'ID Telegram harus berupa angka';
        }

        if (!empty($errors)) {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $errors
                ]);
                exit;
            } else {
                setFlashMessage('error', implode('<br>', array_values($errors)));
                header('Location: ' . BASE_URL . '/login');
                exit;
            }
        }

        // Create user
        $userData = [
            'username' => $nik, // Use NIK as username
            'email' => $email,
            'password' => $password,
            'full_name' => $fullName,
            'phone' => $phone, // Can be empty during registration
            'address' => $address,
            'nik' => $nik,
            'telegram_chat_id' => $telegramChatId,
            'role' => ROLE_USER
        ];

        if ($this->userModel->create($userData)) {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'message' => 'Pendaftaran berhasil! Silakan login dengan akun Anda.'
                ]);
                exit;
            } else {
                // Set success message
                $_SESSION['success'] = SUCCESS_REGISTER;

                // Redirect to login
                header('Location: ' . BASE_URL . '/login');
                exit;
            }
        } else {
            $errorMsg = 'Terjadi kesalahan saat mendaftar. Silakan coba lagi.';

            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => $errorMsg,
                    'errors' => ['general' => $errorMsg]
                ]);
                exit;
            } else {
                $errors['general'] = $errorMsg;
                $this->renderView('auth/register', [
                    'title' => 'Register - ' . APP_NAME,
                    'errors' => $errors,
                    'old' => [
                        'nik' => $nik,
                        'email' => $email,
                        'nama' => $fullName,
                        'telegram_chat_id' => $telegramChatId,
                        'phone' => $phone,
                        'address' => $address
                    ]
                ]);
            }
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

        // Start output buffering to capture view content
        ob_start();
        include VIEWS_DIR . '/' . $view . '.php';
        $content = ob_get_clean();

        // Include layout
        require VIEWS_DIR . '/layouts/auth.php';
    }
}
