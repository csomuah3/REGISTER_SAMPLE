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
        /* /* Import Google Fonts */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        /* Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px 20px 40px 20px;
            position: relative;
            overflow-x: hidden;
            overflow-y: auto;
        }

        /* Animated Background Shapes */
        body::before,
        body::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(135deg, #48cc6c, #2dd55b);
            opacity: 0.3;
            z-index: 600;
        }

        body::before {
            width: 600px;
            height: 500px;
            top: -200px;
            right: -200px;
            animation: float 4s ease-in-out infinite;
        }

        body::after {
            width: 500px;
            height: 500px;
            bottom: -170px;
            left: -150px;
            animation: float 4s ease-in-out infinite reverse;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(10deg);
            }
        }

        /* Main Container */
        .login-container,
        .register-container,
        .auth-container,
        form {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
            max-width: 1700px;
            width: 100%;
            padding: 40px;
            margin: 15px auto;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Form Header */
        .form-header {
            text-align: left;
            margin-bottom: 30px;
        }

        .form-title,
        h2 {
            font-size: 5rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 8px;
        }

        .form-subtitle,
        .subtitle {
            color: #718096;
            font-size: 40px;
            margin-bottom: 20px;
        }

        .form-subtitle a,
        .subtitle a {
            color: #48cc6c;
            text-decoration: none;
            font-weight: 500;
        }

        .form-subtitle a:hover,
        .subtitle a:hover {
            text-decoration: underline;
        }

        /* Form Fields */
        .form-group,
        .input-group,
        .form-field {
            margin-bottom: 20px;
            position: relative;
        }

        .form-group label,
        .input-group label,
        .form-field label {
            display: block;
            font-size: 1.3rem;
            font-weight: 500;
            color: #4a5568;
            margin-bottom: 8px;
        }

        .form-group input,
        .input-group input,
        .form-field input,
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="tel"],
        select {
            width: 100%;
            padding: 16px 18px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1.4rem;
            font-family: inherit;
            transition: all 0.3s ease;
            background: #f8fafc;
        }

        .form-group input:focus,
        .input-group input:focus,
        .form-field input:focus,
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus,
        input[type="tel"]:focus,
        select:focus {
            outline: none;
            border-color: #48cc6c;
            background: white;
            box-shadow: 0 0 0 3px rgba(72, 204, 108, 0.1);
        }

        .form-group input::placeholder,
        .input-group input::placeholder,
        .form-field input::placeholder {
            color: #a0aec0;
        }

        /* Input Icons */
        .form-group input[type="text"],
        .form-group input[type="password"],
        .form-group input[type="email"],
        .form-group input[type="tel"] {
            padding-right: 50px;
        }

        .form-group::after {
            content: '👤';
            position: absolute;
            right: 18px;
            top: 42px;
            font-size: 1.3rem;
            color: #a0aec0;
            pointer-events: none;
        }

        .form-group:has(input[type="password"])::after {
            content: '🔒';
        }

        .form-group:has(input[type="email"])::after {
            content: '📧';
        }

        .form-group:has(input[type="tel"])::after {
            content: '📱';
        }

        /* Select Dropdown */
        select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 12px center;
            background-repeat: no-repeat;
            background-size: 16px;
            padding-right: 40px;
        }

        /* Submit Button */
        .submit-btn,
        .btn-primary,
        .sign-up-btn,
        button[type="submit"] {
            width: 100%;
            background: linear-gradient(135deg, #48cc6c, #2dd55b);
            color: white;
            border: none;
            padding: 16px;
            border-radius: 12px;
            font-size: 1.4rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            text-transform: none;
        }

        .submit-btn:hover,
        .btn-primary:hover,
        .sign-up-btn:hover,
        button[type="submit"]:hover {
            background: linear-gradient(135deg, #2dd55b, #48cc6c);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(72, 204, 108, 0.3);
        }

        /* Radio Button Groups */
        .radio-group,
        .register-as-group {
            margin: 20px 0;
        }

        .radio-group label,
        .register-as-group label {
            display: block;
            font-size: 1.3rem;
            font-weight: 500;
            color: #4a5568;
            margin-bottom: 12px;
        }

        .radio-options {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .radio-option {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            background: #f8fafc;
            transition: all 0.3s ease;
            flex: 1;
            min-width: 140px;
        }

        .radio-option:hover {
            border-color: #48cc6c;
            background: #f0fff4;
        }

        .radio-option input[type="radio"] {
            width: 18px;
            height: 18px;
            margin: 0;
            accent-color: #48cc6c;
        }

        .radio-option.selected,
        .radio-option:has(input:checked) {
            border-color: #48cc6c;
            background: #f0fff4;
            color: #22543d;
        }

        .radio-option span {
            font-size: 1.1rem;
            font-weight: 500;
        }

        /* Password Requirements */
        .password-requirements {
            font-size: 1rem;
            color: #718096;
            margin-top: 5px;
            font-style: italic;
        }

        /* Divider */
        .divider,
        .or-divider {
            display: flex;
            align-items: center;
            margin: 25px 0;
            color: #a0aec0;
            font-size: 1.2rem;
        }

        .divider::before,
        .divider::after,
        .or-divider::before,
        .or-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        .divider span,
        .or-divider span {
            margin: 0 15px;
            background: white;
            padding: 0 10px;
        }

        /* Social Buttons */
        .social-login,
        .social-buttons {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
        }

        .social-btn,
        .google-btn,
        .twitter-btn,
        .facebook-btn {
            flex: 1;
            padding: 12px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            background: white;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: #4a5568;
        }

        .social-btn:hover,
        .google-btn:hover,
        .twitter-btn:hover,
        .facebook-btn:hover {
            border-color: #48cc6c;
            background: #f0fff4;
            transform: translateY(-1px);
        }

        .social-btn svg,
        .social-btn i {
            width: 20px;
            height: 20px;
        }

        /* Toggle Link */
        .toggle-form,
        .switch-form {
            text-align: center;
            margin-top: 25px;
            color: #718096;
            font-size: 1.2rem;
        }

        .toggle-form a,
        .switch-form a {
            color: #48cc6c;
            text-decoration: none;
            font-weight: 500;
        }

        .toggle-form a:hover,
        .switch-form a:hover {
            text-decoration: underline;
        }

        /* Password Strength Indicator */
        .password-strength {
            margin-top: 5px;
            font-size: 1rem;
        }

        .strength-weak {
            color: #f56565;
        }

        .strength-medium {
            color: #ed8936;
        }

        .strength-strong {
            color: #48cc6c;
        }

        /* Remember Me */
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 15px 0;
            font-size: 1.1rem;
            color: #4a5568;
        }

        .checkbox-group input[type="checkbox"] {
            width: auto;
            margin: 0;
        }

        /* Error Messages */
        .error-message,
        .alert-danger {
            background: #fed7d7;
            color: #c53030;
            padding: 12px 15px;
            border-radius: 8px;
            font-size: 1.1rem;
            margin-bottom: 15px;
            border: 1px solid #feb2b2;
        }

        .success-message,
        .alert-success {
            background: #c6f6d5;
            color: #22543d;
            padding: 12px 15px;
            border-radius: 8px;
            font-size: 1.1rem;
            margin-bottom: 15px;
            border: 1px solid #9ae6b4;
        }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {

            .login-container,
            .register-container,
            .auth-container,
            form {
                max-width: 90%;
                padding: 30px 25px;
                margin: 10px auto;
            }

            .form-title,
            h1,
            h2,
            .page-title {
                font-size: 3rem;
            }

            .form-group label,
            .input-group label,
            .form-field label {
                font-size: 1.1rem;
            }

            .form-group input,
            .input-group input,
            .form-field input,
            input[type="text"],
            input[type="email"],
            input[type="password"],
            input[type="tel"],
            select {
                font-size: 1.2rem;
            }

            .radio-options {
                flex-direction: column;
                gap: 12px;
            }

            .radio-option {
                min-width: auto;
            }

            body::before,
            body::after {
                display: none;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 10px;
            }

            .login-container,
            .register-container,
            .auth-container,
            form {
                padding: 25px 20px;
                border-radius: 15px;
            }

            .form-title,
            h1,
            h2,
            .page-title {
                font-size: 2.5rem;
            }

            .form-group label,
            .input-group label,
            .form-field label {
                font-size: 1rem;
            }

            .form-group input,
            .input-group input,
            .form-field input,
            input[type="text"],
            input[type="email"],
            input[type="password"],
            input[type="tel"],
            select {
                font-size: 1.1rem;
            }
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