<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?= base_url('assets/css/app.css') ?>" rel="stylesheet">
</head>
<body>

    <div class="app-wrapper">
        <?php $active = 'mahasiswa'; require __DIR__ . '/../partials/sidebar.php'; ?>

        <div class="main-content">
            <div class="topbar">
                <div>
                    <h2>Data Mahasiswa</h2>
                    <div class="subtitle">Daftar mahasiswa yang terdaftar</div>
                </div>
                <div>
                </div>
            </div>

            <div class="content-card">
                <div class="content-card-header">
                    <h5>Daftar Mahasiswa</h5>
                    <span class="badge-prodi"><?= count($mahasiswaList ?? []) ?> data</span>
                </div>
                <div class="content-card-body">
                    <table class="table-modern">
                        <thead>
                            <tr>
                                <th>NIM</th>
                                <th>Nama</th>
                                <th>Prodi</th>
                                <th>Angkatan</th>
                                <th>Status</th>
                                <th style="text-align:center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($mahasiswaList ?? [] as $mhs) : ?>
                                <tr>
                                    <td><?= htmlspecialchars($mhs['nim']) ?></td>
                                    <td><?= htmlspecialchars($mhs['nama']) ?></td>
                                    <td>
                                        <span class="badge-prodi">
                                            <?= htmlspecialchars($prodiMap[$mhs['prodi_id']] ?? '-') ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($mhs['angkatan']) ?></td>
                                    <td>
                                        <span class="badge-status badge-status-<?= htmlspecialchars($mhs['status']) ?>">
                                            <?= htmlspecialchars(ucfirst($mhs['status'])) ?>
                                        </span>
                                    </td>
                                    <td style="text-align:center;">
                                        <a href="<?= base_url('mahasiswa/detail?nim=' . urlencode($mhs['nim'])) ?>" class="btn-action">
                                            <i class="bi bi-eye-fill"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
