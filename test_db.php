<?php
require_once __DIR__ . '/settings/db_class.php';

echo "<h2>Database Connection Test</h2>";

try {
    $db = new db_connection();
    echo "Database class instantiated successfully<br>";

    $connection = $db->db_connect();
    if ($connection) {
        echo "✅ Database connection successful<br>";

        // Test a simple query
        $result = $db->db_query("SELECT 1 as test");
        if ($result) {
            echo "✅ Query execution successful<br>";
        } else {
            echo "❌ Query execution failed<br>";
        }

        // Test brands table
        $brands = $db->db_query("SELECT * FROM brands LIMIT 1");
        if ($brands) {
            echo "✅ Brands table accessible<br>";
        } else {
            echo "❌ Brands table not accessible<br>";
        }

    } else {
        echo "❌ Database connection failed<br>";
        echo "MySQL Error: " . mysqli_connect_error() . "<br>";
    }

} catch (Exception $e) {
    echo "❌ Exception: " . $e->getMessage() . "<br>";
} catch (Error $e) {
    echo "❌ Fatal Error: " . $e->getMessage() . "<br>";
}

echo "<br><h3>Database Constants:</h3>";
echo "SERVER: " . (defined('SERVER') ? SERVER : 'NOT DEFINED') . "<br>";
echo "USERNAME: " . (defined('USERNAME') ? USERNAME : 'NOT DEFINED') . "<br>";
echo "DATABASE: " . (defined('DATABASE') ? DATABASE : 'NOT DEFINED') . "<br>";
?>