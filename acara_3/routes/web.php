<?php
// routes/web.php
// Daftar route yang diizinkan.
// Format: 'url' => ['NamaController', 'method']
// Diakses lewat: public/index.php?url=namacontroller/method

return [
    'mahasiswa/index'  => ['MahasiswaController', 'index'],
    'mahasiswa/detail' => ['MahasiswaController', 'detail'],   // ?url=mahasiswa/detail&nim=E25001
    'dosen/index'      => ['DosenController', 'index'],
];
