<?php
require_once __DIR__ . '/../controllers/product_controller.php';
require_once __DIR__ . '/../controllers/category_controller.php';
require_once __DIR__ . '/../controllers/brand_controller.php';

// Handle product actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {

    $action = $_REQUEST['action'] ?? '';

    switch ($action) {
        case 'view_all_products':
            $products = view_all_products_ctr();
            echo json_encode($products);
            break;

        case 'search_products':
            $query = $_REQUEST['query'] ?? '';
            if (!empty($query)) {
                $products = search_products_ctr($query);
                echo json_encode($products);
            } else {
                echo json_encode(['error' => 'Search query is required']);
            }
            break;

        case 'filter_by_category':
            $cat_id = $_REQUEST['cat_id'] ?? 0;
            if ($cat_id > 0) {
                $products = filter_products_by_category_ctr($cat_id);
                echo json_encode($products);
            } else {
                echo json_encode(['error' => 'Valid category ID is required']);
            }
            break;

        case 'filter_by_brand':
            $brand_id = $_REQUEST['brand_id'] ?? 0;
            if ($brand_id > 0) {
                $products = filter_products_by_brand_ctr($brand_id);
                echo json_encode($products);
            } else {
                echo json_encode(['error' => 'Valid brand ID is required']);
            }
            break;

        case 'view_single_product':
            $product_id = $_REQUEST['product_id'] ?? 0;
            if ($product_id > 0) {
                $product = view_single_product_ctr($product_id);
                echo json_encode($product);
            } else {
                echo json_encode(['error' => 'Valid product ID is required']);
            }
            break;

        case 'get_categories':
            $categories = get_all_categories_ctr();
            echo json_encode($categories);
            break;

        case 'get_brands':
            $brands = get_all_brands_ctr();
            echo json_encode($brands);
            break;

        case 'combined_filter':
            $search_query = $_REQUEST['query'] ?? '';
            $cat_ids = $_REQUEST['cat_ids'] ?? [];
            $brand_ids = $_REQUEST['brand_ids'] ?? [];
            $min_price = isset($_REQUEST['min_price']) ? (float)$_REQUEST['min_price'] : 0;
            $max_price = isset($_REQUEST['max_price']) ? (float)$_REQUEST['max_price'] : PHP_FLOAT_MAX;

            // Start with all products
            $products = view_all_products_ctr();

            // Apply search filter if provided
            if (!empty($search_query)) {
                $products = array_filter($products, function($product) use ($search_query) {
                    return stripos($product['product_title'], $search_query) !== false ||
                           stripos($product['product_desc'], $search_query) !== false ||
                           stripos($product['product_keywords'], $search_query) !== false;
                });
            }

            // Apply category filter (multiple categories)
            if (!empty($cat_ids) && is_array($cat_ids)) {
                $products = array_filter($products, function($product) use ($cat_ids) {
                    return in_array($product['product_cat'], $cat_ids);
                });
            }

            // Apply brand filter (multiple brands)
            if (!empty($brand_ids) && is_array($brand_ids)) {
                $products = array_filter($products, function($product) use ($brand_ids) {
                    return in_array($product['product_brand'], $brand_ids);
                });
            }

            // Apply price range filter
            if ($min_price > 0 || $max_price < PHP_FLOAT_MAX) {
                $products = array_filter($products, function($product) use ($min_price, $max_price) {
                    $price = (float)$product['product_price'];
                    return $price >= $min_price && $price <= $max_price;
                });
            }

            echo json_encode(array_values($products));
            break;

        default:
            echo json_encode(['error' => 'Invalid action']);
            break;
    }
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
?>