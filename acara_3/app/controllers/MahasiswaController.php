<?php
// app/controllers/MahasiswaController.php
// Controller: menerima request, mengambil data dari Model, lalu meneruskannya ke View.

require_once APP_PATH . '/models/Mahasiswa.php';

class MahasiswaController
{
    private Mahasiswa $model;

    public function __construct()
    {
        $this->model = new Mahasiswa();
    }

    // URL: ?url=mahasiswa/index
    public function index(): void
    {
        $mahasiswa = $this->model->getAll();
        require APP_PATH . '/views/mahasiswa/index.php';
    }

    // URL: ?url=mahasiswa/detail&nim=E25001
    public function detail(): void
    {
        // 1. Tangkap parameter nim dari $_GET
        $nim = $_GET['nim'] ?? '';

        // 2. Cari data mahasiswa berdasarkan NIM lewat Model
        $mhs = $this->model->getByNim($nim);

        if ($mhs === null) {
            http_response_code(404);
        }

        // 3. Teruskan ke View (bernilai null jika NIM tidak ditemukan)
        require APP_PATH . '/views/mahasiswa/detail.php';
    }
}
