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
            max-width: 650px;
            margin: 40px auto;
            padding: 0 18px;
        }

        .card {
            background: #fff;
            border-radius: 14px;
            padding: 25px;
            box-shadow: 0 8px 25px #0001;
        }

        label {
            display: block;
            margin-top: 14px;
            font-weight: 600;
        }

        input,
        select {
            width: 100%;
            box-sizing: border-box;
            padding: 10px;
            margin-top: 6px;
            border: 1px solid #d0d5dd;
            border-radius: 8px;
        }

        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            border: 0;
            border-radius: 8px;
            background: #1f6feb;
            color: #fff;
            text-decoration: none;
            cursor: pointer;
        }

        .back {
            background: #667085;
        }
    </style>
</head>

<body>

    <div class="wrap">
        <div class="card">

            <h1><?= htmlspecialchars($title) ?></h1>

            <?php
            $edit = !empty($data);
            ?>

            <form
                method="post"
                action="?page=mahasiswa&action=<?= $edit
                    ? 'update&id=' . $data['id']
                    : 'store' ?>"
            >

                <label>NIM</label>
                <input
                    type="text"
                    name="nim"
                    required
                    value="<?= htmlspecialchars($data['nim'] ?? '') ?>"
                >

                <label>Nama</label>
                <input
                    type="text"
                    name="nama"
                    required
                    value="<?= htmlspecialchars($data['nama'] ?? '') ?>"
                >

                <label>Program Studi</label>
                <select name="prodi_id">

                    <?php foreach ($prodi as $p): ?>

                        <option
                            value="<?= $p['id'] ?>"
                            <?= (($data['prodi_id'] ?? '') == $p['id'])
                                ? 'selected'
                                : '' ?>
                        >
                            <?= htmlspecialchars($p['nama']) ?>
                        </option>

                    <?php endforeach; ?>

                </select>

                <label>Angkatan</label>
                <input
                    type="number"
                    name="angkatan"
                    required
                    value="<?= htmlspecialchars(
                        $data['angkatan'] ?? date('Y')
                    ) ?>"
                >

                <label>Status</label>
                <select name="status">

                    <?php foreach (['aktif', 'cuti', 'lulus'] as $s): ?>

                        <option
                            value="<?= $s ?>"
                            <?= (($data['status'] ?? 'aktif') === $s)
                                ? 'selected'
                                : '' ?>
                        >
                            <?= $s ?>
                        </option>

                    <?php endforeach; ?>

                </select>

                <button class="btn" type="submit">
                    Simpan
                </button>

                <a
                    class="btn back"
                    href="?page=mahasiswa"
                >
                    Kembali
                </a>

            </form>

        </div>
    </div>

</body>

</html>