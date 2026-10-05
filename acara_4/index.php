<?php
// index.php
require_once __DIR__ . '/app/Models/Mahasiswa.php';

use App\Models\Mahasiswa;

// Membuat beberapa object Mahasiswa
$daftarMahasiswa = [
    new Mahasiswa("2401001", "Budi Santoso", "D4 Teknik Informatika"),
    new Mahasiswa("2401015", "Siti Aminah", "D4 Teknik Informatika"),
    new Mahasiswa("2302032", "Andi Wijaya", "D4 Teknik Informatika"),
];

// Mengirim data ke View melalui variabel $content (path partial yang akan di load layout)
$content = __DIR__ . '/app/Views/mahasiswa/index.php';
require __DIR__ . '/app/Views/layouts/main.php';