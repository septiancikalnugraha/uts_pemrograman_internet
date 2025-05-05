<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    header('Location: ../index.php');
    exit();
}

// Get statistics
$stmt = $conn->query("SELECT COUNT(*) as total FROM articles");
$total_articles = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

$stmt = $conn->query("SELECT COUNT(*) as total FROM categories");
$total_categories = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

$stmt = $conn->query("SELECT COUNT(*) as total FROM users");
$total_users = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Simple CMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="../index.php">Simple CMS</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="articles.php">Articles</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="categories.php">Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="users.php">Users</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Admin Dashboard</h1>
        
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Articles</h5>
                        <h2><?php echo $total_articles; ?></h2>
                        <a href="articles.php" class="text-white">View Articles</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Categories</h5>
                        <h2><?php echo $total_categories; ?></h2>
                        <a href="categories.php" class="text-white">View Categories</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Users</h5>
                        <h2><?php echo $total_users; ?></h2>
                        <a href="users.php" class="text-white">View Users</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Recent Articles</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        $stmt = $conn->query("SELECT * FROM articles ORDER BY created_at DESC LIMIT 5");
                        $recent_articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        
                        if (empty($recent_articles)) {
                            echo '<p>No articles found.</p>';
                        } else {
                            echo '<ul class="list-group">';
                            foreach ($recent_articles as $article) {
                                echo '<li class="list-group-item">';
                                echo '<a href="edit_article.php?id=' . $article['id'] . '">' . 
                                     htmlspecialchars($article['title']) . '</a>';
                                echo '<small class="text-muted d-block">' . 
                                     date('F j, Y', strtotime($article['created_at'])) . '</small>';
                                echo '</li>';
                            }
                            echo '</ul>';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Recent Users</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        $stmt = $conn->query("SELECT * FROM users ORDER BY created_at DESC LIMIT 5");
                        $recent_users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        
                        if (empty($recent_users)) {
                            echo '<p>No users found.</p>';
                        } else {
                            echo '<ul class="list-group">';
                            foreach ($recent_users as $user) {
                                echo '<li class="list-group-item">';
                                echo htmlspecialchars($user['username']);
                                echo '<small class="text-muted d-block">' . 
                                     date('F j, Y', strtotime($user['created_at'])) . '</small>';
                                echo '</li>';
                            }
                            echo '</ul>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 