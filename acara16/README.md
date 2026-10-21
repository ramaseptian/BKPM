# Sistem Informasi Akademik (Sederhana)

Proyek latihan Workshop Sistem Informasi Web Server (TIF330805) — gabungan
Acara 6 (Middleware, Auth Sederhana, Struktur Folder Lengkap), Acara 7
(Database, CREATE TABLE, Model Dasar), dan Acara 8 (PDO, Prepared Statement,
Relasi, dan CRUD Lengkap).

## Cara menjalankan
1. Buka phpMyAdmin, buat database baru bernama **acara_7**
   (collation `utf8mb4_general_ci`), lalu pilih database tersebut dan
   **Import** file `acara_7.sql`. Nama database harus sama dengan `dbname`
   pada `config/database.php`.
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
- Acara 10 ini tidak mengubah perilaku aplikasi - seluruhnya diverifikasi
  ulang (tampil, tambah, ubah, hapus) dan tetap berjalan normal.

## Acara 12 — Branch, Merge, Pull Request, dan Conflict
Project ini dikelola dengan Git (repository GitHub: `si-akademik`).
- `main` : branch utama (kode stabil).
- `fitur-login` : fitur Halaman Login (tombol lihat/sembunyikan password,
  validasi input kosong, username tetap terisi saat login gagal). Sudah
  di-merge ke `main` dengan `git merge fitur-login`.
- `fitur-judul-login` : dipakai untuk simulasi merge conflict (mengubah baris
  subjudul login yang sama dengan `main`). Conflict diselesaikan secara manual
  lalu di-commit dengan pesan "Menyelesaikan merge conflict".
- `fitur-footer-login` : branch fitur yang BELUM di-merge, dipakai untuk
  membuat Pull Request ke `main` di GitHub.

Riwayat lengkap: `git log --oneline --graph --all`

## Acara 13 — Service Layer, Validasi, Session, dan Business Logic
- Database disamakan dengan `acara_7.sql` (nama database `acara_7`, tabel
  `mahasiswa` TANPA kolom email). Kolom/field email dihapus dari Entity,
  Repository, Controller, dan View.
- `app/Services/MahasiswaService.php` (baru): menerima `MahasiswaRepository`
  dan `ProdiRepository` lewat constructor. Berisi `create()`, `update()`,
  `delete()` dan seluruh validasi mahasiswa (NIM wajib/angka/unik, nama wajib,
  prodi harus ada, angkatan 2000-2100, status valid).
- `app/Repositories/ProdiRepository.php` (baru): `all()`, `find()`, `exists()`.
- `MahasiswaRepository`: ditambah `existsByNim()` dan `create()` kini
  mengembalikan id baru.
- `MahasiswaController` hanya mengatur alur: memanggil Service lalu
  redirect/view. Tidak ada validasi dan query SQL di Controller.
- Flash message (`$_SESSION['flash']`) bertipe success/error ditampilkan lewat
  `app/Views/partials/flash.php` dan dihapus dari session setelah sekali tampil:
  "Data mahasiswa berhasil ditambahkan/diubah", "NIM sudah terdaftar",
  "Data gagal disimpan".

## Acara 14 — Security, Exception, Logging, PRG, dan Integrasi CRUD Lengkap
- **Logging**: `app/Core/Logger.php` mencatat error ke `storage/logs/app.log`
  (waktu, level, konteks, kelas exception, pesan, file:baris). Stack trace TIDAK
  dicatat agar password database tidak ikut tertulis. File `app.log` dibuat
  otomatis saat error pertama terjadi dan diabaikan Git (`.gitignore`).
- **Exception handling**: `MahasiswaService` membungkus create/update/delete
  dengan try-catch. Pengguna hanya melihat "Data gagal disimpan." / "Data gagal
  dihapus."; detail error masuk ke log. Exception yang lolos (mis. MySQL mati)
  ditangani handler global di `public/index.php` dan menampilkan halaman 500
  yang aman (`app/Views/errors/500.php`). `display_errors` dimatikan.
