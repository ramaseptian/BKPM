<?php
// Controller: hanya memanggil method Model lalu membentuk response JSON.
// Tidak ada perintah SQL di sini.
class MahasiswaController {
    private Mahasiswa $model;

    public function __construct() {
        $this->model = new Mahasiswa();
    }

    // GET /mahasiswa  atau  GET /mahasiswa?id=1
    public function index(): void {
        header('Content-Type: application/json');

        $id = $_GET['id'] ?? null;

        if ($id !== null) {
            $data = ctype_digit((string) $id) ? $this->model->find((int) $id) : false;

            if (!$data) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan']);
                exit;
            }

            echo json_encode(['success' => true, 'data' => $data]);
            exit;
        }

        echo json_encode(['success' => true, 'data' => $this->model->getAll()]);
    }

    // POST /mahasiswa
    public function store(): void {
        header('Content-Type: application/json');

        $data  = json_decode(file_get_contents('php://input'), true);
        $error = $this->validasi($data);

        if ($error) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $error]);
            exit;
        }

        $data['status'] = $data['status'] ?? 'aktif';

        try {
            $id = $this->model->create($data);
        } catch (InvalidArgumentException $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            exit;
        }

        http_response_code(201);
        echo json_encode([
            'success' => true,
            'message' => 'Data berhasil ditambahkan',
            'data'    => ['id' => $id],
        ]);
    }

    // PUT /mahasiswa?id=1
    public function update($id): void {
        header('Content-Type: application/json');

        if (!ctype_digit((string) $id) || !$this->model->find((int) $id)) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan']);
            exit;
        }

        $data  = json_decode(file_get_contents('php://input'), true);
        $error = $this->validasi($data);

        if ($error) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $error]);
            exit;
        }

        $data['status'] = $data['status'] ?? 'aktif';

        try {
            $this->model->update((int) $id, $data);
        } catch (InvalidArgumentException $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            exit;
        }

        echo json_encode(['success' => true, 'message' => 'Data berhasil diperbarui']);
    }

    // DELETE /mahasiswa?id=1
    public function destroy($id): void {
        header('Content-Type: application/json');

        if (!ctype_digit((string) $id) || !$this->model->find((int) $id)) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan']);
            exit;
        }

        $this->model->delete((int) $id);
        echo json_encode(['success' => true, 'message' => 'Data berhasil dihapus']);
    }

    // Pemeriksaan kelengkapan dan bentuk data. Mengembalikan pesan error,
    // atau null jika data sudah benar.
    private function validasi($data): ?string {
        if (!is_array($data)) {
            return 'Body harus berupa JSON yang valid';
        }

        foreach (['nim', 'nama', 'prodi_id', 'angkatan'] as $field) {
            if (!isset($data[$field]) || !is_scalar($data[$field]) || trim((string) $data[$field]) === '') {
                return "Field $field wajib diisi";
            }
        }

        if (!ctype_digit((string) $data['prodi_id']) || !ctype_digit((string) $data['angkatan'])) {
            return 'prodi_id dan angkatan harus berupa angka';
        }

        if (isset($data['status']) && !in_array($data['status'], ['aktif', 'cuti', 'lulus'], true)) {
            return 'Status harus aktif, cuti, atau lulus';
        }

        return null;
    }
}
