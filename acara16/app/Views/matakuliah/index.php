<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mata Kuliah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?= base_url('assets/css/app.css') ?>" rel="stylesheet">
</head>
<body>
    <div class="app-wrapper">
        <?php $active = 'matakuliah'; require __DIR__ . '/../partials/sidebar.php'; ?>
        <div class="main-content">
            <div class="topbar">
                <div>
                    <h2>Mata Kuliah</h2>
                    <div class="subtitle">Kelola data mata kuliah</div>
                </div>
                <div class="user-chip">
                    <i class="bi bi-person-circle"></i>
                    <span>Selamat datang, <strong><?= htmlspecialchars($_SESSION['username'] ?? '') ?></strong></span>
                </div>
            </div>

            <?php if (!empty($flash)) : ?>
                <div class="form-alert-success" data-flash>
                    <i class="bi bi-check-circle-fill"></i>
                    <?= htmlspecialchars($flash['message']) ?>
                </div>
            <?php endif; ?>

            <div class="content-card">
                <div class="content-card-header">
                    <h5>Daftar Mata Kuliah</h5>
                    <span class="badge-prodi"><?= count($matakuliahList) ?> data</span>
                </div>
                <div class="toolbar">
                    <form method="GET" action="<?= base_url('matakuliah') ?>" class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" name="q" placeholder="Cari kode atau nama..." value="<?= htmlspecialchars($keyword ?? '') ?>">
                    </form>
                    <a href="<?= base_url('matakuliah/create') ?>" class="btn-primary-action">
                        <i class="bi bi-plus-lg"></i> Tambah Mata Kuliah
                    </a>
                </div>
                <div class="content-card-body">
                    <?php if (empty($matakuliahList)) : ?>
                        <div class="empty-state">Belum ada data mata kuliah yang cocok.</div>
                    <?php else : ?>
                    <table class="table-modern">
                        <thead>
                            <tr><th>Kode</th><th>Nama Mata Kuliah</th><th>SKS</th><th>Prodi</th><th style="text-align:center;">Aksi</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($matakuliahList as $mk) : ?>
                            <tr>
                                <td><?= htmlspecialchars($mk['kode']) ?></td>
                                <td><?= htmlspecialchars($mk['nama']) ?></td>
                                <td><?= (int) $mk['sks'] ?></td>
                                <td><span class="badge-prodi"><?= htmlspecialchars($mk['prodi_nama']) ?></span></td>
                                <td style="text-align:center; white-space:nowrap;">
                                    <a href="<?= base_url('matakuliah/edit?id=' . $mk['id']) ?>" class="btn-icon edit" title="Ubah"><i class="bi bi-pencil-fill"></i></a>
                                    <form action="<?= base_url('matakuliah/destroy') ?>" method="POST" style="display:inline;"
                                          data-confirm-delete="Yakin ingin menghapus mata kuliah <?= htmlspecialchars($mk['nama']) ?>?">
                                        <input type="hidden" name="id" value="<?= $mk['id'] ?>">
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