- **Security**: validasi di Service, prepared statement di seluruh Repository,
  `htmlspecialchars` pada semua keluaran, cookie sesi HttpOnly + SameSite=Lax,
  `session_regenerate_id()` saat login, header X-Content-Type-Options /
  X-Frame-Options / Referrer-Policy, aksi ubah data hanya lewat POST.
- **PRG**: setelah create/update/delete (berhasil maupun gagal validasi) selalu
  redirect, sehingga refresh tidak mengirim ulang form.
- **CRUD lengkap**: `/mahasiswa`, `/mahasiswa/create`, `/mahasiswa/edit?id=`,
  dan `POST /mahasiswa/delete` (sebelumnya `/mahasiswa/destroy`).
- `MahasiswaRepository` kini memiliki `all()`, `find()`, `create()`, `update()`,
  `delete()`, dan `existsByNim()`.
- Catatan: BKPM Acara 14 memakai kolom `email`, sedangkan database `acara_7`
  (dipakai sejak Acara 13) tidak memilikinya, sehingga validasi email tidak
  diterapkan; skenario "email tidak valid" diganti dengan NIM tidak valid.

## Acara 15 — Konsep API dan JSON
API Data Mahasiswa dengan response JSON: `api/mahasiswa.php`
(alamat tanpa .php, `/api/mahasiswa`, juga bisa lewat `api/.htaccess`).

| Method | URL | Fungsi | Status |
|--------|-----|--------|--------|
| GET  | `/api/mahasiswa.php` | Daftar mahasiswa | 200 |
| GET  | `/api/mahasiswa.php?id=1` | Satu mahasiswa | 200 / 404 / 400 |
| GET  | `/api/mahasiswa.php?q=budi` | Cari nama/NIM (tambahan) | 200 |
| POST | `/api/mahasiswa.php` | Tambah mahasiswa (body JSON) | 201 / 400 / 409 |
| lain | - | Tidak didukung | 405 |

Contoh body POST (database acara_7 tidak punya kolom email):
`{"nim":"2401004","nama":"Dewi","prodi_id":1,"angkatan":2026,"status":"aktif"}`

Format response: `{"success": true, "message": "...", "data": ..., "errors": {...}}`.
API memakai ulang `MahasiswaService` dan `MahasiswaRepository`, sehingga validasi,
prepared statement, exception handling, dan logging sama dengan aplikasi web.
Koleksi Postman: `docs/postman/si-akademik-api.postman_collection.json`
(Import ke Postman, variabel `base_url` = `http://localhost/si-akademik`).

## Acara 16 — RESTful API dan Endpoint Request/Response
`api/mahasiswa.php` dikembangkan menjadi RESTful API (resource `mahasiswa`).
Alamat `/api/mahasiswa/{id}` dipetakan oleh `api/.htaccess` (mod_rewrite).

| Method | Endpoint | Fungsi | Status |
|--------|----------|--------|--------|
| GET    | `/api/mahasiswa` | Seluruh mahasiswa | 200 |
| GET    | `/api/mahasiswa/{id}` | Mahasiswa berdasarkan ID | 200 / 404 |
| POST   | `/api/mahasiswa` | Menambah mahasiswa | 201 / 400 |
| PUT    | `/api/mahasiswa/{id}` | Mengubah mahasiswa | 200 / 400 / 404 |
| DELETE | `/api/mahasiswa/{id}` | Menghapus mahasiswa | 200 / 404 |

Request dan response berformat JSON: `{"success", "message", "data", "errors"?}`.
Body POST/PUT: `nim`, `nama`, `prodi_id`, `angkatan` (wajib), `status` (opsional).
Database acara_7 tidak memiliki kolom email, sehingga field email pada contoh
modul tidak dipakai. Koleksi Postman: `docs/postman/si-akademik-restful-api.postman_collection.json`.

