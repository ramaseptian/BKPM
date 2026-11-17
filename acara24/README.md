# Sistem Informasi Akademik - API CRUD (BKPM Acara 17 sampai 20)

API CRUD berpola MVC dengan PHP Native, Router, dan Front Controller.

## Struktur
```
sistem-informasi-akademik/
├── app/                 (kode aplikasi, tidak diakses langsung browser)
│   ├── config/Database.php      koneksi PDO
│   ├── core/autoload.php        autoloader class
│   ├── core/Router.php          memetakan URL ke Controller
│   ├── core/Response.php        format response JSON seragam (Acara 18)
│   ├── config/app.php           APP_ENV dan NIM_LENGTH (Acara 18)
│   ├── models/Mahasiswa.php     query tabel mahasiswa
│   ├── models/Prodi.php         query tabel prodi (Tugas Mandiri)
│   ├── controllers/MahasiswaController.php
│   └── controllers/ProdiController.php
├── public/              (satu-satunya folder yang diakses browser)
│   ├── index.php        Front Controller
│   ├── .htaccess        mengarahkan semua request ke index.php
│   └── views/           (diisi mulai acara 24)
├── docs/postman/        koleksi Postman
└── acara_7.sql          database
```

## Cara menjalankan
1. Nyalakan Apache (mod_rewrite aktif) dan MySQL pada XAMPP.
2. phpMyAdmin: buat database `acara_7`, lalu Import `acara_7.sql`.
3. Salin folder ini ke `C:\xampp\htdocs\`.
4. Akses `http://localhost/NAMA_FOLDER/public/mahasiswa`.

## Endpoint
| Method | URL | Fungsi |
|--------|-----|--------|
| GET | `/public/mahasiswa` | Seluruh mahasiswa |
| GET | `/public/mahasiswa?id=1` | Satu mahasiswa |
| POST | `/public/mahasiswa` | Tambah mahasiswa |
| PUT | `/public/mahasiswa?id=1` | Ubah mahasiswa |
| DELETE | `/public/mahasiswa?id=1` | Hapus mahasiswa |
| (sama) | `/public/prodi` | CRUD program studi |

Body POST/PUT mahasiswa: `nim`, `nama`, `prodi_id`, `angkatan` (wajib), `status` (opsional, default aktif).
Body POST/PUT prodi: `kode`, `nama`.
Database `acara_7` memakai `prodi_id` (relasi ke tabel prodi), bukan kolom teks `prodi` seperti pada contoh modul.

## Acara 18 - JSON Encoding dan HTTP Status Code
Seluruh response dibuat oleh `Response::json($kode, $success, $pesan, $data, $errors)`:
`{"success", "message", "data", "errors"?, "timestamp"}`. `errors` hanya muncul bila ada
kesalahan validasi per field. JSON dirapikan (pretty print) hanya jika `APP_ENV` = `development`
(`app/config/app.php`).

| Kode | Dipakai untuk |
|------|---------------|
| 200 | GET/PUT/DELETE berhasil |
| 201 | POST berhasil |
| 400 | Body bukan JSON, field wajib kosong, ID bukan angka |
| 404 | Data atau endpoint tidak ditemukan |
| 405 | Method tidak diizinkan |
| 409 | NIM/kode duplikat, prodi masih dipakai |
| 422 | Format data salah (NIM bukan 7 digit, status tidak valid, prodi tidak ada) |
| 500 | Kesalahan server / koneksi database |

Catatan: modul menyebut NIM 9 digit, tetapi data pada acara_7 memakai NIM 7 digit
(2401001), sehingga aturan diatur 7 digit lewat `NIM_LENGTH`.
Koleksi Postman: `docs/postman/response-status.postman_collection.json`.

## Acara 19 - Postman dan Autentikasi (Register)
- Tabel baru `users` (database `acara_7`): jalankan `database/users.sql` di phpMyAdmin
  (tab SQL). File `acara_7.sql` tidak diubah.
- `app/models/User.php`: `create()` dan `findByUsername()`, dengan validasi email
  (`filter_var` + `FILTER_VALIDATE_EMAIL`), serta username/email tidak boleh duplikat.
- `app/controllers/AuthController.php`: `register()`. Kata sandi disimpan dengan
  `password_hash()` dan tidak pernah dikirim balik.
- `app/core/Router.php`: resource `register` (hanya POST).
- Endpoint: `POST /public/register` dengan body `{"username","email","password"}`.
  Status: 201, 400 (kosong), 422 (email tidak valid), 409 (username/email sudah ada), 405 (bukan POST).
