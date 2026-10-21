<?php
// app/Views/errors/500.php
// Halaman error yang AMAN untuk pengguna (BKPM Acara 14, langkah 6 dan 10):
// tidak menampilkan pesan database, nama tabel, path file, atau stack trace.
// Detail kesalahan hanya tercatat di storage/logs/app.log.
$home = function_exists('base_url') ? base_url('dashboard') : '/';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terjadi Kesalahan</title>
    <style>
        body { min-height: 100vh; margin: 0; display: flex; align-items: center; justify-content: center;
               font-family: 'Segoe UI', system-ui, sans-serif; background: #eef2f9; color: #111827; }
        .box { width: 420px; max-width: 90%; background: #fff; border-radius: 14px; padding: 36px 32px;
               text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
        .code { font-size: 3rem; font-weight: 700; color: #ef4444; margin: 0; }
        h1 { font-size: 1.25rem; margin: 8px 0 12px; }
        p { color: #6b7280; font-size: 0.92rem; line-height: 1.5; margin: 0 0 22px; }
        a { display: inline-block; background: #3b82f6; color: #fff; text-decoration: none;
            padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: 0.9rem; }
    </style>
</head>
<body>
    <div class="box">
        <p class="code">500</p>
        <h1>Terjadi kesalahan pada server</h1>
        <p>Maaf, permintaan Anda belum dapat diproses. Kesalahan ini sudah dicatat.
           Silakan coba lagi beberapa saat lagi.</p>
        <a href="<?= htmlspecialchars($home) ?>">Kembali ke Dashboard</a>
    </div>
</body>
</html>
