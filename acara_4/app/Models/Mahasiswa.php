<?php
// app/Models/Mahasiswa.php
namespace App\Models;

class Mahasiswa
{
    private string $nim;
    private string $nama;
    private string $prodi;

    public function __construct(string $nim, string $nama, string $prodi)
    {
        $this->nim   = $nim;
        $this->nama  = $nama;
        $this->prodi = $prodi;
    }

    public function getNim(): string
    {
        return $this->nim;
    }

    public function getNama(): string
    {
        return $this->nama;
    }

    public function getProdi(): string
    {
        return $this->prodi;
    }

    public function setNama(string $nama): void
    {
        if (strlen($nama) < 3) {
            throw new \InvalidArgumentException("Nama terlalu pendek");
        }
        $this->nama = $nama;
    }

    // Tugas Mandiri: menentukan angkatan dari 2 digit awal NIM
    // Format NIM diasumsikan: TTNNNNN, TT = 2 digit tahun masuk (mis. 24 -> 2024)
    public function getAngkatan(): string
    {
        $dua_digit = substr($this->nim, 0, 2);
        if (!ctype_digit($dua_digit)) {
            return "Tidak diketahui";
        }
        return "20" . $dua_digit;
    }

    public function getLabel(): string
    {
        return "{$this->nim} - {$this->nama} ({$this->prodi})";
    }
}
