<?php
require_once __DIR__ . '/settings/core.php';

// Check if user is logged in
if (!check_login()) {
    header("Location: login/login.php");
    exit;
}

$user_id = $_SESSION['user_id'] ?? 0;
$user_name = $_SESSION['name'] ?? 'User';
$user_initial = strtoupper(substr($user_name, 0, 1));

// Check if user has a profile picture
$profile_image = null;
try {
    $stmt = $pdo->prepare("SELECT profile_image FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
    $profile_image = $user_data['profile_image'] ?? null;
} catch (Exception $e) {
    // Handle error silently
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Dashboard - FlavourHub</title>
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
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 2rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Cards */
        .dashboard-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 1rem;
            border: 1px solid rgba(139, 95, 191, 0.1);
            box-shadow: 0 8px 32px rgba(139, 95, 191, 0.1);
            margin-bottom: 2rem;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .dashboard-card:hover {
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

        /* Profile Section */
        .profile-section {
            text-align: center;
            padding: 2rem;
        }

        .profile-avatar-container {
            position: relative;
            display: inline-block;
            margin-bottom: 1.5rem;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #8b5fbf;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #8b5fbf, #f093fb);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
            font-weight: 700;
        }

        .profile-avatar.has-image {
            background: none;
            font-size: inherit;
            color: inherit;
        }

        .profile-avatar:hover {
            transform: scale(1.05);
            border-color: #6366f1;
            box-shadow: 0 8px 32px rgba(139, 95, 191, 0.3);
        }

        .upload-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 120px;
            height: 120px;
            background: rgba(139, 95, 191, 0.8);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            cursor: pointer;
            color: white;
            font-size: 1.5rem;
        }

        .profile-avatar-container:hover .upload-overlay {
            opacity: 1;
        }

        .profile-name {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 0.5rem;
        }

        .profile-role {
            color: #6b7280;
            font-size: 1rem;
        }

        /* Form Elements */
        .form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .form-control {
            border: 2px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 0.75rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
        }

        .form-control:focus {
            border-color: #8b5fbf;
            box-shadow: 0 0 0 3px rgba(139, 95, 191, 0.1);
            background: #ffffff;
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
            color: white;
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .btn-secondary:hover {
            background: #4b5563;
            transform: translateY(-1px);
            color: white;
        }

        /* Progress */
        .progress {
            height: 10px;
            border-radius: 5px;
            background: #f3f4f6;
        }

        .progress-bar {
            background: linear-gradient(135deg, #8b5fbf, #6366f1);
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 0.75rem;
            padding: 1.5rem;
            border: 1px solid rgba(139, 95, 191, 0.1);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(139, 95, 191, 0.15);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #8b5fbf, #6366f1);
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
            margin-bottom: 1rem;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #6b7280;
            font-size: 0.875rem;
            font-weight: 500;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .content-area {
                padding: 1rem;
            }

            .page-title {
                font-size: 2rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <!-- Background Circles -->
    <div class="bg-circle bg-circle-1"></div>
    <div class="bg-circle bg-circle-2"></div>
    <div class="bg-circle bg-circle-3"></div>

    <!-- Header -->
    <header class="main-header">
        <div class="container-fluid px-4">
            <div class="d-flex align-items-center justify-content-between">
                <!-- Logo -->
                <a href="index.php" class="logo">
                    FlavourHub<span class="co">co</span>
                </a>

                <!-- Header Actions -->
                <div>
                    <a href="index.php" class="btn btn-secondary btn-sm me-2">Back to Home</a>
                    <a href="login/logout.php" class="btn btn-outline-secondary btn-sm">Logout</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Content Area -->
    <div class="content-area">
        <h1 class="page-title">Dashboard</h1>

        <!-- User Profile Section -->
        <div class="dashboard-card">
            <div class="card-header">
                <i class="fas fa-user me-2"></i>Profile Information
            </div>
            <div class="card-body">
                <div class="profile-section">
                    <div class="profile-avatar-container">
                        <?php if ($profile_image): ?>
                            <img src="<?= htmlspecialchars($profile_image) ?>" alt="Profile Picture" class="profile-avatar has-image" id="profileAvatar">
                        <?php else: ?>
                            <div class="profile-avatar" id="profileAvatar">
                                <?= $user_initial ?>
                            </div>
                        <?php endif; ?>
                        <div class="upload-overlay" onclick="document.getElementById('profilePictureInput').click()">
                            <i class="fas fa-camera"></i>
                        </div>
                    </div>
                    <h3 class="profile-name"><?= htmlspecialchars($user_name) ?></h3>
                    <p class="profile-role">FlavourHub Member</p>

                    <!-- Profile Picture Upload Form -->
                    <form id="profilePictureForm" enctype="multipart/form-data" style="margin-top: 1.5rem;">
                        <input type="file" id="profilePictureInput" name="profile_picture" accept="image/*" style="display: none;" onchange="uploadProfilePicture()">
                        <div id="upload-progress" style="display: none; margin-top: 1rem;">
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-value">0</div>
                <div class="stat-label">Total Orders</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <div class="stat-value">0</div>
                <div class="stat-label">Favorites</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-value">0</div>
                <div class="stat-label">Reviews</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-value">Recent</div>
                <div class="stat-label">Last Activity</div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="dashboard-card">
            <div class="card-header">
                <i class="fas fa-bolt me-2"></i>Quick Actions
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="#" class="btn btn-primary w-100">
                            <i class="fas fa-shopping-cart me-2"></i>
                            Browse Products
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="#" class="btn btn-secondary w-100">
                            <i class="fas fa-history me-2"></i>
                            Order History
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="#" class="btn btn-secondary w-100">
                            <i class="fas fa-heart me-2"></i>
                            My Favorites
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="#" class="btn btn-secondary w-100">
                            <i class="fas fa-cog me-2"></i>
                            Settings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function uploadProfilePicture() {
            const fileInput = document.getElementById('profilePictureInput');
            const file = fileInput.files[0];

            if (!file) return;

            // Validate file type
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            if (!allowedTypes.includes(file.type)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid File Type',
                    text: 'Please select a valid image file (JPG, PNG, GIF, WEBP)'
                });
                return;
            }

            // Validate file size (5MB max)
            if (file.size > 5 * 1024 * 1024) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Too Large',
                    text: 'Please select an image smaller than 5MB'
                });
                return;
            }

            const formData = new FormData();
            formData.append('profile_picture', file);

            const progressContainer = document.getElementById('upload-progress');
            const progressBar = progressContainer.querySelector('.progress-bar');

            progressContainer.style.display = 'block';
            progressBar.style.width = '0%';

            // Create XMLHttpRequest for upload progress
            const xhr = new XMLHttpRequest();

            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    const percentComplete = (e.loaded / e.total) * 100;
                    progressBar.style.width = percentComplete + '%';
                }
            });

            xhr.addEventListener('load', function() {
                progressContainer.style.display = 'none';

                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.status === 'success') {
                            // Update profile avatar
                            const profileAvatar = document.getElementById('profileAvatar');
                            if (profileAvatar.tagName === 'DIV') {
                                // Replace div with img
                                const img = document.createElement('img');
                                img.src = response.image_url;
                                img.alt = 'Profile Picture';
                                img.className = 'profile-avatar has-image';
                                img.id = 'profileAvatar';
                                profileAvatar.parentNode.replaceChild(img, profileAvatar);
                            } else {
                                // Update existing img
                                profileAvatar.src = response.image_url;
                            }

                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Profile picture updated successfully',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            throw new Error(response.message || 'Upload failed');
                        }
                    } catch (e) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Upload Failed',
                            text: e.message || 'Something went wrong'
                        });
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Upload Failed',
                        text: 'Server error occurred'
                    });
                }
            });

            xhr.addEventListener('error', function() {
                progressContainer.style.display = 'none';
                Swal.fire({
                    icon: 'error',
                    title: 'Upload Failed',
                    text: 'Network error occurred'
                });
            });

            xhr.open('POST', 'actions/upload_profile_picture_action.php');
            xhr.send(formData);
        }
    </script>
</body>

</html>