# Simple CMS

Sebuah Content Management System (CMS) sederhana yang mirip dengan WordPress, dibangun menggunakan PHP dan MySQL.

## Fitur

- Sistem autentikasi (login/register)
- Manajemen artikel (CRUD)
- Manajemen kategori
- Dashboard admin
- Tampilan frontend untuk pengunjung

## Persyaratan Sistem

- PHP 7.4 atau lebih tinggi
- MySQL 5.7 atau lebih tinggi
- Web server (Apache/Nginx)
- Composer (untuk mengelola dependensi)

## Instalasi

1. Clone repository ini ke direktori web server Anda:
```bash
git clone https://github.com/username/simple-cms.git
```

2. Buat database baru dan import file `database.sql`:
```bash
mysql -u username -p < database.sql
```

3. Konfigurasi koneksi database di `config/database.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'username');
define('DB_PASS', 'password');
define('DB_NAME', 'cms_db');
```

4. Akses aplikasi melalui web browser:
```
http://localhost/simple-cms
```

## Kredensial Default

- Username: admin
- Password: password

## Struktur Proyek

```
simple-cms/
├── admin/              # Halaman admin
├── assets/             # File statis (CSS, JS, gambar)
├── config/             # File konfigurasi
├── includes/           # File PHP yang sering digunakan
├── pages/              # Halaman frontend
├── database.sql        # Struktur database
├── index.php           # File utama
├── login.php           # Halaman login
├── register.php        # Halaman registrasi
└── README.md           # Dokumentasi
```

## Kontribusi

Silakan buat pull request untuk kontribusi. Untuk perubahan besar, harap buka issue terlebih dahulu untuk mendiskusikan perubahan yang diinginkan.

## Lisensi

Proyek ini dilisensikan di bawah MIT License - lihat file [LICENSE](LICENSE) untuk detail lebih lanjut. 