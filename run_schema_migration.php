<?php
require_once 'config/database.php';

try {
    $pdo = getDBConnection();

    echo "🔄 Adding additional_data column to letter_requests table...\n";

    // Add additional_data column
    $pdo->exec("
        ALTER TABLE letter_requests
        ADD COLUMN additional_data JSON AFTER request_data
    ");

    echo "✅ Column added successfully\n";

    // Note: JSON columns in MySQL cannot be indexed directly with traditional indexes
    // For performance optimization, we can add generated columns later if needed
    echo "ℹ️  Skipping JSON index (not supported in this MySQL version)\n";

    // Initialize empty JSON objects
    $pdo->exec("
        UPDATE letter_requests
        SET additional_data = '{}'
        WHERE additional_data IS NULL
    ");

    echo "✅ Existing records initialized\n";
    echo "🎯 Schema migration completed successfully!\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";

    // Check if column already exists
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "ℹ️  Column already exists\n";
    }
}
