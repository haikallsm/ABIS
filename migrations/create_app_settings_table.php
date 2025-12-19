<?php
/**
 * Migration: Create App Settings Table
 * FIX: data previously stored in config files - now persisted to database
 */

require_once '../config/config.php';

echo "Creating app_settings table...\n";

try {
    // Create app_settings table
    $sql = "
        CREATE TABLE IF NOT EXISTS app_settings (
            id INT PRIMARY KEY AUTO_INCREMENT,
            setting_key VARCHAR(100) NOT NULL UNIQUE,
            value TEXT,
            type ENUM('string', 'integer', 'boolean', 'float', 'json') DEFAULT 'string',
            description TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_setting_key (setting_key)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";

    // Execute the query
    $pdo = getDBConnection();
    $pdo->exec($sql);

    echo "✓ app_settings table created successfully\n";

    // Insert default telegram settings if they don't exist
    $defaultSettings = [
        [
            'setting_key' => 'telegram_bot_token',
            'value' => 'YOUR_BOT_TOKEN_HERE',
            'type' => 'string',
            'description' => 'Telegram Bot Token untuk notifikasi'
        ],
        [
            'setting_key' => 'telegram_test_chat_id',
            'value' => 'YOUR_TEST_CHAT_ID',
            'type' => 'string',
            'description' => 'Chat ID Telegram untuk testing'
        ],
        [
            'setting_key' => 'telegram_notifications_enabled',
            'value' => '0',
            'type' => 'boolean',
            'description' => 'Aktifkan/nonaktifkan notifikasi Telegram'
        ],
        [
            'setting_key' => 'telegram_debug_mode',
            'value' => '0',
            'type' => 'boolean',
            'description' => 'Mode debug untuk logging Telegram'
        ]
    ];

    $insertSql = "INSERT IGNORE INTO app_settings (setting_key, value, type, description) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($insertSql);

    foreach ($defaultSettings as $setting) {
        $stmt->execute([
            $setting['setting_key'],
            $setting['value'],
            $setting['type'],
            $setting['description']
        ]);
    }

    echo "✓ Default telegram settings inserted\n";
    echo "Migration completed successfully!\n";

} catch (Exception $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}
