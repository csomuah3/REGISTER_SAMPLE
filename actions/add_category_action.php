<?php

/**
 * Add Category Action
 * A script that receives data from the category creation form, 
 * invokes the relevant function from the category controller, 
 * and returns a message to the caller
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

// Check if request method is POST (from the category creation form)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
    exit();
}

// Receive data from the category creation form
$category_name = trim($_POST['category_name'] ?? '');
$user_id = $_SESSION['user_id'];

// Validate the received data
if (empty($category_name)) {
    echo json_encode([
        'success' => false,
        'message' => 'Category name is required'
    ]);
    exit();
}

// Validate category name length
if (strlen($category_name) < 2) {
    echo json_encode([
        'success' => false,
        'message' => 'Category name must be at least 2 characters long'
    ]);
    exit();
}

if (strlen($category_name) > 100) {
    echo json_encode([
        'success' => false,
        'message' => 'Category name must not exceed 100 characters'
    ]);
    exit();
}

try {
    // Invoke the relevant function from the category controller
    $result = add_category_ctr($category_name, $user_id);

    // Return a message to the caller based on the result
    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => 'Category added successfully'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Category already exists or failed to add category'
        ]);
    }
} catch (Exception $e) {
    // Return error message to the caller
    echo json_encode([
        'success' => false,
        'message' => 'Error adding category: ' . $e->getMessage()
    ]);
}
