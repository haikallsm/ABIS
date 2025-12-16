<?php
/**
 * Test Script: Simulate Form Submission
 * ABIS - Aplikasi Desa Digital
 */

require_once 'config/database.php';
require_once 'config/constants.php';
require_once 'config/config.php';

// Simulate POST request for SKD (Surat Keterangan Domisili)
echo "🧪 TESTING FORM SUBMISSION SIMULATION\n";
echo "=====================================\n\n";

// Simulate the POST data that dynamic form would send
$_POST = [
    'csrf_token' => 'test_csrf_token', // Would be generated properly in real form
    'letter_type_id' => '1', // SKD
    'nama' => 'John Doe', // Auto-filled from profile
    'nik' => '1234567890123456', // Auto-filled from profile
    'alamat' => 'Jl. Sudirman No. 1', // Auto-filled from profile
    'keperluan' => 'Untuk keperluan administrasi bank', // Required field
    'alamat_domisili' => 'Jl. Sudirman No. 1, RT 01 RW 01' // Required field
];

// Simulate session and authentication
$_SESSION = [
    'user_id' => 14, // Valid user ID from our debug script
    'user_role' => 'user',
    'username' => 'test_user'
];

// Simulate SERVER variables
$_SERVER['REQUEST_METHOD'] = 'POST';

// Include and test UserController logic
require_once 'app/controllers/UserController.php';
require_once 'app/models/User.php';
require_once 'app/models/LetterType.php';
require_once 'app/models/LetterRequest.php';

// Create controller instance
$userController = new UserController();

// Mock the getCurrentUserId function
function getCurrentUserId() {
    return 14; // Valid user ID
}

// Mock requireAuth function
function requireAuth($role) {
    // Do nothing for test
    return true;
}

// Test the processCreateRequest method
try {
    echo "🚀 Testing processCreateRequest method...\n";
    ob_start(); // Capture output since method uses header() redirects

    $userController->processCreateRequest();

    $output = ob_get_clean();
    echo "✅ Method executed without fatal errors\n";

    // Check if request was created
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("
        SELECT id, user_id, letter_type_id, status, request_data, additional_data
        FROM letter_requests
        WHERE user_id = ? AND letter_type_id = ?
        ORDER BY created_at DESC LIMIT 1
    ");
    $stmt->execute([14, 1]);
    $request = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($request) {
        echo "✅ Request created successfully!\n";
        echo "📋 Request Details:\n";
        echo "  - ID: {$request['id']}\n";
        echo "  - Status: {$request['status']}\n";
        echo "  - Request Data: " . substr($request['request_data'], 0, 100) . "...\n";
        echo "  - Additional Data: " . ($request['additional_data'] ?: 'None') . "\n";

        // Clean up test data
        $pdo->prepare("DELETE FROM letter_requests WHERE id = ?")->execute([$request['id']]);
        echo "🧹 Test data cleaned up\n";
    } else {
        echo "❌ Request was not created\n";
    }

} catch (Exception $e) {
    echo "❌ Error during form submission test: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\n🎯 FORM SUBMISSION TEST COMPLETE\n";
echo "===============================\n";

echo "\n🔧 If test passes but real form fails, check:\n";
echo "1. JavaScript errors in browser console\n";
echo "2. CSRF token validation\n";
echo "3. Network request payload\n";
echo "4. Form field names matching backend expectations\n";
echo "5. Dynamic form JavaScript loading properly\n";
