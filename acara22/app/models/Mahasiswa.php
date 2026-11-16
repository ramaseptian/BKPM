<?php
// Model: seluruh query SQL untuk tabel mahasiswa ada di sini (bukan di Controller).
// Perintah yang mengubah data dibungkus try-catch (Acara 20): jika database
// bermasalah, detail error hanya dicatat dan client menerima pesan umum (500).
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
        try {
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
        } catch (PDOException $e) {
            $this->gagal($e);
        }
    }

    public function update(int $id, array $data): void {
        try {
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
        } catch (PDOException $e) {
            $this->gagal($e);
        }
    }

    public function delete(int $id): void {
        try {
            $stmt = $this->db->prepare('DELETE FROM mahasiswa WHERE id = :id');
            $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            $this->gagal($e);
        }
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

    // Mencatat detail error ke log server, lalu mengirim 500 tanpa detail.
    private function gagal(PDOException $e): void {
        error_log($e->getMessage());
        Response::json(500, false, 'Terjadi kesalahan pada server');
    }
}
