<!DOCTYPE html>
<html lang="en">
<?php
// --- server-side handler (kept minimal) ---
session_start();
require_once __DIR__ . '/../controllers/user_controller.php';

$reg_success = '';
$reg_error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // read posted fields
    $name         = trim($_POST['name']         ?? '');
    $email        = trim($_POST['email']        ?? '');
    $password     =        $_POST['password']   ?? '';
    $phone_number = trim($_POST['phone_number'] ?? '');
    $country      = trim($_POST['country']      ?? '');
    $city         = trim($_POST['city']         ?? '');
    $role         = (int)($_POST['role']        ?? 1);

    // call your existing controller
    $res = register_user_ctr($name, $email, $password, $phone_number, $country, $city, $role);

    if (is_array($res) && ($res['status'] ?? '') === 'success') {
        $reg_success = $res['message'] ?? 'Registration successful. You can now log in.';
        // If you prefer an immediate redirect to login, uncomment:
        // header('Location: login.php'); exit;
    } else {
        $reg_error = is_array($res) ? ($res['message'] ?? 'Registration failed') : 'Registration failed';
    }
}
?>

<head>
    <meta charset="UTF-8">
    <title> Register - Taste of Africa </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <style>
        .register-container {
            background: rgba(255, 255, 255, 0.95);
            /* semi-white box */
            padding: 20px;
            background-color: gainsboro;
            border-radius: 10px;
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.2);
        }

        .btn-custom {
            background-color: #D19C97;
            border-color: #D19C97;
            color: #fff;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .btn-custom:hover {
            background-color: #b77a7a;
            border-color: #b77a7a;
        }

        .highlight {
            color: #D19C97;
            transition: color 0.3s;
        }

        .highlight:hover {
            color: #b77a7a;
        }

        body {
            background-color: rgb(58, 166, 198, 1);
            background-attachment: fixed;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            background-size: 20px 20px;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            font-family: Serif, Times New Roman;
            font-size: 115%;
        }

        .register-container {
            margin-top: 50px;
        }

        .card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #D19C97;
            color: #fff;
        }

        .form-check-input:checked {
            background-color: #D19C97;
            border-color: #D19C97;
        }

        .form-check-input:focus {
            border-color: #D19C97;
            box-shadow: 0 0 0 0.2rem rgba(209, 156, 151, 0.25);
        }

        .animate-pulse-custom {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }

        .loading {
            display: none;
        }

        .btn:disabled {
            opacity: 0.6;
        }
    </style>
</head>

<body>
    <div class="container register-container">
        <div class="row justify-content-center animate__animated animate__fadeInDown">
            <div class="col-md-6">
                <div class="card animate__animated animate__zoomIn">
                    <div class="card-header text-center">
                        <h4>Register</h4>
                    </div>
                    <div class="card-body">

                        <!-- Server-side alerts -->
                        <?php if (!empty($reg_success)): ?>
                            <div class="alert alert-success text-center" role="alert">
                                <?= htmlspecialchars($reg_success) ?>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($reg_error)): ?>
                            <div class="alert alert-danger text-center" role="alert">
                                <?= htmlspecialchars($reg_error) ?>
                            </div>
                        <?php endif; ?>

                        <!-- Client-side error (inline JS will use this) -->
                        <div id="register-error" class="alert alert-danger text-center" style="display:none;" role="alert"></div>

                        <!-- form: now posts to this same file -->
                        <form id="register-form" method="POST" action="">
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" id="name" name="name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" id="email" name="email" class="form-control" required>

                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" id="password" name="password" class="form-control" required>
                                <small class="text-muted">Password must be at least 6 characters</small>
                            </div>
                            <div class="mb-3">
                                <label for="phone_number" class="form-label">Phone Number</label>
                                <input type="tel" id="phone_number" name="phone_number" class="form-control" placeholder="e.g. +233 55 123 4567" required>
                            </div>
                            <div class="mb-3">
                                <label for="country" class="form-label">Country</label>
                                <select id="country" name="country" class="form-control" required>
                                    <option value="">Select Country</option>
                                    <option value="Ghana" selected>Ghana</option>
                                    <option value="Nigeria">Nigeria</option>
                                    <option value="South Africa">South Africa</option>
                                    <option value="Kenya">Kenya</option>
                                    <option value="Egypt">Egypt</option>
                                    <option value="Morocco">Morocco</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="city" class="form-label">City</label>
                                <input type="text" id="city" name="city" class="form-control" placeholder="e.g. Accra" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Register As</label>
                                <div class="d-flex justify-content-start">
                                    <div class="form-check me-3">
                                        <input class="form-check-input" type="radio" name="role" id="customer" value="1" checked>
                                        <label class="form-check-label" for="customer">Customer</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="role" id="owner" value="2">
                                        <label class="form-check-label" for="owner">Restaurant Owner</label>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-custom w-100">Register</button>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        Already have an account? <a href="login.php" class="highlight">Login here</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Commented out to stop the AJAX error popup; leaving it here (not deleted) -->
    <!-- <script src="../js/register.js?v=9999"></script> -->

    <!-- Inline JS (validation only; normal POST submit to PHP above) -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('register-form');
            if (!form) return;

            const nameEl = document.getElementById('name');
            const emailEl = document.getElementById('email');
            const passEl = document.getElementById('password');
            const phoneEl = document.getElementById('phone_number');
            const countryEl = document.getElementById('country');
            const cityEl = document.getElementById('city');
            const errorEl = document.getElementById('register-error');
            const submitBtn = form.querySelector('button[type="submit"]');

            const emailRegex = /^[^\\s@]+@[^\\s@]+\\.[^\\s@]+$/;

            function setError(msg) {
                if (errorEl) {
                    errorEl.textContent = msg;
                    errorEl.style.display = 'block';
                }
            }

            function clearError() {
                if (errorEl) {
                    errorEl.textContent = '';
                    errorEl.style.display = 'none';
                }
            }

            form.addEventListener('submit', function(e) {
                clearError();

                const name = nameEl.value.trim();
                const email = emailEl.value.trim();
                const pass = passEl.value;

                if (!name) {
                    e.preventDefault();
                    setError('Full Name is required.');
                    return;
                }
                // if (!emailRegex.test(email)) {
                //     e.preventDefault();
                //     setError('Please enter a valid email address.');
                //     return;
                // }
                if (!pass) {
                    e.preventDefault();
                    setError('Password is required.');
                    return;
                }
                if (pass.length < 6) {
                    e.preventDefault();
                    setError('Password must be at least 6 characters.');
                    return;
                }
                if (!phoneEl.value.trim()) {
                    e.preventDefault();
                    setError('Phone Number is required.');
                    return;
                }
                if (!countryEl.value) {
                    e.preventDefault();
                    setError('Please select your country.');
                    return;
                }
                if (!cityEl.value.trim()) {
                    e.preventDefault();
                    setError('City is required.');
                    return;
                }

                // Valid → allow normal POST to PHP at the top
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Creating account...';
                }
            });
        });
    </script>
</body>

</html>