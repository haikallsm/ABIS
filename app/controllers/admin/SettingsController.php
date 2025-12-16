<?php

/**
 * Admin Settings Controller
 * Handles application settings, including Telegram bot configuration
 *
 * ABIS - Aplikasi Desa Digital
 */

require_once 'config/session.php';
require_once 'config/telegram.php';
require_once 'utils/TelegramBot.php';

class SettingsController
{
    private $telegramBot;

    public function __construct()
    {
        $this->telegramBot = new TelegramBot();
    }

    /**
     * Display Telegram settings page
     */
    public function telegram()
    {
        $currentSettings = $this->getCurrentSettings();
        $botInfo = null;
        $recentLogs = [];
        $testResult = null;

        // Check if bot token is configured
        if (!empty($currentSettings['bot_token'])) {
            try {
                $botInfo = $this->telegramBot->getBotInfo();
                $recentLogs = $this->getRecentLogs();
            } catch (Exception $e) {
                $testResult = ['success' => false, 'message' => $e->getMessage()];
            }
        }

        // Handle POST request for settings update
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleSettingsUpdate();
            return; // Response handled in handleSettingsUpdate
        }

        $viewData = [
            'currentSettings' => $currentSettings,
            'botInfo' => $botInfo,
            'recentLogs' => $recentLogs,
            'testResult' => $testResult,
            'pageTitle' => 'Pengaturan Telegram Bot'
        ];

        $this->render('admin/telegram-settings', $viewData);
    }

    /**
     * Handle settings update via AJAX
     */
    private function handleSettingsUpdate()
    {
        try {
            $action = $_POST['action'] ?? '';

            switch ($action) {
                case 'update_settings':
                    $this->updateTelegramSettings();
                    break;

                case 'test_connection':
                    $this->testTelegramConnection();
                    break;

                default:
                    throw new Exception('Aksi tidak valid');
            }

        } catch (Exception $e) {
            $this->jsonResponse([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update Telegram settings
     */
    private function updateTelegramSettings()
    {
        $botToken = trim($_POST['bot_token'] ?? '');
        $testChatId = trim($_POST['test_chat_id'] ?? '');
        $notificationsEnabled = isset($_POST['notifications_enabled']) ? 'true' : 'false';

        // Validate bot token format
        if (!empty($botToken) && !preg_match('/^[0-9]+:[A-Za-z0-9_-]+$/', $botToken)) {
            throw new Exception('Format Bot Token tidak valid');
        }

        // Update configuration file
        $this->updateConfigFile([
            'TELEGRAM_BOT_TOKEN' => $botToken,
            'TELEGRAM_TEST_CHAT_ID' => $testChatId,
            'TELEGRAM_NOTIFICATIONS_ENABLED' => $notificationsEnabled === 'true'
        ]);

        $this->jsonResponse([
            'success' => true,
            'message' => 'Pengaturan Telegram berhasil disimpan'
        ]);
    }

    /**
     * Test Telegram bot connection
     */
    private function testTelegramConnection()
    {
        try {
            $botInfo = $this->telegramBot->getBotInfo();

            // Send test message if chat ID is provided
            $settings = $this->getCurrentSettings();
            if (!empty($settings['test_chat_id'])) {
                $this->telegramBot->sendMessage(
                    $settings['test_chat_id'],
                    "🧪 Test koneksi bot berhasil!\n\n" .
                    "🤖 Bot: @" . ($botInfo['username'] ?? 'Unknown') . "\n" .
                    "📅 Waktu: " . date('d/m/Y H:i:s') . "\n" .
                    "🔗 Sistem: ABIS - Aplikasi Desa Digital"
                );
            }

            $this->jsonResponse([
                'success' => true,
                'message' => 'Koneksi bot berhasil! Pesan test telah dikirim.',
                'bot_info' => $botInfo
            ]);

        } catch (Exception $e) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Gagal terhubung ke bot: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get current Telegram settings
     */
    private function getCurrentSettings()
    {
        return [
            'bot_token' => defined('TELEGRAM_BOT_TOKEN') ? TELEGRAM_BOT_TOKEN : '',
            'test_chat_id' => defined('TELEGRAM_TEST_CHAT_ID') ? TELEGRAM_TEST_CHAT_ID : '',
            'notifications_enabled' => defined('TELEGRAM_NOTIFICATIONS_ENABLED') ?
                TELEGRAM_NOTIFICATIONS_ENABLED : true
        ];
    }

    /**
     * Update configuration file
     */
    private function updateConfigFile($settings)
    {
        $configFile = ROOT_DIR . '/config/telegram.php';

        if (!file_exists($configFile)) {
            throw new Exception('File konfigurasi tidak ditemukan');
        }

        // Read current config
        $configContent = file_get_contents($configFile);

        // Update settings
        $replacements = [
            '/define\(["\']TELEGRAM_BOT_TOKEN["\'],\s*["\'][^"\']*["\']\);/' =>
                "define('TELEGRAM_BOT_TOKEN', '" . addslashes($settings['TELEGRAM_BOT_TOKEN']) . "');",

            '/define\(["\']TELEGRAM_TEST_CHAT_ID["\'],\s*["\'][^"\']*["\']\);/' =>
                "define('TELEGRAM_TEST_CHAT_ID', '" . addslashes($settings['TELEGRAM_TEST_CHAT_ID']) . "');",

            '/define\(["\']TELEGRAM_NOTIFICATIONS_ENABLED["\'],\s*["\'][^"\']*["\']\);/' =>
                "define('TELEGRAM_NOTIFICATIONS_ENABLED', " . ($settings['TELEGRAM_NOTIFICATIONS_ENABLED'] ? 'true' : 'false') . ");"
        ];

        foreach ($replacements as $pattern => $replacement) {
            $configContent = preg_replace($pattern, $replacement, $configContent);
        }

        // Write back to file
        if (file_put_contents($configFile, $configContent) === false) {
            throw new Exception('Gagal menyimpan konfigurasi');
        }
    }

    /**
     * Get recent Telegram logs
     */
    private function getRecentLogs()
    {
        $logFile = defined('TELEGRAM_LOG_FILE') ? TELEGRAM_LOG_FILE : ROOT_DIR . '/logs/telegram.log';

        if (!file_exists($logFile)) {
            return [];
        }

        $logs = [];
        $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        // Get last 10 lines
        $recentLines = array_slice($lines, -10);

        foreach ($recentLines as $line) {
            if (preg_match('/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] (.+)/', $line, $matches)) {
                $logs[] = [
                    'timestamp' => $matches[1],
                    'message' => $matches[2]
                ];
            }
        }

        return array_reverse($logs);
    }

    /**
     * Render view helper
     */
    private function render($view, $data = [])
    {
        extract($data);
        ob_start();
        $viewPath = VIEWS_DIR . '/' . $view . '.php';
        if (!file_exists($viewPath)) {
            die("View file not found: {$viewPath}");
        }
        include $viewPath;
        $content = ob_get_clean();
        include VIEWS_DIR . '/layouts/admin.php';
    }

    /**
     * JSON response helper
     */
    private function jsonResponse($data, $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
