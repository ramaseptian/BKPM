<?php
// app/Services/MahasiswaService.php
// Service Layer (BKPM Acara 13) + Exception Handling & Logging (Acara 14).
// Seluruh LOGIKA BISNIS mahasiswa ada di sini:
//   Controller  -> mengatur alur request/response
//   Service     -> validasi dan aturan bisnis (class ini)
//   Repository  -> menjalankan query ke database
//
// MahasiswaRepository dan ProdiRepository diterima lewat constructor
// (Constructor Injection, langkah 2). Setiap method create/update/delete
// mengembalikan array hasil, bukan melempar exception ke Controller:
//   ['success' => bool, 'errors' => ['field' => 'pesan', ...], ...]

class MahasiswaService
{
    public const STATUS_VALID = ['aktif', 'cuti', 'lulus'];

    private MahasiswaRepository $repo;
    private ProdiRepository $prodiRepo;

    public function __construct(MahasiswaRepository $repo, ProdiRepository $prodiRepo)
    {
        $this->repo      = $repo;
        $this->prodiRepo = $prodiRepo;
    }

    // ============== QUERY (diteruskan ke Repository) ==============
    public function list(?string $keyword = null): array
    {
        return $this->repo->allWithProdi($keyword);
    }

    public function findById(int $id): ?Mahasiswa
    {
        return $this->repo->find($id);
    }

    // Satu mahasiswa lengkap dengan nama prodi (dipakai oleh API).
    public function detail(int $id): ?Mahasiswa
    {
        return $this->repo->findWithProdi($id);
    }

    public function findByNim(?string $nim): ?Mahasiswa
    {
        return $this->repo->findByNim($nim);
    }

    public function prodiOptions(): array
    {
        return $this->prodiRepo->all();
    }

    // ============== CREATE ==============
    // Seluruh proses dibungkus try-catch (BKPM Acara 14, langkah 5): jika
    // database bermasalah, detail error DICATAT ke app.log dan pengguna hanya
    // menerima pesan umum "Data gagal disimpan." (tanpa informasi teknis).
    public function create(array $input): array
    {
        $data = $this->normalize($input);

        try {
            $errors = $this->validate($data);
            if (!empty($errors)) {
                return ['success' => false, 'errors' => $errors];
            }

            $id = $this->repo->create($this->buildEntity($data));
            return ['success' => true, 'errors' => [], 'id' => $id];
        } catch (InvalidArgumentException | PDOException $e) {
            return [
                'success' => false,
                'errors'  => $this->handleException($e, 'Gagal menambah data mahasiswa', 'Data gagal disimpan.'),
            ];
        }
    }

    // ============== UPDATE ==============
    public function update(int $id, array $input): array
    {
        $data = $this->normalize($input);

        try {
            if ($id <= 0 || $this->repo->find($id) === null) {
                return [
                    'success'   => false,
                    'not_found' => true,
                    'errors'    => ['general' => 'Data mahasiswa tidak ditemukan.'],
                ];
            }

            $errors = $this->validate($data, $id);
            if (!empty($errors)) {
                return ['success' => false, 'errors' => $errors];
            }

            $this->repo->update($id, $this->buildEntity($data));
            return ['success' => true, 'errors' => []];
        } catch (InvalidArgumentException | PDOException $e) {
            return [
                'success' => false,
                'errors'  => $this->handleException($e, 'Gagal mengubah data mahasiswa (id=' . $id . ')', 'Data gagal disimpan.'),
            ];
        }
    }

