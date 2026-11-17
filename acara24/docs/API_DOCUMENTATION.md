# Dokumentasi API - Sistem Informasi Akademik

API RESTful berbasis PHP Native dengan pola MVC. Seluruh request masuk melalui
Front Controller (`public/index.php`), diteruskan oleh Router ke Controller, lalu
Controller memanggil Model untuk mengakses database `acara_7`.

**Base URL:** `http://localhost/<nama-folder>/public`
(contoh: `http://localhost/sistem-informasi-akademik/public`)

---

## 1. Peta Router

Router mengambil nama resource dari segmen terakhir URL lalu meneruskannya ke Controller.

| Resource (URL) | Controller | Keterangan |
|----------------|------------|------------|
| `/mahasiswa` | `MahasiswaController` | CRUD data mahasiswa (index/store/update/destroy) |
| `/login` | `AuthController::login` | Autentikasi pengguna, menghasilkan token |
| `/register` | `AuthController::register` | Pendaftaran pengguna baru |
| `/prodi` | `ProdiController` | CRUD program studi (index/store/update/destroy) |

Resource yang tidak terdaftar dijawab `404 Endpoint tidak ditemukan`. Method HTTP yang
tidak didukung dijawab `405 Method tidak diizinkan`.

## 2. Ringkasan Seluruh Endpoint

| No | Method | Endpoint | Controller::method | Autentikasi | Fungsi |
|----|--------|----------|--------------------|-------------|--------|
| 1 | POST | `/register` | `AuthController::register()` | Publik | Mendaftarkan pengguna baru |
| 2 | POST | `/login` | `AuthController::login()` | Publik | Login, mengembalikan token |
| 3 | GET | `/mahasiswa` | `MahasiswaController::index()` | Publik | Seluruh mahasiswa |
| 4 | GET | `/mahasiswa?id={id}` | `MahasiswaController::index()` | Publik | Satu mahasiswa |
| 5 | POST | `/mahasiswa` | `MahasiswaController::store()` | **Token** | Menambah mahasiswa |
| 6 | PUT | `/mahasiswa?id={id}` | `MahasiswaController::update()` | **Token** | Mengubah mahasiswa |
| 7 | DELETE | `/mahasiswa?id={id}` | `MahasiswaController::destroy()` | **Token** | Menghapus mahasiswa |
| 8 | GET | `/prodi` | `ProdiController::index()` | Publik | Seluruh program studi |
| 9 | GET | `/prodi?id={id}` | `ProdiController::index()` | Publik | Satu program studi |
| 10 | POST | `/prodi` | `ProdiController::store()` | Publik* | Menambah program studi |
| 11 | PUT | `/prodi?id={id}` | `ProdiController::update()` | Publik* | Mengubah program studi |
| 12 | DELETE | `/prodi?id={id}` | `ProdiController::destroy()` | Publik* | Menghapus program studi |

\* Resource `prodi` belum dilindungi token pada versi ini.

## 3. Ketentuan Umum

### Format response
Semua response berformat JSON dengan struktur yang sama:

```json
{
    "success": true,
    "message": "Data berhasil diambil",
    "data": null,
    "errors": { "nama_field": "pesan kesalahan" },
    "timestamp": "2026-10-28T10:30:00+07:00"
}
```

`errors` hanya muncul bila ada kesalahan validasi per field. `data` bernilai `null` pada response gagal.

### Kode status HTTP

| Kode | Arti | Contoh |
|------|------|--------|
| 200 | OK | GET/PUT/DELETE/login berhasil |
| 201 | Created | Data atau pengguna baru dibuat |
| 400 | Bad Request | Body bukan JSON, field wajib kosong, ID bukan angka |
| 401 | Unauthorized | Token tidak ada/tidak valid/kedaluwarsa, atau login salah |
| 404 | Not Found | Data atau endpoint tidak ditemukan |
| 405 | Method Not Allowed | Method tidak didukung |
| 409 | Conflict | NIM/username/email/kode sudah dipakai |
| 422 | Unprocessable Entity | Data lengkap tetapi formatnya tidak valid |
| 500 | Internal Server Error | Kegagalan server atau database |

