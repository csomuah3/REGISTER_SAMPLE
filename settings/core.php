<?php
// Settings/core.php
session_start();

//for header redirection
ob_start();

//function to check for login
function isLoggedIn()
{
    if (!isset($_SESSION['user_id'])) {
        return false;
    } else {
        return true;
    }
}


//function to get user ID


//function to check for role (admin, customer, etc)
function isAdmin()
{
    if (isLoggedIn()) {
        return $_SESSION['user_role'] == 2;
    }
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if a user is logged in
 * Returns true if a session has been created, false otherwise
 */
function check_login() {
    // Check if user session variables exist and are not empty
    if (isset($_SESSION['user_id']) && 
        !empty($_SESSION['user_id']) && 
        isset($_SESSION['user_email']) && 
        !empty($_SESSION['user_email'])) {
        return true;
    }
    return false;
}

/**
 * Check if a user has administrative privileges
 * Returns true if user has admin role, false otherwise  
 */
function check_admin() {
    // First check if user is logged in
    if (!check_login()) {
        return false;
    }
    
    // Check if user has admin role
    if (isset($_SESSION['user_role']) && 
        ($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'administrator' || $_SESSION['user_role'] == 1)) {
        return true;
    }
    
    return false;
}

/**
 * Get logged-in user ID
 */
function get_user_id() {
    if (check_login()) {
        return $_SESSION['user_id'];
    }
        return null;
    }
    ?>