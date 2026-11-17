# Test Case - Pengujian Manual (Blackbox) API Sistem Informasi Akademik

Teknik: **Equivalence Partitioning** (satu perwakilan data dari tiap partisi valid dan tidak valid).
Alat: Postman (manual). Ruang lingkup: `/register`, `/login`, dan `/mahasiswa` (GET, POST, PUT, DELETE).

Hasil yang diharapkan mengikuti spesifikasi API (`docs/API_DOCUMENTATION.md` dan `docs/openapi.yaml`).

## Ruang lingkup endpoint

| No | Method | Endpoint | Controller::method | Token |
|----|--------|----------|--------------------|-------|
| 1 | POST | `/register` | `AuthController::register()` | Tidak |
| 2 | POST | `/login` | `AuthController::login()` | Tidak |
| 3 | GET | `/mahasiswa` | `MahasiswaController::index()` | Tidak |
| 4 | POST | `/mahasiswa` | `MahasiswaController::store()` | Ya |
| 5 | PUT | `/mahasiswa?id={id}` | `MahasiswaController::update()` | Ya |
| 6 | DELETE | `/mahasiswa?id={id}` | `MahasiswaController::destroy()` | Ya |

## Partisi data (contoh: field `nim`)

| Partisi | Kelas | Perwakilan | Hasil |
|---------|-------|------------|-------|
| Valid | 7 digit angka dan belum dipakai | `2401010` | 201 |
| Tidak valid | Kosong | `""` | 400 |
| Tidak valid | Bukan 7 digit angka | `abc` | 422 |
| Tidak valid | Sudah dipakai | NIM milik data lain | 409 |

## Test case dari modul (TC-01 sampai TC-06)

| ID | Skenario | Data Uji | Hasil Diharapkan | Hasil Aktual | Status |
|----|----------|----------|------------------|--------------|--------|
| TC-01 | NIM valid dan lengkap (`store`) | nim 7 digit baru, data lengkap, dengan token | 201 Created | _(isi)_ | _(Pass/Fail)_ |
| TC-02 | NIM kosong (partisi tidak valid) | `nim=""` | 400 Bad Request | _(isi)_ | _(Pass/Fail)_ |
| TC-03 | NIM duplikat (partisi tidak valid) | nim milik data lain | 409 Conflict | _(isi)_ | _(Pass/Fail)_ |
| TC-04 | Login kredensial benar (`login`) | username dan password benar | 200 OK, token diterima | _(isi)_ | _(Pass/Fail)_ |
| TC-05 | Login kredensial salah | password salah | 401 Unauthorized | _(isi)_ | _(Pass/Fail)_ |
| TC-06 | Akses `store` tanpa token (`requireAuth`) | tanpa header Authorization | 401 Unauthorized | _(isi)_ | _(Pass/Fail)_ |

## Test case tambahan (Tugas Mandiri 1): `update` dan `destroy`

| ID | Skenario | Data Uji | Hasil Diharapkan | Hasil Aktual | Status |
|----|----------|----------|------------------|--------------|--------|
| TC-07 | `update` data valid, NIM sendiri tidak berubah (batas: bukan duplikat) | PUT id ada, data lengkap, token | 200 OK | _(isi)_ | _(Pass/Fail)_ |
| TC-08 | `update` id tidak ada | PUT id=9999, token | 404 Not Found | _(isi)_ | _(Pass/Fail)_ |
| TC-09 | `update` NIM milik data lain | PUT id ada, nim milik data lain | 409 Conflict | _(isi)_ | _(Pass/Fail)_ |
| TC-10 | `update` format NIM salah | PUT id ada, `nim=abc` | 422 Unprocessable Entity | _(isi)_ | _(Pass/Fail)_ |
| TC-11 | `update` tanpa token | PUT id ada, tanpa header Authorization | 401 Unauthorized | _(isi)_ | _(Pass/Fail)_ |
| TC-12 | `update` id bukan angka | PUT id=abc, token | 400 Bad Request | _(isi)_ | _(Pass/Fail)_ |
| TC-13 | `destroy` id ada | DELETE id ada, token | 200 OK | _(isi)_ | _(Pass/Fail)_ |
| TC-14 | `destroy` id tidak ada | DELETE id=9999, token | 404 Not Found | _(isi)_ | _(Pass/Fail)_ |
| TC-15 | `destroy` tanpa token | DELETE id ada, tanpa header Authorization | 401 Unauthorized | _(isi)_ | _(Pass/Fail)_ |
| TC-16 | `destroy` id bukan angka | DELETE id=abc, token | 400 Bad Request | _(isi)_ | _(Pass/Fail)_ |

## Penyesuaian dari contoh modul

- **TC-01:** modul memakai NIM 9 digit (`220100123`). Database `acara_7` memakai NIM 7 digit, sehingga aturan API adalah 7 digit (`NIM_LENGTH = 7`).
- **TC-02:** modul menerima 400 atau 422. API mengembalikan **400** (field wajib kosong).
- **TC-03:** modul menyebut 422. Sesuai keputusan Acara 18 dan dokumentasi API, NIM duplikat dijawab **409 Conflict**.

## Cara menjalankan

1. Import `docs/postman/test-case.postman_collection.json` ke Postman dan pilih Environment (`base_url`).
2. Jalankan folder **Persiapan** terlebih dahulu (membuat akun, token, dan satu data pembanding).
3. Jalankan TC-01 sampai TC-16. Catat kode status dan pesan sebenarnya pada kolom **Hasil Aktual**.
4. **Status** = Pass jika hasil aktual sama dengan hasil diharapkan, selain itu Fail.
5. Jika ada Fail, perbaiki Model/Controller yang relevan, lalu jalankan ulang (retest) dan catat hasilnya.

Catatan: token hanya berlaku 5 menit. Jika muncul 401 yang tidak diharapkan, jalankan ulang login pada folder Persiapan.
