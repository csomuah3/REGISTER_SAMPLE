<?php
require_once __DIR__ . '/../settings/core.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!check_login()) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Check if file was uploaded
        if (!isset($_FILES['profile_picture']) || $_FILES['profile_picture']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['status' => 'error', 'message' => 'No file uploaded or upload error']);
            exit;
        }

        $file = $_FILES['profile_picture'];
        $uploadDir = __DIR__ . '/../uploads/profiles/';
        $webPath = '../uploads/profiles/';

        // Ensure upload directory exists
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Validate file type
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($fileExtension, $allowedTypes)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid file type. Only JPG, PNG, GIF, WEBP allowed.']);
            exit;
        }

        // Check file size (5MB limit)
        if ($file['size'] > 5 * 1024 * 1024) {
            echo json_encode(['status' => 'error', 'message' => 'File too large. Maximum 5MB allowed.']);
            exit;
        }

        $user_id = $_SESSION['user_id'];

        // Generate unique filename
        $uniqueName = 'profile_' . $user_id . '_' . time() . '.' . $fileExtension;
        $uploadPath = $uploadDir . $uniqueName;

        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            $fullImageUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/REGISTER_SAMPLE/uploads/profiles/' . $uniqueName;

            // Update user's profile image in database
            try {
                // First, ensure the profile_image column exists
                $pdo->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS profile_image VARCHAR(255)");

                // Delete old profile picture if exists
                $stmt = $pdo->prepare("SELECT profile_image FROM users WHERE user_id = ?");
                $stmt->execute([$user_id]);
                $oldData = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($oldData && $oldData['profile_image']) {
                    $oldFileName = basename($oldData['profile_image']);
                    $oldFilePath = $uploadDir . $oldFileName;
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }

                // Update with new profile image
                $stmt = $pdo->prepare("UPDATE users SET profile_image = ? WHERE user_id = ?");
                $result = $stmt->execute([$fullImageUrl, $user_id]);

                if ($result) {
                    echo json_encode([
                        'status' => 'success',
                        'message' => 'Profile picture updated successfully',
                        'image_url' => $fullImageUrl
                    ]);
                } else {
                    // Delete uploaded file if database update fails
                    unlink($uploadPath);
                    echo json_encode(['status' => 'error', 'message' => 'Failed to update profile in database']);
                }

            } catch (Exception $e) {
                // Delete uploaded file if database operation fails
                unlink($uploadPath);
                echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to upload file']);
        }

    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Upload failed: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>