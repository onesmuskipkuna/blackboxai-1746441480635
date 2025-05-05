<?php
require_once __DIR__ . '/../config.php';

echo "Testing database connection...\n";

try {
    $conn = getDbConnection();
    echo "Database connection successful.\n";

    // Test a simple query
    $stmt = $conn->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables in database:\n";
    foreach ($tables as $table) {
        echo "- $table\n";
    }
} catch (Exception $e) {
    echo "Database connection failed: " . $e->getMessage() . "\n";
}
?>
