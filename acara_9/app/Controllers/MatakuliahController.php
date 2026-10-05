<?php
// app/Controllers/MatakuliahController.php
class MatakuliahController extends Controller
{
    public function index(): void
    {
        $keyword       = trim($_GET['q'] ?? '');
        $matakuliahList = Matakuliah::allWithProdi($keyword !== '' ? $keyword : null);
        $prodiList     = Prodi::all();
        $flash         = flash_get();

        $this->view('matakuliah/index', compact('matakuliahList', 'prodiList', 'keyword', 'flash'));
    }

    public function create(): void
    {
        $prodiList = Prodi::all();
        $error     = $_SESSION['form_error'] ?? null;
        unset($_SESSION['form_error']);
        $this->view('matakuliah/create', compact('prodiList', 'error'));
    }

    public function store(): void
    {
        $data = $this->validated();
        if ($data === null) {
            $this->redirect('matakuliah/create');
        }

        Matakuliah::create($data);
        flash_set('success', 'Mata kuliah berhasil ditambahkan.');
        $this->redirect('matakuliah');
    }

    public function edit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $mk = Matakuliah::find($id);

        if (!$mk) {
            http_response_code(404);
            echo "Data mata kuliah tidak ditemukan.";
            return;
        }

        $prodiList = Prodi::all();
        $error     = $_SESSION['form_error'] ?? null;
        unset($_SESSION['form_error']);
        $this->view('matakuliah/edit', compact('mk', 'prodiList', 'error'));
    }

    public function update(): void
    {
        $id   = (int) ($_POST['id'] ?? 0);
        $data = $this->validated();

        if ($data === null) {
            $this->redirect('matakuliah/edit?id=' . $id);
        }

        Matakuliah::update($id, $data);
        flash_set('success', 'Mata kuliah berhasil diperbarui.');
        $this->redirect('matakuliah');
    }

    public function destroy(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        Matakuliah::delete($id);
        flash_set('success', 'Mata kuliah berhasil dihapus.');
        $this->redirect('matakuliah');
    }

    private function validated(): ?array
    {
        $kode    = trim($_POST['kode'] ?? '');
        $nama    = trim($_POST['nama'] ?? '');
        $sks     = (int) ($_POST['sks'] ?? 0);
        $prodiId = (int) ($_POST['prodi_id'] ?? 0);

        if ($kode === '' || $nama === '' || $sks <= 0 || $prodiId <= 0) {
            $_SESSION['form_error'] = 'Kode, Nama, SKS, dan Prodi wajib diisi dengan benar.';
            return null;
        }

        return ['kode' => $kode, 'nama' => $nama, 'sks' => $sks, 'prodi_id' => $prodiId];
    }
}