### CORS
Semua response menyertakan header `Access-Control-Allow-Origin: *`, `Access-Control-Allow-Methods`
(GET, POST, PUT, DELETE, OPTIONS), dan `Access-Control-Allow-Headers` (Content-Type, Authorization),
sehingga API dapat dipanggil dari halaman web lain (lintas origin). Permintaan preflight (`OPTIONS`)
dijawab `204`.

### Autentikasi
Endpoint bertanda **Token** membutuhkan header:

```
Authorization: Bearer <token>
```

Token didapat dari `POST /login` dan berlaku **5 menit**. Jika token tidak ada, dijawab
`401 Token tidak ditemukan`. Jika token salah atau kedaluwarsa, dijawab
`401 Token tidak valid atau kedaluwarsa`.

---

## 4. Endpoint Autentikasi

### POST /register
**Controller:** `AuthController::register()`  **Autentikasi:** Publik

**Headers:** `Content-Type: application/json`

**Request Body:**

| Field | Tipe | Wajib | Keterangan |
|-------|------|-------|------------|
| username | string | Ya | Unik, maksimal 50 karakter |
| email | string | Ya | Format email valid, unik, maksimal 100 karakter |
| password | string | Ya | Disimpan dalam bentuk hash |

```json
{ "username": "andi123", "email": "andi@example.com", "password": "rahasia123" }
```

**Response 201 Created:**

```json
{ "success": true, "message": "Registrasi berhasil", "data": { "id": "1" }, "timestamp": "..." }
```

**Response gagal:**

| Kode | message | Penyebab |
|------|---------|----------|
| 400 | Body harus berupa JSON yang valid | Body bukan JSON |
| 400 | Username, email, dan password wajib diisi | Ada field kosong (rinciannya di `errors`) |
| 422 | Format email tidak valid | Email bukan format yang benar |
| 422 | Username maksimal 50 karakter / Email maksimal 100 karakter | Terlalu panjang |
| 409 | Username sudah terdaftar / Email sudah terdaftar | Data ganda |
| 405 | Method tidak diizinkan | Method bukan POST |

### POST /login
**Controller:** `AuthController::login()`  **Autentikasi:** Publik

**Headers:** `Content-Type: application/json`

**Request Body:**

| Field | Tipe | Wajib | Keterangan |
|-------|------|-------|------------|
| username | string | Ya | Username terdaftar |
| password | string | Ya | Kata sandi |

```json
{ "username": "andi123", "password": "rahasia123" }
```

**Response 200 OK:**

```json
{
    "success": true,
    "message": "Login berhasil",
    "data": { "token": "eyJhbGciOiJIUzI1NiIs...", "role": "user" },
    "timestamp": "..."
}
```

**Response gagal:**

| Kode | message | Penyebab |
|------|---------|----------|
| 400 | Body harus berupa JSON yang valid | Body bukan JSON |
| 400 | Username dan password wajib diisi | Field kosong |
| 401 | Username atau password salah | Kredensial salah (pesan sama untuk username dan password) |
| 405 | Method tidak diizinkan | Method bukan POST |

---

## 5. Endpoint Mahasiswa

Data mahasiswa memiliki field: `id`, `nim`, `nama`, `prodi_id`, `angkatan`, `status`
(`aktif`/`cuti`/`lulus`), `created_at`, `updated_at`, serta `prodi` (nama program studi, hasil JOIN).

### GET /mahasiswa
**Controller:** `MahasiswaController::index()`  **Autentikasi:** Publik

**Response 200 OK:** `data` berupa array mahasiswa.

```json
{
    "success": true,
    "message": "Data berhasil diambil",
    "data": [
        {
            "id": 1, "nim": "2401001", "nama": "Budi Santoso", "prodi_id": 1,
            "angkatan": 2024, "status": "aktif", "prodi": "Teknik Informatika"
        }
    ],
    "timestamp": "..."
}
```

### GET /mahasiswa?id={id}
**Controller:** `MahasiswaController::index()`  **Autentikasi:** Publik

**Query:** `id` (integer, wajib pada bentuk ini).

**Response 200 OK:** `data` berupa satu objek mahasiswa.

**Response gagal:**

| Kode | message | Penyebab |
|------|---------|----------|
| 400 | ID harus berupa angka | `id` bukan angka |
| 404 | Data tidak ditemukan | Tidak ada mahasiswa dengan `id` itu |

