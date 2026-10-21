# Sistem Informasi Akademik - API CRUD (BKPM Acara 17, 18, dan 19)

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

