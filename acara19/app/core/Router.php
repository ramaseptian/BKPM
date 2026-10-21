<?php
// Router: membaca URL dan method HTTP, lalu meneruskannya ke Controller.
// Seluruh response (404, 405, 500) dibuat melalui Response::json().
class Router {
    // Peta sederhana: nama resource pada URL => nama class Controller
    private array $map = [
        'mahasiswa' => 'MahasiswaController',
        'prodi'     => 'ProdiController',
        'register'  => 'AuthController',
    ];

    public function dispatch(): void {
        $uri      = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        $segments = explode('/', $uri);
        $resource = end($segments) ?: 'mahasiswa';

        $controllerName = $this->map[$resource] ?? null;

        if (!$controllerName) {
            Response::json(404, false, 'Endpoint tidak ditemukan');
        }

        $controller = new $controllerName();

        try {
            // register bukan resource CRUD biasa: hanya menerima POST dan
            // langsung memanggil aksi register().
            if ($resource === 'register') {
                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                    Response::json(405, false, 'Method tidak diizinkan');
                }
                $controller->register();
                return;
            }

            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET':    $controller->index(); break;
                case 'POST':   $controller->store(); break;
                case 'PUT':    $controller->update($_GET['id'] ?? null); break;
                case 'DELETE': $controller->destroy($_GET['id'] ?? null); break;
                default:
                    Response::json(405, false, 'Method tidak diizinkan');
            }
        } catch (PDOException $e) {
            // Detail error database hanya dicatat, tidak dikirim ke client.
            error_log($e->getMessage());
            Response::json(500, false, 'Terjadi kesalahan pada server');
        }
    }
}
