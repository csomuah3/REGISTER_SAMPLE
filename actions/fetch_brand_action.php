<?php
session_start();
require_once __DIR__ . '/../controllers/brand_controller.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

try {
    $user_id = $_SESSION['user_id'];
    $brands = get_brands_ctr($user_id);

    echo json_encode(['status' => 'success', 'data' => $brands]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to fetch brands: ' . $e->getMessage()]);
}
?>