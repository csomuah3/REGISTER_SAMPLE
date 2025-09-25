<?php
// Simple DB connection test
require_once __DIR__ . '/settings/db_class.php';

$db = new db_connection();

if ($db->db_connect()) {
    echo "<h2 style='color:green'>✅ Database connection successful!</h2>";

    // Try a simple query on customer table
    $sql = "SELECT COUNT(*) AS total FROM customer";
    if ($db->db_query($sql)) {
        $row = mysqli_fetch_assoc($db->results);
        echo "<p>Customer rows in table: <b>" . $row['total'] . "</b></p>";
    } else {
        echo "<p style='color:orange'>⚠️ Query failed. Check if table 'customer' exists.</p>";
    }
} else {
    echo "<h2 style='color:red'>❌ Database connection failed.</h2>";
}
