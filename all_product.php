<?php
require_once(__DIR__ . '/settings/core.php');
require_once(__DIR__ . '/controllers/product_controller.php');
require_once(__DIR__ . '/controllers/category_controller.php');
require_once(__DIR__ . '/controllers/brand_controller.php');

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
            background-color: #f8fafc;
            color: #1a202c;
        }

        .main-header {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
            box-shadow: 0 2px 10px rgba(139, 95, 191, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
            padding: 12px 0;
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

        .filters-section {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(139, 95, 191, 0.1);
            margin-bottom: 30px;
        }

        .filter-group {
            margin-bottom: 20px;
        }

        .filter-label {
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 8px;
            display: block;
        }

        .filter-select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8fafc;
        }

        .filter-select:focus {
            outline: none;
            border-color: #8b5fbf;
            background: white;
            box-shadow: 0 0 0 3px rgba(139, 95, 191, 0.1);
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .product-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(139, 95, 191, 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(139, 95, 191, 0.2);
        }

        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background: #f8fafc;
        }

        .product-content {
            padding: 20px;
        }

        .product-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 8px;
            line-height: 1.4;
        }

        .product-price {
            font-size: 1.3rem;
            font-weight: 700;
            color: #8b5fbf;
            margin-bottom: 10px;
        }

        .product-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            font-size: 0.9rem;
            color: #64748b;
        }

        .add-to-cart-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #8b5fbf, #f093fb);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .add-to-cart-btn:hover {
            background: linear-gradient(135deg, #764ba2, #8b5fbf);
            transform: scale(1.02);
        }

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin: 40px 0;
        }

        .page-btn {
            padding: 10px 15px;
            border: 2px solid #e2e8f0;
            background: white;
            color: #4a5568;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .page-btn:hover, .page-btn.active {
            background: #8b5fbf;
            color: white;
            border-color: #8b5fbf;
        }

        .no-products {
            text-align: center;
            padding: 60px 20px;
            color: #64748b;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            background: linear-gradient(135deg, #8b5fbf, #f093fb);
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-bottom: 30px;
        }

        .back-btn:hover {
            background: linear-gradient(135deg, #764ba2, #8b5fbf);
            color: white;
            transform: scale(1.02);
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
                    <?php if ($is_logged_in): ?>
                        <a href="login/logout.php" class="btn btn-outline-danger">Logout</a>
                    <?php else: ?>
                        <a href="login/login.php" class="btn btn-outline-primary">Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <div class="container mt-4">
        <a href="index.php" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Back to Home
        </a>

        <div class="filters-section">
            <h3 class="mb-4" style="color: #8b5fbf;">Filter Products</h3>
            <div class="row">
                <div class="col-md-4">
                    <div class="filter-group">
                        <label class="filter-label">Filter by Category</label>
                        <select class="filter-select" id="categoryFilter">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['cat_id']; ?>">
                                    <?php echo htmlspecialchars($category['cat_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="filter-group">
                        <label class="filter-label">Filter by Brand</label>
                        <select class="filter-select" id="brandFilter">
                            <option value="">All Brands</option>
                            <?php foreach ($brands as $brand): ?>
                                <option value="<?php echo $brand['brand_id']; ?>">
                                    <?php echo htmlspecialchars($brand['brand_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="filter-group">
                        <label class="filter-label">Search Products</label>
                        <input type="text" class="filter-select" id="searchInput" placeholder="Search by name or keywords...">
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <button class="btn btn-primary" id="clearFilters">Clear All Filters</button>
                </div>
            </div>
        </div>

        <div id="productsContainer">
            <?php if (empty($products_to_display)): ?>
                <div class="no-products">
                    <i class="fas fa-box-open fa-4x mb-3" style="color: #cbd5e0;"></i>
                    <h3>No Products Found</h3>
                    <p>There are no products available at the moment.</p>
                </div>
            <?php else: ?>
                <div class="product-grid" id="productGrid">
                    <?php foreach ($products_to_display as $product): ?>
                        <div class="product-card" onclick="viewProduct(<?php echo $product['product_id']; ?>)">
                            <img src="<?php echo !empty($product['product_image']) ? 'images/' . htmlspecialchars($product['product_image']) : 'images/no-image.jpg'; ?>"
                                 alt="<?php echo htmlspecialchars($product['product_title']); ?>"
                                 class="product-image"
                                 onerror="this.src='images/no-image.jpg'">
                            <div class="product-content">
                                <h5 class="product-title"><?php echo htmlspecialchars($product['product_title']); ?></h5>
                                <div class="product-price">$<?php echo number_format($product['product_price'], 2); ?></div>
                                <div class="product-meta">
                                    <span><i class="fas fa-tag"></i> <?php echo htmlspecialchars($product['cat_name'] ?? 'N/A'); ?></span>
                                    <span><i class="fas fa-store"></i> <?php echo htmlspecialchars($product['brand_name'] ?? 'N/A'); ?></span>
                                </div>
                                <button class="add-to-cart-btn" onclick="event.stopPropagation(); addToCart(<?php echo $product['product_id']; ?>)">
                                    <i class="fas fa-shopping-cart"></i> Add to Cart
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
            alert('Add to cart functionality - Product ID: ' + productId);
        }

        // Filter functionality
        document.addEventListener('DOMContentLoaded', function() {
            const categoryFilter = document.getElementById('categoryFilter');
            const brandFilter = document.getElementById('brandFilter');
            const searchInput = document.getElementById('searchInput');
            const clearFilters = document.getElementById('clearFilters');

            function applyFilters() {
                const categoryId = categoryFilter.value;
                const brandId = brandFilter.value;
                const searchQuery = searchInput.value;

                const params = new URLSearchParams();
                if (categoryId) params.append('cat_id', categoryId);
                if (brandId) params.append('brand_id', brandId);
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

                if (products.length === 0) {
                    productGrid.innerHTML = `
                        <div class="no-products col-12">
                            <i class="fas fa-search fa-4x mb-3" style="color: #cbd5e0;"></i>
                            <h3>No Products Found</h3>
                            <p>Try adjusting your filters or search terms.</p>
                        </div>
                    `;
                } else {
                    productGrid.innerHTML = products.map(product => `
                        <div class="product-card" onclick="viewProduct(${product.product_id})">
                            <img src="${product.product_image ? 'images/' + product.product_image : 'images/no-image.jpg'}"
                                 alt="${product.product_title}"
                                 class="product-image"
                                 onerror="this.src='images/no-image.jpg'">
                            <div class="product-content">
                                <h5 class="product-title">${product.product_title}</h5>
                                <div class="product-price">$${parseFloat(product.product_price).toFixed(2)}</div>
                                <div class="product-meta">
                                    <span><i class="fas fa-tag"></i> ${product.cat_name || 'N/A'}</span>
                                    <span><i class="fas fa-store"></i> ${product.brand_name || 'N/A'}</span>
                                </div>
                                <button class="add-to-cart-btn" onclick="event.stopPropagation(); addToCart(${product.product_id})">
                                    <i class="fas fa-shopping-cart"></i> Add to Cart
                                </button>
                            </div>
                        </div>
                    `).join('');
                }
            }

            categoryFilter.addEventListener('change', applyFilters);
            brandFilter.addEventListener('change', applyFilters);

            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(applyFilters, 500);
            });

            clearFilters.addEventListener('click', function() {
                categoryFilter.value = '';
                brandFilter.value = '';
                searchInput.value = '';
                location.reload();
            });
        });
    </script>
</body>
</html>