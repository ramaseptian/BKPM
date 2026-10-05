<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Mahasiswa</title>
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
                    <h2>Detail Mahasiswa</h2>
                    <div class="subtitle">Informasi lengkap data mahasiswa</div>
                </div>
                <div>
                </div>
            </div>

            <div class="content-card" style="max-width: 520px;">
                <div class="content-card-header">
                    <h5><i class="bi bi-person-badge me-2"></i>Profil Mahasiswa</h5>
                </div>
                <div class="content-card-body p-4">
                    <div class="detail-row">
                        <div class="label">NIM</div>
                        <div class="value"><?= htmlspecialchars($mhs['nim']) ?></div>
                    </div>
                    <div class="detail-row">
                        <div class="label">Nama</div>
                        <div class="value"><?= htmlspecialchars($mhs['nama']) ?></div>
                    </div>
                    <div class="detail-row">
                        <div class="label">Program Studi</div>
                        <div class="value">
                            <span class="badge-prodi"><?= htmlspecialchars($prodiMap[$mhs['prodi_id']] ?? '-') ?></span>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="label">Angkatan</div>
                        <div class="value"><?= htmlspecialchars($mhs['angkatan']) ?></div>
                    </div>
                    <div class="detail-row">
                        <div class="label">Status</div>
                        <div class="value">
                            <span class="badge-status badge-status-<?= htmlspecialchars($mhs['status']) ?>">
                                <?= htmlspecialchars(ucfirst($mhs['status'])) ?>
                            </span>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="<?= base_url('mahasiswa') ?>" class="btn-back">
                            <i class="bi bi-arrow-left"></i> Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
