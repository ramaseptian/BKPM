<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program Studi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?= base_url('assets/css/app.css') ?>" rel="stylesheet">
</head>
<body>
    <div class="app-wrapper">
        <?php $active = 'prodi'; require __DIR__ . '/../partials/sidebar.php'; ?>
        <div class="main-content">
            <div class="topbar">
                <div>
                    <h2>Program Studi</h2>
                    <div class="subtitle">Kelola data program studi</div>
                </div>
                <div class="user-chip">
                    <i class="bi bi-person-circle"></i>
                    <span>Selamat datang, <strong><?= htmlspecialchars($_SESSION['username'] ?? '') ?></strong></span>
                </div>
            </div>

            <?php if (!empty($flash)) : ?>
                <div class="<?= $flash['type'] === 'error' ? 'form-alert-error' : 'form-alert-success' ?>" data-flash>
                    <i class="bi <?= $flash['type'] === 'error' ? 'bi-exclamation-triangle-fill' : 'bi-check-circle-fill' ?>"></i>
                    <?= htmlspecialchars($flash['message']) ?>
                </div>
            <?php endif; ?>

            <div class="content-card">
                <div class="content-card-header">
                    <h5>Daftar Program Studi</h5>
                    <span class="badge-prodi"><?= count($prodiList) ?> data</span>
                </div>
                <div class="toolbar" style="justify-content:flex-end;">
                    <a href="<?= base_url('prodi/create') ?>" class="btn-primary-action">
                        <i class="bi bi-plus-lg"></i> Tambah Prodi
                    </a>
                </div>
                <div class="content-card-body">
                    <?php if (empty($prodiList)) : ?>
                        <div class="empty-state">Belum ada data program studi.</div>
                    <?php else : ?>
                    <table class="table-modern">
                        <thead>
                            <tr><th>Kode</th><th>Nama Program Studi</th><th style="text-align:center;">Aksi</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($prodiList as $p) : ?>
                            <tr>
                                <td><span class="badge-prodi"><?= htmlspecialchars($p['kode']) ?></span></td>
                                <td><?= htmlspecialchars($p['nama']) ?></td>
                                <td style="text-align:center; white-space:nowrap;">
                                    <a href="<?= base_url('prodi/edit?id=' . $p['id']) ?>" class="btn-icon edit" title="Ubah"><i class="bi bi-pencil-fill"></i></a>
                                    <form action="<?= base_url('prodi/destroy') ?>" method="POST" style="display:inline;"
                                          data-confirm-delete="Yakin ingin menghapus prodi <?= htmlspecialchars($p['nama']) ?>?">
                                        <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                        <button type="submit" class="btn-icon delete" title="Hapus"><i class="bi bi-trash-fill"></i></button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('assets/js/app.js') ?>"></script>
</body>
</html>
