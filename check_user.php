<?php
require_once 'config/database.php';
require_once 'config/constants.php';

try {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare('SELECT id, username, full_name FROM users WHERE id = ?');
    $stmt->execute([1]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo "✅ User exists: ID {$user['id']} - {$user['username']} ({$user['full_name']})\n";
    } else {
        echo "❌ User with ID 1 not found\n";

        // Check total users
        $stmt = $pdo->query('SELECT COUNT(*) as total FROM users');
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "Total users in database: {$result['total']}\n";

        if ($result['total'] > 0) {
            $stmt = $pdo->query('SELECT id, username FROM users LIMIT 5');
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo "First 5 users:\n";
            foreach ($users as $u) {
                echo "  - ID {$u['id']}: {$u['username']}\n";
            }
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
