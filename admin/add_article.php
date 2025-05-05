<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    header('Location: ../index.php');
    exit();
}

// Get all categories
try {
    $stmt = $conn->query("SELECT * FROM categories ORDER BY name");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = 'Failed to load categories: ' . $e->getMessage();
    $categories = [];
}

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
        try {
            $slug = createUniqueSlug('articles', $title);
            
            $stmt = $conn->prepare("
                INSERT INTO articles (title, slug, content, category_id, user_id, status) 
                VALUES (:title, :slug, :content, :category_id, :user_id, :status)
            ");
            
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':slug', $slug);
            $stmt->bindParam(':content', $content);
            $stmt->bindParam(':category_id', $category_id);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':status', $status);
            
            if ($stmt->execute()) {
                header('Location: articles.php?success=Article added successfully');
                exit();
            } else {
                $error = 'Failed to add article. Please try again.';
            }
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Artikel - Admin SmartCare</title>
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
            <h1>Tambah Artikel</h1>
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
                        <input type="text" class="form-control" id="title" name="title" required placeholder="Judul artikel">
                    </div>

                    <div class="mb-3">
                        <label for="category_id" class="form-label">Kategori</label>
                        <select class="form-select" id="category_id" name="category_id">
                            <option value="">Pilih Kategori</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>">
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label">Isi Artikel</label>
                        <textarea class="form-control" id="content" name="content" rows="10" required placeholder="Tulis isi artikel di sini..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="draft">Draft</option>
                            <option value="published">Publikasi</option>
                        </select>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Tambah Artikel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 