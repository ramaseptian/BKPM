-- =====================================================================
-- si_akademik.sql
-- Workshop Sistem Informasi Web Server (TIF330805)
-- Acara 7 - Database, CREATE TABLE, dan Model Dasar
--
-- Cara pakai:
--   1. Buka phpMyAdmin -> tab SQL (atau Import), lalu jalankan file ini.
--   2. File ini akan membuat database si_akademik beserta 3 tabel utama
--      (prodi, mahasiswa, matakuliah), mengisi data awal (seeding), dan
--      menambahkan kolom status pada tabel mahasiswa (Tugas Mandiri).
-- =====================================================================

CREATE DATABASE IF NOT EXISTS si_akademik
    CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

USE si_akademik;

-- ---------------------------------------------------------------------
-- Langkah 2: CREATE TABLE
-- ---------------------------------------------------------------------

-- a. Tabel prodi
CREATE TABLE IF NOT EXISTS prodi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode VARCHAR(10) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- b. Tabel mahasiswa
CREATE TABLE IF NOT EXISTS mahasiswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nim VARCHAR(20) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    prodi_id INT NOT NULL,
    angkatan YEAR NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_mahasiswa_prodi FOREIGN KEY (prodi_id) REFERENCES prodi(id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

-- c. Tabel matakuliah
CREATE TABLE IF NOT EXISTS matakuliah (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode VARCHAR(10) NOT NULL UNIQUE,
    nama VARCHAR(150) NOT NULL,
    sks TINYINT NOT NULL,
    prodi_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_matakuliah_prodi FOREIGN KEY (prodi_id) REFERENCES prodi(id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

-- ---------------------------------------------------------------------
-- Langkah 3: Data Awal (Seeding)
-- ---------------------------------------------------------------------

INSERT INTO prodi (kode, nama) VALUES
('TI', 'Teknik Informatika'),
('SI', 'Sistem Informasi'),
('TK', 'Teknik Komputer');

INSERT INTO mahasiswa (nim, nama, email, prodi_id, angkatan) VALUES
('2401001', 'Budi Santoso', 'budi@email.com', 1, 2024),
('2401002', 'Ani Wijaya', 'ani@email.com', 1, 2024),
('2402001', 'Citra Lestari', 'citra@email.com', 2, 2024);

INSERT INTO matakuliah (kode, nama, sks, prodi_id) VALUES
('TI101', 'Pemrograman Dasar', 3, 1),
('TI102', 'Basis Data', 3, 1),
('SI101', 'Pengantar SI', 2, 2);

-- ---------------------------------------------------------------------
-- Tugas Mandiri:
-- Tambahkan kolom 'status' (ENUM 'aktif','cuti','lulus') pada tabel
-- mahasiswa, isi default 'aktif', lalu update data yang sudah ada.
-- ---------------------------------------------------------------------

ALTER TABLE mahasiswa
    ADD COLUMN status ENUM('aktif', 'cuti', 'lulus') NOT NULL DEFAULT 'aktif'
    AFTER angkatan;

-- Data yang sudah ada dipastikan/di-update eksplisit menjadi 'aktif'
-- (bukan hanya mengandalkan DEFAULT saat ALTER TABLE dijalankan).
UPDATE mahasiswa SET status = 'aktif' WHERE status IS NULL OR status = '';
