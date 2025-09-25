<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login - Taste of Africa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <style>
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
            background-color: #f8f9fa;
            background-image:
                repeating-linear-gradient(0deg,
                    #b77a7a,
                    #b77a7a 1px,
                    transparent 1px,
                    transparent 20px),
                repeating-linear-gradient(90deg,
                    #b77a7a,
                    #b77a7a 1px,
                    transparent 1px,
                    transparent 20px),
                linear-gradient(rgba(183, 122, 122, 0.1),
                    rgba(183, 122, 122, 0.1));
            background-blend-mode: overlay;
            background-size: 20px 20px;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .login-container {
            margin-top: 100px;
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