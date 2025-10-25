<?php
require_once(__DIR__ . '/settings/core.php');
require_once(__DIR__ . '/controllers/product_controller.php');
require_once(__DIR__ . '/controllers/category_controller.php');
require_once(__DIR__ . '/controllers/brand_controller.php');
require_once(__DIR__ . '/helpers/image_helper.php');

$is_logged_in = check_login();
$is_admin = false;

if ($is_logged_in) {
    $is_admin = check_admin();
}

// Get all products, categories, and brands
$products = view_all_products_ctr();
$categories = get_all_categories_ctr();
$brands = get_all_brands_ctr();

// Pagination settings
$products_per_page = 10;
$total_products = count($products);
$total_pages = ceil($total_products / $products_per_page);
$current_page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($current_page - 1) * $products_per_page;
$products_to_display = array_slice($products, $offset, $products_per_page);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>All Products - FlavorHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Dancing+Script:wght@400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 100%);
            color: #1a202c;
            position: relative;
            overflow-x: hidden;
        }

        /* Background Pattern */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background:
                radial-gradient(circle at 20% 20%, rgba(139, 95, 191, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(240, 147, 251, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 40% 60%, rgba(139, 95, 191, 0.03) 0%, transparent 50%);
            z-index: -1;
        }

        .main-header {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
            box-shadow: 0 4px 20px rgba(139, 95, 191, 0.15);
            position: sticky;
            top: 0;
            z-index: 1000;
            padding: 15px 0;
            backdrop-filter: blur(10px);
        }

        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: #8b5fbf;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .page-title {
            background: linear-gradient(135deg, #8b5fbf, #f093fb);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 2.5rem;
            font-weight: 800;
            text-align: center;
            margin: 30px 0;
            position: relative;
        }

        .page-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(135deg, #8b5fbf, #f093fb);
            border-radius: 2px;
        }

        .filters-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 35px;
            border-radius: 25px;
            box-shadow: 0 8px 32px rgba(139, 95, 191, 0.12);
            margin-bottom: 40px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            position: relative;
            overflow: hidden;
        }

        .filters-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(135deg, #8b5fbf, #f093fb);
        }

        .filter-title {
            color: #8b5fbf;
            font-weight: 700;
            font-size: 1.4rem;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .filter-title::before {
            content: '🔍';
            font-size: 1.2rem;
        }

        .filter-group {
            margin-bottom: 25px;
        }

        .filter-label {
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 12px;
            display: block;
            font-size: 1rem;
        }

        .filter-select, .filter-input {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e2e8f0;
            border-radius: 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(248, 250, 252, 0.8);
            backdrop-filter: blur(10px);
        }

        .filter-select:focus, .filter-input:focus {
            outline: none;
            border-color: #8b5fbf;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 0 0 4px rgba(139, 95, 191, 0.1);
            transform: translateY(-2px);
        }

        .clear-filters-btn {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            width: 100%;
            justify-content: center;
        }

        .clear-filters-btn:hover {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(239, 68, 68, 0.3);
        }

        .stats-bar {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            padding: 20px 30px;
            border-radius: 20px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(139, 95, 191, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .product-count {
            font-weight: 600;
            color: #8b5fbf;
            font-size: 1.1rem;
        }

        .view-toggle {
            display: flex;
            gap: 8px;
        }

        .view-btn {
            padding: 8px 12px;
            border: 2px solid #e2e8f0;
            background: white;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .view-btn.active, .view-btn:hover {
            background: #8b5fbf;
            color: white;
            border-color: #8b5fbf;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 30px;
            margin-bottom: 50px;
        }

        .product-grid.list-view {
            grid-template-columns: 1fr;
        }

        .product-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(139, 95, 191, 0.15);
            transition: all 0.4s ease;
            cursor: pointer;
            border: 1px solid rgba(255, 255, 255, 0.3);
            position: relative;
        }

        .product-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(139, 95, 191, 0.05), rgba(240, 147, 251, 0.05));
            opacity: 0;
            transition: all 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 16px 48px rgba(139, 95, 191, 0.25);
        }

        .product-card:hover::before {
            opacity: 1;
        }

        .product-image-container {
            position: relative;
            width: 100%;
            height: 240px;
            overflow: hidden;
            background: linear-gradient(135deg, #f8fafc, #e2e8f0);
        }

        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.4s ease;
        }

        .product-card:hover .product-image {
            transform: scale(1.1);
        }

        .product-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: linear-gradient(135deg, #8b5fbf, #f093fb);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .product-content {
            padding: 25px;
            position: relative;
            z-index: 2;
        }

        .product-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 12px;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-price {
            font-size: 1.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #8b5fbf, #f093fb);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 15px;
        }

        .product-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            gap: 15px;
        }

        .meta-tag {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            background: rgba(139, 95, 191, 0.1);
            border-radius: 20px;
            font-size: 0.85rem;
            color: #8b5fbf;
            font-weight: 500;
        }

        .add-to-cart-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #8b5fbf, #f093fb);
            color: white;
            border: none;
            border-radius: 15px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .add-to-cart-btn:hover {
            background: linear-gradient(135deg, #764ba2, #8b5fbf);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(139, 95, 191, 0.4);
        }

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            margin: 50px 0;
        }

        .page-btn {
            padding: 12px 18px;
            border: 2px solid rgba(139, 95, 191, 0.2);
            background: rgba(255, 255, 255, 0.9);
            color: #8b5fbf;
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-weight: 600;
            backdrop-filter: blur(10px);
        }

        .page-btn:hover, .page-btn.active {
            background: linear-gradient(135deg, #8b5fbf, #f093fb);
            color: white;
            border-color: transparent;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(139, 95, 191, 0.3);
        }

        .no-products {
            text-align: center;
            padding: 80px 20px;
            color: #64748b;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            box-shadow: 0 8px 32px rgba(139, 95, 191, 0.1);
        }

        .no-products-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 15px 25px;
            background: linear-gradient(135deg, #8b5fbf, #f093fb);
            color: white;
            text-decoration: none;
            border-radius: 15px;
            font-weight: 700;
            transition: all 0.3s ease;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(139, 95, 191, 0.3);
        }

        .back-btn:hover {
            background: linear-gradient(135deg, #764ba2, #8b5fbf);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(139, 95, 191, 0.4);
        }

        .hero-bar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 25px 30px;
            margin-bottom: 30px;
            box-shadow: 0 8px 32px rgba(139, 95, 191, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .hero-actions .btn {
            border-radius: 12px;
            padding: 10px 20px;
            font-weight: 600;
            border-width: 2px;
        }

        .search-suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(139, 95, 191, 0.15);
            z-index: 1000;
            max-height: 300px;
            overflow-y: auto;
            margin-top: 5px;
        }

        .suggestion-item {
            padding: 12px 15px;
            border-bottom: 1px solid #f1f5f9;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .suggestion-item:hover {
            background: linear-gradient(135deg, #f8f9ff, #f0f2ff);
            color: #8b5fbf;
        }

        .suggestion-item:last-child {
            border-bottom: none;
        }

        .suggestion-icon {
            color: #8b5fbf;
            font-size: 0.9rem;
        }

        .checkbox-dropdown {
            position: relative;
            width: 100%;
        }

        .dropdown-toggle {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            background: white;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 1rem;
            color: #4a5568;
            transition: all 0.3s ease;
        }

        .dropdown-toggle:hover {
            border-color: #8b5fbf;
            box-shadow: 0 0 0 3px rgba(139, 95, 191, 0.1);
        }

        .dropdown-toggle.active {
            border-color: #8b5fbf;
            box-shadow: 0 0 0 3px rgba(139, 95, 191, 0.1);
        }

        .dropdown-arrow {
            transition: transform 0.3s ease;
            color: #8b5fbf;
        }

        .dropdown-toggle.active .dropdown-arrow {
            transform: rotate(180deg);
        }

        .dropdown-content {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(139, 95, 191, 0.15);
            z-index: 1000;
            max-height: 250px;
            overflow-y: auto;
            margin-top: 5px;
            display: none;
        }

        .dropdown-content.show {
            display: block;
        }

        .checkbox-item {
            padding: 10px 15px;
            border-bottom: 1px solid #f1f5f9;
            transition: all 0.2s ease;
        }

        .checkbox-item:hover {
            background: linear-gradient(135deg, #f8f9ff, #f0f2ff);
        }

        .checkbox-item:last-child {
            border-bottom: none;
        }

        .checkbox-item input[type="checkbox"] {
            margin-right: 10px;
            width: 16px;
            height: 16px;
            accent-color: #8b5fbf;
            cursor: pointer;
        }

        .checkbox-item label {
            cursor: pointer;
            color: #4a5568;
            font-weight: 500;
            margin: 0;
        }

        .checkbox-item:hover label {
            color: #8b5fbf;
        }

        .floating-elements {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .bubble {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(139, 95, 191, 0.1), rgba(240, 147, 251, 0.1));
            animation: float 6s ease-in-out infinite;
        }

        .bubble:nth-child(1) {
            width: 100px;
            height: 100px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .bubble:nth-child(2) {
            width: 150px;
            height: 150px;
            top: 20%;
            right: 15%;
            animation-delay: 2s;
        }

        .bubble:nth-child(3) {
            width: 80px;
            height: 80px;
            bottom: 30%;
            left: 20%;
            animation-delay: 4s;
        }

        .bubble:nth-child(4) {
            width: 120px;
            height: 120px;
            bottom: 20%;
            right: 10%;
            animation-delay: 1s;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
            }
            50% {
                transform: translateY(-20px) rotate(180deg);
            }
        }

        @media (max-width: 768px) {
            .product-grid {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
                gap: 20px;
            }

            .filters-section {
                padding: 20px;
            }

            .page-title {
                font-size: 2rem;
            }

            .stats-bar {
                flex-direction: column;
                align-items: stretch;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <header class="main-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-2">
                    <a href="index.php" class="logo">
                        <i class="fas fa-utensils"></i>
                        <span>FlavorHub</span>
                    </a>
                </div>
                <div class="col-lg-8 text-end">
                    <h1 class="mb-0" style="color: #8b5fbf; font-weight: 700;">All Products</h1>
                </div>
                <div class="col-lg-2 text-end">
                    <div class="d-flex align-items-center justify-content-end gap-3">
                        <!-- Cart Icon -->
                        <a href="#" class="cart-icon position-relative" onclick="showCart()">
                            <i class="fas fa-shopping-cart" style="font-size: 1.5rem; color: #8b5fbf;"></i>
                            <span class="cart-badge position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cartCount">
                                0
                            </span>
                        </a>

                        <?php if ($is_logged_in): ?>
                            <a href="login/logout.php" class="btn btn-outline-danger">Logout</a>
                        <?php else: ?>
                            <a href="login/login.php" class="btn btn-outline-primary">Login</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Floating Background Elements -->
    <div class="floating-elements">
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
    </div>

    <div class="container mt-4">
        <!-- Hero Bar -->
        <div class="hero-bar">
            <div class="d-flex align-items-center justify-content-between">
                <a href="index.php" class="back-btn">
                    <i class="fas fa-arrow-left"></i>
                    Back to Home
                </a>
                <h1 class="page-title mb-0">All Products</h1>
                <div class="hero-actions">
                    <button class="btn btn-outline-primary" onclick="window.location.href='#filters'">
                        <i class="fas fa-filter"></i>
                        Filters
                    </button>
                </div>
            </div>
        </div>

        <div class="filters-section" id="filters">
            <h3 class="filter-title">Filter Products</h3>
            <div class="row">
                <div class="col-md-4">
                    <div class="filter-group">
                        <label class="filter-label">Filter by Category</label>
                        <div class="checkbox-dropdown" id="categoryDropdown">
                            <button type="button" class="dropdown-toggle" data-target="categoryCheckboxes">
                                <span class="dropdown-text">All Categories</span>
                                <i class="fas fa-chevron-down dropdown-arrow"></i>
                            </button>
                            <div class="dropdown-content" id="categoryCheckboxes">
                                <div class="checkbox-item">
                                    <input type="checkbox" id="category_all" value="" checked>
                                    <label for="category_all">All Categories</label>
                                </div>
                                <?php foreach ($categories as $category): ?>
                                    <div class="checkbox-item">
                                        <input type="checkbox" id="category_<?php echo $category['cat_id']; ?>" value="<?php echo $category['cat_id']; ?>" class="category-checkbox">
                                        <label for="category_<?php echo $category['cat_id']; ?>"><?php echo htmlspecialchars($category['cat_name']); ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="filter-group">
                        <label class="filter-label">Filter by Brand</label>
                        <div class="checkbox-dropdown" id="brandDropdown">
                            <button type="button" class="dropdown-toggle" data-target="brandCheckboxes">
                                <span class="dropdown-text">All Brands</span>
                                <i class="fas fa-chevron-down dropdown-arrow"></i>
                            </button>
                            <div class="dropdown-content" id="brandCheckboxes">
                                <div class="checkbox-item">
                                    <input type="checkbox" id="brand_all" value="" checked>
                                    <label for="brand_all">All Brands</label>
                                </div>
                                <?php foreach ($brands as $brand): ?>
                                    <div class="checkbox-item">
                                        <input type="checkbox" id="brand_<?php echo $brand['brand_id']; ?>" value="<?php echo $brand['brand_id']; ?>" class="brand-checkbox">
                                        <label for="brand_<?php echo $brand['brand_id']; ?>"><?php echo htmlspecialchars($brand['brand_name']); ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="filter-group">
                        <label class="filter-label">Search Products</label>
                        <div class="search-autocomplete-container position-relative">
                            <input type="text" class="filter-input" id="searchInput" placeholder="Search by name or keywords..." autocomplete="off">
                            <i class="fas fa-search search-icon" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #8b5fbf; cursor: pointer;" onclick="performInstantSearch()"></i>
                            <div id="searchSuggestions" class="search-suggestions" style="display: none;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <button class="clear-filters-btn" id="clearFilters">
                        <i class="fas fa-times"></i>
                        Clear All Filters
                    </button>
                </div>
            </div>
        </div>

        <div class="stats-bar">
            <div class="product-count">
                <i class="fas fa-box"></i>
                Showing <?php echo count($products_to_display); ?> of <?php echo $total_products; ?> products
            </div>
            <div class="view-toggle">
                <button class="view-btn active" onclick="toggleView('grid')" title="Grid View">
                    <i class="fas fa-th"></i>
                </button>
                <button class="view-btn" onclick="toggleView('list')" title="List View">
                    <i class="fas fa-list"></i>
                </button>
            </div>
        </div>

        <div id="productsContainer">
            <?php if (empty($products_to_display)): ?>
                <div class="no-products">
                    <div class="no-products-icon">📦</div>
                    <h3>No Products Found</h3>
                    <p>There are no products available at the moment.</p>
                </div>
            <?php else: ?>
                <div class="product-grid" id="productGrid">
                    <?php foreach ($products_to_display as $product): ?>
                        <div class="product-card" onclick="viewProduct(<?php echo $product['product_id']; ?>)">
                            <div class="product-image-container">
                                <?php
                                $image_url = get_product_image_url($product['product_image'], $product['product_title'], '320x240');
                                $image_onerror = get_image_onerror($product['product_title'], '320x240');
                                ?>
                                <img src="<?php echo $image_url; ?>"
                                     alt="<?php echo htmlspecialchars($product['product_title']); ?>"
                                     class="product-image"
                                     onerror="<?php echo $image_onerror; ?>">
                                <div class="product-badge">New</div>
                            </div>
                            <div class="product-content">
                                <h5 class="product-title"><?php echo htmlspecialchars($product['product_title']); ?></h5>
                                <div class="product-price">$<?php echo number_format($product['product_price'], 2); ?></div>
                                <div class="product-meta">
                                    <span class="meta-tag">
                                        <i class="fas fa-tag"></i>
                                        <?php echo htmlspecialchars($product['cat_name'] ?? 'N/A'); ?>
                                    </span>
                                    <span class="meta-tag">
                                        <i class="fas fa-store"></i>
                                        <?php echo htmlspecialchars($product['brand_name'] ?? 'N/A'); ?>
                                    </span>
                                </div>
                                <button class="add-to-cart-btn" onclick="event.stopPropagation(); addToCart(<?php echo $product['product_id']; ?>)">
                                    <i class="fas fa-shopping-cart"></i>
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if ($total_pages > 1): ?>
                    <div class="pagination">
                        <?php if ($current_page > 1): ?>
                            <a href="?page=<?php echo $current_page - 1; ?>" class="page-btn">
                                <i class="fas fa-chevron-left"></i> Previous
                            </a>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <a href="?page=<?php echo $i; ?>"
                               class="page-btn <?php echo $i == $current_page ? 'active' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>

                        <?php if ($current_page < $total_pages): ?>
                            <a href="?page=<?php echo $current_page + 1; ?>" class="page-btn">
                                Next <i class="fas fa-chevron-right"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function viewProduct(productId) {
            window.location.href = 'single_product.php?id=' + productId;
        }

        function addToCart(productId) {
            // Add visual feedback
            const btn = event.target.closest('.add-to-cart-btn');
            const originalText = btn.innerHTML;

            btn.innerHTML = '<i class="fas fa-check"></i> Added!';
            btn.style.background = 'linear-gradient(135deg, #10b981, #059669)';

            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.style.background = 'linear-gradient(135deg, #8b5fbf, #f093fb)';
            }, 1500);

            // Here you would normally send AJAX request to add to cart
            console.log('Add to cart functionality - Product ID: ' + productId);

            // Update cart count
            updateCartCount();
        }

        function showCart() {
            alert('Cart functionality will be implemented soon!\nThis will show your cart items.');
        }

        function updateCartCount() {
            // This would normally get the actual cart count from storage/database
            const cartCountElement = document.getElementById('cartCount');
            let currentCount = parseInt(cartCountElement.textContent);
            cartCountElement.textContent = currentCount + 1;
        }

        function toggleView(viewType) {
            const productGrid = document.getElementById('productGrid');
            const viewBtns = document.querySelectorAll('.view-btn');

            // Update button states
            viewBtns.forEach(btn => btn.classList.remove('active'));
            event.target.closest('.view-btn').classList.add('active');

            // Update grid layout
            if (viewType === 'list') {
                productGrid.classList.add('list-view');
            } else {
                productGrid.classList.remove('list-view');
            }
        }

        // JavaScript Image Helper Functions
        function getProductImageUrl(imageFilename, productTitle, size = '320x240') {
            // List of possible image directories to check (in order of preference)
            const imageDirectories = [
                'uploads/products/',
                'uploads/',
                'images/',
                'assets/images/',
                'assets/img/'
            ];

            if (imageFilename) {
                // Check if filename already contains a path
                if (imageFilename.includes('/')) {
                    return imageFilename;
                }

                // Try common directories
                for (let dir of imageDirectories) {
                    // We can't check file existence in JavaScript, so we'll use the first directory
                    return dir + imageFilename;
                }
            }

            // Return placeholder if no image
            return generatePlaceholderUrl(productTitle, size);
        }

        function generatePlaceholderUrl(text, size = '320x240') {
            const encodedText = encodeURIComponent(text);
            return `https://via.placeholder.com/${size}/8b5fbf/ffffff?text=${encodedText}`;
        }

        function getImageOnerror(productTitle, size = '320x240') {
            const placeholderUrl = generatePlaceholderUrl(productTitle, size);
            return `this.src='${placeholderUrl}'`;
        }

        // Autocomplete functionality
        let allProducts = <?php echo json_encode($products); ?>;

        function showSearchSuggestions(query) {
            const suggestions = document.getElementById('searchSuggestions');
            const filteredProducts = allProducts.filter(product =>
                product.product_title.toLowerCase().includes(query.toLowerCase()) ||
                (product.product_keywords && product.product_keywords.toLowerCase().includes(query.toLowerCase()))
            ).slice(0, 5); // Show only top 5 suggestions

            if (filteredProducts.length > 0) {
                let suggestionsHTML = '';
                filteredProducts.forEach(product => {
                    suggestionsHTML += `
                        <div class="suggestion-item" onclick="selectSuggestion('${product.product_title}', ${product.product_id})">
                            <i class="fas fa-search suggestion-icon"></i>
                            <span>${highlightMatch(product.product_title, query)}</span>
                        </div>
                    `;
                });

                // Add "Search for..." option
                suggestionsHTML += `
                    <div class="suggestion-item" onclick="performSearchFor('${query}')">
                        <i class="fas fa-arrow-right suggestion-icon"></i>
                        <span>Search for "<strong>${query}</strong>"</span>
                    </div>
                `;

                suggestions.innerHTML = suggestionsHTML;
                suggestions.style.display = 'block';
            } else {
                suggestions.innerHTML = `
                    <div class="suggestion-item" onclick="performSearchFor('${query}')">
                        <i class="fas fa-search suggestion-icon"></i>
                        <span>Search for "<strong>${query}</strong>"</span>
                    </div>
                `;
                suggestions.style.display = 'block';
            }
        }

        function hideSearchSuggestions() {
            document.getElementById('searchSuggestions').style.display = 'none';
        }

        function highlightMatch(text, query) {
            const regex = new RegExp(`(${query})`, 'gi');
            return text.replace(regex, '<strong>$1</strong>');
        }

        function selectSuggestion(productTitle, productId) {
            document.getElementById('searchInput').value = productTitle;
            hideSearchSuggestions();
            applyFilters();
        }

        function performSearchFor(query) {
            document.getElementById('searchInput').value = query;
            hideSearchSuggestions();
            applyFilters();
        }

        function performInstantSearch() {
            applyFilters();
        }

        // Hide suggestions when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.search-autocomplete-container')) {
                hideSearchSuggestions();
            }
        });

        // Checkbox Dropdown functionality
        function initCheckboxDropdowns() {
            // Initialize dropdowns
            document.querySelectorAll('.dropdown-toggle').forEach(button => {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const content = document.getElementById(targetId);
                    const isOpen = content.classList.contains('show');

                    // Close all dropdowns first
                    document.querySelectorAll('.dropdown-content').forEach(dropdown => {
                        dropdown.classList.remove('show');
                    });
                    document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
                        toggle.classList.remove('active');
                    });

                    // Toggle current dropdown
                    if (!isOpen) {
                        content.classList.add('show');
                        this.classList.add('active');
                    }
                });
            });

            // Handle category checkboxes
            document.querySelectorAll('.category-checkbox, #category_all').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    if (this.id === 'category_all') {
                        // If "All Categories" is checked, uncheck others
                        if (this.checked) {
                            document.querySelectorAll('.category-checkbox').forEach(cb => cb.checked = false);
                        }
                    } else {
                        // If any specific category is checked, uncheck "All Categories"
                        if (this.checked) {
                            document.getElementById('category_all').checked = false;
                        }
                    }
                    updateCategoryDropdownText();
                    applyFilters();
                });
            });

            // Handle brand checkboxes
            document.querySelectorAll('.brand-checkbox, #brand_all').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    if (this.id === 'brand_all') {
                        // If "All Brands" is checked, uncheck others
                        if (this.checked) {
                            document.querySelectorAll('.brand-checkbox').forEach(cb => cb.checked = false);
                        }
                    } else {
                        // If any specific brand is checked, uncheck "All Brands"
                        if (this.checked) {
                            document.getElementById('brand_all').checked = false;
                        }
                    }
                    updateBrandDropdownText();
                    applyFilters();
                });
            });

            // Close dropdowns when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.checkbox-dropdown')) {
                    document.querySelectorAll('.dropdown-content').forEach(dropdown => {
                        dropdown.classList.remove('show');
                    });
                    document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
                        toggle.classList.remove('active');
                    });
                }
            });
        }

        function updateCategoryDropdownText() {
            const checkedCategories = document.querySelectorAll('.category-checkbox:checked');
            const allCategoriesChecked = document.getElementById('category_all').checked;
            const dropdown = document.querySelector('#categoryDropdown .dropdown-text');

            if (allCategoriesChecked || checkedCategories.length === 0) {
                dropdown.textContent = 'All Categories';
            } else if (checkedCategories.length === 1) {
                dropdown.textContent = checkedCategories[0].nextElementSibling.textContent;
            } else {
                dropdown.textContent = `${checkedCategories.length} Categories Selected`;
            }
        }

        function updateBrandDropdownText() {
            const checkedBrands = document.querySelectorAll('.brand-checkbox:checked');
            const allBrandsChecked = document.getElementById('brand_all').checked;
            const dropdown = document.querySelector('#brandDropdown .dropdown-text');

            if (allBrandsChecked || checkedBrands.length === 0) {
                dropdown.textContent = 'All Brands';
            } else if (checkedBrands.length === 1) {
                dropdown.textContent = checkedBrands[0].nextElementSibling.textContent;
            } else {
                dropdown.textContent = `${checkedBrands.length} Brands Selected`;
            }
        }

        // Filter functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const clearFilters = document.getElementById('clearFilters');

            // Initialize checkbox dropdowns
            initCheckboxDropdowns();

            function applyFilters() {
                // Get selected categories
                const selectedCategories = Array.from(document.querySelectorAll('.category-checkbox:checked')).map(cb => cb.value);
                const allCategoriesSelected = document.getElementById('category_all').checked;

                // Get selected brands
                const selectedBrands = Array.from(document.querySelectorAll('.brand-checkbox:checked')).map(cb => cb.value);
                const allBrandsSelected = document.getElementById('brand_all').checked;

                const searchQuery = searchInput.value;

                const params = new URLSearchParams();

                // Add category filters
                if (!allCategoriesSelected && selectedCategories.length > 0) {
                    selectedCategories.forEach(catId => {
                        if (catId) params.append('cat_ids[]', catId);
                    });
                }

                // Add brand filters
                if (!allBrandsSelected && selectedBrands.length > 0) {
                    selectedBrands.forEach(brandId => {
                        if (brandId) params.append('brand_ids[]', brandId);
                    });
                }

                if (searchQuery) params.append('query', searchQuery);
                params.append('action', 'combined_filter');

                fetch('actions/product_actions.php?' + params.toString())
                    .then(response => response.json())
                    .then(data => {
                        updateProductGrid(data);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }

            function updateProductGrid(products) {
                const productGrid = document.getElementById('productGrid');
                const productCount = document.querySelector('.product-count');

                // Update product count
                productCount.innerHTML = `<i class="fas fa-box"></i> Showing ${products.length} products`;

                if (products.length === 0) {
                    productGrid.innerHTML = `
                        <div class="no-products" style="grid-column: 1 / -1;">
                            <div class="no-products-icon">🔍</div>
                            <h3>No Products Found</h3>
                            <p>Try adjusting your filters or search terms.</p>
                        </div>
                    `;
                } else {
                    productGrid.innerHTML = products.map(product => {
                        const imagePath = getProductImageUrl(product.product_image, product.product_title);
                        const imageOnerror = getImageOnerror(product.product_title);

                        return \`
                        <div class="product-card" onclick="viewProduct(\${product.product_id})">
                            <div class="product-image-container">
                                <img src="\${imagePath}"
                                     alt="\${product.product_title}"
                                     class="product-image"
                                     onerror="\${imageOnerror}">
                                <div class="product-badge">New</div>
                            </div>
                            <div class="product-content">
                                <h5 class="product-title">\${product.product_title}</h5>
                                <div class="product-price">$\${parseFloat(product.product_price).toFixed(2)}</div>
                                <div class="product-meta">
                                    <span class="meta-tag">
                                        <i class="fas fa-tag"></i>
                                        \${product.cat_name || 'N/A'}
                                    </span>
                                    <span class="meta-tag">
                                        <i class="fas fa-store"></i>
                                        \${product.brand_name || 'N/A'}
                                    </span>
                                </div>
                                <button class="add-to-cart-btn" onclick="event.stopPropagation(); addToCart(\${product.product_id})">
                                    <i class="fas fa-shopping-cart"></i>
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                        \`;
                    }).join('');
                }
            }

            categoryFilter.addEventListener('change', applyFilters);
            brandFilter.addEventListener('change', applyFilters);

            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);

                const query = this.value.trim();

                if (query.length >= 2) {
                    // Show autocomplete suggestions
                    showSearchSuggestions(query);
                    searchTimeout = setTimeout(applyFilters, 500);
                } else {
                    hideSearchSuggestions();
                    if (query.length === 0) {
                        searchTimeout = setTimeout(applyFilters, 500);
                    }
                }
            });

            clearFilters.addEventListener('click', function() {
                // Reset all checkboxes
                document.querySelectorAll('.category-checkbox, .brand-checkbox').forEach(cb => cb.checked = false);
                document.getElementById('category_all').checked = true;
                document.getElementById('brand_all').checked = true;

                // Reset search input
                searchInput.value = '';

                // Update dropdown texts
                updateCategoryDropdownText();
                updateBrandDropdownText();

                // Apply filters (which will show all products)
                applyFilters();
            });
        });
    </script>
</body>
</html>