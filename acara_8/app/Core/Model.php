<?php
// app/Core/Model.php
// Base Model — sejak Acara 8, koneksi PDO diambil dari Database::getInstance()
// (satu koneksi tunggal / singleton, lihat app/Core/Database.php), bukan lagi
// dikelola sendiri oleh Model.

abstract class Model
{
    protected static function db(): PDO
    {
        return Database::getInstance();
    }
}
