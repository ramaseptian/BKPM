<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?= base_url('assets/css/app.css') ?>" rel="stylesheet">
</head>
<body>

    <div class="app-wrapper">
        <?php $active = 'dashboard'; require __DIR__ . '/../partials/sidebar.php'; ?>

        <div class="main-content">
            <div class="topbar">
                <div>
                    <h2>Dashboard</h2>
                    <div class="subtitle">Sistem Informasi Akademik Politeknik Negeri Jember</div>
                </div>
                <div class="user-chip">
                    <i class="bi bi-person-circle"></i>
                    <span>Selamat datang, <strong><?= htmlspecialchars($username) ?></strong></span>
                </div>
            </div>

            <?php if (!empty($flash)) : ?>
                <div class="alert alert-success d-flex align-items-center gap-2 mb-4" data-flash role="alert" style="border-radius:10px;">
                    <i class="bi bi-check-circle-fill"></i>
                    <div><?= htmlspecialchars($flash['message']) ?></div>
                </div>
            <?php endif; ?>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="icon-box blue"><i class="bi bi-people-fill"></i></div>
                        <div>
                            <div class="stat-number"><?= (int) $totalMahasiswa ?></div>
                            <div class="stat-label">Total Mahasiswa</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="icon-box green"><i class="bi bi-person-workspace"></i></div>
                        <div>
                            <div class="stat-number"><?= (int) $totalDosen ?></div>
                            <div class="stat-label">Total Dosen</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="icon-box orange"><i class="bi bi-calendar-check"></i></div>
                        <div>
                            <div class="stat-number">2026</div>
                            <div class="stat-label">Tahun Akademik</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-card">
                <div class="content-card-header">
                    <h5>Menu Cepat</h5>
                </div>
                <div class="content-card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <a href="<?= base_url('mahasiswa') ?>" class="text-decoration-none">
                                <div class="d-flex align-items-center gap-3 p-3 rounded-3" style="background:#f9fafb; border:1px solid #eef0f4;">
                                    <div class="icon-box blue" style="width:42px;height:42px;font-size:1.1rem;"><i class="bi bi-people-fill"></i></div>
                                    <div>
                                        <div class="fw-semibold text-dark">Data Mahasiswa</div>
                                        <div class="text-muted small">Lihat dan kelola data mahasiswa</div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="<?= base_url('dosen') ?>" class="text-decoration-none">
                                <div class="d-flex align-items-center gap-3 p-3 rounded-3" style="background:#f9fafb; border:1px solid #eef0f4;">
                                    <div class="icon-box green" style="width:42px;height:42px;font-size:1.1rem;"><i class="bi bi-person-workspace"></i></div>
                                    <div>
                                        <div class="fw-semibold text-dark">Data Dosen</div>
                                        <div class="text-muted small">Lihat dan kelola data dosen</div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('assets/js/app.js') ?>"></script>
</body>
</html>
