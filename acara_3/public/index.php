<?php
// public/index.php
// Entry point aplikasi (Front Controller).
// Semua request masuk lewat sini, lalu diarahkan ke Controller sesuai parameter ?url=

require_once __DIR__ . '/../config/config.php';

// 1. Muat daftar route
$routes = require ROUTES_PATH . '/web.php';

// 2. Ambil parameter url dari $_GET, contoh: "mahasiswa/detail"
$url = isset($_GET['url']) ? trim($_GET['url'], '/') : DEFAULT_ROUTE;
$url = strtolower($url);

// Jika hanya "mahasiswa" tanpa method, anggap method-nya "index"
if (strpos($url, '/') === false) {
    $url .= '/index';
}

// 3. Cek apakah route terdaftar
if (!isset($routes[$url])) {
    http_response_code(404);
    echo '<h1>404 - Halaman tidak ditemukan</h1>';
    echo '<p><a href="index.php">Kembali ke beranda</a></p>';
    exit;
}

[$controllerName, $method] = $routes[$url];

// 4. Muat file Controller lalu jalankan method-nya
require_once APP_PATH . '/controllers/' . $controllerName . '.php';

$controller = new $controllerName();
$controller->$method();
