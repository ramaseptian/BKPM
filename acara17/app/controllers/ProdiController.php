<?php
// Controller resource kedua (Tugas Mandiri 1), polanya sama dengan MahasiswaController.
class ProdiController {
    private Prodi $model;

    public function __construct() {
        $this->model = new Prodi();
    }

    // GET /prodi  atau  GET /prodi?id=1
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

    // POST /prodi
    public function store(): void {
        header('Content-Type: application/json');

        $data  = json_decode(file_get_contents('php://input'), true);
        $error = $this->validasi($data);

        if ($error) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $error]);
            exit;
        }

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

    // PUT /prodi?id=1
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

        try {
            $this->model->update((int) $id, $data);
        } catch (InvalidArgumentException $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            exit;
        }

        echo json_encode(['success' => true, 'message' => 'Data berhasil diperbarui']);
    }

    // DELETE /prodi?id=1
    public function destroy($id): void {
        header('Content-Type: application/json');

        if (!ctype_digit((string) $id) || !$this->model->find((int) $id)) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan']);
            exit;
        }

        try {
            $this->model->delete((int) $id);
        } catch (PDOException $e) {
            // Prodi yang masih dipakai mahasiswa/mata kuliah ditolak oleh database.
            if ((int) ($e->errorInfo[1] ?? 0) === 1451) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Prodi tidak dapat dihapus karena masih dipakai']);
                exit;
            }
            throw $e;
        }

        echo json_encode(['success' => true, 'message' => 'Data berhasil dihapus']);
    }

    private function validasi($data): ?string {
        if (!is_array($data)) {
            return 'Body harus berupa JSON yang valid';
        }

        foreach (['kode', 'nama'] as $field) {
            if (!isset($data[$field]) || !is_scalar($data[$field]) || trim((string) $data[$field]) === '') {
                return "Field $field wajib diisi";
            }
        }

        if (strlen((string) $data['kode']) > 10) {
            return 'Kode maksimal 10 karakter';
        }

        return null;
    }
}
