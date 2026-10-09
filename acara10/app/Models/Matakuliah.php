<?php
// app/Models/Matakuliah.php
class Matakuliah extends Model
{
    public static function allWithProdi(?string $keyword = null): array
    {
        $sql = "SELECT mk.*, p.nama AS prodi_nama
                FROM matakuliah mk
                JOIN prodi p ON mk.prodi_id = p.id";

        $params = [];
        if ($keyword !== null && $keyword !== '') {
            $sql .= " WHERE mk.nama LIKE :kw_nama OR mk.kode LIKE :kw_kode";
            $params['kw_nama'] = '%' . $keyword . '%';
            $params['kw_kode'] = '%' . $keyword . '%';
        }

        $sql .= " ORDER BY mk.kode";

        $stmt = self::db()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $stmt = self::db()->prepare("SELECT * FROM matakuliah WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public static function create(array $data): void
    {
        $stmt = self::db()->prepare(
            "INSERT INTO matakuliah (kode, nama, sks, prodi_id)
             VALUES (:kode, :nama, :sks, :prodi_id)"
        );
        $stmt->execute($data);
    }

    public static function update(int $id, array $data): void
    {
        $data['id'] = $id;
        $stmt = self::db()->prepare(
            "UPDATE matakuliah
             SET kode = :kode, nama = :nama, sks = :sks, prodi_id = :prodi_id
             WHERE id = :id"
        );
        $stmt->execute($data);
    }

    public static function delete(int $id): void
    {
        $stmt = self::db()->prepare("DELETE FROM matakuliah WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}
