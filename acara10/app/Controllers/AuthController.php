<?php
// app/Controllers/AuthController.php
class AuthController extends Controller
{
    // Menampilkan form login (BKPM: method loginForm)
    public function loginForm(): void
    {
        if (!empty($_SESSION['login']) && $_SESSION['login'] === true) {
            $this->redirect('dashboard');
        }

        $error = $_SESSION['login_error'] ?? null;
        unset($_SESSION['login_error']);

        $flash = flash_get();

        $this->view('auth/login', compact('error', 'flash'));
    }

    // Memproses submit form login (BKPM: method login)
    public function login(): void
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        // Simulasi login sukses dengan hardcode username/password
        if ($username === 'admin' && $password === '12345') {
            $_SESSION['login']    = true;
            $_SESSION['username'] = $username;

            // Tugas Mandiri: flash message setelah login sukses
            flash_set('success', 'Selamat datang, ' . ucfirst($username) . '!');

            $this->redirect('dashboard');
        }

        $_SESSION['login_error'] = 'Username atau password salah.';
        $this->redirect('login');
    }

    public function dashboard(): void
    {
        $username       = $_SESSION['username'] ?? '';
        $mahasiswaRepo  = new MahasiswaRepository(Database::getInstance());
        $totalMahasiswa = $mahasiswaRepo->count();
        $totalDosen     = count(Dosen::all());
        $flash          = flash_get();

        $this->view('dashboard/index', compact('username', 'totalMahasiswa', 'totalDosen', 'flash'));
    }

    public function logout(): void
    {
        unset($_SESSION['login'], $_SESSION['username']);

        // Tugas Mandiri: flash message setelah logout
        flash_set('success', 'Anda telah logout.');

        $this->redirect('login');
    }
}
