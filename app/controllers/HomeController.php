<?php
/**
 * Home Controller
 * ABIS - Aplikasi Desa Digital
 */

class HomeController {
    private $userModel;
    private $letterRequestModel;
    private $letterTypeModel;

    public function __construct() {
        $this->userModel = new User();
        $this->letterRequestModel = new LetterRequest();
        $this->letterTypeModel = new LetterType();
    }

    /**
     * Display homepage
     */
    public function index() {
        // Get statistics for dashboard
        $stats = [
            'total_users' => $this->userModel->getStats()['total'] ?? 0,
            'total_requests' => $this->letterRequestModel->getStats()['total'] ?? 0,
            'completed_requests' => $this->letterRequestModel->getStats()['completed'] ?? 0,
            'pending_requests' => $this->letterRequestModel->getStats()['pending'] ?? 0,
            'approved_requests' => $this->letterRequestModel->getStats()['approved'] ?? 0
        ];

        // Get active letter types
        $letter_types = $this->letterTypeModel->getAllActive();

        // Load homepage view
        ob_start();
        include VIEWS_DIR . '/home/index.php';
        $content = ob_get_clean();

        // Load layout
        include VIEWS_DIR . '/layouts/main.php';
    }

    /**
     * Handle 404 errors
     */
    public function notFound() {
        http_response_code(404);
        $title = 'Halaman Tidak Ditemukan';

        ob_start();
        include VIEWS_DIR . '/errors/404.php';
        $content = ob_get_clean();

        include VIEWS_DIR . '/layouts/main.php';
    }

    /**
     * Handle 403 errors
     */
    public function forbidden() {
        http_response_code(403);
        $title = 'Akses Ditolak';

        ob_start();
        include VIEWS_DIR . '/errors/403.php';
        $content = ob_get_clean();

        include VIEWS_DIR . '/layouts/main.php';
    }

    /**
     * Handle 500 errors
     */
    public function serverError() {
        http_response_code(500);
        $title = 'Kesalahan Server';

        ob_start();
        include VIEWS_DIR . '/errors/500.php';
        $content = ob_get_clean();

        include VIEWS_DIR . '/layouts/main.php';
    }
}
