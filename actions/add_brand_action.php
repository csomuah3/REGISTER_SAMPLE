<?php
session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Include the brand class directly
        require_once __DIR__ . '/../classes/brand_class.php';

        $brand_name = trim($_POST['brand_name'] ?? '');
        $category_id = (int)($_POST['category_id'] ?? 0);
        $user_id = $_SESSION['user_id'];

        // Validate input
        if (empty($brand_name)) {
            echo json_encode(['status' => 'error', 'message' => 'Brand name is required']);
            exit;
        }

        if ($category_id <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'Please select a valid category']);
            exit;
        }

        // Create brand instance and add brand directly
        $brand = new Brand();
        $result = $brand->add_brand($brand_name, $category_id, $user_id);

        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Brand added successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add brand to database']);
        }

    } catch (Exception $e) {
        error_log("Brand creation error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
    } catch (Error $e) {
        error_log("Brand creation fatal error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Fatal error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>