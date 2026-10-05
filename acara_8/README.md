# Sistem Informasi Akademik (Sederhana)

Proyek latihan Workshop Sistem Informasi Web Server (TIF330805) — gabungan
Acara 6 (Middleware, Auth Sederhana, Struktur Folder Lengkap), Acara 7
(Database, CREATE TABLE, Model Dasar), dan Acara 8 (PDO, Prepared Statement,
Relasi, dan CRUD Lengkap).

## Cara menjalankan
1. Import `si_akademik.sql` melalui phpMyAdmin (tab SQL / Import) terlebih
   dahulu. Pastikan database yang terbentuk bernama **si_akademik**, sama
   seperti `dbname` pada `config/database.php`.
2. Salin folder ini ke `C:\xampp\htdocs\kelazzz`.
3. Aktifkan modul **Apache** dan **MySQL** pada XAMPP.
4. Akses `http://localhost/kelazzz/public/`.
5. Login dengan akun contoh (hardcode): `admin` / `12345`.

## Fitur
- Routing berbasis array (`routes/web.php`) + `Router` (`app/Core/Router.php`).
- Autentikasi sederhana berbasis session (`AuthController`, `AuthMiddleware`).
- Proteksi halaman `/dashboard`, `/mahasiswa`, `/dosen` — otomatis redirect
  ke `/login` jika belum login.
- Flash message setelah login sukses dan setelah logout.
- **Data Mahasiswa: dari database MySQL/MariaDB (si_akademik)** melalui
  `Mahasiswa::all()` / `Mahasiswa::findByNim()` (PDO, lihat `app/Core/Model.php`).
- **Data Prodi**: dipetakan dari tabel `prodi` (`app/Models/Prodi.php`) untuk
  menampilkan nama program studi pada tabel & detail mahasiswa.
- Data Dosen masih berupa array sederhana (`app/Models/Dosen.php`), karena
  BKPM Acara 7 tidak membuat tabel dosen.

## Acara 8 — CRUD Lengkap
- `app/Core/Database.php`: singleton PDO (satu koneksi bersama).
- CRUD Mahasiswa, Program Studi, dan Mata Kuliah (index, create, store,
  edit, update, destroy) memakai PDO prepared statement.
- Daftar mahasiswa & mata kuliah memakai JOIN untuk menampilkan nama prodi.
- Hapus wajib lewat POST + konfirmasi JavaScript; prodi yang masih dipakai
  tidak dapat dihapus (FOREIGN KEY ... ON DELETE RESTRICT).
- Tugas Mandiri: pencarian mahasiswa (nama/NIM) dan mata kuliah (kode/nama)
  memakai LIKE + prepared statement.
