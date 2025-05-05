<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    header('Location: ../index.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: articles.php');
    exit();
}

$article_id = (int)$_GET['id'];

// Get article details
$stmt = $conn->prepare("SELECT * FROM articles WHERE id = :id");
$stmt->bindParam(':id', $article_id);
$stmt->execute();
$article = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$article) {
    header('Location: articles.php');
    exit();
}

// Get all categories
$stmt = $conn->query("SELECT * FROM categories ORDER BY name");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize($_POST['title']);
    $content = $_POST['content'];
    $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
    $status = $_POST['status'];
    $user_id = $_SESSION['user_id'];
    
    if (empty($title) || empty($content)) {
        $error = 'Please fill in all required fields';
    } else {
        $slug = createUniqueSlug('articles', $title, $article_id);
        
        $stmt = $conn->prepare("
            UPDATE articles 
            SET title = :title, slug = :slug, content = :content, 
                category_id = :category_id, status = :status,
                user_id = :user_id
            WHERE id = :id
        ");
        
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':slug', $slug);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':id', $article_id);
        
        if ($stmt->execute()) {
            header('Location: articles.php?success=Article updated successfully');
            exit();
        } else {
            $error = 'Failed to update article. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Artikel - Admin SmartCare</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="../index.php">SmartCare</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Dasbor</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="articles.php">Artikel</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="categories.php">Kategori</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="users.php">Pengguna</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php">Keluar</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Edit Artikel</h1>
            <a href="articles.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Daftar Artikel
            </a>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul</label>
                        <input type="text" class="form-control" id="title" name="title" 
                               value="<?php echo htmlspecialchars($article['title']); ?>" required placeholder="Judul artikel">
                    </div>

                    <div class="mb-3">
                        <label for="category_id" class="form-label">Kategori</label>
                        <select class="form-select" id="category_id" name="category_id">
                            <option value="">Pilih Kategori</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>" 
                                    <?php echo $category['id'] == $article['category_id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label">Isi Artikel</label>
                        <textarea class="form-control" id="content" name="content" rows="10" required placeholder="Tulis isi artikel di sini..."><?php 
                            echo htmlspecialchars($article['content']); 
                        ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="draft" <?php echo $article['status'] === 'draft' ? 'selected' : ''; ?>>Draft</option>
                            <option value="published" <?php echo $article['status'] === 'published' ? 'selected' : ''; ?>>Publikasi</option>
                        </select>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 