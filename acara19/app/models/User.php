<?php
// Model User: seluruh query tabel users. Kata sandi yang diterima Model sudah
// berupa hash (dibuat oleh Controller dengan password_hash()).
class User {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function create(array $data): string {
        $this->validate($data);

        $stmt = $this->db->prepare(
            'INSERT INTO users (username, email, password) VALUES (:u, :e, :p)'
        );
        $stmt->execute([
            ':u' => $data['username'],
            ':e' => $data['email'],
            ':p' => $data['password'],
        ]);
        return $this->db->lastInsertId();
    }

    public function findByUsername(string $username) {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE username = :u');
        $stmt->execute([':u' => $username]);
        return $stmt->fetch();
    }

    // Validasi di Model (Tugas Mandiri 1): format email harus valid
    // (filter_var + FILTER_VALIDATE_EMAIL), serta username dan email tidak
    // boleh sudah terdaftar.
    private function validate(array $data): void {
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Format email tidak valid', 422);
        }
        if (strlen($data['username']) > 50) {
            throw new InvalidArgumentException('Username maksimal 50 karakter', 422);
        }
        if (strlen($data['email']) > 100) {
            throw new InvalidArgumentException('Email maksimal 100 karakter', 422);
        }

        if ($this->findByUsername($data['username'])) {
            throw new InvalidArgumentException('Username sudah terdaftar', 409);
        }

        $stmt = $this->db->prepare('SELECT COUNT(*) FROM users WHERE email = :e');
        $stmt->execute([':e' => $data['email']]);
        if ((int) $stmt->fetchColumn() > 0) {
            throw new InvalidArgumentException('Email sudah terdaftar', 409);
        }
    }
}