    // ============== DELETE ==============
    public function delete(int $id): array
    {
        try {
            if ($id <= 0 || $this->repo->find($id) === null) {
                return [
                    'success'   => false,
                    'not_found' => true,
                    'errors'    => ['general' => 'Data mahasiswa tidak ditemukan.'],
                ];
            }

            $this->repo->delete($id);
            return ['success' => true, 'errors' => []];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'errors'  => $this->handleException($e, 'Gagal menghapus data mahasiswa (id=' . $id . ')', 'Data gagal dihapus.'),
            ];
        }
    }

    // ============== VALIDASI (langkah 3: dipindah dari Controller) ==============
    // $exceptId diisi saat update supaya NIM milik data itu sendiri tidak
    // dianggap duplikat.
    private function validate(array $data, ?int $exceptId = null): array
    {
        $errors = [];

        // NIM: wajib, angka, maksimal 20 karakter, dan tidak boleh sama.
        if ($data['nim'] === '') {
            $errors['nim'] = 'NIM wajib diisi.';
        } elseif (!ctype_digit($data['nim'])) {
            $errors['nim'] = 'NIM harus berupa angka.';
        } elseif (strlen($data['nim']) > 20) {
            $errors['nim'] = 'NIM maksimal 20 karakter.';
        } elseif ($this->repo->existsByNim($data['nim'], $exceptId)) {
            $errors['nim'] = 'NIM sudah terdaftar.';
        }

        // Nama: wajib, maksimal 100 karakter.
        if ($data['nama'] === '') {
            $errors['nama'] = 'Nama wajib diisi.';
        } elseif (mb_strlen($data['nama']) > 100) {
            $errors['nama'] = 'Nama maksimal 100 karakter.';
        }

        // Program studi: wajib dipilih dan harus ada di database.
        if ($data['prodi_id'] <= 0) {
            $errors['prodi_id'] = 'Program studi wajib dipilih.';
        } elseif (!$this->prodiRepo->exists($data['prodi_id'])) {
            $errors['prodi_id'] = 'Program studi tidak ditemukan.';
        }

        // Angkatan: tahun 2000 sampai 2100.
        if ($data['angkatan'] < 2000 || $data['angkatan'] > 2100) {
            $errors['angkatan'] = 'Angkatan harus berupa tahun antara 2000 dan 2100.';
        }

        // Status: hanya aktif, cuti, atau lulus.
        if (!in_array($data['status'], self::STATUS_VALID, true)) {
            $errors['status'] = 'Status tidak valid.';
        }

        return $errors;
    }

    // Merapikan input mentah ($_POST) menjadi tipe data yang pasti.
    private function normalize(array $input): array
    {
        return [
            'nim'      => trim((string) ($input['nim'] ?? '')),
            'nama'     => trim((string) ($input['nama'] ?? '')),
            'prodi_id' => (int) ($input['prodi_id'] ?? 0),
            'angkatan' => (int) ($input['angkatan'] ?? 0),
            'status'   => (string) ($input['status'] ?? 'aktif'),
        ];
    }

    private function buildEntity(array $data): Mahasiswa
    {
        $mhs = new Mahasiswa(
            $data['nim'],
            $data['nama'],
            $data['prodi_id'],
            $data['angkatan'],
            $data['status']
        );
        $mhs->setProdiId($data['prodi_id']);

        return $mhs;
    }

    // Mencatat exception ke app.log, lalu mengembalikan pesan yang AMAN
    // ditampilkan kepada pengguna.
    //  - InvalidArgumentException : pesan dari Entity (aman, ditulis sendiri)
    //  - PDOException kode 1062   : duplicate entry -> "NIM sudah terdaftar."
    //  - PDOException lainnya     : pesan umum, detail hanya ada di log
    private function handleException(Throwable $e, string $context, string $userMessage): array
    {
        if ($e instanceof InvalidArgumentException) {
            Logger::warning($context, $e);
            return ['general' => $e->getMessage()];
        }

        if ($e instanceof PDOException && ($e->errorInfo[1] ?? null) === 1062) {
            Logger::warning($context . ' (NIM duplikat)', $e);
            return ['nim' => 'NIM sudah terdaftar.'];
        }

        Logger::error($context, $e);
        return ['general' => $userMessage];
    }
}
