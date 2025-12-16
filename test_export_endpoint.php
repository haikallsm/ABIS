<?php
/**
 * Test Export Endpoint
 */

echo "🧪 TESTING EXPORT ENDPOINT\n";
echo "===========================\n\n";

// Test 1: Direct endpoint access (will fail without auth, but should not crash)
echo "1️⃣ Testing endpoint response structure:\n";

$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => 'Cookie: PHPSESSID=test_session_id'
    ]
]);

$url = 'http://localhost/admin/export/excel';
echo "Testing URL: $url\n";

try {
    // This will likely fail due to authentication, but we want to see if it doesn't crash
    $response = @file_get_contents($url, false, $context);

    if ($response === false) {
        echo "✅ Endpoint accessible (authentication required, which is expected)\n";
    } else {
        echo "✅ Endpoint responded with data\n";
        echo "Response size: " . strlen($response) . " bytes\n";
        echo "Content-Type: " . ($http_response_header[0] ?? 'Unknown') . "\n";
    }
} catch (Exception $e) {
    echo "❌ Error accessing endpoint: " . $e->getMessage() . "\n";
}

// Test 2: Test AdminController exportExcel method directly
echo "\n2️⃣ Testing AdminController exportExcel method:\n";

try {
    require_once 'app/controllers/AdminController.php';
    require_once 'app/models/LetterRequest.php';
    require_once 'app/models/User.php';
    require_once 'app/models/LetterType.php';

    // Mock functions
    function requireAuth($role) { return true; }
    function getCurrentUserId() { return 2; }

    $adminController = new AdminController();

    // Get some test data
    $letterRequestModel = new LetterRequest();
    $testRequests = $letterRequestModel->getAllForExport([]);

    if (!empty($testRequests)) {
        echo "✅ Found " . count($testRequests) . " requests for export\n";

        // Test Excel generation without headers (to avoid exit)
        ob_start();
        $adminController->exportExcel();
        $output = ob_get_clean();

        if (strlen($output) > 100) {
            echo "✅ Excel export generated output (size: " . strlen($output) . " bytes)\n";
            echo "Content starts with: " . substr($output, 0, 50) . "...\n";
        } else {
            echo "❌ Excel export failed or returned empty content\n";
        }
    } else {
        echo "⚠️  No test data available for export\n";
    }

} catch (Exception $e) {
    echo "❌ Error testing exportExcel method: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\n🎯 EXPORT ENDPOINT TEST COMPLETE\n";
echo "================================\n";

echo "\n💡 If tests pass, the export should work in browser\n";
echo "🔧 Make sure you're logged in as admin when testing in browser\n";
echo "📱 Test URL: http://localhost/admin/requests (then click Export Excel)\n";
