<?php
class MahasiswaController extends Controller {
    public function __construct(private MahasiswaRepository $repository) {}
    public function index(): void {
        $this->view('mahasiswa/index',['title'=>'Manajemen Data Mahasiswa','mahasiswa'=>$this->repository->all()]);
    }
    public function create(): void {
        $this->view('mahasiswa/form',['title'=>'Tambah Mahasiswa','data'=>null,'prodi'=>$this->repository->prodi()]);
    }
    public function store(): void {
        $this->repository->create($this->input());
        $this->redirect('?page=mahasiswa');
    }
    public function edit(int $id): void {
        $this->view('mahasiswa/form',['title'=>'Ubah Mahasiswa','data'=>$this->repository->find($id),'prodi'=>$this->repository->prodi()]);
    }
    public function update(int $id): void {
        $this->repository->update($id,$this->input());
        $this->redirect('?page=mahasiswa');
    }
    public function delete(int $id): void {
        $this->repository->delete($id);
        $this->redirect('?page=mahasiswa');
    }
    private function input(): array {
        return [
            'nim'=>trim($_POST['nim']??''),
            'nama'=>trim($_POST['nama']??''),
            'email'=>trim($_POST['email']??''),
            'prodi_id'=>(int)($_POST['prodi_id']??0),
            'angkatan'=>(int)($_POST['angkatan']??date('Y')),
            'status'=>$_POST['status']??'aktif'
        ];
    }
}
