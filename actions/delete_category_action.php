<?php

/**
 * Delete Category Action
 * A script that receives an ID/name of a category and invokes the relevant function 
 * from the category controller to delete that category, and returns a message to the caller
 */

// Include core functions and category controller
require_once(__DIR__ . '/../settings/core.php');
require_once(__DIR__ . '/../controllers/category_controller.php');

// Set content type to JSON for the caller
header('Content-Type: application/json');

// Check if user is logged in
if (!check_login()) {
    echo json_encode([
        'success' => false,
        'message' => 'User not logged in'
    ]);
    exit();
}

// Check if user is admin
if (!check_admin()) {
    echo json_encode([
        'success' => false,
        'message' => 'Access denied. Admin privileges required.'
    ]);
    exit();
}

// Check if request method is POST or DELETE
if (!in_array($_SERVER['REQUEST_METHOD'], ['POST', 'DELETE'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
    exit();
}

// Receive an ID/name of a category
$category_id = 0;
$category_name = '';

// Handle different request methods
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_id = intval($_POST['category_id'] ?? 0);
    $category_name = trim($_POST['category_name'] ?? '');
} else {
    // DELETE method or query parameters
    $category_id = intval($_GET['id'] ?? 0);
    $category_name = trim($_GET['name'] ?? '');
}

$user_id = $_SESSION['user_id'];

// Validate the received data - ID is preferred but name can be used as fallback
if ($category_id <= 0 && empty($category_name)) {
    echo json_encode([
        'success' => false,
        'message' => 'Category ID or name is required'
    ]);
    exit();
}

// If only name is provided, we need to get the ID first
if ($category_id <= 0 && !empty($category_name)) {
    // This would require a function to find category by name - for now we'll require ID
    echo json_encode([
        'success' => false,
        'message' => 'Category ID is required for deletion'
    ]);
    exit();
}

try {
    // Security check - verify category exists and belongs to user
    $existing_category = get_category_ctr($category_id, $user_id);

    if (!$existing_category) {
        echo json_encode([
            'success' => false,
            'message' => 'Category not found or access denied'
        ]);
        exit();
    }

    // Invoke the relevant function from the category controller to delete that category
    $result = delete_category_ctr($category_id, $user_id);

    // Return a message to the caller based on the result
    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => 'Category deleted successfully'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to delete category'
        ]);
    }
} catch (Exception $e) {
    // Return error message to the caller
    echo json_encode([
        'success' => false,
        'message' => 'Error deleting category: ' . $e->getMessage()
    ]);
}