- Postman (`docs/postman/`): collection `api-sia-mvc`, environment `Local` dan `Staging`
  (variabel `base_url`). Pilih environment di pojok kanan atas Postman.

## Acara 20 - API Authentication (Token) dan Error Handling
- `app/core/Auth.php`: `generateToken()`, `validateToken()`, `requireAuth()` (token format JWT,
  HS256, tanpa pustaka). Masa berlaku token 5 menit (`Auth::TOKEN_LIFETIME = 300`).
- `AuthController::login()`: `POST /public/login` dengan `{"username","password"}`;
  berhasil mengembalikan `token` dan `role`.
- `MahasiswaController`: `store()`, `update()`, `destroy()` memanggil `Auth::requireAuth()`
  (header `Authorization: Bearer <token>`). `GET /mahasiswa` tetap publik.
- `Mahasiswa` (Model): `create()`, `update()`, `delete()` dibungkus try-catch; detail error
  dicatat dengan `error_log()`, client menerima 500 dengan pesan umum.
- Status baru: 401 (token tidak ada/tidak valid/kedaluwarsa, atau username/password salah).
- Tidak ada perubahan database (memakai tabel `users` dari Acara 19). Akun dibuat lewat `POST /register`.
- Catatan: resource `prodi` belum dilindungi token (sesuai prosedur, hanya Mahasiswa).
- Koleksi Postman: `docs/postman/api-sia-mvc.postman_collection.json` (14 request, token disimpan otomatis).

## Acara 21 - Dokumentasi Endpoint
- `docs/API_DOCUMENTATION.md`: peta Router, tabel ringkasan seluruh endpoint beserta Controller
  penanganannya, format response, kode status, autentikasi, dan dokumentasi tiap endpoint
  (request body, response sukses dan gagal).
- Koleksi Postman (`docs/postman/api-sia-mvc.postman_collection.json`) kini memiliki Description
  (nama Controller dan method) dan Examples (response sukses/gagal) pada setiap request.
- Tidak ada perubahan kode aplikasi maupun database.

## Acara 22 - Dokumentasi Endpoint (OpenAPI/Swagger)
- `docs/openapi.yaml`: dokumentasi OpenAPI 3.0 yang dikonversi dari `docs/API_DOCUMENTATION.md`.
  Path mengikuti resource pada peta Router: `/register`, `/login`, `/mahasiswa` (GET, POST, PUT, DELETE),
  dan `/prodi`. Skema keamanan `bearerAuth` (http/bearer, JWT) dipasang pada POST, PUT, DELETE `/mahasiswa`.
- Cara melihat: buka https://editor.swagger.io, tempelkan seluruh isi `docs/openapi.yaml` pada panel kiri,
  pratinjau tampil di panel kanan. Simpan tangkapan layar ke folder `docs/`.
- Tidak ada perubahan kode aplikasi maupun database.

## Acara 23 - Manual Testing dan Blackbox Testing
- `docs/TEST_CASE.md`: ruang lingkup, partisi data (equivalence partitioning), dan 16 test case
  (6 dari modul + 10 tambahan untuk `update` dan `destroy`). Kolom Hasil Aktual dan Status diisi
  setelah pengujian manual di Postman.
- `docs/postman/test-case.postman_collection.json`: koleksi Postman untuk menjalankan TC-01 sampai
  TC-16 (dengan folder Persiapan dan Pembersihan). Setiap request memeriksa kode status yang diharapkan.
- Penyesuaian dari modul: NIM 7 digit (sesuai acara_7), NIM kosong dijawab 400, NIM duplikat dijawab 409.
- Tidak ada perubahan kode aplikasi maupun database.

## Acara 24 - Integrasi API dengan Frontend
- `public/index.php`: header CORS (`Access-Control-Allow-*`) diaktifkan sekali di Front Controller dan
  berlaku untuk semua resource. Permintaan preflight `OPTIONS` dijawab 204.
- `public/views/index.html` dan `public/views/app.js`: halaman statis yang mengambil `GET /mahasiswa`
  dengan `fetch` (async/await) lalu menampilkannya pada tabel HTML (NIM, Nama, Program Studi).
  Alamat API dihitung otomatis dari alamat halaman, jadi nama folder proyek bebas.
- Tugas mandiri: teks "Memuat data..." saat menunggu dan "Belum ada data" saat data kosong
  (ditambah pesan galat bila API gagal).
- Alat bantu uji tampilan: `views/index.html?simulasi=lambat|kosong|error`.
- Buka: `http://localhost/NAMA_FOLDER/public/views/index.html`.
- Tidak ada perubahan database.

