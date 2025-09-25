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
	<title>Home</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<style>
		.menu-tray {
			position: fixed;
			top: 16px;
			right: 16px;
			background: rgba(255, 255, 255, 0.95);
			border: 1px solid #e6e6e6;
			border-radius: 8px;
			padding: 6px 10px;
			box-shadow: 0 4px 10px rgba(0, 0, 0, 0.06);
			z-index: 1000;
		}

		.menu-tray a {
			margin-left: 8px;
		}
	</style>
</head>

<body>

	<div class="menu-tray">
		<span class="me-2">Menu:</span>
		
		<?php if (!$is_logged_in): ?>
			<!-- If not logged in, Register | Login -->
			<a href="login/register.php" class="btn btn-sm btn-outline-primary">Register</a>
			<span class="mx-1">|</span>
			<a href="login/login.php" class="btn btn-sm btn-outline-secondary">Login</a>
		
		<?php elseif ($is_admin): ?>
			<!-- If logged in and an admin, Logout | Category (navigates to admin/category.php) -->
			<span class="me-2">Hello, <?= htmlspecialchars($_SESSION['name'] ?? 'Admin') ?></span>
			<a href="login/logout.php" class="btn btn-sm btn-outline-danger">Logout</a>
			<span class="mx-1">|</span>
			<a href="admin/category.php" class="btn btn-sm btn-outline-success">Category</a>
		
		<?php else: ?>
			<!-- If logged in and not an admin, Logout -->
			<span class="me-2">Hello, <?= htmlspecialchars($_SESSION['name'] ?? 'User') ?></span>
			<a href="login/logout.php" class="btn btn-sm btn-outline-danger">Logout</a>
		
		<?php endif; ?>
	</div>

	<div class="container" style="padding-top:120px;">
		<div class="text-center">
			<h1>Welcome</h1>
			
			<?php if (!$is_logged_in): ?>
				<p class="text-muted">Use the menu in the top-right to Register or Login.</p>
			<?php elseif ($is_admin): ?>
				<p class="text-muted">Welcome Admin! You can manage categories using the menu above.</p>
				<div class="mt-4">
					<a href="admin/category.php" class="btn btn-primary">Manage Categories</a>
				</div>
			<?php else: ?>
				<p class="text-muted">Welcome! Browse our categories and products.</p>
				<div class="mt-4">
					<button class="btn btn-outline-primary">Browse Products</button>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>