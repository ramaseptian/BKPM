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
                    <div>
                    </div>
                    <div class="detail-row">
                        <div class="label">Program Studi</div>
                        <div class="value">
                            <span class="badge-prodi"><?= htmlspecialchars($mhs['prodi_nama']) ?></span>
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

                    <div class="mt-4 d-flex gap-2">
                        <a href="<?= base_url('mahasiswa') ?>" class="btn-back">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <a href="<?= base_url('mahasiswa/edit?id=' . $mhs['id']) ?>" class="btn-primary-action">
                            <i class="bi bi-pencil-fill"></i> Ubah
                        </a>
                        <form action="<?= base_url('mahasiswa/destroy') ?>" method="POST"
                              data-confirm-delete="Yakin ingin menghapus data mahasiswa <?= htmlspecialchars($mhs['nama']) ?>?">
                            <input type="hidden" name="id" value="<?= $mhs['id'] ?>">
                            <button type="submit" class="btn-icon delete" style="width:auto; padding:9px 16px;">
                                <i class="bi bi-trash-fill"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('assets/js/app.js') ?>"></script>
</body>
</html>
