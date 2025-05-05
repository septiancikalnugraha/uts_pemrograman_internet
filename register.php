<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $email = sanitize($_POST['email']);

    if (empty($username) || empty($password) || empty($confirm_password) || empty($email)) {
        $error = 'Silakan isi semua kolom.';
    } elseif ($password !== $confirm_password) {
        $error = 'Kata sandi tidak cocok.';
    } else {
        // Check if username already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $error = 'Username sudah terdaftar.';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $role = 'customer'; // Default role
            
            $stmt = $conn->prepare("INSERT INTO users (username, password, email, role) VALUES (:username, :password, :email, :role)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':role', $role);
            
            if ($stmt->execute()) {
                $success = 'Pendaftaran berhasil! Silakan masuk.';
            } else {
                $error = 'Pendaftaran gagal. Silakan coba lagi.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - SmartCare</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .card {
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
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
        .signup-footer {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center mt-5 mb-5">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header text-white text-center">
                        <!-- Uncomment and add your logo image if available -->
                        <!-- <img src="assets/img/logo.png" alt="SmartCare Logo" class="logo"> -->
                        <h2 class="brand-name">SmartCare</h2>
                        <p class="mb-0">Daftar akun baru untuk mengakses layanan kami</p>
                    </div>
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger text-center">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo $error; ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($success): ?>
                            <div class="alert alert-success text-center">
                                <i class="bi bi-check-circle-fill me-2"></i><?php echo $success; ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="" class="needs-validation" novalidate>
                            <div class="mb-4">
                                <label for="username" class="form-label">Username</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                                    <input type="text" class="form-control" id="username" name="username" required placeholder="Buat username Anda">
                                </div>
                                <small class="text-muted">Username harus unik.</small>
                            </div>
                            <div class="mb-4">
                                <label for="email" class="form-label">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                                    <input type="email" class="form-control" id="email" name="email" required placeholder="Masukkan email aktif">
                                </div>
                                <small class="text-muted">Kami tidak akan membagikan email Anda kepada siapapun.</small>
                            </div>
                            <div class="mb-4">
                                <label for="password" class="form-label">Kata Sandi</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                    <input type="password" class="form-control" id="password" name="password" required placeholder="Buat kata sandi">
                                </div>
                                <small class="text-muted">Gunakan kombinasi huruf, angka, dan karakter khusus.</small>
                            </div>
                            <div class="mb-4">
                                <label for="confirm_password" class="form-label">Konfirmasi Kata Sandi</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required placeholder="Ulangi kata sandi">
                                </div>
                                <small class="text-muted">Pastikan sama dengan kata sandi di atas.</small>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-person-plus-fill me-2"></i>Daftar Sekarang
                                </button>
                            </div>
                        </form>
                        <div class="text-center signup-footer">
                            <p>Sudah punya akun? <a href="login.php" class="fw-bold text-primary">Masuk di sini</a></p>
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