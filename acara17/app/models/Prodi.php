<?php
// Model resource kedua (Tugas Mandiri 1), memakai pola yang sama dengan Mahasiswa.
// Memakai tabel prodi yang sudah ada pada database acara_7.
class Prodi {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getAll(): array {
        return $this->db->query('SELECT * FROM prodi ORDER BY id DESC')->fetchAll();
    }

    public function find(int $id) {
        $stmt = $this->db->prepare('SELECT * FROM prodi WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function create(array $data): string {
        $this->validate($data);

        $stmt = $this->db->prepare('INSERT INTO prodi (kode, nama) VALUES (:kode, :nama)');
        $stmt->execute([':kode' => $data['kode'], ':nama' => $data['nama']]);
        return $this->db->lastInsertId();
    }

    public function update(int $id, array $data): void {
        $this->validate($data, $id);

        $stmt = $this->db->prepare('UPDATE prodi SET kode = :kode, nama = :nama WHERE id = :id');
        $stmt->execute([':kode' => $data['kode'], ':nama' => $data['nama'], ':id' => $id]);
    }

    public function delete(int $id): void {
        $stmt = $this->db->prepare('DELETE FROM prodi WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }

    // Kode prodi tidak boleh duplikat.
    private function validate(array $data, ?int $exceptId = null): void {
        $sql    = 'SELECT COUNT(*) FROM prodi WHERE kode = :kode';
        $params = [':kode' => $data['kode']];
        if ($exceptId !== null) {
            $sql .= ' AND id <> :id';
            $params[':id'] = $exceptId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        if ((int) $stmt->fetchColumn() > 0) {
            throw new InvalidArgumentException('Kode prodi sudah terdaftar');
        }
    }
}
