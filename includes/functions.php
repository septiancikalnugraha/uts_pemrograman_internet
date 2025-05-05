<?php
// Fungsi untuk mendapatkan semua artikel
function getArticles($limit = 10, $offset = 0) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM articles WHERE status = 'published' ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fungsi untuk mendapatkan artikel berdasarkan ID
function getArticleById($id) {
    global $conn;
    $stmt = $conn->prepare("
        SELECT a.*, u.username as author_name 
        FROM articles a 
        LEFT JOIN users u ON a.author_id = u.id 
        WHERE a.id = :id
    ");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fungsi untuk mendapatkan semua kategori
function getCategories() {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM service_categories");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fungsi untuk mengecek apakah user sudah login
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Fungsi untuk mengecek apakah user adalah admin
function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

// Fungsi untuk mengecek apakah user adalah staff
function isStaff() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'staff';
}

// Fungsi untuk sanitasi input
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Fungsi untuk membuat slug dari string
function createSlug($string) {
    $string = strtolower($string);
    $string = preg_replace('/[^a-z0-9-]/', '-', $string);
    $string = preg_replace('/-+/', '-', $string);
    return trim($string, '-');
}

// Fungsi untuk mengecek apakah slug sudah ada
function isSlugExists($table, $slug, $id = null) {
    global $conn;
    try {
        $sql = "SELECT COUNT(*) as count FROM $table WHERE slug = :slug";
        if ($id !== null) {
            $sql .= " AND id != :id";
        }
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':slug', $slug);
        if ($id !== null) {
            $stmt->bindParam(':id', $id);
        }
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    } catch(PDOException $e) {
        error_log("Error checking slug: " . $e->getMessage());
        return false;
    }
}

// Fungsi untuk membuat slug unik
function createUniqueSlug($table, $string, $id = null) {
    $slug = createSlug($string);
    $originalSlug = $slug;
    $counter = 1;
    
    while (isSlugExists($table, $slug, $id)) {
        $slug = $originalSlug . '-' . $counter;
        $counter++;
    }
    
    return $slug;
}

// Fungsi untuk mendapatkan layanan unggulan
function getFeaturedServices($limit = 6) {
    global $conn;
    try {
        $stmt = $conn->prepare("
            SELECT s.*, c.name as category_name 
            FROM services s 
            LEFT JOIN service_categories c ON s.category_id = c.id 
            WHERE s.is_featured = 1 
            ORDER BY s.created_at DESC 
            LIMIT :limit
        ");
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Error getting featured services: " . $e->getMessage());
        return [];
    }
}

// Fungsi untuk mendapatkan artikel terbaru
function getLatestArticles($limit = 3) {
    global $conn;
    $stmt = $conn->prepare("
        SELECT a.*, u.username as author_name 
        FROM articles a 
        LEFT JOIN users u ON a.author_id = u.id 
        WHERE a.status = 'published' 
        ORDER BY a.created_at DESC 
        LIMIT :limit
    ");
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fungsi untuk mendapatkan kategori layanan
function getServiceCategories() {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM service_categories ORDER BY name");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fungsi untuk mendapatkan layanan berdasarkan kategori
function getServicesByCategory($category_id) {
    global $conn;
    $stmt = $conn->prepare("
        SELECT s.*, sc.name as category_name 
        FROM services s 
        LEFT JOIN service_categories sc ON s.category_id = sc.id 
        WHERE s.category_id = :category_id 
        ORDER BY s.name
    ");
    $stmt->bindParam(':category_id', $category_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fungsi untuk mendapatkan detail layanan
function getServiceById($id) {
    global $conn;
    $stmt = $conn->prepare("
        SELECT s.*, sc.name as category_name 
        FROM services s 
        LEFT JOIN service_categories sc ON s.category_id = sc.id 
        WHERE s.id = :id
    ");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fungsi untuk mendapatkan semua artikel
function getAllArticles($limit = 10, $offset = 0) {
    global $conn;
    $stmt = $conn->prepare("
        SELECT a.*, u.username as author_name 
        FROM articles a 
        LEFT JOIN users u ON a.author_id = u.id 
        WHERE a.status = 'published' 
        ORDER BY a.created_at DESC 
        LIMIT :limit OFFSET :offset
    ");
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fungsi untuk mendapatkan testimonial yang disetujui
function getApprovedTestimonials($limit = 5) {
    global $conn;
    $stmt = $conn->prepare("
        SELECT t.*, s.name as service_name 
        FROM testimonials t 
        LEFT JOIN service_requests sr ON t.service_request_id = sr.id 
        LEFT JOIN services s ON sr.service_id = s.id 
        WHERE t.status = 'approved' 
        ORDER BY t.created_at DESC 
        LIMIT :limit
    ");
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fungsi untuk mendapatkan permintaan layanan pengguna
function getUserServiceRequests($user_id) {
    global $conn;
    try {
        $stmt = $conn->prepare("
            SELECT sr.*, s.name as service_name, sc.name as category_name 
            FROM service_requests sr 
            LEFT JOIN services s ON sr.service_id = s.id 
            LEFT JOIN service_categories sc ON s.category_id = sc.id 
            WHERE sr.user_id = :user_id 
            ORDER BY sr.created_at DESC
        ");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Error getting user service requests: " . $e->getMessage());
        return [];
    }
}
?> 