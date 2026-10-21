<?php
// app/Models/Dosen.php
// Catatan: Acara 7 hanya membuat tabel prodi, mahasiswa, dan matakuliah
// (tidak ada tabel dosen di BKPM), sehingga data Dosen tetap berupa array
// sederhana seperti pada Acara 6. Karena Base Model (app/Core/Model.php)
// sekarang berisi koneksi PDO, class ini TIDAK lagi "extends Model" -
// cukup berdiri sendiri dengan data statisnya sendiri.
class Dosen
{
    private static array $data = [
        ['nidn' => '001', 'nama' => 'Ahmad', 'prodi' => 'Teknik Informatika'],
        ['nidn' => '002', 'nama' => 'Siti',  'prodi' => 'Sistem Informasi'],
        ['nidn' => '003', 'nama' => 'Budi',  'prodi' => 'Teknik Informatika'],
    ];

    public static function all(): array
    {
        return self::$data;
    }
}
