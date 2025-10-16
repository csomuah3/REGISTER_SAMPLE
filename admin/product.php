<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../settings/core.php';
require_admin(); // only admins
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Manage Products</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Import Google Fonts */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        /* Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #f8f9ff 0%, #f1f5f9 50%, #e2e8f0 100%);
            color: #1a202c;
            padding: 0 !important;
            position: relative;
            overflow-x: hidden;
            min-height: 100vh;
        }

        /* Animated Background Circles */
        .bg-circle {
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            z-index: 1;
        }

        .bg-circle-1 {
            width: 200px;
            height: 200px;
            background: linear-gradient(135deg, rgba(139, 95, 191, 0.1), rgba(240, 147, 251, 0.1));
            top: 10%;
            left: -5%;
            animation: float 6s ease-in-out infinite;
        }

        .bg-circle-2 {
            width: 150px;
            height: 150px;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(168, 85, 247, 0.1));
            top: 70%;
            right: -5%;
            animation: float 8s ease-in-out infinite reverse;
        }

        .bg-circle-3 {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, rgba(236, 72, 153, 0.1), rgba(251, 113, 133, 0.1));
            top: 40%;
            left: 70%;
            animation: float 7s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        /* Layout */
        .main-container {
            display: flex;
            min-height: 100vh;
            position: relative;
            z-index: 2;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, #8b5fbf 0%, #6366f1 50%, #a855f7 100%);
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 4px 0 24px rgba(139, 95, 191, 0.15);
        }

        .sidebar-header {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar-title {
            color: #e2e8f0;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .sidebar-subtitle {
            color: rgba(226, 232, 240, 0.8);
            font-size: 0.875rem;
            font-weight: 400;
        }

        .sidebar-nav {
            padding: 1.5rem 0;
        }

        .nav-item {
            margin-bottom: 0.5rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.875rem 1.5rem;
            color: rgba(226, 232, 240, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            border-radius: 0;
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            transform: translateX(4px);
        }

        .nav-link.active {
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
            border-right: 3px solid #ffffff;
        }

        .nav-link i {
            margin-right: 0.75rem;
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 280px;
            background: transparent;
        }

        /* Header */
        .main-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(139, 95, 191, 0.1);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: 0 2px 20px rgba(139, 95, 191, 0.08);
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: #8b5fbf;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .logo:hover {
            color: #6366f1;
            transform: scale(1.05);
        }

        .co {
            background: linear-gradient(135deg, #8b5fbf, #a855f7);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            margin-left: 0.5rem;
            box-shadow: 0 2px 8px rgba(139, 95, 191, 0.3);
        }

        /* Content Area */
        .content-area {
            padding: 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 2rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Cards */
        .admin-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 1rem;
            border: 1px solid rgba(139, 95, 191, 0.1);
            box-shadow: 0 8px 32px rgba(139, 95, 191, 0.1);
            margin-bottom: 2rem;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .admin-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(139, 95, 191, 0.15);
        }

        .card-header {
            background: linear-gradient(135deg, #8b5fbf, #6366f1);
            color: white;
            padding: 1.25rem 1.5rem;
            font-weight: 600;
            font-size: 1.1rem;
            border: none;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Form Elements */
        .form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            border: 2px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 0.75rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
        }

        .form-control:focus, .form-select:focus {
            border-color: #8b5fbf;
            box-shadow: 0 0 0 3px rgba(139, 95, 191, 0.1);
            background: #ffffff;
        }

        .form-control::placeholder {
            color: #9ca3af;
        }

        /* Buttons */
        .btn {
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #8b5fbf, #6366f1);
            color: white;
            box-shadow: 0 4px 12px rgba(139, 95, 191, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(139, 95, 191, 0.4);
            background: linear-gradient(135deg, #7c3aed, #5b21b6);
        }

        .btn-edit {
            background: linear-gradient(135deg, #06b6d4, #0891b2);
            color: white;
            font-size: 0.875rem;
            padding: 0.5rem 1rem;
        }

        .btn-edit:hover {
            background: linear-gradient(135deg, #0891b2, #0e7490);
            transform: translateY(-1px);
        }

        .btn-delete {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            font-size: 0.875rem;
            padding: 0.5rem 1rem;
        }

        .btn-delete:hover {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .btn-secondary:hover {
            background: #4b5563;
            transform: translateY(-1px);
        }

        /* Table */
        .table-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(139, 95, 191, 0.1);
        }

        .table {
            margin: 0;
            background: transparent;
        }

        .table thead th {
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            color: #374151;
            font-weight: 600;
            border: none;
            padding: 1rem;
            text-transform: uppercase;
            font-size: 0.875rem;
            letter-spacing: 0.05em;
        }

        .table tbody td {
            padding: 1rem;
            border-top: 1px solid #e5e7eb;
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background: rgba(139, 95, 191, 0.05);
        }

        .product-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 0.375rem;
            border: 2px solid #e5e7eb;
        }

        /* Upload Features */
        .profile-img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border: 4px solid #8b5fbf;
            transition: all 0.3s ease;
        }

        .profile-img:hover {
            transform: scale(1.05);
            border-color: #6366f1;
        }

        .uploaded-image-item {
            position: relative;
            margin-bottom: 1rem;
        }

        .uploaded-image {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 0.5rem;
            border: 2px solid #e5e7eb;
            transition: all 0.3s ease;
        }

        .uploaded-image:hover {
            border-color: #8b5fbf;
            transform: scale(1.02);
        }

        .image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(139, 95, 191, 0.8);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            border-radius: 0.5rem;
            cursor: pointer;
        }

        .uploaded-image-item:hover .image-overlay {
            opacity: 1;
        }

        .copy-url-btn {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            border-radius: 0.25rem;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }

        .copy-url-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
        }

        .progress {
            height: 10px;
            border-radius: 5px;
            background: #f3f4f6;
        }

        .progress-bar {
            background: linear-gradient(135deg, #8b5fbf, #6366f1);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .content-area {
                padding: 1rem;
            }

            .page-title {
                font-size: 2rem;
            }
        }
    </style>
</head>

<body>
    <!-- Background Circles -->
    <div class="bg-circle bg-circle-1"></div>
    <div class="bg-circle bg-circle-2"></div>
    <div class="bg-circle bg-circle-3"></div>

    <div class="main-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h1 class="sidebar-title">Admin Panel</h1>
                <p class="sidebar-subtitle">Manage your products</p>
            </div>
            <nav class="sidebar-nav">
                <div class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-home"></i>
                        Dashboard
                    </a>
                </div>
                <div class="nav-item">
                    <a href="category.php" class="nav-link">
                        <i class="fas fa-list"></i>
                        Categories
                    </a>
                </div>
                <div class="nav-item">
                    <a href="brand.php" class="nav-link">
                        <i class="fas fa-tags"></i>
                        Brands
                    </a>
                </div>
                <div class="nav-item">
                    <a href="product.php" class="nav-link active">
                        <i class="fas fa-box"></i>
                        Products
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-shopping-cart"></i>
                        Orders
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-users"></i>
                        Customers
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-chart-bar"></i>
                        Analytics
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-cog"></i>
                        Settings
                    </a>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <header class="main-header">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <!-- Logo -->
                        <a href="../index.php" class="logo">
                            FlavourHub<span class="co">co</span>
                        </a>

                        <!-- Header Actions -->
                        <div>
                            <span class="me-3">Welcome, <?= htmlspecialchars($_SESSION['name'] ?? 'Admin') ?>!</span>
                            <a href="../index.php" class="btn btn-secondary btn-sm me-2">Back to Home</a>
                            <a href="../login/logout.php" class="btn btn-outline-secondary btn-sm">Logout</a>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <div class="content-area">
                <h1 class="page-title">Product Management</h1>

                <!-- Upload Features Row -->
                <div class="row mb-4">
                    <!-- Bulk Image Upload -->
                    <div class="col-md-8">
                        <div class="admin-card">
                            <div class="card-header">
                                <i class="fas fa-images me-2"></i>Bulk Image Upload for Products
                            </div>
                            <div class="card-body">
                                <form id="bulkUploadForm" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label for="bulk_images" class="form-label">Select Product Images</label>
                                        <input type="file" class="form-control" id="bulk_images" name="images[]" multiple accept="image/*" required>
                                        <div class="form-text">Select multiple images (JPG, PNG, GIF, WEBP). Max 5MB per file.</div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-upload me-2"></i>Upload Images
                                    </button>
                                </form>
                                <div id="upload-progress" class="mt-3" style="display: none;">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                                    </div>
                                </div>
                                <div id="uploaded-images" class="mt-3 row"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Picture Upload -->
                    <div class="col-md-4">
                        <div class="admin-card">
                            <div class="card-header">
                                <i class="fas fa-user-circle me-2"></i>Profile Picture
                            </div>
                            <div class="card-body text-center">
                                <div class="profile-picture-container mb-3">
                                    <img id="current-profile" src="https://via.placeholder.com/120x120/8b5fbf/ffffff?text=<?= substr(htmlspecialchars($_SESSION['name'] ?? 'Admin'), 0, 1) ?>"
                                         alt="Profile Picture" class="rounded-circle profile-img">
                                </div>
                                <form id="profileUploadForm" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*" required>
                                        <div class="form-text">JPG, PNG, GIF only. Max 2MB.</div>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fas fa-camera me-2"></i>Update Photo
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add Product Form -->
                <div class="admin-card">
                    <div class="card-header">
                        Add New Product
                    </div>
                    <div class="card-body">
                        <form id="addProductForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="product_title" class="form-label">Product Title</label>
                                    <input type="text" class="form-control" id="product_title" name="product_title" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="product_price" class="form-label">Price ($)</label>
                                    <input type="number" step="0.01" min="0" class="form-control" id="product_price" name="product_price" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="category_id" class="form-label">Category</label>
                                    <select class="form-select" id="category_id" name="category_id" required>
                                        <option value="">Select Category</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="brand_id" class="form-label">Brand</label>
                                    <select class="form-select" id="brand_id" name="brand_id" required>
                                        <option value="">Select Brand</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="product_desc" class="form-label">Description</label>
                                <textarea class="form-control" id="product_desc" name="product_desc" rows="3"></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="product_image" class="form-label">Image URL</label>
                                    <input type="url" class="form-control" id="product_image" name="product_image">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="product_keywords" class="form-label">Keywords</label>
                                    <input type="text" class="form-control" id="product_keywords" name="product_keywords" placeholder="Separate with commas">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-plus"></i>
                                Add Product
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Products Table -->
                <div class="admin-card">
                    <div class="card-header">
                        Your Products
                    </div>
                    <div class="card-body p-0">
                        <div class="table-container">
                            <table class="table" id="productTable">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Product Title</th>
                                        <th>Price</th>
                                        <th>Category</th>
                                        <th>Brand</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="6" class="text-center">No products found</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="updateProductForm">
                        <input type="hidden" id="edit_product_id" name="product_id">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_product_title" class="form-label">Product Title</label>
                                <input type="text" class="form-control" id="edit_product_title" name="product_title" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_product_price" class="form-label">Price ($)</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="edit_product_price" name="product_price" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_category_id" class="form-label">Category</label>
                                <select class="form-select" id="edit_category_id" name="category_id" required>
                                    <option value="">Select Category</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_brand_id" class="form-label">Brand</label>
                                <select class="form-select" id="edit_brand_id" name="brand_id" required>
                                    <option value="">Select Brand</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="edit_product_desc" class="form-label">Description</label>
                            <textarea class="form-control" id="edit_product_desc" name="product_desc" rows="3"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_product_image" class="form-label">Image URL</label>
                                <input type="url" class="form-control" id="edit_product_image" name="product_image">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_product_keywords" class="form-label">Keywords</label>
                                <input type="text" class="form-control" id="edit_product_keywords" name="product_keywords">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Update Product
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/product.js"></script>
</body>

</html>