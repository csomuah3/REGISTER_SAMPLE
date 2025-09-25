<?php

/**
 * Category Class - extends database connection
 * Contains category methods: add category, edit category, delete category, get category, etc.
 */

// Include database connection
require_once(__DIR__ . '/../settings/db_class.php');

class Category extends db_connection
{

    /**
     * Add a new category
     */
    public function add_category($category_name, $user_id)
    {
        // Check if category name already exists for this user
        $check_sql = "SELECT cat_id FROM categories WHERE cat_name = ? AND user_id = ?";
        $check_result = $this->db_query($check_sql, $category_name, $user_id);

        if ($this->db_count($check_result) > 0) {
            return false; // Category already exists
        }

        // Insert new category
        $sql = "INSERT INTO categories (cat_name, user_id) VALUES (?, ?)";
        return $this->db_query($sql, $category_name, $user_id);
    }

    /**
     * Get all categories for a specific user
     */
    public function get_user_categories($user_id)
    {
        $sql = "SELECT cat_id, cat_name, created_at FROM categories WHERE user_id = ? ORDER BY cat_name ASC";
        return $this->db_fetch_all($sql, $user_id);
    }

    /**
     * Get a single category by ID
     */
    public function get_category($cat_id, $user_id)
    {
        $sql = "SELECT cat_id, cat_name FROM categories WHERE cat_id = ? AND user_id = ?";
        return $this->db_fetch_one($sql, $cat_id, $user_id);
    }

    /**
     * Update category name
     */
    public function update_category($cat_id, $category_name, $user_id)
    {
        // Check if new name already exists (excluding current category)
        $check_sql = "SELECT cat_id FROM categories WHERE cat_name = ? AND user_id = ? AND cat_id != ?";
        $check_result = $this->db_query($check_sql, $category_name, $user_id, $cat_id);

        if ($this->db_count($check_result) > 0) {
            return false; // Category name already exists
        }

        // Update category
        $sql = "UPDATE categories SET cat_name = ? WHERE cat_id = ? AND user_id = ?";
        return $this->db_query($sql, $category_name, $cat_id, $user_id);
    }

    /**
     * Delete a category
     */
    public function delete_category($cat_id, $user_id)
    {
        $sql = "DELETE FROM categories WHERE cat_id = ? AND user_id = ?";
        return $this->db_query($sql, $cat_id, $user_id);
    }
}
