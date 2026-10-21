<?php
// app/Core/Router.php
// Router sederhana: mencocokkan URI + HTTP method ke Controller@method,
// dan menjalankan middleware (jika didaftarkan) sebelum Controller dipanggil.

class Router
{
    private array $routes;

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    public function dispatch(): void
    {
        $uri    = $this->resolveUri();
        $method = $_SERVER['REQUEST_METHOD'];

        $route = $this->routes[$method][$uri] ?? null;

        if ($route === null) {
            http_response_code(404);
            echo "404 - Halaman tidak ditemukan";
            return;
        }

        // Jalankan setiap middleware yang didaftarkan pada rute ini
        foreach ($route['middleware'] ?? [] as $middlewareClass) {
            (new $middlewareClass())->handle();
        }

        $controller = new $route['controller']();
        $action     = $route['action'];
        $controller->$action();
    }

    /**
     * Mengambil path URL, membuang base path folder project secara otomatis
     * (dihitung dari SCRIPT_NAME), supaya project tetap jalan baik diakses
     * langsung maupun dari dalam subfolder htdocs.
     */
    private function resolveUri(): string
    {
        $requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $scriptDir   = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));

        $uri = $requestPath;
        if ($scriptDir !== '' && $scriptDir !== '/' && str_starts_with($uri, $scriptDir)) {
            $uri = substr($uri, strlen($scriptDir));
        }

        $uri = '/' . trim($uri, '/');
        return strtolower($uri);
    }
}
