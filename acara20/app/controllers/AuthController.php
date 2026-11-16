<?php
// AuthController: bukan CRUD standar. Aksi: register() (Acara 19) dan login() (Acara 20).
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

    // POST /login  (Acara 20)
    public function login(): void {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!is_array($data)) {
            Response::json(400, false, 'Body harus berupa JSON yang valid');
        }

        $errors = [];
        foreach (['username', 'password'] as $field) {
            if (!isset($data[$field]) || !is_scalar($data[$field]) || trim((string) $data[$field]) === '') {
                $errors[$field] = "Field $field wajib diisi";
            }
        }
        if ($errors) {
            Response::json(400, false, 'Username dan password wajib diisi', null, $errors);
        }

        $user = $this->model->findByUsername(trim((string) $data['username']));

        // Pesan sama untuk username salah maupun password salah, agar
        // pengunjung tidak dapat mengetahui username mana yang terdaftar.
        if (!$user || !password_verify((string) $data['password'], $user['password'])) {
            Response::json(401, false, 'Username atau password salah');
        }

        $token = Auth::generateToken(['id' => $user['id'], 'role' => $user['role']]);

        Response::json(200, true, 'Login berhasil', ['token' => $token, 'role' => $user['role']]);
    }
}
