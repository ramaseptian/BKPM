<?php
// app/Controllers/MahasiswaController.php
class MahasiswaController extends Controller
{
    public function index(): void
    {
        $mahasiswaList = Mahasiswa::all();
        $this->view('mahasiswa/index', compact('mahasiswaList'));
    }

    public function detail(): void
    {
        $nim = $_GET['nim'] ?? null;
        $mhs = Mahasiswa::findByNim($nim);

        if (!$mhs) {
            http_response_code(404);
            echo "Data mahasiswa tidak ditemukan.";
            return;
        }

        $this->view('mahasiswa/detail', compact('mhs'));
    }
}
