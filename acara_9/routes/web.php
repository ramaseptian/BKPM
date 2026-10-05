<?php
// routes/web.php
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

        // ---- Mahasiswa (CRUD) ----
        '/mahasiswa' => [
            'controller' => 'MahasiswaController',
            'action'     => 'index',
            'middleware' => ['AuthMiddleware'],
        ],
        '/mahasiswa/create' => [
            'controller' => 'MahasiswaController',
            'action'     => 'create',
            'middleware' => ['AuthMiddleware'],
        ],
        '/mahasiswa/edit' => [
            'controller' => 'MahasiswaController',
            'action'     => 'edit',
            'middleware' => ['AuthMiddleware'],
        ],
        '/mahasiswa/detail' => [
            'controller' => 'MahasiswaController',
            'action'     => 'detail',
            'middleware' => ['AuthMiddleware'],
        ],

        // ---- Prodi (CRUD) ----
        '/prodi' => [
            'controller' => 'ProdiController',
            'action'     => 'index',
            'middleware' => ['AuthMiddleware'],
        ],
        '/prodi/create' => [
            'controller' => 'ProdiController',
            'action'     => 'create',
            'middleware' => ['AuthMiddleware'],
        ],
        '/prodi/edit' => [
            'controller' => 'ProdiController',
            'action'     => 'edit',
            'middleware' => ['AuthMiddleware'],
        ],

        // ---- Mata Kuliah (CRUD) ----
        '/matakuliah' => [
            'controller' => 'MatakuliahController',
            'action'     => 'index',
            'middleware' => ['AuthMiddleware'],
        ],
        '/matakuliah/create' => [
            'controller' => 'MatakuliahController',
            'action'     => 'create',
            'middleware' => ['AuthMiddleware'],
        ],
        '/matakuliah/edit' => [
            'controller' => 'MatakuliahController',
            'action'     => 'edit',
            'middleware' => ['AuthMiddleware'],
        ],

        // ---- Dosen (masih data array sederhana, dari Acara 6) ----
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

        '/mahasiswa/store' => [
            'controller' => 'MahasiswaController',
            'action'     => 'store',
            'middleware' => ['AuthMiddleware'],
        ],
        '/mahasiswa/update' => [
            'controller' => 'MahasiswaController',
            'action'     => 'update',
            'middleware' => ['AuthMiddleware'],
        ],
        '/mahasiswa/destroy' => [
            'controller' => 'MahasiswaController',
            'action'     => 'destroy',
            'middleware' => ['AuthMiddleware'],
        ],

        '/prodi/store' => [
            'controller' => 'ProdiController',
            'action'     => 'store',
            'middleware' => ['AuthMiddleware'],
        ],
        '/prodi/update' => [
            'controller' => 'ProdiController',
            'action'     => 'update',
            'middleware' => ['AuthMiddleware'],
        ],
        '/prodi/destroy' => [
            'controller' => 'ProdiController',
            'action'     => 'destroy',
            'middleware' => ['AuthMiddleware'],
        ],

        '/matakuliah/store' => [
            'controller' => 'MatakuliahController',
            'action'     => 'store',
            'middleware' => ['AuthMiddleware'],
        ],
        '/matakuliah/update' => [
            'controller' => 'MatakuliahController',
            'action'     => 'update',
            'middleware' => ['AuthMiddleware'],
        ],
        '/matakuliah/destroy' => [
            'controller' => 'MatakuliahController',
            'action'     => 'destroy',
            'middleware' => ['AuthMiddleware'],
        ],
    ],
];
