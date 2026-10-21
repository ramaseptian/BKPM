<?php
// public/index.php  — Front Controller (satu-satunya pintu masuk aplikasi)

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';

require_once __DIR__ . '/../app/Core/Logger.php';
require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Core/Controller.php';
require_once __DIR__ . '/../app/Core/Model.php';
require_once __DIR__ . '/../app/Core/Router.php';
require_once __DIR__ . '/../app/Core/Middleware/AuthMiddleware.php';

require_once __DIR__ . '/../app/Models/Mahasiswa.php';
require_once __DIR__ . '/../app/Repositories/MahasiswaRepository.php';
require_once __DIR__ . '/../app/Repositories/ProdiRepository.php';
require_once __DIR__ . '/../app/Services/MahasiswaService.php';
require_once __DIR__ . '/../app/Models/Prodi.php';
require_once __DIR__ . '/../app/Models/Matakuliah.php';
require_once __DIR__ . '/../app/Models/Dosen.php';

require_once __DIR__ . '/../app/Controllers/HomeController.php';
require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/MahasiswaController.php';
require_once __DIR__ . '/../app/Controllers/ProdiController.php';
require_once __DIR__ . '/../app/Controllers/MatakuliahController.php';
require_once __DIR__ . '/../app/Controllers/DosenController.php';

require __DIR__ . '/../routes/web.php';

// ---- Keamanan & penanganan error (BKPM Acara 14) ----
// 1) Error teknis tidak boleh tampil di browser; semuanya dicatat ke app.log.
ini_set('display_errors', '0');
error_reporting(E_ALL);

// 2) Header keamanan dasar.
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('Referrer-Policy: same-origin');

// 3) Warning/notice PHP dicatat sebagai WARNING.
set_error_handler(function (int $severity, string $message, string $file, int $line): bool {
    if (!(error_reporting() & $severity)) {
        return false;
    }
    Logger::warning('PHP error: ' . $message . ' (' . basename($file) . ':' . $line . ')');
    return true;
});

// 4) Exception yang lolos dari try-catch (mis. database mati) dicatat, lalu
//    pengguna hanya melihat halaman 500 yang aman.
set_exception_handler(function (Throwable $e): void {
    Logger::error('Exception tidak tertangani', $e);
    if (!headers_sent()) {
        http_response_code(500);
    }
    require __DIR__ . '/../app/Views/errors/500.php';
});


(new Router($routes))->dispatch();
