<?php
require_once 'config/database.php';

try {
    $pdo = getDBConnection();
    $stmt = $pdo->query('DESCRIBE letter_requests');
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "Letter Requests Table Structure:\n";
    echo "================================\n";

    foreach ($columns as $column) {
        echo sprintf("%-20s %-15s %-10s %-10s %-20s\n",
            $column['Field'],
            $column['Type'],
            $column['Null'],
            $column['Key'],
            $column['Default'] ?? 'NULL'
        );
    }

    // Check if additional_data column exists
    $hasAdditionalData = false;
    foreach ($columns as $column) {
        if ($column['Field'] === 'additional_data') {
            $hasAdditionalData = true;
            break;
        }
    }

    echo "\nAdditional Data Column: " . ($hasAdditionalData ? "✅ EXISTS" : "❌ MISSING") . "\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
