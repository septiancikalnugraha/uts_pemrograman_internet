<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

// Tentukan halaman yang akan ditampilkan
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Include header
include 'includes/header.php';

// Tampilkan konten berdasarkan halaman
switch($page) {
    case 'home':
        include 'pages/home.php';
        break;
    case 'article':
        include 'pages/article.php';
        break;
    case 'category':
        include 'pages/category.php';
        break;
    default:
        include 'pages/404.php';
}

// Include footer
include 'includes/footer.php';
?> 