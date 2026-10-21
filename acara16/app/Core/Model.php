<?php
// app/Core/Model.php
// BaseModel (BKPM Acara 10: Inheritance, Base Controller, Base Model,
// Repository Pattern). Diwariskan (extends) oleh Prodi dan Matakuliah,
// sehingga keduanya tidak perlu membuat koneksi PDO masing-masing --
// cukup memanggil db(), yang mengambil satu koneksi tunggal dari
// Database::getInstance() (singleton, app/Core/Database.php).
//
// Catatan: Mahasiswa TIDAK lagi mewarisi class ini sejak Acara 9, karena
// Mahasiswa sudah berperan sebagai Entity murni (lihat app/Models/Mahasiswa.php)
// dan akses datanya dipindahkan sepenuhnya ke MahasiswaRepository
// (Repository Pattern, app/Repositories/MahasiswaRepository.php).

abstract class Model
{
    protected static function db(): PDO
    {
        return Database::getInstance();
    }
}
