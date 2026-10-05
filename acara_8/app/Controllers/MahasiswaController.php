<?php
// app/Controllers/MahasiswaController.php
class MahasiswaController extends Controller
{
    // Tugas Mandiri: pencarian berdasarkan nama atau NIM (LIKE + prepared statement)
    public function index(): void
    {
        $keyword       = trim($_GET['q'] ?? '');
        $mahasiswaList = Mahasiswa::allWithProdi($keyword !== '' ? $keyword : null);
        $prodiList     = Prodi::all();
        $flash         = flash_get();

        $this->view('mahasiswa/index', compact('mahasiswaList', 'prodiList', 'keyword', 'flash'));
    }

    public function detail(): void
    {
        $nim = $_GET['nim'] ?? null;
        $mhs = Mahasiswa::findByNim($nim);

        if (!$mhs) {
            http_response_code(404);
            echo "Data mahasiswa tidak ditemukan.";
            return;
        }

        $this->view('mahasiswa/detail', compact('mhs'));
    }

    public function create(): void
    {
        $prodiList = Prodi::all();
        $old       = $_SESSION['old_mahasiswa'] ?? [];
        $error     = $_SESSION['form_error'] ?? null;
        unset($_SESSION['old_mahasiswa'], $_SESSION['form_error']);

        $this->view('mahasiswa/create', compact('prodiList', 'old', 'error'));
    }

    public function store(): void
    {
        $data = $this->validated();

        if ($data === null) {
            $this->redirect('mahasiswa/create');
        }

        Mahasiswa::create($data);
        flash_set('success', 'Data mahasiswa berhasil ditambahkan.');
        $this->redirect('mahasiswa');
    }

    public function edit(): void
    {
        $id  = (int) ($_GET['id'] ?? 0);
        $mhs = Mahasiswa::find($id);

        if (!$mhs) {
            http_response_code(404);
            echo "Data mahasiswa tidak ditemukan.";
            return;
        }

        $prodiList = Prodi::all();
        $error     = $_SESSION['form_error'] ?? null;
        unset($_SESSION['form_error']);

        $this->view('mahasiswa/edit', compact('mhs', 'prodiList', 'error'));
    }

    public function update(): void
    {
        $id   = (int) ($_POST['id'] ?? 0);
        $data = $this->validated($id);

        if ($data === null) {
            $this->redirect('mahasiswa/edit?id=' . $id);
        }

        Mahasiswa::update($id, $data);
        flash_set('success', 'Data mahasiswa berhasil diperbarui.');
        $this->redirect('mahasiswa');
    }

    // Best Practice BKPM: destroy WAJIB lewat POST, bukan GET.
    public function destroy(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        Mahasiswa::delete($id);
        flash_set('success', 'Data mahasiswa berhasil dihapus.');
        $this->redirect('mahasiswa');
    }

    // Validasi sederhana pada input tambah/ubah mahasiswa.
    private function validated(int $excludeId = 0): ?array
    {
        $nim      = trim($_POST['nim'] ?? '');
        $nama     = trim($_POST['nama'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $prodiId  = (int) ($_POST['prodi_id'] ?? 0);
        $angkatan = (int) ($_POST['angkatan'] ?? date('Y'));
        $status   = $_POST['status'] ?? 'aktif';

        if ($nim === '' || $nama === '' || $email === '' || $prodiId <= 0) {
            $_SESSION['form_error']    = 'NIM, Nama, Email, dan Prodi wajib diisi.';
            $_SESSION['old_mahasiswa'] = $_POST;
            return null;
        }

        if (!in_array($status, ['aktif', 'cuti', 'lulus'], true)) {
            $status = 'aktif';
        }

        return [
            'nim'      => $nim,
            'nama'     => $nama,
            'email'    => $email,
            'prodi_id' => $prodiId,
            'angkatan' => $angkatan,
            'status'   => $status,
        ];
    }
}
