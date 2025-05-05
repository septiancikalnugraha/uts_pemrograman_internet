<?php
session_start();
require_once 'includes/functions.php';
if (!isLoggedIn()) {
    header('Location: login.php?redirect=' . urlencode(basename($_SERVER['PHP_SELF'])));
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami - SmartCare</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body">
                    <h1 class="mb-4 text-primary"><i class="bi bi-info-circle"></i> Tentang SmartCare</h1>
                    <p>SmartCare adalah layanan service handphone profesional yang berkomitmen memberikan solusi terbaik untuk segala permasalahan perangkat Anda. Dengan teknisi berpengalaman, peralatan modern, dan layanan bergaransi, kami siap membantu Anda dengan cepat, aman, dan harga terjangkau.</p>
                    <h4 class="mt-4">Keunggulan Kami</h4>
                    <ul>
                        <li><i class="bi bi-shield-check text-success"></i> Bergaransi & Transparan</li>
                        <li><i class="bi bi-person-badge text-primary"></i> Teknisi Berpengalaman</li>
                        <li><i class="bi bi-clock-history text-warning"></i> Proses Cepat & Bisa Ditunggu</li>
                        <li><i class="bi bi-cash-coin text-success"></i> Harga Jujur & Kompetitif</li>
                        <li><i class="bi bi-geo-alt text-danger"></i> Lokasi strategis & mudah dijangkau</li>
                    </ul>
                    <h4 class="mt-4">Visi & Misi</h4>
                    <p><b>Visi:</b> Menjadi pusat layanan handphone terpercaya dan terbaik di Indonesia.</p>
                    <p><b>Misi:</b> Memberikan pelayanan terbaik, solusi cepat, dan edukasi kepada pelanggan tentang perawatan perangkat.</p>
                    <div class="mt-4">
                        <h5>Kontak SmartCare</h5>
                        <ul class="list-unstyled">
                            <li><i class="bi bi-geo-alt"></i> Jl. Contoh No. 123</li>
                            <li><i class="bi bi-telephone"></i> (021) 123-4567</li>
                            <li><i class="bi bi-envelope"></i> info@smartcare.com</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 