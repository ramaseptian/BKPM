# Sistem Informasi Akademik (Sederhana)

Proyek latihan Workshop Sistem Informasi Web Server (TIF330805) — gabungan
Acara 6 (Middleware, Auth Sederhana, Struktur Folder Lengkap), Acara 7
(Database, CREATE TABLE, Model Dasar), dan Acara 8 (PDO, Prepared Statement,
Relasi, dan CRUD Lengkap).

## Cara menjalankan
1. Buka phpMyAdmin, buat database baru bernama **acara_7**
   (collation `utf8mb4_general_ci`), pilih database tersebut, lalu **Import**
   file `acara_7.sql`. Nama database harus sama dengan `dbname` pada
   `config/database.php`.
2. Salin folder ini ke `C:\xampp\htdocs\si-akademik`.
3. Aktifkan modul **Apache** dan **MySQL** pada XAMPP.
4. Akses `http://localhost/si-akademik/public/`.
5. Login dengan akun contoh (hardcode): `admin` / `12345`.

## Fitur
- Routing berbasis array (`routes/web.php`) + `Router` (`app/Core/Router.php`).
- Autentikasi sederhana berbasis session (`AuthController`, `AuthMiddleware`).
- Proteksi halaman `/dashboard`, `/mahasiswa`, `/dosen` — otomatis redirect
  ke `/login` jika belum login.
- Flash message setelah login sukses dan setelah logout.
- **Data Mahasiswa: dari database MySQL/MariaDB (acara_7)** melalui
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

## Acara 9 — OOP MVC Lanjutan (Object Composition, Dependency Injection, Getter/Setter)
- `app/Models/Mahasiswa.php` sekarang murni Entity: seluruh atribut private,
  hanya bisa diakses lewat getter, dan hanya bisa diubah lewat setter yang
  memvalidasi datanya (NIM harus angka, nama tidak boleh kosong).
- `app/Repositories/MahasiswaRepository.php` (baru): menerima PDO lewat
  constructor (Constructor Injection), memusatkan seluruh query CRUD
  mahasiswa, dan selalu bekerja dengan object Mahasiswa, bukan array mentah.
- `app/Controllers/MahasiswaController.php`: menerima MahasiswaRepository
  lewat constructor sendiri - Controller tidak lagi membuat koneksi database
  secara langsung (Object Composition: Controller memiliki Repository,
  Repository memiliki PDO).
- Program Studi dan Mata Kuliah TIDAK diikutkan pada refactor ini, karena
  BKPM Acara 9 studi kasusnya khusus pada "Aplikasi Manajemen Data
  Mahasiswa" saja.

## Acara 10 — Inheritance, Base Controller, Base Model, Repository Pattern
- `app/Core/Controller.php` adalah BaseController: diwariskan (extends) oleh
  SELURUH Controller (Home, Auth, Mahasiswa, Prodi, Matakuliah, Dosen).
- `app/Core/Model.php` adalah BaseModel: diwariskan oleh Prodi dan Matakuliah.
  Mahasiswa tidak lagi mewarisi BaseModel sejak Acara 9 karena sudah menjadi
  Entity murni, data-nya ditangani oleh MahasiswaRepository.
- `app/Repositories/MahasiswaRepository.php` (dibuat pada Acara 9) adalah
  bukti Repository Pattern: seluruh query SQL mahasiswa terpusat di sini,
  MahasiswaController sama sekali tidak menulis SQL.
- Database disamakan dengan `acara_7.sql`: tabel `mahasiswa` tidak memiliki
  kolom email, sehingga email dihapus dari Entity, Repository, Controller,
  dan View. `MahasiswaRepository` juga memiliki method `all()` sesuai modul.
- Acara 10 ini tidak mengubah perilaku aplikasi - seluruhnya diverifikasi
  ulang (tampil, tambah, ubah, hapus) dan tetap berjalan normal.

## Catatan Riwayat Perubahan
- Memperbarui file README untuk dokumentasi proyek.