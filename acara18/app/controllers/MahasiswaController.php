<?php
// Controller: memanggil Model lalu mengirim response lewat Response::json().
// Tidak ada SQL dan tidak ada pembentukan JSON manual di sini.
class MahasiswaController {
    private Mahasiswa $model;

    public function __construct() {
        $this->model = new Mahasiswa();
    }

    // GET /mahasiswa  atau  GET /mahasiswa?id=1
    public function index(): void {
        $id = $_GET['id'] ?? null;

        if ($id !== null) {
            Response::json(200, true, 'Data berhasil diambil', $this->ambilData($id));
        }

        Response::json(200, true, 'Data berhasil diambil', $this->model->getAll());
    }

    // POST /mahasiswa
    public function store(): void {
        $data = json_decode(file_get_contents('php://input'), true);
        $this->validasi($data);

        $data['status'] = $data['status'] ?? 'aktif';

        try {
            $id = $this->model->create($data);
        } catch (InvalidArgumentException $e) {
            Response::json($e->getCode() ?: 422, false, $e->getMessage());
        }

        Response::json(201, true, 'Data berhasil ditambahkan', ['id' => $id]);
    }

    // PUT /mahasiswa?id=1
    public function update($id): void {
        $this->ambilData($id);

        $data = json_decode(file_get_contents('php://input'), true);
        $this->validasi($data);

        $data['status'] = $data['status'] ?? 'aktif';

        try {
            $this->model->update((int) $id, $data);
        } catch (InvalidArgumentException $e) {
            Response::json($e->getCode() ?: 422, false, $e->getMessage());
        }

        Response::json(200, true, 'Data berhasil diperbarui');
    }

    // DELETE /mahasiswa?id=1
    public function destroy($id): void {
        $this->ambilData($id);

        $this->model->delete((int) $id);
        Response::json(200, true, 'Data berhasil dihapus');
    }

    // Mengambil satu data. ID bukan angka -> 400, data tidak ada -> 404.
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

    // Validasi data request.
    //  400 = data tidak lengkap (field wajib kosong / body bukan JSON)
    //  422 = data lengkap tetapi formatnya tidak valid (mis. NIM bukan 7 digit)
    // Daftar kesalahan per field dikirim pada field "errors".
    private function validasi($data): void {
        if (!is_array($data)) {
            Response::json(400, false, 'Body harus berupa JSON yang valid');
        }

        $errors = [];
        foreach (['nim', 'nama', 'prodi_id', 'angkatan'] as $field) {
            if (!isset($data[$field]) || !is_scalar($data[$field]) || trim((string) $data[$field]) === '') {
                $errors[$field] = "Field $field wajib diisi";
            }
        }
        if ($errors) {
            Response::json(400, false, 'Data belum lengkap', null, $errors);
        }

        if (!ctype_digit((string) $data['nim']) || strlen((string) $data['nim']) !== NIM_LENGTH) {
            $errors['nim'] = 'Format NIM tidak valid, harus ' . NIM_LENGTH . ' digit angka';
        }
        if (!ctype_digit((string) $data['prodi_id'])) {
            $errors['prodi_id'] = 'prodi_id harus berupa angka';
        }
        if (!ctype_digit((string) $data['angkatan'])) {
            $errors['angkatan'] = 'angkatan harus berupa angka';
        }
        if (isset($data['status']) && !in_array($data['status'], ['aktif', 'cuti', 'lulus'], true)) {
            $errors['status'] = 'Status harus aktif, cuti, atau lulus';
        }
        if ($errors) {
            Response::json(422, false, 'Validasi gagal', null, $errors);
        }
    }
}
