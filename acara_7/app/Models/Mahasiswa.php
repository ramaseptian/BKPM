<?php

class Mahasiswa extends Model
{
    public static function all(): array
    {
        $stmt = self::db()->query("SELECT * FROM mahasiswa ORDER BY nim");
        return $stmt->fetchAll();
    }

    public static function findByNim(?string $nim): ?array
    {
        if ($nim === null || $nim === '') {
            return null;
        }

        $stmt = self::db()->prepare("SELECT * FROM mahasiswa WHERE nim = :nim");
        $stmt->execute(['nim' => $nim]);
        $row = $stmt->fetch();

        return $row ?: null;
    }
}
