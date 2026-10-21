<?php
// api/mahasiswa.php
// RESTful API Data Mahasiswa (BKPM Acara 16). Resource: mahasiswa.
//
//   GET    /api/mahasiswa        -> mengambil seluruh mahasiswa
//   GET    /api/mahasiswa/{id}   -> mengambil mahasiswa berdasarkan ID
//   POST   /api/mahasiswa        -> menambah mahasiswa     (body JSON)
//   PUT    /api/mahasiswa/{id}   -> mengubah mahasiswa     (body JSON)
//   DELETE /api/mahasiswa/{id}   -> menghapus mahasiswa
//
// Resource + HTTP method menentukan tindakan. Alamat /api/mahasiswa/{id}
// dipetakan ke file ini oleh api/.htaccess.
//
// Alur: endpoint + HTTP method + request -> RESTful API -> Service ->
//       Repository -> database -> response JSON.

require_once __DIR__ . '/../app/Core/Logger.php';
require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Models/Mahasiswa.php';
require_once __DIR__ . '/../app/Repositories/MahasiswaRepository.php';
require_once __DIR__ . '/../app/Repositories/ProdiRepository.php';
require_once __DIR__ . '/../app/Services/MahasiswaService.php';

// Error teknis tidak boleh tampil di respons; semuanya dicatat ke app.log.
ini_set('display_errors', '0');
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

// Field yang wajib dikirim pada POST dan PUT.
const FIELD_WAJIB = ['nim', 'nama', 'prodi_id', 'angkatan'];

// ---------------------------------------------------------------
// Response JSON: { success, message, data, errors? }
// ---------------------------------------------------------------
function respond(int $status, bool $success, string $message, $data = null, ?array $errors = null): void
{
    http_response_code($status);

    $body = [
        'success' => $success,
        'message' => $message,
        'data'    => $data,
    ];
    if ($errors !== null) {
        $body['errors'] = $errors;
    }

    echo json_encode(
        $body,
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_INVALID_UTF8_SUBSTITUTE
    );
    exit;
}

function buildService(): MahasiswaService
{
    $db = Database::getInstance();

    return new MahasiswaService(new MahasiswaRepository($db), new ProdiRepository($db));
}

// Exception yang lolos menghasilkan 500 dengan pesan umum; detail teknis
// hanya dicatat ke app.log.
set_exception_handler(function (Throwable $e): void {
    Logger::error('API: exception tidak tertangani', $e);
    respond(500, false, 'Terjadi kesalahan pada server.');
});

// ---------------------------------------------------------------
// Utilitas request
// ---------------------------------------------------------------

// Membaca body JSON. Body harus berupa JSON object yang valid.
function readJsonBody(): array
{
    $raw   = file_get_contents('php://input');
    $input = json_decode($raw === false ? '' : $raw, true);

    if (json_last_error() !== JSON_ERROR_NONE || !is_array($input)
        || ($input !== [] && array_keys($input) === range(0, count($input) - 1))) {
        respond(400, false, 'Body harus berupa JSON object yang valid.');
    }

    // Nilai field harus berupa teks atau angka, bukan array/object bersarang.
    $errors = [];
    foreach (array_merge(FIELD_WAJIB, ['status']) as $key) {
        if (isset($input[$key]) && !is_scalar($input[$key])) {
            $errors[$key] = 'Nilai harus berupa teks atau angka.';
        }
    }
    if (!empty($errors)) {
        respond(400, false, 'Request tidak valid.', null, $errors);
    }

    return $input;
}

// "Data JSON tidak lengkap": field wajib tidak dikirim sama sekali.
function requireFields(array $input): void
{
    $errors = [];
    foreach (FIELD_WAJIB as $key) {
        if (!array_key_exists($key, $input) || $input[$key] === null) {
            $errors[$key] = 'Field ' . $key . ' wajib dikirim.';
        }
    }

    if (!empty($errors)) {
        respond(400, false, 'Data JSON tidak lengkap.', null, $errors);
    }
}

// Menerjemahkan hasil gagal dari Service menjadi status HTTP.
function respondFailure(array $result): void
{
    if (!empty($result['not_found'])) {
        respond(404, false, 'Data mahasiswa tidak ditemukan.');
    }

    // Kesalahan server (database): Service sudah mencatatnya ke app.log.
    if (isset($result['errors']['general'])) {
        respond(500, false, $result['errors']['general']);
    }

    respond(400, false, 'Data tidak valid.', null, $result['errors']);
}

// ---------------------------------------------------------------
// GET /api/mahasiswa   dan   GET /api/mahasiswa/{id}
// ---------------------------------------------------------------
function listAll(): void
{
    $list = buildService()->list();

    respond(
        200,
        true,
        'Data berhasil diambil',
        array_map(fn (Mahasiswa $m) => $m->toArray(), $list)
    );
}

function showOne(int $id): void
{
    $mhs = buildService()->detail($id);
    if ($mhs === null) {
        respond(404, false, 'Data mahasiswa tidak ditemukan.');
    }

    respond(200, true, 'Data berhasil diambil', $mhs->toArray());
}

// ---------------------------------------------------------------
// POST /api/mahasiswa
// ---------------------------------------------------------------
function createOne(): void
{
    $input = readJsonBody();
    requireFields($input);

    $service = buildService();
    $result  = $service->create($input);
    if (!$result['success']) {
        respondFailure($result);
    }

    $mhs = $service->detail((int) $result['id']);
    respond(201, true, 'Data mahasiswa berhasil ditambahkan', $mhs ? $mhs->toArray() : null);
}

// ---------------------------------------------------------------
// PUT /api/mahasiswa/{id}
// ---------------------------------------------------------------
function updateOne(int $id): void
{
    $input = readJsonBody();
    requireFields($input);

    $service = buildService();
    $result  = $service->update($id, $input);
    if (!$result['success']) {
        respondFailure($result);
    }

    $mhs = $service->detail($id);
    respond(200, true, 'Data mahasiswa berhasil diubah', $mhs ? $mhs->toArray() : null);
}

// ---------------------------------------------------------------
// DELETE /api/mahasiswa/{id}
// ---------------------------------------------------------------
function deleteOne(int $id): void
{
    $result = buildService()->delete($id);
    if (!$result['success']) {
        respondFailure($result);
    }

    respond(200, true, 'Data mahasiswa berhasil dihapus');
}

// ---------------------------------------------------------------
// Dispatch: resource + HTTP method menentukan tindakan
// ---------------------------------------------------------------
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$rawId  = isset($_GET['id']) ? (is_string($_GET['id']) ? trim($_GET['id']) : '') : null;

if ($rawId === null) {
    // Koleksi: /api/mahasiswa
    if ($method === 'GET') {
        listAll();
    } elseif ($method === 'POST') {
        createOne();
    }
    respond(400, false, 'Request tidak valid. Gunakan GET atau POST pada /api/mahasiswa.');
}

// Satu data: /api/mahasiswa/{id}
if (!ctype_digit($rawId) || (int) $rawId <= 0) {
    respond(400, false, 'ID harus berupa angka bulat positif.');
}
$id = (int) $rawId;

if ($method === 'GET') {
    showOne($id);
} elseif ($method === 'PUT') {
    updateOne($id);
} elseif ($method === 'DELETE') {
    deleteOne($id);
}
respond(400, false, 'Request tidak valid. Gunakan GET, PUT, atau DELETE pada /api/mahasiswa/{id}.');