### POST /mahasiswa
**Controller:** `MahasiswaController::store()`  **Autentikasi:** **Token** (`Auth::requireAuth()`)

**Headers:** `Content-Type: application/json`, `Authorization: Bearer <token>`

**Request Body:**

| Field | Tipe | Wajib | Keterangan |
|-------|------|-------|------------|
| nim | string | Ya | 7 digit angka, unik |
| nama | string | Ya | Nama lengkap |
| prodi_id | integer | Ya | Harus ada pada tabel `prodi` |
| angkatan | integer | Ya | Tahun angkatan |
| status | string | Tidak | `aktif` (bawaan), `cuti`, atau `lulus` |

```json
{ "nim": "2401010", "nama": "Andi Saputra", "prodi_id": 1, "angkatan": 2026, "status": "aktif" }
```

**Response 201 Created:**

```json
{ "success": true, "message": "Data berhasil ditambahkan", "data": { "id": "5" }, "timestamp": "..." }
```

**Response gagal:**

| Kode | message | Penyebab |
|------|---------|----------|
| 401 | Token tidak ditemukan | Header Authorization tidak ada |
| 401 | Token tidak valid atau kedaluwarsa | Token salah atau lewat 5 menit |
| 400 | Body harus berupa JSON yang valid | Body bukan JSON |
| 400 | Data belum lengkap | Field wajib kosong (rincian di `errors`) |
| 422 | Validasi gagal | NIM bukan 7 digit angka, `prodi_id`/`angkatan` bukan angka, atau `status` tidak valid |
| 422 | Program studi tidak ditemukan | `prodi_id` tidak ada |
| 409 | NIM sudah terdaftar | NIM sudah dipakai |
| 500 | Terjadi kesalahan pada server | Kegagalan database |

### PUT /mahasiswa?id={id}
**Controller:** `MahasiswaController::update()`  **Autentikasi:** **Token**

**Headers:** `Content-Type: application/json`, `Authorization: Bearer <token>`

**Query:** `id` (integer, wajib). **Request Body:** sama dengan `POST /mahasiswa`.

**Response 200 OK:**

```json
{ "success": true, "message": "Data berhasil diperbarui", "data": null, "timestamp": "..." }
```

**Response gagal:** sama dengan `POST /mahasiswa`, ditambah:

| Kode | message | Penyebab |
|------|---------|----------|
| 400 | ID harus berupa angka | `id` bukan angka |
| 404 | Data tidak ditemukan | `id` tidak ada |

NIM milik data itu sendiri tidak dianggap duplikat saat diubah.

### DELETE /mahasiswa?id={id}
**Controller:** `MahasiswaController::destroy()`  **Autentikasi:** **Token**

**Headers:** `Authorization: Bearer <token>`

**Query:** `id` (integer, wajib).

**Response 200 OK:**

```json
{ "success": true, "message": "Data berhasil dihapus", "data": null, "timestamp": "..." }
```

**Response gagal:** 401 (token), 400 (`ID harus berupa angka`), 404 (`Data tidak ditemukan`).

---

## 6. Endpoint Prodi

Pola sama dengan mahasiswa, tanpa token pada versi ini. Field: `kode` (string, maksimal
10 karakter, unik) dan `nama` (string).

| Method | Endpoint | Sukses | Gagal |
|--------|----------|--------|-------|
| GET | `/prodi` | 200 daftar prodi | - |
| GET | `/prodi?id={id}` | 200 satu prodi | 400 ID bukan angka, 404 tidak ditemukan |
| POST | `/prodi` | 201 `{ "id": "..." }` | 400 data belum lengkap, 422 kode lebih dari 10 karakter, 409 kode sudah terdaftar |
| PUT | `/prodi?id={id}` | 200 diperbarui | 400, 404, 422, 409 |
| DELETE | `/prodi?id={id}` | 200 dihapus | 404, 409 prodi masih dipakai mahasiswa/mata kuliah |

---

## 7. Dokumentasi di Postman

Collection **API Sistem Informasi Akademik (MVC)** menyimpan deskripsi (nama Controller dan method)
serta contoh response (Examples) pada setiap request. Dokumentasi web dapat dibuka melalui
tombol *View Documentation* pada Collection.
