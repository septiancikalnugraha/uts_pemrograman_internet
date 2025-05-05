-- Create database
CREATE DATABASE IF NOT EXISTS smartcare_db;
USE smartcare_db;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    role ENUM('admin', 'staff', 'customer') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_email (email)
);

-- Create service categories table
CREATE TABLE IF NOT EXISTS service_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    slug VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,
    icon VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_slug (slug)
);

-- Create services table
CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    category_id INT,
    image VARCHAR(255),
    is_featured TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES service_categories(id) ON DELETE SET NULL,
    INDEX idx_slug (slug),
    INDEX idx_category (category_id),
    INDEX idx_featured (is_featured)
);

-- Create articles table
CREATE TABLE IF NOT EXISTS articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    content TEXT NOT NULL,
    excerpt TEXT,
    image VARCHAR(255),
    author_id INT NOT NULL,
    status ENUM('published', 'draft') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_slug (slug),
    INDEX idx_status (status),
    INDEX idx_author (author_id)
);

-- Create service requests table
CREATE TABLE IF NOT EXISTS service_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    service_id INT NOT NULL,
    device_type VARCHAR(50) NOT NULL,
    device_model VARCHAR(100) NOT NULL,
    problem_description TEXT NOT NULL,
    status ENUM('pending', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending',
    estimated_price DECIMAL(10,2),
    final_price DECIMAL(10,2),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_service (service_id),
    INDEX idx_status (status)
);

-- Create testimonials table
CREATE TABLE IF NOT EXISTS testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100) NOT NULL,
    content TEXT NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    service_request_id INT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (service_request_id) REFERENCES service_requests(id) ON DELETE SET NULL,
    INDEX idx_status (status),
    INDEX idx_request (service_request_id)
);

-- Insert default admin user
INSERT INTO users (username, password, email, full_name, role) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@smartcare.com', 'Admin User', 'admin');
-- Default password: password

-- Insert sample service categories
INSERT INTO service_categories (name, slug, description, icon) VALUES
('Screen Repair', 'screen-repair', 'Professional screen replacement and repair services', 'bi-display'),
('Battery Replacement', 'battery-replacement', 'Battery replacement and power issues solutions', 'bi-battery-charging'),
('Water Damage', 'water-damage', 'Water damage repair and prevention services', 'bi-droplet'),
('Software Issues', 'software-issues', 'Operating system and software troubleshooting', 'bi-code-slash'),
('Hardware Repair', 'hardware-repair', 'Internal component repair and replacement', 'bi-tools');

-- Insert sample services
INSERT INTO services (name, slug, description, price, category_id, is_featured) VALUES
('iPhone Screen Replacement', 'iphone-screen-replacement', 'Professional iPhone screen replacement service with original parts', 1500000.00, 1, 1),
('Android Screen Repair', 'android-screen-repair', 'High-quality screen repair for all Android devices', 1000000.00, 1, 1),
('Battery Replacement', 'battery-replacement', 'Replace your old battery with a new one', 500000.00, 2, 1),
('Water Damage Repair', 'water-damage-repair', 'Professional water damage assessment and repair', 800000.00, 3, 1),
('Software Update', 'software-update', 'Operating system update and optimization', 300000.00, 4, 0),
('Motherboard Repair', 'motherboard-repair', 'Complex motherboard repair and component replacement', 2000000.00, 5, 0); 