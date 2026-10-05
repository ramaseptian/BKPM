<?php
// app/Controllers/ProdiController.php
class ProdiController extends Controller
{
    public function index(): void
    {
        $prodiList = Prodi::all();
        $flash     = flash_get();
        $this->view('prodi/index', compact('prodiList', 'flash'));
    }

    public function create(): void
    {
        $error = $_SESSION['form_error'] ?? null;
        unset($_SESSION['form_error']);
        $this->view('prodi/create', compact('error'));
    }

    public function store(): void
    {
        $data = $this->validated();
        if ($data === null) {
            $this->redirect('prodi/create');
        }

        Prodi::create($data);
        flash_set('success', 'Program studi berhasil ditambahkan.');
        $this->redirect('prodi');
    }

    public function edit(): void
    {
        $id    = (int) ($_GET['id'] ?? 0);
        $prodi = Prodi::find($id);

        if (!$prodi) {
            http_response_code(404);
            echo "Data prodi tidak ditemukan.";
            return;
        }

        $error = $_SESSION['form_error'] ?? null;
        unset($_SESSION['form_error']);
        $this->view('prodi/edit', compact('prodi', 'error'));
    }

    public function update(): void
    {
        $id   = (int) ($_POST['id'] ?? 0);
        $data = $this->validated();

        if ($data === null) {
            $this->redirect('prodi/edit?id=' . $id);
        }

        Prodi::update($id, $data);
        flash_set('success', 'Program studi berhasil diperbarui.');
        $this->redirect('prodi');
    }

    public function destroy(): void
    {
        $id = (int) ($_POST['id'] ?? 0);

        try {
            Prodi::delete($id);
            flash_set('success', 'Program studi berhasil dihapus.');
        } catch (PDOException $e) {
            // Foreign key ON DELETE RESTRICT akan menolak penghapusan
            // selama prodi masih dipakai mahasiswa/mata kuliah.
            flash_set('error', 'Prodi tidak bisa dihapus karena masih dipakai oleh mahasiswa atau mata kuliah.');
        }

        $this->redirect('prodi');
    }

    private function validated(): ?array
    {
        $kode = trim($_POST['kode'] ?? '');
        $nama = trim($_POST['nama'] ?? '');

        if ($kode === '' || $nama === '') {
            $_SESSION['form_error'] = 'Kode dan Nama prodi wajib diisi.';
            return null;
        }

        return ['kode' => $kode, 'nama' => $nama];
    }
}
