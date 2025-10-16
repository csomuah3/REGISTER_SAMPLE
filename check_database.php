<?php
require_once __DIR__ . '/settings/db_class.php';

try {
    $db = new db_connection();

    echo "<h2>Database Structure Check</h2>";

    // Check if brands table exists
    $sql = "SHOW TABLES LIKE 'brands'";
    $result = $db->db_fetch_one($sql);

    if ($result) {
        echo "<h3>Brands table exists!</h3>";
        // Show brands table structure
        $sql = "DESCRIBE brands";
        $columns = $db->db_fetch_all($sql);
        echo "<h4>Brands table structure:</h4>";
        echo "<pre>";
        print_r($columns);
        echo "</pre>";
    } else {
        echo "<h3>Brands table does NOT exist - need to create it</h3>";
    }

    // Check categories table structure
    $sql = "SHOW TABLES LIKE 'categories'";
    $result = $db->db_fetch_one($sql);

    if ($result) {
        echo "<h3>Categories table exists!</h3>";
        // Show categories table structure
        $sql = "DESCRIBE categories";
        $columns = $db->db_fetch_all($sql);
        echo "<h4>Categories table structure:</h4>";
        echo "<pre>";
        print_r($columns);
        echo "</pre>";
    } else {
        echo "<h3>Categories table does NOT exist</h3>";
    }

    // Show all tables
    $sql = "SHOW TABLES";
    $tables = $db->db_fetch_all($sql);
    echo "<h3>All tables in database:</h3>";
    echo "<pre>";
    print_r($tables);
    echo "</pre>";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
