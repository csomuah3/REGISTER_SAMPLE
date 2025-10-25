<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Testing index.php components...<br><br>";

try {
    echo "1. Testing core.php inclusion...<br>";
    require_once(__DIR__ . '/settings/core.php');
    echo "✓ Core loaded successfully<br><br>";

    echo "2. Testing category controller...<br>";
    require_once(__DIR__ . '/controllers/category_controller.php');
    echo "✓ Category controller loaded<br><br>";

    echo "3. Testing brand controller...<br>";
    require_once(__DIR__ . '/controllers/brand_controller.php');
    echo "✓ Brand controller loaded<br><br>";

    echo "4. Testing login check...<br>";
    $is_logged_in = check_login();
    echo "✓ Login check completed: " . ($is_logged_in ? 'logged in' : 'not logged in') . "<br><br>";

    echo "5. Testing admin check...<br>";
    if ($is_logged_in) {
        $is_admin = check_admin();
        echo "✓ Admin check completed: " . ($is_admin ? 'is admin' : 'not admin') . "<br><br>";
    } else {
        echo "✓ Skipped admin check (not logged in)<br><br>";
    }

    echo "6. Testing get_all_categories_ctr()...<br>";
    $categories = get_all_categories_ctr();
    echo "✓ Categories loaded: " . count($categories) . " categories found<br><br>";

    echo "7. Testing get_all_brands_ctr()...<br>";
    $brands = get_all_brands_ctr();
    echo "✓ Brands loaded: " . count($brands) . " brands found<br><br>";

    echo "All tests passed! The components should work in index.php<br>";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
    echo "File: " . $e->getFile() . "<br>";
    echo "Line: " . $e->getLine() . "<br>";
} catch (Error $e) {
    echo "❌ Fatal Error: " . $e->getMessage() . "<br>";
    echo "File: " . $e->getFile() . "<br>";
    echo "Line: " . $e->getLine() . "<br>";
}
?>