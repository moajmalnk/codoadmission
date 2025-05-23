<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CODO AI Innovations - Login</title>
    <link rel="icon" href="https://codoacademy.com/uploads/system/e7c3fb5390c74909db1bb3559b24007a.png"
        type="image/x-icon">
    <link rel="apple-touch-icon" sizes="180x180"
        href="https://codoacademy.com/uploads/system/e7c3fb5390c74909db1bb3559b24007a.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="navbar.css">
    <style>
        :root {
            --primary-color: #00b764;
            --secondary-color: #00203f;
            --text-color: #2d3436;
            --bg-color: #f0f3ff;
            --border-color: #e5e7eb;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background-color: var(--bg-color);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .main-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .login-container {
            max-width: 450px;
            width: 100%;
        }

        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 2.5rem;
            position: relative;
            overflow: hidden;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
        }

        .logo {
            max-width: 150px;
            margin-bottom: 0rem;
        }

        .login-title {
            color: var(--secondary-color);
            font-weight: 600;
            margin-bottom: 1.5rem;
            font-size: 1.75rem;
        }

        .form-label {
            font-weight: 500;
            color: var(--text-color);
            margin-bottom: 0.5rem;
        }

        .form-control {
            padding: 0.75rem 1rem;
            border-radius: 10px;
            border: 2px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(0, 183, 100, 0.1);
        }

        .input-group-text {
            background: none;
            border: 2px solid var(--border-color);
            border-right: none;
            color: #6c757d;
        }

        .password-field {
            border-left: none;
        }

        .btn-login {
            padding: 0.75rem;
            border-radius: 10px;
            font-weight: 600;
            background-color: var(--primary-color);
            border: none;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background-color: #009e54;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 183, 100, 0.2);
        }

        .alert {
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            display: none;
        }

        .alert-danger {
            background-color: #fff5f5;
            border-color: #feb2b2;
            color: #c53030;
        }

        @media (max-width: 576px) {
            .login-card {
                padding: 2rem;
                margin: 1rem;
            }

            .login-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container" style="padding: 0px 10px 0px 0px !important;">
            <a class="navbar-brand" href="#">
                <img src="https://codoacademy.com/uploads/system/0623b9b92a325936b0a00502d95c22e6.png" 
                     alt="CODO AI Innovations" class="logo">
            </a>
            <div class="ms-auto">
                <a href="https://codoacademy.com" class="btn btn-outline-primary" target="_blank">
                    <i class="fas fa-globe me-2"></i>Visit Website
                </a>
            </div>
        </div>
    </nav>

    <div class="main-content">
        <div class="login-container">
            <div class="login-card">
                <h1 class="login-title text-center">Welcome Back!</h1>
                
                <div class="alert alert-danger" id="errorAlert" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <span id="errorMessage"></span>
                </div>
                
                <form id="loginForm">
                    <div class="mb-4">
                        <label for="username" class="form-label">Username</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-user"></i>
                            </span>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" class="form-control password-field" id="password" name="password" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-login w-100" id="loginButton">
                        <i class="fas fa-sign-in-alt me-2"></i>Sign In
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="login.js"></script>
</body>
</html> 