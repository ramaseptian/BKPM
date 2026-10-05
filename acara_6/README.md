# Sistem Informasi Akademik (Sederhana)

Proyek latihan Workshop Sistem Informasi Web Server (TIF330805) — Acara 6:
Middleware, Auth Sederhana, dan Struktur Folder Lengkap.

## Cara menjalankan
1. Salin folder ini ke `C:\xampp\htdocs\kelazzz`.
2. Aktifkan modul Apache pada XAMPP.
3. Akses `http://localhost/kelazzz/public/`.
4. Login dengan akun contoh (hardcode): `admin` / `12345`.

## Fitur
- Routing berbasis array (`routes/web.php`) + `Router` (`app/Core/Router.php`).
- Autentikasi sederhana berbasis session (`AuthController`, `AuthMiddleware`).
- Proteksi halaman `/dashboard`, `/mahasiswa`, `/dosen` — otomatis redirect
  ke `/login` jika belum login.
- Flash message setelah login sukses dan setelah logout.
- Data Mahasiswa & Dosen (Model + tampilan Bootstrap).
