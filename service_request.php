<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: login.php?redirect=service_request.php?id=' . $_GET['id']);
    exit();
}

$error = '';
$success = '';

// Get service details
if (isset($_GET['id'])) {
    try {
        $service_id = (int)$_GET['id'];
        $service = getServiceById($service_id);
        
        if (!$service) {
            header('Location: services.php');
            exit();
        }
    } catch(PDOException $e) {
        error_log("Error getting service details: " . $e->getMessage());
        $error = "Terjadi kesalahan saat memuat detail layanan. Silakan coba lagi nanti.";
    }
} else {
    header('Location: services.php');
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $device_type = sanitize($_POST['device_type']);
        $device_model = sanitize($_POST['device_model']);
        $problem_description = sanitize($_POST['problem_description']);
        $user_id = $_SESSION['user_id'];
        
        if (empty($device_type) || empty($device_model) || empty($problem_description)) {
            $error = "Semua field harus diisi.";
        } else {
            $stmt = $conn->prepare("
                INSERT INTO service_requests (
                    user_id, service_id, device_type, device_model, 
                    problem_description, status, estimated_price
                ) VALUES (
                    :user_id, :service_id, :device_type, :device_model,
                    :problem_description, 'pending', :estimated_price
                )
            ");
            
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':service_id', $service_id);
            $stmt->bindParam(':device_type', $device_type);
            $stmt->bindParam(':device_model', $device_model);
            $stmt->bindParam(':problem_description', $problem_description);
            $stmt->bindParam(':estimated_price', $service['price']);
            
            if ($stmt->execute()) {
                $success = "Permintaan layanan berhasil dikirim. Tim kami akan segera menghubungi Anda.";
            } else {
                $error = "Gagal mengirim permintaan layanan. Silakan coba lagi.";
            }
        }
    } catch(PDOException $e) {
        error_log("Error submitting service request: " . $e->getMessage());
        $error = "Terjadi kesalahan saat mengirim permintaan layanan. Silakan coba lagi nanti.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Layanan - SmartCare</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-phone"></i> SmartCare
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="services.php">Layanan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="blog.php">Artikel</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">Tentang Kami</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Kontak</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="admin/index.php">Dasbor</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Keluar</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Masuk</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">Daftar</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="bg-primary text-white py-5">
        <div class="container">
            <h1 class="display-4">Pesan Layanan</h1>
            <p class="lead">Isi form di bawah ini untuk memesan layanan kami.</p>
        </div>
    </div>

    <!-- Service Request Form -->
    <div class="container py-5">
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php else: ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title text-primary"><?php echo htmlspecialchars($service['name']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($service['description']); ?></p>
                            <p class="text-muted">Estimasi Harga: Rp <?php echo number_format($service['price'], 0, ',', '.'); ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Form Pemesanan</h5>
                            <form method="POST" action="">
                                <div class="mb-3">
                                    <label for="device_type" class="form-label">Tipe Perangkat</label>
                                    <select class="form-select" id="device_type" name="device_type" required>
                                        <option value="">Pilih Tipe Perangkat</option>
                                        <option value="Smartphone">Smartphone</option>
                                        <option value="Tablet">Tablet</option>
                                        <option value="Laptop">Laptop</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="device_model" class="form-label">Model Perangkat</label>
                                    <input type="text" class="form-control" id="device_model" name="device_model" 
                                           required placeholder="Contoh: iPhone 12, Samsung Galaxy S21">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="problem_description" class="form-label">Deskripsi Masalah</label>
                                    <textarea class="form-control" id="problem_description" name="problem_description" 
                                              rows="4" required placeholder="Jelaskan masalah yang Anda alami..."></textarea>
                                </div>
                                
                                <button type="submit" class="btn btn-primary w-100">Kirim Permintaan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>SmartCare</h5>
                    <p>Layanan service HP profesional, bergaransi, dan harga terjangkau.</p>
                </div>
                <div class="col-md-4">
                    <h5>Menu Cepat</h5>
                    <ul class="list-unstyled">
                        <li><a href="services.php" class="text-white">Layanan</a></li>
                        <li><a href="blog.php" class="text-white">Artikel</a></li>
                        <li><a href="about.php" class="text-white">Tentang Kami</a></li>
                        <li><a href="contact.php" class="text-white">Kontak</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Kontak Kami</h5>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-geo-alt"></i> Jl. Contoh No. 123</li>
                        <li><i class="bi bi-telephone"></i> (021) 123-4567</li>
                        <li><i class="bi bi-envelope"></i> info@smartcare.com</li>
                    </ul>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p>&copy; <?php echo date('Y'); ?> SmartCare. Hak cipta dilindungi.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 