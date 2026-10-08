CREATE DATABASE IF NOT EXISTS acara_10 CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE acara_10;

CREATE TABLE IF NOT EXISTS prodi (
  id INT AUTO_INCREMENT PRIMARY KEY,
  kode VARCHAR(10) NOT NULL UNIQUE,
  nama VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS mahasiswa (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nim VARCHAR(20) NOT NULL UNIQUE,
  nama VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  prodi_id INT NOT NULL,
  angkatan YEAR NOT NULL,
  status ENUM('aktif','cuti','lulus') NOT NULL DEFAULT 'aktif',
  CONSTRAINT fk_mahasiswa_prodi FOREIGN KEY (prodi_id) REFERENCES prodi(id)
    ON DELETE RESTRICT ON UPDATE CASCADE
);

INSERT IGNORE INTO prodi (kode,nama) VALUES
('TI','Teknik Informatika'),('SI','Sistem Informasi'),('TK','Teknik Komputer');

INSERT IGNORE INTO mahasiswa (nim,nama,email,prodi_id,angkatan,status) VALUES
('2401001','Budi Santoso','budi@example.com',1,2024,'aktif'),
('2401002','Ani Wijaya','ani@example.com',3,2025,'aktif'),
('2402001','Citra Lestari','citra@example.com',2,2026,'aktif');
