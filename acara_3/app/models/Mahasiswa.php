<?php
// app/models/Mahasiswa.php
// Model: bertanggung jawab atas data mahasiswa.

class Mahasiswa
{
    private array $data = [
        ['nim' => 'E25001', 'nama' => 'Stephen Curry',   'prodi' => 'Teknik Informatika'],
        ['nim' => 'E25002', 'nama' => 'Lebron James',    'prodi' => 'Teknik Informatika'],
        ['nim' => 'E25003', 'nama' => 'Kevin Durant',    'prodi' => 'Teknik Informatika'],
        ['nim' => 'E25004', 'nama' => 'Klay Thompson',   'prodi' => 'Teknik Informatika'],
        ['nim' => 'E25005', 'nama' => 'Anthony Edwards', 'prodi' => 'Teknik Informatika'],
        ['nim' => 'E25006', 'nama' => 'Jalen Brunson',   'prodi' => 'Teknik Informatika'],
    ];

    /**
     * Mengambil seluruh data mahasiswa.
     */
    public function getAll(): array
    {
        return $this->data;
    }

    /**
     * Mencari satu mahasiswa berdasarkan NIM.
     * Mengembalikan null jika tidak ditemukan.
     */
    public function getByNim(string $nim): ?array
    {
        foreach ($this->data as $mhs) {
            if (strcasecmp($mhs['nim'], $nim) === 0) {
                return $mhs;
            }
        }
        return null;
    }
}
