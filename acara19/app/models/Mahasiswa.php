<?php
// Model: seluruh query SQL untuk tabel mahasiswa ada di sini (bukan di Controller).
class Mahasiswa {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    // Seluruh mahasiswa, lengkap dengan nama prodi (JOIN)
    public function getAll(): array {
        return $this->db->query(
            'SELECT m.*, p.nama AS prodi
             FROM mahasiswa m JOIN prodi p ON m.prodi_id = p.id
             ORDER BY m.id DESC'
        )->fetchAll();
    }

    public function find(int $id) {
        $stmt = $this->db->prepare(
            'SELECT m.*, p.nama AS prodi
             FROM mahasiswa m JOIN prodi p ON m.prodi_id = p.id
             WHERE m.id = :id'
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function create(array $data): string {
        $this->validate($data);

        $stmt = $this->db->prepare(
            'INSERT INTO mahasiswa (nim, nama, prodi_id, angkatan, status)
             VALUES (:nim, :nama, :prodi_id, :angkatan, :status)'
        );
        $stmt->execute([
            ':nim'      => $data['nim'],
            ':nama'     => $data['nama'],
            ':prodi_id' => $data['prodi_id'],
            ':angkatan' => $data['angkatan'],
            ':status'   => $data['status'],
        ]);
        return $this->db->lastInsertId();
    }

    public function update(int $id, array $data): void {
        $this->validate($data, $id);

        $stmt = $this->db->prepare(
            'UPDATE mahasiswa
             SET nim = :nim, nama = :nama, prodi_id = :prodi_id,
                 angkatan = :angkatan, status = :status
             WHERE id = :id'
        );
        $stmt->execute([
            ':nim'      => $data['nim'],
            ':nama'     => $data['nama'],
            ':prodi_id' => $data['prodi_id'],
            ':angkatan' => $data['angkatan'],
            ':status'   => $data['status'],
            ':id'       => $id,
        ]);
    }

    public function delete(int $id): void {
        $stmt = $this->db->prepare('DELETE FROM mahasiswa WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }

    // Validasi di Model (Tugas Mandiri 2): NIM tidak boleh duplikat dan
    // program studi harus ada. $exceptId dipakai saat update agar NIM milik
    // data itu sendiri tidak dianggap duplikat.
    private function validate(array $data, ?int $exceptId = null): void {
        $sql    = 'SELECT COUNT(*) FROM mahasiswa WHERE nim = :nim';
        $params = [':nim' => $data['nim']];
        if ($exceptId !== null) {
            $sql .= ' AND id <> :id';
            $params[':id'] = $exceptId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        if ((int) $stmt->fetchColumn() > 0) {
            throw new InvalidArgumentException('NIM sudah terdaftar', 409);
        }

        $stmt = $this->db->prepare('SELECT COUNT(*) FROM prodi WHERE id = :id');
        $stmt->execute([':id' => $data['prodi_id']]);
        if ((int) $stmt->fetchColumn() === 0) {
            throw new InvalidArgumentException('Program studi tidak ditemukan', 422);
        }
    }
}
