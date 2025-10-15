<?php
require_once __DIR__ . '/../settings/db_class.php';

class Brand extends db_connection {

    // Add a new brand
    public function add_brand($brand_name, $category_id, $user_id) {
        $sql = "INSERT INTO brands (brand_name, category_id, user_id) VALUES (?, ?, ?)";
        return $this->db_query($sql, $brand_name, $category_id, $user_id);
    }

    // Get all brands for a specific user
    public function get_brands_by_user($user_id) {
        $sql = "SELECT b.brand_id, b.brand_name, b.category_id, c.cat_name
                FROM brands b
                JOIN categories c ON b.category_id = c.cat_id
                WHERE b.user_id = ?
                ORDER BY c.cat_name, b.brand_name";
        return $this->db_fetch_all($sql, $user_id);
    }

    // Get a specific brand by ID
    public function get_brand_by_id($brand_id) {
        $sql = "SELECT b.brand_id, b.brand_name, b.category_id, c.cat_name
                FROM brands b
                JOIN categories c ON b.category_id = c.cat_id
                WHERE b.brand_id = ?";
        return $this->db_fetch_one($sql, $brand_id);
    }

    // Update a brand
    public function update_brand($brand_id, $brand_name, $category_id) {
        $sql = "UPDATE brands SET brand_name = ?, category_id = ? WHERE brand_id = ?";
        return $this->db_query($sql, $brand_name, $category_id, $brand_id);
    }

    // Delete a brand
    public function delete_brand($brand_id) {
        $sql = "DELETE FROM brands WHERE brand_id = ?";
        return $this->db_query($sql, $brand_id);
    }

    // Check if brand name + category combination exists for a user
    public function check_brand_exists($brand_name, $category_id, $user_id, $brand_id = null) {
        if ($brand_id) {
            $sql = "SELECT brand_id FROM brands WHERE brand_name = ? AND category_id = ? AND user_id = ? AND brand_id != ?";
            return $this->db_fetch_one($sql, $brand_name, $category_id, $user_id, $brand_id);
        } else {
            $sql = "SELECT brand_id FROM brands WHERE brand_name = ? AND category_id = ? AND user_id = ?";
            return $this->db_fetch_one($sql, $brand_name, $category_id, $user_id);
        }
    }
}
?>