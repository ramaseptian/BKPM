<?php
// Controller resource kedua, polanya sama dengan MahasiswaController.
class ProdiController {
    private Prodi $model;

    public function __construct() {
        $this->model = new Prodi();
    }

    // GET /prodi  atau  GET /prodi?id=1
    public function index(): void {
        $id = $_GET['id'] ?? null;

        if ($id !== null) {
            Response::json(200, true, 'Data berhasil diambil', $this->ambilData($id));
        }

        Response::json(200, true, 'Data berhasil diambil', $this->model->getAll());
    }

    // POST /prodi
    public function store(): void {
        $data = json_decode(file_get_contents('php://input'), true);
        $this->validasi($data);

        try {
            $id = $this->model->create($data);
        } catch (InvalidArgumentException $e) {
            Response::json($e->getCode() ?: 422, false, $e->getMessage());
        }

        Response::json(201, true, 'Data berhasil ditambahkan', ['id' => $id]);
    }

    // PUT /prodi?id=1
    public function update($id): void {
        $this->ambilData($id);

        $data = json_decode(file_get_contents('php://input'), true);
        $this->validasi($data);

        try {
            $this->model->update((int) $id, $data);
        } catch (InvalidArgumentException $e) {
            Response::json($e->getCode() ?: 422, false, $e->getMessage());
        }

        Response::json(200, true, 'Data berhasil diperbarui');
    }

    // DELETE /prodi?id=1
    public function destroy($id): void {
        $this->ambilData($id);

        try {
            $this->model->delete((int) $id);
        } catch (PDOException $e) {
            // Prodi yang masih dipakai mahasiswa/mata kuliah ditolak oleh database.
            if ((int) ($e->errorInfo[1] ?? 0) === 1451) {
                Response::json(409, false, 'Prodi tidak dapat dihapus karena masih dipakai');
            }
            throw $e;
        }

        Response::json(200, true, 'Data berhasil dihapus');
    }

    private function ambilData($id): array {
        if (!ctype_digit((string) $id)) {
            Response::json(400, false, 'ID harus berupa angka');
        }

        $data = $this->model->find((int) $id);
        if (!$data) {
            Response::json(404, false, 'Data tidak ditemukan');
        }

        return $data;
    }

    private function validasi($data): void {
        if (!is_array($data)) {
            Response::json(400, false, 'Body harus berupa JSON yang valid');
        }

        $errors = [];
        foreach (['kode', 'nama'] as $field) {
            if (!isset($data[$field]) || !is_scalar($data[$field]) || trim((string) $data[$field]) === '') {
                $errors[$field] = "Field $field wajib diisi";
            }
        }
        if ($errors) {
            Response::json(400, false, 'Data belum lengkap', null, $errors);
        }

        if (strlen((string) $data['kode']) > 10) {
            $errors['kode'] = 'Kode maksimal 10 karakter';
            Response::json(422, false, 'Validasi gagal', null, $errors);
        }
    }
}
