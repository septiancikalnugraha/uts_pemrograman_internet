<?php
$articles = getArticles();
?>

<div class="row">
    <div class="col-md-8">
        <h1 class="mb-4">Latest Articles</h1>
        <?php if (empty($articles)): ?>
            <div class="alert alert-info">No articles found.</div>
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
                            Posted on <?php echo date('F j, Y', strtotime($article['created_at'])); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                Categories
            </div>
            <div class="card-body">
                <?php
                $categories = getCategories();
                if (!empty($categories)):
                    echo '<ul class="list-unstyled">';
                    foreach ($categories as $category):
                        echo '<li><a href="index.php?page=category&id=' . $category['id'] . '">' . 
                             htmlspecialchars($category['name']) . '</a></li>';
                    endforeach;
                    echo '</ul>';
                else:
                    echo '<p>No categories found.</p>';
                endif;
                ?>
            </div>
        </div>
    </div>
</div> 