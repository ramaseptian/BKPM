<?php

class MahasiswaController extends Controller
{
    private function prodiMap(): array
    {
        $map = [];
        foreach (Prodi::all() as $prodi) {
            $map[$prodi['id']] = $prodi['nama'];
        }
        return $map;
    }

    public function index(): void
    {
        $mahasiswaList = Mahasiswa::all();
        $prodiMap      = $this->prodiMap();
        $this->view('mahasiswa/index', compact('mahasiswaList', 'prodiMap'));
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

        $prodiMap = $this->prodiMap();
        $this->view('mahasiswa/detail', compact('mhs', 'prodiMap'));
    }
}