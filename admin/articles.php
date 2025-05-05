<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    header('Location: ../index.php');
    exit();
}

// Handle article deletion
if (isset($_POST['delete_article'])) {
    $article_id = (int)$_POST['article_id'];
    try {
        $stmt = $conn->prepare("DELETE FROM articles WHERE id = :id");
        $stmt->bindParam(':id', $article_id);
        if ($stmt->execute()) {
            header('Location: articles.php?success=Article deleted successfully');
            exit();
        } else {
            header('Location: articles.php?error=Failed to delete article');
            exit();
        }
    } catch (PDOException $e) {
        header('Location: articles.php?error=' . urlencode($e->getMessage()));
        exit();
    }
}

// Get all articles with category and author information
try {
    $stmt = $conn->query("
        SELECT a.*, c.name as category_name, u.username as author_name 
        FROM articles a 
        LEFT JOIN categories c ON a.category_id = c.id 
        LEFT JOIN users u ON a.user_id = u.id 
        ORDER BY a.created_at DESC
    ");
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = $e->getMessage();
    $articles = [];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Artikel - Admin SmartCare</title>
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
            <h1>Daftar Artikel</h1>
            <a href="add_article.php" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah Artikel
            </a>
        </div>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Kategori</th>
                                <th>Penulis</th>
                                <th>Status</th>
                                <th>Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($articles)): ?>
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada artikel.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($articles as $article): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($article['title']); ?></td>
                                        <td><?php echo $article['category_name'] ? htmlspecialchars($article['category_name']) : 'Tanpa Kategori'; ?></td>
                                        <td><?php echo htmlspecialchars($article['author_name']); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $article['status'] === 'published' ? 'success' : 'warning'; ?>">
                                                <?php echo $article['status'] === 'published' ? 'Publikasi' : 'Draft'; ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('d M Y', strtotime($article['created_at'])); ?></td>
                                        <td>
                                            <a href="edit_article.php?id=<?php echo $article['id']; ?>" class="btn btn-sm btn-primary">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form method="POST" action="" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus artikel ini?');">
                                                <input type="hidden" name="article_id" value="<?php echo $article['id']; ?>">
                                                <button type="submit" name="delete_article" class="btn btn-sm btn-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 