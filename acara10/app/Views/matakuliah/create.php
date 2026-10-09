<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Mata Kuliah</title>
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
                    <h2>Tambah Mata Kuliah</h2>
                    <div class="subtitle">Isi form berikut untuk menambah mata kuliah</div>
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
                <form method="POST" action="<?= base_url('matakuliah/store') ?>">
                    <div class="form-group">
                        <label>Kode Mata Kuliah</label>
                        <input type="text" name="kode" value="<?= htmlspecialchars('') ?>" required autofocus>
                    </div>
                    <div class="form-group">
                        <label>Nama Mata Kuliah</label>
                        <input type="text" name="nama" value="<?= htmlspecialchars('') ?>" required>
                    </div>
                    <div class="form-group">
                        <label>SKS</label>
                        <input type="number" name="sks" value="<?= htmlspecialchars('') ?>" min="1" max="10" required>
                    </div>
                    <div class="form-group">
                        <label>Program Studi</label>
                        <select name="prodi_id" required>
                            <option value="">-- Pilih Prodi --</option>
                            <?php foreach ($prodiList as $prodi) : ?>
                                <option value="<?= $prodi['id'] ?>" <?= ('' == $prodi['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($prodi['nama']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn-primary-action"><i class="bi bi-save-fill"></i> Simpan</button>
                        <a href="<?= base_url('matakuliah') ?>" class="btn-back"><i class="bi bi-arrow-left"></i> Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('assets/js/app.js') ?>"></script>
</body>
</html>
