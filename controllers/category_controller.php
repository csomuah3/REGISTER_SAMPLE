<?php

/**
 * Category Controller
 * Creates an instance of the category class and runs the methods
 * For this lab, you need an add_category_ctr($kwargs) method to invoke the category_class::add($args) method
 */

// Include the category class
require_once(__DIR__ . '/../classes/category_class.php');

/**
 * Add a new category
 */
function add_category_ctr($category_name, $user_id)
{
    $category = new Category();
    return $category->add_category($category_name, $user_id);
}

/**
 * Get all categories for a user
 */
function get_user_categories_ctr($user_id)
{
    $category = new Category();
    return $category->get_user_categories($user_id);
}

/**
 * Get a single category
 */
function get_category_ctr($cat_id, $user_id)
{
    $category = new Category();
    return $category->get_category($cat_id, $user_id);
}

/**
 * Update a category
 */
function update_category_ctr($cat_id, $category_name, $user_id)
{
    $category = new Category();
    return $category->update_category($cat_id, $category_name, $user_id);
}

/**
 * Delete a category
 */
function delete_category_ctr($cat_id, $user_id)
{
    $category = new Category();
    return $category->delete_category($cat_id, $user_id);
}
