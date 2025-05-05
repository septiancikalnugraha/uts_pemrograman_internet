<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

if (!isLoggedIn()) {
    header('Location: login.php?redirect=' . urlencode(basename($_SERVER['PHP_SELF'])));
    exit();
}

try {
    // Get all service categories
    $categories = getServiceCategories();

    // Get category ID from URL if exists
    $category_id = isset($_GET['category']) ? (int)$_GET['category'] : null;

    // Get services based on category or all services
    $current_category = null;
    if ($category_id) {
        $services = getServicesByCategory($category_id);
        foreach ($categories as $category) {
            if ($category['id'] == $category_id) {
                $current_category = $category;
                break;
            }
        }
    } else {
        $stmt = $conn->prepare("
            SELECT s.*, sc.name as category_name 
            FROM services s 
            LEFT JOIN service_categories sc ON s.category_id = sc.id 
            ORDER BY s.name
        ");
        $stmt->execute();
        $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch(PDOException $e) {
    error_log("Error in services.php: " . $e->getMessage());
    $error = "Terjadi kesalahan saat memuat data layanan. Silakan coba lagi nanti.";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layanan Kami - SmartCare</title>
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
            <h1 class="display-4">Layanan Kami</h1>
            <p class="lead">Solusi perbaikan HP profesional, cepat, dan bergaransi untuk semua kebutuhan Anda.</p>
        </div>
    </div>

    <!-- Services Content -->
    <div class="container py-5">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php else: ?>
            <div class="row">
                <!-- Categories Sidebar -->
                <div class="col-md-3">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Kategori Layanan</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <a href="services.php" class="text-decoration-none <?php echo !$category_id ? 'text-primary fw-bold' : 'text-dark'; ?>">
                                        Semua Layanan
                                    </a>
                                </li>
                                <?php foreach ($categories as $category): ?>
                                    <li class="mb-2">
                                        <a href="services.php?category=<?php echo $category['id']; ?>" 
                                           class="text-decoration-none <?php echo $category_id == $category['id'] ? 'text-primary fw-bold' : 'text-dark'; ?>">
                                            <i class="bi <?php echo $category['icon']; ?> me-2"></i>
                                            <?php echo htmlspecialchars($category['name']); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Services List -->
                <div class="col-md-9">
                    <?php if ($current_category): ?>
                        <h2 class="mb-4"><?php echo htmlspecialchars($current_category['name']); ?></h2>
                        <p class="text-muted mb-4"><?php echo htmlspecialchars($current_category['description']); ?></p>
                    <?php endif; ?>

                    <?php if (empty($services)): ?>
                        <div class="alert alert-info">
                            Tidak ada layanan pada kategori ini.
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($services as $service): ?>
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100 border-primary">
                                        <?php if ($service['image']): ?>
                                            <img src="<?php echo htmlspecialchars($service['image']); ?>" 
                                                 class="card-img-top" alt="<?php echo htmlspecialchars($service['name']); ?>">
                                        <?php endif; ?>
                                        <div class="card-body">
                                            <h5 class="card-title text-primary"><?php echo htmlspecialchars($service['name']); ?></h5>
                                            <p class="card-text"><?php echo htmlspecialchars($service['description']); ?></p>
                                            <span class="badge bg-success mb-2">Mulai dari Rp <?php echo number_format($service['price'], 0, ',', '.'); ?></span>
                                            <a href="service_request.php?id=<?php echo $service['id']; ?>" class="btn btn-outline-primary w-100 mt-2">Pesan Layanan</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
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
 