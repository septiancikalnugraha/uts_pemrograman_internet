<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = 'Silakan isi semua kolom.';
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_role'] = $user['role'];
            header('Location: index.php');
            exit();
        } else {
            $error = 'Username atau password salah.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - SmartCare</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
        }
        .card {
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .card-header {
            border-radius: 15px 15px 0 0 !important;
            background: linear-gradient(135deg, #007bff, #0056b3);
            padding: 20px;
            border: none;
        }
        .btn-primary {
            background: linear-gradient(135deg, #007bff, #0056b3);
            border: none;
            padding: 12px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #0056b3, #004094);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.4);
        }
        .form-control {
            border-radius: 8px;
            padding: 12px;
            border: 1px solid #ced4da;
            background-color: #f8f9fa;
        }
        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
            border-color: #80bdff;
        }
        .logo {
            max-width: 120px;
            margin-bottom: 10px;
        }
        .brand-name {
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 10px;
            color: white;
        }
        .card-body {
            padding: 30px;
        }
        .alert {
            border-radius: 8px;
            border: none;
        }
        .form-label {
            font-weight: 600;
            color: #495057;
        }
        .input-group-text {
            background-color: #f8f9fa;
            border-radius: 8px 0 0 8px;
            border-right: none;
        }
        .input-group .form-control {
            border-radius: 0 8px 8px 0;
        }
        .text-muted {
            font-size: 0.9rem;
        }
        .login-footer {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
        }
        .remember-me {
            display: flex;
            align-items: center;
        }
        .remember-me input {
            margin-right: 5px;
        }
        .forgot-password {
            text-align: right;
        }
        .welcome-text {
            color: white;
            max-width: 80%;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow">
                    <div class="card-header text-white text-center">
                        <!-- Uncomment and add your logo image if available -->
                        <!-- <img src="assets/img/logo.png" alt="SmartCare Logo" class="logo"> -->
                        <h2 class="brand-name">SmartCare</h2>
                        <p class="welcome-text mb-0">Selamat datang kembali! Silakan masuk ke akun Anda</p>
                    </div>
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo $error; ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="" class="needs-validation" novalidate>
                            <div class="mb-4">
                                <label for="username" class="form-label">Username</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                                    <input type="text" class="form-control" id="username" name="username" required placeholder="Masukkan username Anda">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Kata Sandi</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                    <input type="password" class="form-control" id="password" name="password" required placeholder="Masukkan kata sandi Anda">
                                </div>
                            </div>
                            
                            <div class="row mb-4">
                                <div class="col-6">
                                    <div class="remember-me">
                                        <input type="checkbox" id="remember" name="remember">
                                        <label for="remember" class="ms-1">Ingat saya</label>
                                    </div>
                                </div>
                                <div class="col-6 forgot-password">
                                    <a href="#" class="text-primary">Lupa kata sandi?</a>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                                </button>
                            </div>
                        </form>
                        
                        <div class="text-center login-footer">
                            <p>Belum punya akun? <a href="register.php" class="fw-bold text-primary">Daftar di sini</a></p>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <p class="text-muted">&copy; <?php echo date('Y'); ?> SmartCare. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form validation
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    </script>
</body>
</html>