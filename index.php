<?php
// Start session and include core functions
require_once(__DIR__ . '/settings/core.php');

// Check login status and admin status
$is_logged_in = check_login();
$is_admin = false;

if ($is_logged_in) {
	$is_admin = check_admin();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>FlavorHub - Your One-Stop Food Destination</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
	<style>
		/* Import Google Fonts */
		@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Dancing+Script:wght@400;500;600;700&display=swap');

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
			overflow-x: hidden;
		}

		/* Header Styles */
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

		.logo .co {
			background: linear-gradient(135deg, #8b5fbf, #f093fb);
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
			border-color: #8b5fbf;
			background: white;
			box-shadow: 0 0 0 3px rgba(139, 95, 191, 0.1);
		}

		.search-icon {
			position: absolute;
			left: 18px;
			top: 50%;
			transform: translateY(-50%);
			color: #8b5fbf;
			font-size: 1.1rem;
		}

		.search-btn {
			position: absolute;
			right: 6px;
			top: 50%;
			transform: translateY(-50%);
			background: linear-gradient(135deg, #8b5fbf, #f093fb);
			border: none;
			padding: 8px 16px;
			border-radius: 20px;
			color: white;
			font-weight: 500;
			cursor: pointer;
			transition: all 0.3s ease;
		}

		.search-btn:hover {
			background: linear-gradient(135deg, #764ba2, #8b5fbf);
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
			background: rgba(139, 95, 191, 0.1);
			color: #8b5fbf;
		}

		.cart-badge {
			position: absolute;
			top: -2px;
			right: -2px;
			background: linear-gradient(135deg, #f093fb, #8b5fbf);
			color: white;
			font-size: 0.75rem;
			padding: 2px 6px;
			border-radius: 10px;
			min-width: 18px;
			text-align: center;
		}

		.user-menu {
			position: relative;
		}

		.login-btn {
			background: linear-gradient(135deg, #8b5fbf, #f093fb);
			color: white;
			border: none;
			padding: 10px 20px;
			border-radius: 20px;
			font-weight: 500;
			text-decoration: none;
			transition: all 0.3s ease;
			display: inline-block;
		}

		.login-btn:hover {
			background: linear-gradient(135deg, #764ba2, #8b5fbf);
			transform: translateY(-1px);
			color: white;
		}

		.logout-btn {
			background: linear-gradient(135deg, #ef4444, #dc2626);
			color: white;
			border: none;
			padding: 8px 16px;
			border-radius: 16px;
			font-weight: 500;
			text-decoration: none;
			transition: all 0.3s ease;
			display: inline-block;
			font-size: 0.875rem;
		}

		.logout-btn:hover {
			background: linear-gradient(135deg, #dc2626, #b91c1c);
			transform: translateY(-1px);
			color: white;
		}

		.user-dropdown {
			position: relative;
		}

		.user-avatar {
			width: 36px;
			height: 36px;
			background: linear-gradient(135deg, #8b5fbf, #f093fb);
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
			box-shadow: 0 4px 12px rgba(139, 95, 191, 0.3);
		}

		/* Category Navigation */
		.category-nav {
			background: white;
			border-top: 1px solid #e2e8f0;
			padding: 12px 0;
			position: sticky;
			top: 76px;
			z-index: 999;
		}

		.category-list {
			display: flex;
			align-items: center;
			gap: 8px;
			overflow-x: auto;
			padding: 0 16px;
			scrollbar-width: none;
			-ms-overflow-style: none;
		}

		.category-list::-webkit-scrollbar {
			display: none;
		}

		.category-item {
			white-space: nowrap;
			padding: 8px 16px;
			background: #f8fafc;
			border: 2px solid #e2e8f0;
			border-radius: 20px;
			color: #4b5563;
			text-decoration: none;
			font-weight: 500;
			font-size: 0.9rem;
			transition: all 0.3s ease;
			cursor: pointer;
		}

		.category-item:hover,
		.category-item.active {
			background: linear-gradient(135deg, #8b5fbf, #f093fb);
			color: white;
			border-color: #8b5fbf;
			transform: translateY(-1px);
		}

		.category-item.featured {
			background: linear-gradient(135deg, #8b5fbf, #f093fb);
			color: white;
			border-color: #8b5fbf;
		}

		/* Hero Section */
		.hero-section {
			background: linear-gradient(135deg, #f8f9ff 0%, #f1f5f9 50%, #e2e8f0 100%);
			padding: 60px 0;
			margin-top: 20px;
			position: relative;
			overflow: hidden;
		}

		.hero-section::before {
			content: '';
			position: absolute;
			top: -50%;
			right: -20%;
			width: 600px;
			height: 600px;
			background: linear-gradient(135deg, rgba(139, 95, 191, 0.1), rgba(240, 147, 251, 0.1));
			border-radius: 50%;
			animation: float 6s ease-in-out infinite;
		}

		.hero-section::after {
			content: '';
			position: absolute;
			bottom: -50%;
			left: -20%;
			width: 500px;
			height: 500px;
			background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(5, 150, 105, 0.1));
			border-radius: 50%;
			animation: float 6s ease-in-out infinite reverse;
		}

		@keyframes float {

			0%,
			100% {
				transform: translateY(0px) rotate(0deg);
			}

			50% {
				transform: translateY(-20px) rotate(5deg);
			}
		}

		.hero-content {
			position: relative;
			z-index: 2;
		}

		.hero-title {
			font-size: 3.5rem;
			font-weight: 700;
			color: #1a202c;
			margin-bottom: 16px;
			line-height: 1.2;
		}

		.hero-highlight {
			background: linear-gradient(135deg, #8b5fbf, #f093fb);
			-webkit-background-clip: text;
			-webkit-text-fill-color: transparent;
			background-clip: text;
		}

		.hero-subtitle {
			font-size: 1.25rem;
			color: #4b5563;
			margin-bottom: 24px;
			font-weight: 400;
		}

		.hero-features {
			display: flex;
			gap: 24px;
			margin-bottom: 32px;
			flex-wrap: wrap;
		}

		.feature-item {
			display: flex;
			align-items: center;
			gap: 8px;
			color: #374151;
			font-weight: 500;
		}

		.feature-icon {
			color: #8b5fbf;
			font-size: 1.1rem;
		}

		.cta-buttons {
			display: flex;
			gap: 16px;
			flex-wrap: wrap;
		}

		.cta-primary {
			background: linear-gradient(135deg, #8b5fbf, #f093fb);
			color: white;
			padding: 14px 28px;
			border-radius: 25px;
			text-decoration: none;
			font-weight: 600;
			transition: all 0.3s ease;
			border: none;
			cursor: pointer;
		}

		.cta-primary:hover {
			background: linear-gradient(135deg, #764ba2, #8b5fbf);
			transform: translateY(-2px);
			box-shadow: 0 8px 25px rgba(139, 95, 191, 0.3);
			color: white;
		}

		.cta-secondary {
			background: white;
			color: #8b5fbf;
			padding: 14px 28px;
			border: 2px solid #8b5fbf;
			border-radius: 25px;
			text-decoration: none;
			font-weight: 600;
			transition: all 0.3s ease;
		}

		.cta-secondary:hover {
			background: #8b5fbf;
			color: white;
			transform: translateY(-2px);
		}

		/* Promotion Cards */
		.promo-cards {
			display: flex;
			gap: 20px;
			margin-top: 40px;
		}

		.promo-card {
			flex: 1;
			padding: 32px;
			border-radius: 20px;
			position: relative;
			overflow: hidden;
			transition: all 0.3s ease;
			cursor: pointer;
			min-height: 280px;
		}

		.promo-card:hover {
			transform: translateY(-4px);
			box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
		}

		.promo-card.yellow {
			background: linear-gradient(135deg, #fbbf24, #f59e0b);
			color: #1f2937;
		}

		.promo-card.white {
			background: white;
			border: 2px solid #e5e7eb;
			color: #1f2937;
		}

		.promo-badge {
			background: linear-gradient(135deg, #f093fb, #8b5fbf);
			color: white;
			padding: 6px 16px;
			border-radius: 16px;
			font-size: 1rem;
			font-weight: 600;
			margin-bottom: 16px;
			display: inline-block;
		}

		.promo-title {
			font-size: 1.8rem;
			font-weight: 700;
			margin-bottom: 12px;
		}

		.promo-subtitle {
			font-size: 1.1rem;
			margin-bottom: 20px;
			opacity: 0.8;
		}

		.promo-btn {
			background: linear-gradient(135deg, #8b5fbf, #f093fb);
			color: white;
			padding: 12px 20px;
			border-radius: 25px;
			text-decoration: none;
			font-weight: 500;
			font-size: 1rem;
			transition: all 0.3s ease;
		}

		.promo-btn:hover {
			background: linear-gradient(135deg, #764ba2, #8b5fbf);
			color: white;
			transform: scale(1.05);
		}

		/* Admin Panel Styles - Made bigger with purple theme */
		.admin-panel {
			background: linear-gradient(135deg, #8b5fbf, #f093fb);
			color: white;
			padding: 40px;
			border-radius: 24px;
			margin: 60px 0;
			text-align: center;
			min-height: 250px;
			display: flex;
			flex-direction: column;
			justify-content: center;
			align-items: center;
		}

		.admin-panel h3 {
			margin-bottom: 20px;
			font-weight: 700;
			font-size: 2.2rem;
		}

		.admin-panel p {
			margin-bottom: 30px;
			opacity: 0.9;
			font-size: 1.8rem;
			font-weight: 600;
			line-height: 1.4;
			font-family: 'Dancing Script', 'Brush Script MT', 'Lucida Handwriting', cursive;
		}

		.admin-btn {
			background: white;
			color: #8b5fbf;
			padding: 16px 32px;
			border-radius: 25px;
			text-decoration: none;
			font-weight: 600;
			font-size: 1.1rem;
			transition: all 0.3s ease;
		}

		.admin-btn:hover {
			background: rgba(255, 255, 255, 0.9);
			color: #764ba2;
			transform: translateY(-2px);
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

			.hero-title {
				font-size: 2.5rem;
			}

			.promo-cards {
				flex-direction: column;
			}

			.category-list {
				padding: 0 16px;
			}

			.cta-buttons {
				justify-content: center;
			}

			.admin-panel p {
				font-size: 1.4rem;
			}

			.promo-card {
				padding: 24px;
				min-height: 220px;
			}
		}

		@media (max-width: 480px) {
			.hero-title {
				font-size: 2rem;
			}

			.hero-features {
				flex-direction: column;
				gap: 12px;
			}

			.category-nav {
				top: 140px;
			}

			.admin-panel p {
				font-size: 1.2rem;
			}
		}

		/* Animation Classes */
		.animate-fade-in {
			animation: fadeIn 0.6s ease-out;
		}

		.animate-slide-up {
			animation: slideUp 0.8s ease-out;
		}

		@keyframes fadeIn {
			from {
				opacity: 0;
				transform: translateY(20px);
			}

			to {
				opacity: 1;
				transform: translateY(0);
			}
		}

		@keyframes slideUp {
			from {
				opacity: 0;
				transform: translateY(40px);
			}

			to {
				opacity: 1;
				transform: translateY(0);
			}
		}

		/* Legacy menu tray (hidden) */
		.menu-tray {
			display: none;
		}
	</style>
</head>

<body>
	<!-- Main Header -->
	<header class="main-header animate__animated animate__fadeInDown">
		<div class="container">
			<div class="d-flex align-items-center justify-content-between header-container">
				<!-- Logo -->
				<a href="#" class="logo">
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

					<!-- Logout (for admin users) -->
					<?php if ($is_admin): ?>
						<a href="login/logout.php" class="logout-btn">Logout</a>
					<?php endif; ?>

					<!-- Cart -->
					<div class="header-icon">
						<i class="fas fa-shopping-cart"></i>
						<span class="cart-badge">0</span>
					</div>

					<!-- User Menu -->
					<div class="user-menu">
						<?php if (!$is_logged_in): ?>
							<a href="login/login.php" class="login-btn">Login</a>
						<?php else: ?>
							<div class="user-dropdown">
								<div class="user-avatar" title="<?= htmlspecialchars($_SESSION['name'] ?? 'User') ?>">
									<?= strtoupper(substr($_SESSION['name'] ?? 'U', 0, 1)) ?>
								</div>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</header>

	<!-- Category Navigation -->
	<nav class="category-nav animate__animated animate__fadeInUp">
		<div class="container">
			<div class="category-list">
				<a href="#" class="category-item featured">All Categories</a>
				<a href="#" class="category-item active">All Discount</a>
				<a href="#" class="category-item">Fresh Produce</a>
				<a href="#" class="category-item">Dairy & Eggs</a>
				<a href="#" class="category-item">Meat & Seafood</a>
				<a href="#" class="category-item">Bakery</a>
				<a href="#" class="category-item">Beverages</a>
				<a href="#" class="category-item">Snacks</a>
				<a href="#" class="category-item">Frozen Foods</a>
			</div>
		</div>
	</nav>

	<!-- Hero Section -->
	<section class="hero-section">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-lg-6">
					<div class="hero-content animate-fade-in">
						<h1 class="hero-title">
							Your <span class="hero-highlight">Fresh Food Hub</span><br>
							for Every Craving!
						</h1>
						<p class="hero-subtitle">
							Fresh ingredients, quick delivery,<br>
							and quality guaranteed from farm to table!
						</p>

						<div class="hero-features">
							<div class="feature-item">
								<i class="fas fa-shipping-fast feature-icon"></i>
								<span>Fresh Delivery</span>
							</div>
							<div class="feature-item">
								<i class="fas fa-leaf feature-icon"></i>
								<span>Farm Fresh</span>
							</div>
							<div class="feature-item">
								<i class="fas fa-headset feature-icon"></i>
								<span>24/7 Support</span>
							</div>
						</div>

						<div class="cta-buttons">
							<?php if (!$is_logged_in): ?>
								<a href="login/register.php" class="cta-primary">Get Started</a>
								<a href="#" class="cta-secondary">Browse Menu</a>
							<?php elseif ($is_admin): ?>
								<a href="admin/category.php" class="cta-primary">Manage Kitchen</a>
								<a href="#" class="cta-secondary">View Analytics</a>
							<?php else: ?>
								<a href="#" class="cta-primary">Order Now</a>
								<a href="#" class="cta-secondary">View Specials</a>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<div class="col-lg-6">
					<!-- Promotional Cards -->
					<div class="promo-cards animate-slide-up">
						<div class="promo-card yellow">
							<div class="promo-badge">Summer Special</div>
							<div class="promo-title">Fresh<br>Summer<br>Produce</div>
							<div class="promo-subtitle">Get the freshest fruits & vegetables<br>at unbeatable prices</div>
							<a href="#" class="promo-btn">Shop fresh</a>
						</div>
						<div class="promo-card white">
							<div class="promo-badge">20% OFF</div>
							<div class="promo-title">For All<br>Organic<br>Products</div>
							<a href="#" class="promo-btn">Go organic</a>
						</div>
					</div>
				</div>
			</div>

			<!-- Admin Panel (only visible to admins) -->
			<?php if ($is_admin): ?>
				<div class="admin-panel animate__animated animate__zoomIn">
					<h3>Chef Dashboard</h3>
					<p>Welcome back, <?= htmlspecialchars($_SESSION['name'] ?? 'Chef') ?>! Manage your kitchen categories and menu items.</p>
					<a href="admin/category.php" class="admin-btn">Manage Categories</a>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<!-- Scripts -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
				// Add your search logic here
				console.log('Searching for:', query);
				alert('Search functionality will be implemented here: ' + query);
			}
		}

		// Category navigation
		document.querySelectorAll('.category-item').forEach(item => {
			item.addEventListener('click', function(e) {
				e.preventDefault();

				// Remove active class from all items
				document.querySelectorAll('.category-item').forEach(cat => {
					cat.classList.remove('active');
				});

				// Add active class to clicked item
				this.classList.add('active');

				// Add your category filtering logic here
				console.log('Category selected:', this.textContent);
			});
		});

		// Smooth scrolling for internal links
		document.querySelectorAll('a[href^="#"]').forEach(anchor => {
			anchor.addEventListener('click', function(e) {
				e.preventDefault();
				const target = document.querySelector(this.getAttribute('href'));
				if (target) {
					target.scrollIntoView({
						behavior: 'smooth'
					});
				}
			});
		});

		// Add animation classes on scroll
		const observerOptions = {
			threshold: 0.1,
			rootMargin: '0px 0px -50px 0px'
		};

		const observer = new IntersectionObserver((entries) => {
			entries.forEach(entry => {
				if (entry.isIntersecting) {
					entry.target.style.opacity = '1';
					entry.target.style.transform = 'translateY(0)';
				}
			});
		}, observerOptions);

		// Observe elements for animation
		document.querySelectorAll('.promo-card, .hero-content').forEach(el => {
			observer.observe(el);
		});
	</script>
</body>

</html>