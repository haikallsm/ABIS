<?php
/**
 * Migration: Move Telegram Settings from Config File to Database
 * FIX: data previously stored in config files - now persisted to database
 */

require_once '../config/config.php';

echo "Starting migration: Telegram Settings to Database\n";

try {
    // Check if telegram.php config file exists
    $configFile = '../config/telegram.php';
    if (!file_exists($configFile)) {
        echo "Config file not found: {$configFile}\n";
        echo "Migration completed - no config file to migrate\n";
        exit(0);
    }

    // Include config to get current values
    require_once $configFile;

    // Create AppSettings instance
    require_once '../app/models/AppSettings.php';
    $appSettings = new AppSettings();

    // Migrate settings to database
    echo "Migrating TELEGRAM_BOT_TOKEN...\n";
    if (defined('TELEGRAM_BOT_TOKEN') && TELEGRAM_BOT_TOKEN !== 'YOUR_BOT_TOKEN_HERE') {
        $result = $appSettings->set('telegram_bot_token', TELEGRAM_BOT_TOKEN, 'string');
        echo ($result ? "✓" : "✗") . " TELEGRAM_BOT_TOKEN migrated\n";
    } else {
        echo "- TELEGRAM_BOT_TOKEN skipped (default value)\n";
    }

    echo "Migrating TELEGRAM_TEST_CHAT_ID...\n";
    if (defined('TELEGRAM_TEST_CHAT_ID') && TELEGRAM_TEST_CHAT_ID !== 'YOUR_TEST_CHAT_ID') {
        $result = $appSettings->set('telegram_test_chat_id', TELEGRAM_TEST_CHAT_ID, 'string');
        echo ($result ? "✓" : "✗") . " TELEGRAM_TEST_CHAT_ID migrated\n";
    } else {
        echo "- TELEGRAM_TEST_CHAT_ID skipped (default value)\n";
    }

    echo "Migrating TELEGRAM_NOTIFICATIONS_ENABLED...\n";
    if (defined('TELEGRAM_NOTIFICATIONS_ENABLED')) {
        $result = $appSettings->set('telegram_notifications_enabled', TELEGRAM_NOTIFICATIONS_ENABLED, 'boolean');
        echo ($result ? "✓" : "✗") . " TELEGRAM_NOTIFICATIONS_ENABLED migrated\n";
    } else {
        echo "- TELEGRAM_NOTIFICATIONS_ENABLED skipped (not defined)\n";
    }

    echo "Migration completed successfully!\n";
    echo "Telegram settings have been moved from config file to database.\n";
    echo "The config file {$configFile} can now be safely removed or kept as backup.\n";

} catch (Exception $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}
