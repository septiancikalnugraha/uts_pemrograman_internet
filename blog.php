<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

if (!isLoggedIn()) {
    header('Location: login.php?redirect=' . urlencode(basename($_SERVER['PHP_SELF'])));
    exit();
}

$articles = getAllArticles(12, 0);
$categories = getCategories();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog & Artikel - SmartCare</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container py-5">
    <div class="row">
        <div class="col-md-8">
            <h1 class="mb-4 text-primary">Blog & Artikel</h1>
            <?php if (empty($articles)): ?>
                <div class="alert alert-info">Belum ada artikel.</div>
            <?php else: ?>
                <?php foreach ($articles as $article): ?>
                    <div class="card mb-4">
                        <div class="card-body">
                            <h2 class="card-title">
                                <a href="article.php?id=<?php echo $article['id']; ?>" class="text-decoration-none text-primary">
                                    <?php echo htmlspecialchars($article['title']); ?>
                                </a>
                            </h2>
                            <div class="text-muted mb-2">
                                Diposting pada <?php echo date('d M Y', strtotime($article['created_at'])); ?> oleh <b><?php echo htmlspecialchars($article['author_name']); ?></b>
                            </div>
                            <p class="card-text">
                                <?php echo htmlspecialchars(substr($article['content'], 0, 180)) . '...'; ?>
                            </p>
                            <a href="article.php?id=<?php echo $article['id']; ?>" class="btn btn-outline-primary">Baca Selengkapnya</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Kategori Artikel</div>
                <div class="card-body">
                    <?php if (!empty($categories)): ?>
                        <ul class="list-unstyled">
                            <?php foreach ($categories as $category): ?>
                                <li><a href="category.php?id=<?php echo $category['id']; ?>" class="text-decoration-none"><?php echo htmlspecialchars($category['name']); ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p>Tidak ada kategori ditemukan.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 