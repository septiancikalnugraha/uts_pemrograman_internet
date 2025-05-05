<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

// Get featured services
$featured_services = getFeaturedServices();

// Get latest articles
$latest_articles = getLatestArticles();

// Get service categories
$service_categories = getServiceCategories();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartCare - Jasa Service HP Profesional</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
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
                        <a class="nav-link active" href="index.php">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="services.php">Layanan</a>
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
                            <a class="nav-link" href="admin/index.php">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Keluar</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link btn-login" href="login.php">Masuk</a>
                        </li>
                        <li class="nav-item ms-2">
                            <a class="nav-link btn-register" href="register.php">Daftar</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="container mt-4">
        <div class="hero-service">
            <div class="hero-left">
                <h1 class="hero-main-title">JASA <span>SERVICE HP</span></h1>
                <p class="hero-subtitle">Apakah HP Anda mengalami masalah? Jangan khawatir, <b>SmartCare</b> siap membantu!</p>
                <div class="hero-list-title">LAYANAN KAMI:</div>
                <ul class="hero-list">
                    <li><i class="bi bi-tools"></i> Perbaikan Perangkat Keras</li>
                    <li><i class="bi bi-cpu"></i> Perbaikan Perangkat Lunak</li>
                    <li><i class="bi bi-phone"></i> Ganti Layar & Baterai</li>
                </ul>
                <a href="https://wa.me/6285215759965" class="btn-hero-cta"><i class="bi bi-whatsapp"></i> HUBUNGI KAMI</a>
                <div class="hero-contact">
                    <i class="bi bi-telephone"></i> 0852-1575-9965
                </div>
            </div>
            <div class="hero-right">
                <img src="assets/images/teknisi.jpg" alt="Teknisi Service HP" />
            </div>
        </div>
    </div>

    <!-- Service Categories -->
    <div class="container py-5">
        <div class="section-header text-center mb-5">
            <h6 class="section-subtitle">Apa Yang Kami Tawarkan</h6>
            <h2 class="section-title">Kategori Layanan</h2>
            <div class="section-divider"></div>
        </div>
        
        <div class="row">
            <?php foreach ($service_categories as $category): ?>
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="service-category">
                    <div class="icon-wrapper">
                        <i class="bi bi-<?php echo htmlspecialchars($category['icon']); ?>"></i>
                    </div>
                    <h5><?php echo htmlspecialchars($category['name']); ?></h5>
                    <p><?php echo htmlspecialchars($category['description']); ?></p>
                    <a href="category.php?id=<?php echo $category['id']; ?>" class="btn-category">Selengkapnya</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Featured Services -->
    <div class="bg-light py-5">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h6 class="section-subtitle">Solusi Terbaik</h6>
                <h2 class="section-title">Layanan Unggulan</h2>
                <div class="section-divider"></div>
            </div>
            
            <div class="row">
                <?php foreach ($featured_services as $service): ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card service-card h-100">
                            <div class="card-img-wrapper">
                                <img src="assets/images/services/<?php echo $service['image']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($service['name']); ?>">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($service['name']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($service['description']); ?></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="price-tag">Rp <?php echo number_format($service['price'], 0, ',', '.'); ?></span>
                                    <a href="service.php?id=<?php echo $service['id']; ?>" class="btn-service">Detail</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="text-center mt-4">
                <a href="services.php" class="btn-view-all">Lihat Semua Layanan</a>
            </div>
        </div>
    </div>

    <!-- Why Choose Us -->
    <div class="container py-5">
        <div class="section-header text-center mb-5">
            <h6 class="section-subtitle">Keunggulan Kami</h6>
            <h2 class="section-title">Mengapa Memilih SmartCare?</h2>
            <div class="section-divider"></div>
        </div>
        
        <div class="row why-choose-us">
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="feature-box">
                    <div class="icon-box">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h5>Bergaransi</h5>
                    <p>Semua perbaikan bergaransi hingga 3 bulan</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="feature-box">
                    <div class="icon-box">
                        <i class="bi bi-clock"></i>
                    </div>
                    <h5>Cepat</h5>
                    <p>Servis selesai dalam waktu 1-3 hari kerja</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="feature-box">
                    <div class="icon-box">
                        <i class="bi bi-people"></i>
                    </div>
                    <h5>Profesional</h5>
                    <p>Teknisi berpengalaman dan bersertifikat</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="feature-box">
                    <div class="icon-box">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <h5>Terjangkau</h5>
                    <p>Harga kompetitif dengan kualitas terbaik</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Latest Articles -->
    <div class="bg-light py-5">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h6 class="section-subtitle">Tips & Informasi</h6>
                <h2 class="section-title">Artikel Terbaru</h2>
                <div class="section-divider"></div>
            </div>
            
            <div class="row">
                <?php foreach ($latest_articles as $article): ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card blog-card h-100">
                            <div class="card-img-wrapper">
                                <img src="assets/images/blog/<?php echo $article['image']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($article['title']); ?>">
                                <div class="post-date">
                                    <span class="day"><?php echo date('d', strtotime($article['created_at'])); ?></span>
                                    <span class="month"><?php echo date('M', strtotime($article['created_at'])); ?></span>
                                </div>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($article['title']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars(substr($article['content'], 0, 120)) . '...'; ?></p>
                                <a href="article.php?id=<?php echo $article['id']; ?>" class="btn-read-more">Baca Selengkapnya</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="text-center mt-4">
                <a href="blog.php" class="btn-view-all">Lihat Semua Artikel</a>
            </div>
        </div>
    </div>

    <!-- Call To Action -->
    <div class="cta-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 col-md-7">
                    <h3>Butuh Bantuan dengan HP Anda?</h3>
                    <p>Konsultasikan masalah HP Anda dengan teknisi profesional kami sekarang juga!</p>
                </div>
                <div class="col-lg-4 col-md-5 text-md-end">
                    <a href="contact.php" class="btn-cta">Hubungi Kami</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4 mb-md-0">
                    <div class="footer-about">
                        <h5>SmartCare</h5>
                        <p>Layanan service HP profesional, bergaransi, dan harga terjangkau untuk semua merek dan tipe smartphone.</p>
                        <div class="social-links">
                            <a href="#"><i class="bi bi-facebook"></i></a>
                            <a href="#"><i class="bi bi-instagram"></i></a>
                            <a href="#"><i class="bi bi-twitter"></i></a>
                            <a href="#"><i class="bi bi-youtube"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
                    <div class="footer-links">
                        <h5>Menu Cepat</h5>
                        <ul class="list-unstyled">
                            <li><a href="services.php">Layanan</a></li>
                            <li><a href="blog.php">Artikel</a></li>
                            <li><a href="about.php">Tentang Kami</a></li>
                            <li><a href="contact.php">Kontak</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <div class="footer-links">
                        <h5>Layanan</h5>
                        <ul class="list-unstyled">
                            <li><a href="#">Ganti LCD</a></li>
                            <li><a href="#">Ganti Baterai</a></li>
                            <li><a href="#">Perbaikan Software</a></li>
                            <li><a href="#">Water Damage</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <div class="footer-contact">
                        <h5>Kontak Kami</h5>
                        <ul class="list-unstyled">
                            <li><i class="bi bi-geo-alt"></i> Jl. Contoh No. 123, Jakarta</li>
                            <li><i class="bi bi-telephone"></i> 0852-1575-9965</li>
                            <li><i class="bi bi-envelope"></i> info@smartcare.com</li>
                            <li><i class="bi bi-clock"></i> Senin-Sabtu: 09:00 - 18:00</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <p>&copy; <?php echo date('Y'); ?> SmartCare. Hak cipta dilindungi.</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p>Dibuat dengan <i class="bi bi-heart-fill text-danger"></i> oleh SmartCare Team</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>