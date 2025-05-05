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
    <title>Kontak - SmartCare</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <h1 class="mb-4 text-primary"><i class="bi bi-envelope-paper"></i> Kontak Kami</h1>
                    <form>
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Anda" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email aktif" required>
                        </div>
                        <div class="mb-3">
                            <label for="pesan" class="form-label">Pesan</label>
                            <textarea class="form-control" id="pesan" name="pesan" rows="4" placeholder="Tulis pesan Anda..." required></textarea>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i> Kirim Pesan</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3">Info Kontak</h5>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-geo-alt"></i> Jl. Contoh No. 123</li>
                        <li><i class="bi bi-telephone"></i> (021) 123-4567</li>
                        <li><i class="bi bi-envelope"></i> info@smartcare.com</li>
                        <li><i class="bi bi-whatsapp"></i> 0852-1575-9965</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 