<?php
namespace App\Controllers;

class MahasiswaController
{
    private array $mahasiswa = [
        1 => ['nim' => '2401001', 'nama' => 'Budi Santoso'],
        2 => ['nim' => '2401015', 'nama' => 'Siti Aminah'],
        3 => ['nim' => '2302032', 'nama' => 'Andi Wijaya'],
    ];

    public function index(): void
    {
        echo "<h1>Daftar Mahasiswa</h1>";
        echo "<p>Method: MahasiswaController::index() dipanggil melalui GET /mahasiswa</p>";
        echo "<ul>";
        foreach ($this->mahasiswa as $id => $mhs) {
            echo "<li>#{$id} - {$mhs['nim']} - {$mhs['nama']} " .
                 "(<a href=\"/mahasiswa/{$id}\">detail</a>)</li>";
        }
        echo "</ul>";
    }

    public function create(): void
    {
        echo "<h1>Form Tambah Mahasiswa</h1>";
        echo "<p>Method: MahasiswaController::create() dipanggil melalui GET /mahasiswa/create</p>";
    }

    public function store(): void
    {
        echo "<h1>Simpan Mahasiswa</h1>";
        echo "<p>Method: MahasiswaController::store() dipanggil melalui POST /mahasiswa</p>";
    }

    public function show(string $id): void
    {
        echo "<h1>Detail Mahasiswa</h1>";
        echo "<p>Method: MahasiswaController::show(\$id) dipanggil melalui GET /mahasiswa/{$id}</p>";

        if (isset($this->mahasiswa[$id])) {
            $mhs = $this->mahasiswa[$id];
            echo "<p>NIM: {$mhs['nim']}<br>Nama: {$mhs['nama']}</p>";
        } else {
            echo "<p>Data dengan id {$id} tidak ditemukan.</p>";
        }
        echo "<p><a href=\"/mahasiswa\">Kembali ke daftar</a></p>";
    }
}
