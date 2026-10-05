<?php

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../routes/web.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
if ($base !== '' && str_starts_with($uri, $base)) {
    $uri = substr($uri, strlen($base));
}
$uri = $uri === '' ? '/' : $uri;

$method = $_SERVER['REQUEST_METHOD'];

if (isset($routes[$method][$uri])) {
    [$controllerName, $action] = $routes[$method][$uri];
    dispatch($controllerName, $action);
    exit;
}

foreach ($routes[$method] ?? [] as $pattern => $target) {
    if (!str_contains($pattern, '{')) {
        continue;
    }
    // Ubah "/mahasiswa/{id}" menjadi regex "#^/mahasiswa/([^/]+)$#"
    $regex = '#^' . preg_replace('/\{[a-zA-Z_]+\}/', '([^/]+)', $pattern) . '$#';
    if (preg_match($regex, $uri, $matches)) {
        array_shift($matches); // buang full match, sisakan parameter
        [$controllerName, $action] = $target;
        dispatch($controllerName, $action, $matches);
        exit;
    }
}

http_response_code(404);
echo "404 - Halaman tidak ditemukan";
exit;

function dispatch(string $controllerName, string $action, array $params = []): void
{
    $controllerClass = "App\\Controllers\\{$controllerName}";
    require_once __DIR__ . "/../app/Controllers/{$controllerName}.php";
    $controller = new $controllerClass();
    call_user_func_array([$controller, $action], $params);
}