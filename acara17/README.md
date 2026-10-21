# Sistem Informasi Akademik - API CRUD (BKPM Acara 17)

API CRUD berpola MVC dengan PHP Native, Router, dan Front Controller.

## Struktur
```
sistem-informasi-akademik/
├── app/                 (kode aplikasi, tidak diakses langsung browser)
│   ├── config/Database.php      koneksi PDO
│   ├── core/autoload.php        autoloader class
│   ├── core/Router.php          memetakan URL ke Controller
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
