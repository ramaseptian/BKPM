-- Menjadikan satu pengguna sebagai admin (BKPM Acara 25).
-- Tidak mengubah struktur tabel: hanya mengisi kolom role yang sudah ada sejak Acara 19.
--
-- Langkah:
--   1) Daftarkan akun lebih dulu lewat POST /register (role awal selalu "user").
--   2) Ganti GANTI_USERNAME di bawah dengan username akun itu, lalu jalankan di
--      phpMyAdmin (pilih database acara_7, tab SQL).
--   3) Login ulang. Token lama masih berisi role user, token baru berisi role admin.
UPDATE users SET role = 'admin' WHERE username = 'GANTI_USERNAME';
