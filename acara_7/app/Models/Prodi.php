<?php
// app/Models/Prodi.php
// Dipakai untuk memetakan prodi_id -> nama prodi pada tampilan Mahasiswa.
class Prodi extends Model
{
    public static function all(): array
    {
        $stmt = self::db()->query("SELECT * FROM prodi ORDER BY id");
        return $stmt->fetchAll();
    }
}
