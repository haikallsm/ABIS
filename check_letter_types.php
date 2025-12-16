<?php
require_once 'config/database.php';
require_once 'config/constants.php';

try {
    $pdo = getDBConnection();
    $stmt = $pdo->query('SELECT id, name, code FROM letter_types ORDER BY id');
    $types = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "Available Letter Types:\n";
    echo "======================\n";
    foreach ($types as $type) {
        echo "ID: {$type['id']} | Code: {$type['code']} | Name: {$type['name']}\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
