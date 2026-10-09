<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Program Studi</title>
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
                    <h2>Tambah Program Studi</h2>
                    <div class="subtitle">Isi form berikut untuk menambah program studi</div>
                </div>
                <div class="user-chip">
                    <i class="bi bi-person-circle"></i>
                    <span>Selamat datang, <strong><?= htmlspecialchars($_SESSION['username'] ?? '') ?></strong></span>
                </div>
            </div>

            <div class="form-card">
                <?php if (!empty($error)) : ?>
                    <div class="form-alert-error"><i class="bi bi-exclamation-triangle-fill me-1"></i><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <form method="POST" action="<?= base_url('prodi/store') ?>">
                    <div class="form-group">
                        <label>Kode Prodi</label>
                        <input type="text" name="kode" value="<?= htmlspecialchars('') ?>" required autofocus>
                    </div>
                    <div class="form-group">
                        <label>Nama Program Studi</label>
                        <input type="text" name="nama" value="<?= htmlspecialchars('') ?>" required>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn-primary-action"><i class="bi bi-save-fill"></i> Simpan</button>
                        <a href="<?= base_url('prodi') ?>" class="btn-back"><i class="bi bi-arrow-left"></i> Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('assets/js/app.js') ?>"></script>
</body>
</html>
