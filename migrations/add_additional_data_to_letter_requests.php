<?php
/**
 * Migration: Add additional_data JSON column to letter_requests table
 *
 * This migration adds a new JSON column to store additional dynamic data
 * that is specific to certain letter types (education, business, family, events)
 * without cluttering the main request_data JSON column.
 *
 * @created December 2025
 */

require_once __DIR__ . '/../config/database.php';

// Initialize database connection
global $pdo;
if (!$pdo) {
    die("❌ Database connection failed\n");
}

try {
    // Add additional_data column
    $pdo->exec("
        ALTER TABLE letter_requests
        ADD COLUMN additional_data JSON AFTER request_data
    ");

    // Add index for JSON column performance
    $pdo->exec("
        ALTER TABLE letter_requests
        ADD INDEX idx_additional_data (additional_data(255))
    ");

    // Initialize empty JSON objects for existing records
    $pdo->exec("
        UPDATE letter_requests
        SET additional_data = '{}'
        WHERE additional_data IS NULL
    ");

    echo "✅ Migration completed successfully!\n";
    echo "📊 Added additional_data JSON column to letter_requests table\n";
    echo "🔍 Added performance index for JSON queries\n";
    echo "📝 Initialized empty JSON objects for existing records\n";

} catch (Exception $e) {
    echo "❌ Migration failed: " . $e->getMessage() . "\n";

    // Rollback if possible
    try {
        $pdo->exec("ALTER TABLE letter_requests DROP COLUMN additional_data");
        echo "🔄 Migration rolled back\n";
    } catch (Exception $rollbackError) {
        echo "⚠️  Could not rollback: " . $rollbackError->getMessage() . "\n";
    }

    exit(1);
}

echo "\n📋 Migration Summary:\n";
echo "- Added 'additional_data' JSON column for flexible data storage\n";
echo "- Created index for optimal JSON query performance\n";
echo "- Backward compatible with existing request_data structure\n";
echo "- Ready for dynamic form data categorization\n";

echo "\n🎯 Next Steps:\n";
echo "1. Update LetterRequest model to handle additional_data\n";
echo "2. Modify form submission to separate data types\n";
echo "3. Update templates to use merged data sources\n";
echo "4. Test with various letter types\n";
?>
