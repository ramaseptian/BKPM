<?php
// routes/web.php
// Setiap rute berisi Controller, method (action), dan middleware (opsional).
$routes = [
    'GET' => [
        '/' => [
            'controller' => 'HomeController',
            'action'     => 'index',
        ],
        '/login' => [
            'controller' => 'AuthController',
            'action'     => 'loginForm',
        ],
        '/logout' => [
            'controller' => 'AuthController',
            'action'     => 'logout',
        ],
        '/dashboard' => [
            'controller' => 'AuthController',
            'action'     => 'dashboard',
            'middleware' => ['AuthMiddleware'],
        ],
        '/mahasiswa' => [
            'controller' => 'MahasiswaController',
            'action'     => 'index',
            'middleware' => ['AuthMiddleware'],
        ],
        '/mahasiswa/detail' => [
            'controller' => 'MahasiswaController',
            'action'     => 'detail',
            'middleware' => ['AuthMiddleware'],
        ],
        '/dosen' => [
            'controller' => 'DosenController',
            'action'     => 'index',
            'middleware' => ['AuthMiddleware'],
        ],
    ],
    'POST' => [
        '/login' => [
            'controller' => 'AuthController',
            'action'     => 'login',
        ],
    ],
];
