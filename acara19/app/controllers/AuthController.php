<?php
// AuthController: bukan CRUD standar, hanya aksi register() pada acara ini.
// Aksi login() akan ditambahkan pada acara 20.
class AuthController {
    private User $model;

    public function __construct() {
        $this->model = new User();
    }

    // POST /register
    public function register(): void {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!is_array($data)) {
            Response::json(400, false, 'Body harus berupa JSON yang valid');
        }

        $errors = [];
        foreach (['username', 'email', 'password'] as $field) {
            if (!isset($data[$field]) || !is_scalar($data[$field]) || trim((string) $data[$field]) === '') {
                $errors[$field] = "Field $field wajib diisi";
            }
        }
        if ($errors) {
            Response::json(400, false, 'Username, email, dan password wajib diisi', null, $errors);
        }

        // Kata sandi TIDAK disimpan sebagai teks biasa, melainkan hash.
        $user = [
            'username' => trim((string) $data['username']),
            'email'    => trim((string) $data['email']),
            'password' => password_hash((string) $data['password'], PASSWORD_DEFAULT),
        ];

        try {
            $id = $this->model->create($user);
        } catch (InvalidArgumentException $e) {
            Response::json($e->getCode() ?: 422, false, $e->getMessage());
        }

        // Response hanya berisi id; password/hash tidak pernah dikirim balik.
        Response::json(201, true, 'Registrasi berhasil', ['id' => $id]);
    }
}