<?php
/**
 * Cleanup Script - Remove unused debug and test files
 */

$filesToDelete = [
    // Debug files
    'audit_database_usage.php',
    'comprehensive_route_analysis.php',
    'debug_frontend_data.php',
    'debug_letter_requests_status.php',
    'debug_request_creation.php',
    'debug_view_data.php',
    'deep_audit_data_freshness.php',
    'final_audit_complete_system.php',

    // Test files
    'check_columns.php',
    'check_database.php',
    'check_pending_requests.php',
    'create_pending_request.php',
    'create_test_request.php',
    'test_api.php',
    'test_approval_flow.php',
    'test_approve_manual.php',
    'test_approve_reject_ui.php',
    'test_base_url.php',
    'test_complete_letter_flow.php',
    'test_create_request.php',
    'test_data_display.php',
    'test_js_loading.php',
    'test_view_rendering.php',

    // Documentation files (keep only essential ones)
    'FIX_DATABASE_README.md',
    'LARAGON_APACHE_FIX.md',
    'LARAGON_NGINX_SETUP.md',
    'PHP_SERVER_README.md',
    'QUICK_FIX_NGINX.md',
    'SETUP_GUIDE.md',
    'SWITCH_TO_APACHE_GUIDE.md',

    // Script files
    'backup.sh',
    'deploy.sh',
    'telegram_bot_setup.php',
    'test.sh'
];

echo "🧹 CLEANING UP UNUSED FILES\n";
echo "============================\n\n";

$deletedCount = 0;
$notFoundCount = 0;

foreach ($filesToDelete as $file) {
    if (file_exists($file)) {
        if (unlink($file)) {
            echo "✅ Deleted: {$file}\n";
            $deletedCount++;
        } else {
            echo "❌ Failed to delete: {$file}\n";
        }
    } else {
        echo "⚠️  Not found: {$file}\n";
        $notFoundCount++;
    }
}

echo "\n📊 CLEANUP SUMMARY:\n";
echo "==================\n";
echo "Files deleted: {$deletedCount}\n";
echo "Files not found: {$notFoundCount}\n";
echo "Total processed: " . count($filesToDelete) . "\n\n";

echo "🎉 CLEANUP COMPLETE!\n";
echo "===================\n\n";

// Check remaining files in root directory
echo "📁 REMAINING FILES IN ROOT:\n";
echo "==========================\n";

$rootFiles = scandir('.');
$importantFiles = [];

foreach ($rootFiles as $file) {
    if ($file !== '.' && $file !== '..' && !is_dir($file)) {
        $importantFiles[] = $file;
    }
}

foreach ($importantFiles as $file) {
    echo "- {$file}\n";
}

echo "\n✅ PROJECT NOW CLEAN AND OPTIMIZED!\n";
?>
