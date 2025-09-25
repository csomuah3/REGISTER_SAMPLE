<?php

/**
 * Core Functions - Updated to match your actual session variables
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if a user is logged in
 * Updated to match your actual session variable names
 */
function check_login()
{
    // Check if user session variables exist and are not empty
    // Your login sets: user_id, role, name, email
    if (
        isset($_SESSION['user_id']) &&
        !empty($_SESSION['user_id']) &&
        isset($_SESSION['email']) &&
        !empty($_SESSION['email'])
    ) {
        return true;
    }
    return false;
}

/**
 * Check if a user has administrative privileges
 * Updated to check your 'role' session variable
 */
function check_admin()
{
    // First check if user is logged in
    if (!check_login()) {
        return false;
    }

    // Check if user has admin role
    // Your login sets $_SESSION['role'] with user_role value
    if (
        isset($_SESSION['role']) &&
        ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'administrator' || $_SESSION['role'] == 1)
    ) {
        return true;
    }

    return false;
}

/**
 * Get logged-in user ID
 */
function get_user_id()
{
    if (check_login()) {
        return $_SESSION['user_id'];
    }
    return null;
}

/**
 * Get logged-in user email  
 */
function get_user_email()
{
    if (check_login()) {
        return $_SESSION['email']; // Your login sets 'email' not 'user_email'
    }
    return null;
}

/**
 * Get logged-in user name
 */
function get_user_name()
{
    if (check_login()) {
        return $_SESSION['name']; // Your login sets 'name' not 'user_firstname'
    }
    return null;
}
