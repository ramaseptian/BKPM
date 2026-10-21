<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Dosen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?= base_url('assets/css/app.css') ?>" rel="stylesheet">
</head>
<body>

    <div class="app-wrapper">
        <?php $active = 'dosen'; require __DIR__ . '/../partials/sidebar.php'; ?>

        <div class="main-content">
            <div class="topbar">
                <div>
                    <h2>Data Dosen</h2>
                    <div class="subtitle">Daftar dosen yang terdaftar</div>
                </div>
                <div class="user-chip">
                    <i class="bi bi-person-circle"></i>
                    <span>Selamat datang, <strong><?= htmlspecialchars($_SESSION['username'] ?? '') ?></strong></span>
                </div>
            </div>

            <div class="content-card">
                <div class="content-card-header">
                    <h5>Daftar Dosen</h5>
                    <span class="badge-prodi"><?= count($dosenList ?? []) ?> data</span>
                </div>
                <div class="content-card-body">
                    <table class="table-modern">
                        <thead>
                            <tr>
                                <th>NIDN</th>
                                <th>Nama</th>
                                <th>Prodi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dosenList ?? [] as $dsn) : ?>
                                <tr>
                                    <td><?= htmlspecialchars($dsn['nidn']) ?></td>
                                    <td><?= htmlspecialchars($dsn['nama']) ?></td>
                                    <td><span class="badge-prodi"><?= htmlspecialchars($dsn['prodi']) ?></span></td>
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
