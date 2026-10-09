<?php
// app/Models/Prodi.php
class Prodi extends Model
{
    public static function all(): array
    {
        $stmt = self::db()->query("SELECT * FROM prodi ORDER BY id");
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $stmt = self::db()->prepare("SELECT * FROM prodi WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public static function create(array $data): void
    {
        $stmt = self::db()->prepare(
            "INSERT INTO prodi (kode, nama) VALUES (:kode, :nama)"
        );
        $stmt->execute($data);
    }

    public static function update(int $id, array $data): void
    {
        $data['id'] = $id;
        $stmt = self::db()->prepare(
            "UPDATE prodi SET kode = :kode, nama = :nama WHERE id = :id"
        );
        $stmt->execute($data);
    }

    // Prodi tidak boleh dihapus jika masih dipakai mahasiswa/matakuliah
    // (dijaga otomatis oleh FOREIGN KEY ON DELETE RESTRICT di database).
    public static function delete(int $id): void
    {
        $stmt = self::db()->prepare("DELETE FROM prodi WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}
