<?php
session_start();
require_once __DIR__ . '/../controllers/brand_controller.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

    try {
        $result = add_brand_ctr($brand_name, $category_id, $user_id);
        echo json_encode($result);
    } catch (Exception $e) {
        error_log("Brand creation error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Failed to add brand: ' . $e->getMessage()]);
    } catch (Error $e) {
        error_log("Brand creation fatal error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>