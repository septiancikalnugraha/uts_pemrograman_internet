<?php
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$category_id = (int)$_GET['id'];

// Get category details
$stmt = $conn->prepare("SELECT * FROM categories WHERE id = :id");
$stmt->bindParam(':id', $category_id);
$stmt->execute();
$category = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$category) {
    header('Location: index.php?page=404');
    exit();
}

// Get articles in this category
$stmt = $conn->prepare("SELECT * FROM articles WHERE category_id = :category_id AND status = 'published' ORDER BY created_at DESC");
$stmt->bindParam(':category_id', $category_id);
$stmt->execute();
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="row">
    <div class="col-md-8">
        <h1 class="mb-4">Kategori: <?php echo htmlspecialchars($category['name']); ?></h1>
        
        <?php if (!empty($category['description'])): ?>
            <div class="alert alert-info">
                <?php echo htmlspecialchars($category['description']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (empty($articles)): ?>
            <div class="alert alert-info">Belum ada artikel pada kategori ini.</div>
        <?php else: ?>
            <?php foreach ($articles as $article): ?>
                <div class="card mb-4">
                    <div class="card-body">
                        <h2 class="card-title">
                            <a href="index.php?page=article&id=<?php echo $article['id']; ?>" class="text-decoration-none">
                                <?php echo htmlspecialchars($article['title']); ?>
                            </a>
                        </h2>
                        <p class="card-text">
                            <?php echo substr(htmlspecialchars($article['content']), 0, 200) . '...'; ?>
                        </p>
                        <div class="text-muted">
                            Diposting pada <?php echo date('d M Y', strtotime($article['created_at'])); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                Kategori Artikel
            </div>
            <div class="card-body">
                <?php
                $categories = getCategories();
                if (!empty($categories)):
                    echo '<ul class="list-unstyled">';
                    foreach ($categories as $cat):
                        $active = ($cat['id'] == $category_id) ? 'fw-bold text-primary' : '';
                        echo '<li class="' . $active . '"><a href="index.php?page=category&id=' . $cat['id'] . '">' . 
                             htmlspecialchars($cat['name']) . '</a></li>';
                    endforeach;
                    echo '</ul>';
                else:
                    echo '<p>Tidak ada kategori ditemukan.</p>';
                endif;
                ?>
            </div>
        </div>
    </div>
</div> 