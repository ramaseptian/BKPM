<?php
// app/Models/Mahasiswa.php
class Mahasiswa extends Model
{
    // Daftar mahasiswa + nama prodi, menggunakan JOIN (BKPM Acara 8).
    // Jika $keyword diisi, filter berdasarkan nama ATAU nim (Tugas Mandiri).
    public static function allWithProdi(?string $keyword = null): array
    {
        $sql = "SELECT m.*, p.nama AS prodi_nama
                FROM mahasiswa m
                JOIN prodi p ON m.prodi_id = p.id";

        $params = [];
        if ($keyword !== null && $keyword !== '') {
            // Dua placeholder berbeda: dengan emulated prepares dimatikan,
            // satu nama placeholder tidak boleh dipakai dua kali.
            $sql .= " WHERE m.nama LIKE :kw_nama OR m.nim LIKE :kw_nim";
            $params['kw_nama'] = '%' . $keyword . '%';
            $params['kw_nim']  = '%' . $keyword . '%';
        }

        $sql .= " ORDER BY m.nim";

        $stmt = self::db()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function count(): int
    {
        return (int) self::db()->query("SELECT COUNT(*) FROM mahasiswa")->fetchColumn();
    }

    public static function find(int $id): ?array
    {
        $stmt = self::db()->prepare("SELECT * FROM mahasiswa WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public static function findByNim(?string $nim): ?array
    {
        if ($nim === null || $nim === '') {
            return null;
        }
        $stmt = self::db()->prepare(
            "SELECT m.*, p.nama AS prodi_nama
             FROM mahasiswa m
             JOIN prodi p ON m.prodi_id = p.id
             WHERE m.nim = :nim"
        );
        $stmt->execute(['nim' => $nim]);
        return $stmt->fetch() ?: null;
    }

    public static function create(array $data): void
    {
        $stmt = self::db()->prepare(
            "INSERT INTO mahasiswa (nim, nama, prodi_id, angkatan, status)
             VALUES (:nim, :nama, :prodi_id, :angkatan, :status)"
        );
        $stmt->execute([
            'nim'      => $data['nim'],
            'nama'     => $data['nama'],
            'prodi_id' => $data['prodi_id'],
            'angkatan' => $data['angkatan'],
            'status'   => $data['status'],
        ]);
    }

    public static function update(int $id, array $data): void
    {
        $stmt = self::db()->prepare(
            "UPDATE mahasiswa
             SET nim = :nim, nama = :nama,
                 prodi_id = :prodi_id, angkatan = :angkatan, status = :status
             WHERE id = :id"
        );
        $stmt->execute([
            'id'       => $id,
            'nim'      => $data['nim'],
            'nama'     => $data['nama'],
            'prodi_id' => $data['prodi_id'],
            'angkatan' => $data['angkatan'],
            'status'   => $data['status'],
        ]);
    }

    public static function delete(int $id): void
    {
        $stmt = self::db()->prepare("DELETE FROM mahasiswa WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}