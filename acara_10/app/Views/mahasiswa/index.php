<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title) ?></title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f6fa;
            margin: 0;
            color: #172033;
        }

        .wrap {
            max-width: 1050px;
            margin: 40px auto;
            padding: 0 18px;
        }

        .card {
            background: #fff;
            border-radius: 14px;
            padding: 24px;
            box-shadow: 0 8px 25px #0001;
        }

        .top {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        h1 {
            margin: 0 0 6px;
        }

        .btn {
            display: inline-block;
            padding: 9px 14px;
            border-radius: 8px;
            background: #1f6feb;
            color: #fff;
            text-decoration: none;
            border: 0;
            cursor: pointer;
        }

        .danger {
            background: #d92d20;
        }

        .edit {
            background: #475467;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 18px;
        }

        th,
        td {
            padding: 12px;
            border-bottom: 1px solid #eaecf0;
            text-align: left;
        }

        th {
            background: #f8fafc;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 99px;
            background: #e7f6ec;
            color: #18794e;
            font-size: 12px;
        }
    </style>
</head>

<body>

    <div class="wrap">
        <div class="card">

            <div class="top">
                <div>
                    <h1>Data Mahasiswa</h1>
                    <p>Inheritance + Repository Pattern</p>
                </div>

                <a class="btn" href="?page=mahasiswa&action=create">
                    + Tambah Data
                </a>
            </div>

            <table>
                <tr>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Prodi</th>
                    <th>Angkatan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>

                <?php foreach ($mahasiswa as $m): ?>
                    <tr>
                        <td><?= htmlspecialchars($m['nim']) ?></td>

                        <td><?= htmlspecialchars($m['nama']) ?></td>

                        <td>
                            <?= htmlspecialchars($m['prodi_nama']) ?>
                        </td>

                        <td><?= $m['angkatan'] ?></td>

                        <td>
                            <span class="badge">
                                <?= htmlspecialchars($m['status']) ?>
                            </span>
                        </td>

                        <td>
                            <a
                                class="btn edit"
                                href="?page=mahasiswa&action=edit&id=<?= $m['id'] ?>">
                                Ubah
                            </a>

                            <a
                                class="btn danger"
                                href="?page=mahasiswa&action=delete&id=<?= $m['id'] ?>"
                                onclick="return confirm('Hapus data ini?')">
                                Hapus
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>

            </table>

        </div>
    </div>

</body>

</html>