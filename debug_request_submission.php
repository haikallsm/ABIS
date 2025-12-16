<?php
/**
 * Debug Script: Letter Request Submission Issues
 * ABIS - Aplikasi Desa Digital
 */

require_once 'config/database.php';
require_once 'config/constants.php';

echo "🔍 DEBUGGING LETTER REQUEST SUBMISSION\n";
echo "=======================================\n\n";

// 1. Check users
echo "1️⃣ Checking Users:\n";
try {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare('SELECT id, username, full_name FROM users LIMIT 5');
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "Available users:\n";
    foreach($users as $user) {
        echo "  ID: {$user['id']} - {$user['username']} ({$user['full_name']})\n";
    }
    echo "\n";
} catch (Exception $e) {
    echo "❌ Error checking users: " . $e->getMessage() . "\n\n";
}

// 2. Check letter types
echo "2️⃣ Checking Letter Types:\n";
try {
    $stmt = $pdo->prepare('SELECT id, name, code FROM letter_types ORDER BY id');
    $stmt->execute();
    $types = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "Available letter types:\n";
    foreach($types as $type) {
        echo "  ID: {$type['id']} - {$type['code']} ({$type['name']})\n";
    }
    echo "\n";
} catch (Exception $e) {
    echo "❌ Error checking letter types: " . $e->getMessage() . "\n\n";
}

// 3. Check recent requests
echo "3️⃣ Checking Recent Requests:\n";
try {
    $stmt = $pdo->prepare('
        SELECT lr.id, lr.user_id, lr.letter_type_id, lr.status, lr.created_at,
               u.username, lt.name as letter_type_name
        FROM letter_requests lr
        LEFT JOIN users u ON lr.user_id = u.id
        LEFT JOIN letter_types lt ON lr.letter_type_id = lt.id
        ORDER BY lr.created_at DESC LIMIT 5
    ');
    $stmt->execute();
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "Recent letter requests:\n";
    if (empty($requests)) {
        echo "  No requests found\n";
    } else {
        foreach($requests as $req) {
            echo "  ID: {$req['id']} | User: {$req['username']} | Type: {$req['letter_type_name']} | Status: {$req['status']} | Date: {$req['created_at']}\n";
        }
    }
    echo "\n";
} catch (Exception $e) {
    echo "❌ Error checking requests: " . $e->getMessage() . "\n\n";
}

// 4. Test form submission simulation
echo "4️⃣ Testing Form Submission Logic:\n";

$testUserId = 14; // First available user
$testLetterTypeId = 1; // SKD

$testData = [
    'user_id' => $testUserId,
    'letter_type_id' => $testLetterTypeId,
    'nama' => 'Test User',
    'nik' => '1234567890123456',
    'alamat' => 'Jl. Test No. 1',
    'keperluan' => 'Untuk testing sistem',
    'alamat_domisili' => 'Jl. Test No. 1, RT 01 RW 01'
];

try {
    require_once 'app/models/LetterRequest.php';
    $letterRequestModel = new LetterRequest();

    echo "Testing data separation for SKD...\n";
    $requestId = $letterRequestModel->createWithDataSeparation($testData);

    if ($requestId) {
        echo "✅ Test request created successfully (ID: $requestId)\n";

        // Verify data
        $request = $letterRequestModel->findById($requestId);
        if ($request) {
            echo "✅ Request data verification:\n";
            echo "  - nama: " . ($request['nama'] ?? 'MISSING') . "\n";
            echo "  - nik: " . ($request['nik'] ?? 'MISSING') . "\n";
            echo "  - keperluan: " . ($request['keperluan'] ?? 'MISSING') . "\n";
            echo "  - alamat_domisili: " . ($request['alamat_domisili'] ?? 'MISSING') . "\n";
        }

        // Clean up test data
        $pdo->prepare("DELETE FROM letter_requests WHERE id = ?")->execute([$requestId]);
        echo "🧹 Test data cleaned up\n";

    } else {
        echo "❌ Test request creation failed\n";
    }
} catch (Exception $e) {
    echo "❌ Error in form submission test: " . $e->getMessage() . "\n";
}

echo "\n🎯 DEBUGGING COMPLETE\n";
echo "====================\n";
echo "\n💡 Possible Issues:\n";
echo "1. Frontend form not sending data correctly\n";
echo "2. CSRF token validation failing\n";
echo "3. User authentication issues\n";
echo "4. JavaScript errors in dynamic form\n";
echo "5. Required field validation errors\n";
echo "\n🔧 Next: Check browser console and network tab for frontend errors\n";
