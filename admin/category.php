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
    <title>Manage Categories</title>
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
            background-color: #f8fafc;
            color: #1a202c;
            padding: 0 !important;
        }

        /* Header Styles */
        .main-header {
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
            padding: 12px 0;
            margin-bottom: 2rem;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: #2563eb;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .logo .co {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
        }

        .search-container {
            position: relative;
            flex: 1;
            max-width: 500px;
        }

        .search-input {
            width: 100%;
            padding: 12px 20px 12px 50px;
            border: 2px solid #e2e8f0;
            border-radius: 25px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8fafc;
        }

        .search-input:focus {
            outline: none;
            border-color: #2563eb;
            background: white;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            font-size: 1.1rem;
        }

        .search-btn {
            position: absolute;
            right: 6px;
            top: 50%;
            transform: translateY(-50%);
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border: none;
            padding: 8px 16px;
            border-radius: 20px;
            color: white;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .search-btn:hover {
            background: linear-gradient(135deg, #1d4ed8, #1e40af);
            transform: translateY(-50%) scale(1.05);
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .header-icon {
            position: relative;
            padding: 8px;
            border-radius: 8px;
            transition: all 0.3s ease;
            color: #4b5563;
            cursor: pointer;
        }

        .header-icon:hover {
            background: #f1f5f9;
            color: #2563eb;
        }

        .cart-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            background: #ef4444;
            color: white;
            font-size: 0.75rem;
            padding: 2px 6px;
            border-radius: 10px;
            min-width: 18px;
            text-align: center;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .user-avatar:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        /* Enhanced styling for original elements */
        .container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 2rem !important;
            margin-top: 1rem;
        }

        .h4 {
            color: #1a202c;
            font-weight: 700;
            font-size: 1.8rem;
        }

        .btn-outline-secondary {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-outline-secondary:hover {
            background: linear-gradient(135deg, #059669, #047857);
            color: white;
            transform: translateY(-1px);
        }

        .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border: none;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #1d4ed8, #1e40af);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .table {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .table thead th {
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            color: #374151;
            font-weight: 600;
            border: none;
            padding: 16px;
        }

        .table tbody td {
            padding: 12px 16px;
            border-color: #f1f5f9;
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background: #f8fafc;
        }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                gap: 16px;
            }

            .search-container {
                order: 3;
                max-width: none;
            }

            .container {
                margin: 0.5rem;
                padding: 1rem !important;
            }
        }
    </style>
</head>

<body>
    <!-- Main Header (Added for consistency with main page) -->
    <header class="main-header">
        <div class="container-fluid px-4">
            <div class="d-flex align-items-center justify-content-between header-container">
                <!-- Logo -->
                <a href="../index.php" class="logo">
                    flavorhub<span class="co">co</span>
                </a>

                <!-- Search Bar -->
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Search food in FlavorHub">
                    <button class="search-btn">
                        <i class="fas fa-search"></i>
                    </button>
                </div>

                <!-- Header Actions -->
                <div class="header-actions">
                    <!-- Notifications -->
                    <div class="header-icon">
                        <i class="fas fa-bell"></i>
                    </div>

                    <!-- About/Help -->
                    <span class="d-none d-md-inline text-muted">About</span>
                    <span class="d-none d-md-inline text-muted">Help</span>
                    <span class="d-none d-md-inline text-muted">Contact</span>

                    <!-- Cart -->
                    <div class="header-icon">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-badge">0</span>
                    </div>

                    <!-- User Avatar -->
                    <div class="user-avatar" title="<?= htmlspecialchars($_SESSION['name'] ?? 'Chef') ?>">
                        <?= strtoupper(substr($_SESSION['name'] ?? 'C', 0, 1)) ?>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Original body content with enhanced styling -->
    <div class="p-4">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h4 mb-0">Categories</h1>
                <a href="../index.php" class="btn btn-outline-secondary btn-sm">Back to Home</a>
            </div>

            <form id="addForm" class="row g-3 mb-4">
                <div class="col-md-6">
                    <input type="text" class="form-control" name="category_name" placeholder="New category name" required>
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary">Add Category</button>
                </div>
                <div class="col-12">
                    <div id="addMsg" class="small"></div>
                </div>
            </form>

            <table class="table table-striped align-middle" id="catTable">
                <thead>
                    <tr>
                        <th style="width:70%">Name</th>
                        <th style="width:30%">Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <script src="../js/category.js"></script>

    <script>
        // Search functionality
        document.querySelector('.search-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                performSearch();
            }
        });

        document.querySelector('.search-btn').addEventListener('click', performSearch);

        function performSearch() {
            const query = document.querySelector('.search-input').value.trim();
            if (query) {
                console.log('Searching for:', query);
                alert('Search functionality will be implemented here: ' + query);
            }
        }
    </script>
</body>

</html>