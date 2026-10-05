<?php
// File: info.php
// Ganti nilai berikut dengan identitas Anda
$nama = "Samsul";
$nim  = "E41250282";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Info Server</title>
    <style>
        body  { font-family: Arial, sans-serif; margin: 40px; }
        table { border-collapse: collapse; width: 480px; }
        th, td { border: 1px solid #999; padding: 8px 12px; text-align: left; }
        th    { background: #f0f0f0; width: 40%; }
    </style>
</head>
<body>
    <h1>Informasi Server</h1>
    <table>
        <tr><th>Nama</th><td><?php echo $nama; ?></td></tr>
        <tr><th>NIM</th><td><?php echo $nim; ?></td></tr>
        <tr><th>Waktu Server</th><td><?php echo date("Y-m-d H:i:s"); ?></td></tr>
        <tr><th>Versi PHP</th><td><?php echo phpversion(); ?></td></tr>
        <tr><th>Sistem Operasi Server</th><td><?php echo PHP_OS; ?></td></tr>
    </table>
</body>
</html>
