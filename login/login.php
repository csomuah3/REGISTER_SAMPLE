<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login - Taste of Africa</title>
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
            box-shadow: 0 25px 10px rgba(24, 0, 34, 0.1);
            max-width: 1100px;
            width: 100%;
            padding: 30px;
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
            color: #d1456d;
            transition: color 0.3s;
        }

        .highlight:hover {
            color: #b77a7a;
        }


        .card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            padding-right: 0%;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #D19C97;
            color: #fff;
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

        .form-label i {
            margin-left: 5px;
            color: #b77a7a;
        }

        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }

        .btn:disabled {
            opacity: 0.6;
        }
    </style>
</head>

<body>
    <div class="container login-container">
        <div class="row justify-content-center animate__animated animate__fadeInDown">
            <div class="col-md-6">
                <div class="card animate__animated animate__zoomIn">
                    <div class="card-header text-center">
                        <h4>Login</h4>
                    </div>
                    <div class="card-body">
                        <form id="login-form">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <i class="fa fa-envelope"></i></label>
                                <input type="email" id="email" name="email" class="form-control animate__animated animate__fadeInUp" required>
                            </div>
                            <div class="mb-4">
                                <label for="password" class="form-label">Password <i class="fa fa-lock"></i></label>
                                <input type="password" id="password" name="password" class="form-control animate__animated animate__fadeInUp" required>
                                <small class="text-muted">Password must be at least 6 characters</small>
                            </div>
                            <button type="submit" class="btn btn-custom w-100 animate-pulse-custom">Login</button>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        Don't have an account? <a href="register.php" class="highlight">Register here</a>
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
        $(document).ready(function() {
            $('#login-form').submit(function(e) {
                e.preventDefault();

                // Get form values
                var email = $('#email').val().trim();
                var password = $('#password').val();

                console.log('=== LOGIN ATTEMPT ===');
                console.log('Email:', email);

                // Basic validation
                if (email === '' || password === '') {
                    Swal.fire({
                        title: 'Validation Error',
                        text: 'Please fill in all fields!',
                        icon: 'error',
                        confirmButtonColor: '#D19C97'
                    });
                    return;
                }

                // Simple email validation
                if (!email.includes('@') || !email.includes('.')) {
                    Swal.fire({
                        title: 'Validation Error',
                        text: 'Please enter a valid email address!',
                        icon: 'error',
                        confirmButtonColor: '#D19C97'
                    });
                    return;
                }

                // Password length validation
                if (password.length < 6) {
                    Swal.fire({
                        title: 'Validation Error',
                        text: 'Password must be at least 6 characters long!',
                        icon: 'error',
                        confirmButtonColor: '#D19C97'
                    });
                    return;
                }

                // Show loading state
                var $btn = $('button[type="submit"]');
                var originalText = $btn.text();
                $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status"></span>Logging in...');

                // AJAX request for login - using existing register_user_action.php
                $.ajax({
                    url: '../actions/register_user_action.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        email: email,
                        password: password
                        // Note: No 'name' field sent, so the PHP will detect this as a login request
                    },
                    success: function(response) {
                        console.log('=== SERVER RESPONSE (SUCCESS) ===');
                        console.log('Full Response:', response);
                        console.log('Status:', response.status);
                        console.log('Message:', response.message);

                        if (response.status === 'success') {
                            Swal.fire({
                                title: 'Success!',
                                text: response.message,
                                icon: 'success',
                                confirmButtonColor: '#D19C97',
                                timer: 2000,
                                timerProgressBar: true
                            }).then(() => {
                                window.location.href = '../index.php';
                            });
                        } else {
                            Swal.fire({
                                title: 'Login Failed',
                                text: response.message,
                                icon: 'error',
                                confirmButtonColor: '#D19C97'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('=== AJAX ERROR ===');
                        console.log('Status:', status);
                        console.log('Error:', error);
                        console.log('Response Text:', xhr.responseText);
                        console.log('Status Code:', xhr.status);

                        Swal.fire({
                            title: 'Connection Error',
                            text: 'Failed to connect to server. Please try again.',
                            icon: 'error',
                            confirmButtonColor: '#D19C97'
                        });
                    },
                    complete: function() {
                        $btn.prop('disabled', false).text(originalText);
                    }
                });
            });
        });
    </script>
</body>

</html>