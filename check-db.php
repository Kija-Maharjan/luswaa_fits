<?php
$output = [];

try {
    $pdo = new PDO('sqlite:database/database.sqlite');
    
    // List tables
    $tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name")->fetchAll(PDO::FETCH_COLUMN);
    $output[] = "Tables in database: " . implode(", ", $tables);
    
    // Check products table
    if (in_array('products', $tables)) {
        $count = $pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
        $output[] = "✓ Products table exists with $count records";
    } else {
        $output[] = "✗ Products table NOT found";
    }
} catch (Exception $e) {
    $output[] = "Error: " . $e->getMessage();
}

echo implode("\n", $output) . "\n";
