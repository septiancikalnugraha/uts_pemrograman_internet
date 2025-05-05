<?php
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$article_id = (int)$_GET['id'];
try {
    $article = getArticleById($article_id);
} catch (PDOException $e) {
    header('Location: index.php?page=404');
    exit();
}

if (!$article) {
    header('Location: index.php?page=404');
    exit();
}

// Get category name if exists
$category_name = '';
if ($article['category_id']) {
    try {
        $stmt = $conn->prepare("SELECT name FROM categories WHERE id = :id");
        $stmt->bindParam(':id', $article['category_id']);
        $stmt->execute();
        $category = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($category) {
            $category_name = $category['name'];
        }
    } catch (PDOException $e) {
        // Log error but continue execution
        error_log("Error fetching category: " . $e->getMessage());
    }
}

// Get categories for sidebar
try {
    $categories = getCategories();
} catch (PDOException $e) {
    error_log("Error fetching categories: " . $e->getMessage());
    $categories = [];
}
?>

<div class="row">
    <div class="col-md-8">
        <article class="card">
            <div class="card-body">
                <h1 class="card-title text-primary fw-bold"><?php echo htmlspecialchars($article['title']); ?></h1>
                
                <div class="text-muted mb-3">
                    Diposting pada <?php echo date('d M Y', strtotime($article['created_at'])); ?>
                    <?php if ($category_name): ?>
                        <span class="badge bg-info text-dark ms-2">
                            <a href="index.php?page=category&id=<?php echo $article['category_id']; ?>" class="text-dark text-decoration-none">
                                <?php echo htmlspecialchars($category_name); ?>
                            </a>
                        </span>
                    <?php endif; ?>
                </div>
                
                <div class="article-content">
                    <?php echo nl2br(htmlspecialchars($article['content'])); ?>
                </div>
            </div>
        </article>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                Kategori Artikel
            </div>
            <div class="card-body">
                <?php if (!empty($categories)): ?>
                    <ul class="list-unstyled">
                        <?php foreach ($categories as $category): ?>
                            <li>
                                <a href="index.php?page=category&id=<?php echo $category['id']; ?>">
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>Tidak ada kategori ditemukan.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div> 