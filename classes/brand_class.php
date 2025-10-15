<?php
require_once __DIR__ . '/../settings/db_class.php';

class Brand extends db_connection {

    // Check if the brands table has the required columns
    private function check_table_structure() {
        try {
            // Try to add the columns if they don't exist
            $this->db_write_query("ALTER TABLE brands ADD COLUMN category_id INT(11) DEFAULT 1 AFTER brand_name");
        } catch (Exception $e) {
            // Column probably already exists
        }

        try {
            $this->db_write_query("ALTER TABLE brands ADD COLUMN user_id INT(11) DEFAULT 1 AFTER category_id");
        } catch (Exception $e) {
            // Column probably already exists
        }
    }

    // Add a new brand
    public function add_brand($brand_name, $category_id, $user_id) {
        $this->check_table_structure();

        // Escape values for safety
        $brand_name = mysqli_real_escape_string($this->db, $brand_name);
        $category_id = (int)$category_id;
        $user_id = (int)$user_id;

        // Check if columns exist by trying different queries
        try {
            $sql = "INSERT INTO brands (brand_name, category_id, user_id) VALUES ('$brand_name', $category_id, $user_id)";
            return $this->db_write_query($sql);
        } catch (Exception $e) {
            // Fallback to basic structure
            $sql = "INSERT INTO brands (brand_name) VALUES ('$brand_name')";
            return $this->db_write_query($sql);
        }
    }

    // Get all brands for a specific user
    public function get_brands_by_user($user_id) {
        try {
            $sql = "SELECT b.brand_id, b.brand_name,
                           COALESCE(b.category_id, 1) as category_id,
                           COALESCE(c.cat_name, 'General') as cat_name
                    FROM brands b
                    LEFT JOIN categories c ON b.category_id = c.cat_id
                    WHERE COALESCE(b.user_id, ?) = ?
                    ORDER BY c.cat_name, b.brand_name";
            return $this->db_fetch_all($sql, $user_id, $user_id);
        } catch (Exception $e) {
            // Fallback to basic query
            $sql = "SELECT brand_id, brand_name, 1 as category_id, 'General' as cat_name FROM brands ORDER BY brand_name";
            return $this->db_fetch_all($sql);
        }
    }

    // Get a specific brand by ID
    public function get_brand_by_id($brand_id) {
        try {
            $sql = "SELECT b.brand_id, b.brand_name,
                           COALESCE(b.category_id, 1) as category_id,
                           COALESCE(c.cat_name, 'General') as cat_name
                    FROM brands b
                    LEFT JOIN categories c ON b.category_id = c.cat_id
                    WHERE b.brand_id = ?";
            return $this->db_fetch_one($sql, $brand_id);
        } catch (Exception $e) {
            // Fallback to basic query
            $sql = "SELECT brand_id, brand_name, 1 as category_id, 'General' as cat_name FROM brands WHERE brand_id = ?";
            return $this->db_fetch_one($sql, $brand_id);
        }
    }

    // Update a brand
    public function update_brand($brand_id, $brand_name, $category_id) {
        // Escape values for safety
        $brand_name = mysqli_real_escape_string($this->db, $brand_name);
        $category_id = (int)$category_id;
        $brand_id = (int)$brand_id;

        try {
            $sql = "UPDATE brands SET brand_name = '$brand_name', category_id = $category_id WHERE brand_id = $brand_id";
            return $this->db_write_query($sql);
        } catch (Exception $e) {
            // Fallback to basic update
            $sql = "UPDATE brands SET brand_name = '$brand_name' WHERE brand_id = $brand_id";
            return $this->db_write_query($sql);
        }
    }

    // Delete a brand
    public function delete_brand($brand_id) {
        $brand_id = (int)$brand_id;
        $sql = "DELETE FROM brands WHERE brand_id = $brand_id";
        return $this->db_write_query($sql);
    }

    // Check if brand name exists
    public function check_brand_exists($brand_name, $category_id = null, $user_id = null, $brand_id = null) {
        try {
            $brand_name = mysqli_real_escape_string($this->db, $brand_name);
            if ($brand_id) {
                $brand_id = (int)$brand_id;
                $sql = "SELECT brand_id FROM brands WHERE brand_name = '$brand_name' AND brand_id != $brand_id";
                return $this->db_fetch_one($sql);
            } else {
                $sql = "SELECT brand_id FROM brands WHERE brand_name = '$brand_name'";
                return $this->db_fetch_one($sql);
            }
        } catch (Exception $e) {
            return false;
        }
    }

    // Get all brands (for admin)
    public function get_all_brands() {
        try {
            $sql = "SELECT b.brand_id, b.brand_name,
                           COALESCE(b.category_id, 1) as category_id,
                           COALESCE(c.cat_name, 'General') as cat_name
                    FROM brands b
                    LEFT JOIN categories c ON b.category_id = c.cat_id
                    ORDER BY c.cat_name, b.brand_name";
            return $this->db_fetch_all($sql);
        } catch (Exception $e) {
            // Fallback to basic query
            $sql = "SELECT brand_id, brand_name, 1 as category_id, 'General' as cat_name FROM brands ORDER BY brand_name";
            return $this->db_fetch_all($sql);
        }
    }
}
?>