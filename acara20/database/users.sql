-- Tabel users untuk autentikasi (BKPM Acara 19, langkah 2).
-- Jalankan pada database acara_7 (phpMyAdmin > pilih acara_7 > tab SQL).
-- File acara_7.sql sendiri tidak diubah.
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
