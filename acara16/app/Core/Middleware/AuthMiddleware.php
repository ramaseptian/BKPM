<?php
// app/Core/Middleware/AuthMiddleware.php
// Middleware sederhana: memastikan user sudah login sebelum melanjutkan
// ke Controller. Jika belum login, arahkan ke halaman /login.

class AuthMiddleware
{
    public function handle(): void
    {
        if (empty($_SESSION['login']) || $_SESSION['login'] !== true) {
            redirect('login');
        }
    }
}
