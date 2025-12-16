<?php

/**
 * Admin Dashboard Controller
 * Handles dashboard statistics and overview
 *
 * ABIS - Aplikasi Desa Digital
 */

require_once 'config/session.php';
require_once 'app/models/User.php';
require_once 'app/models/LetterRequest.php';

class DashboardController
{
    private $userModel;
    private $letterRequestModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->letterRequestModel = new LetterRequest();
    }

    /**
     * Display admin dashboard with comprehensive statistics
     */
    public function index()
    {
        // Get comprehensive statistics from models
        $userStats = $this->userModel->getDashboardStats();
        $requestStats = $this->letterRequestModel->getDashboardStats();

        // Get today's and this month's statistics
        $todayStats = $this->letterRequestModel->getTodayStats();
        $monthStats = $this->letterRequestModel->getThisMonthStats();

        // Get recent activities
        $recentRequests = $this->letterRequestModel->getRecentRequests(5);
        $pendingRequests = $this->letterRequestModel->getPendingRequests(10);

        // Prepare view data
        $viewData = [
            'userStats' => $userStats,
            'requestStats' => $requestStats,
            'todayStats' => $todayStats,
            'monthStats' => $monthStats,
            'recentRequests' => $recentRequests,
            'pendingRequests' => $pendingRequests,
            'pageTitle' => 'Dashboard Admin'
        ];

        // Render view
        $this->render('admin/dashboard', $viewData);
    }

    /**
     * Get dashboard statistics via AJAX
     */
    public function getStats()
    {
        try {
            $userStats = $this->userModel->getDashboardStats();
            $requestStats = $this->letterRequestModel->getDashboardStats();

            $this->jsonResponse([
                'success' => true,
                'data' => [
                    'users' => $userStats,
                    'requests' => $requestStats
                ]
            ]);
        } catch (Exception $e) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Failed to load dashboard statistics'
            ], 500);
        }
    }

    /**
     * Render view helper
     *
     * @param string $view View name
     * @param array $data View data
     */
    private function render($view, $data = [])
    {
        // Extract data to make variables available in view
        extract($data);

        // Start output buffering
        ob_start();
        $viewPath = VIEWS_DIR . '/' . $view . '.php';
        if (!file_exists($viewPath)) {
            die("View file not found: {$viewPath}");
        }
        include $viewPath;
        $content = ob_get_clean();

        // Load admin layout
        include VIEWS_DIR . '/layouts/admin.php';
    }

    /**
     * JSON response helper
     *
     * @param array $data Response data
     * @param int $status HTTP status code
     */
    private function jsonResponse($data, $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
