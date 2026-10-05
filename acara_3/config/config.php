<?php
// config/config.php
// Konfigurasi dasar aplikasi SI Akademik

define('APP_NAME', 'SI Akademik');
define('INSTITUSI', 'Politeknik Negeri Jember');

// Lokasi folder-folder penting
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('ROUTES_PATH', ROOT_PATH . '/routes');

// Halaman default jika parameter ?url= tidak diisi
define('DEFAULT_ROUTE', 'mahasiswa/index');
