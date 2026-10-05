<?php // app/views/mahasiswa/detail.php — menerima variabel $mhs (array atau null) dari Controller ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Detail Mahasiswa</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root { --warna-utama: #3d8bdb; }
    body {
      font-family: "Plus Jakarta Sans", "Segoe UI", system-ui, -apple-system, sans-serif;
      font-size: 0.8rem;
      min-height: 100vh;
      background: linear-gradient(135deg, #e6eefb 0%, #a8c8ff 100%);
      background-attachment: fixed;
    }
    .judul-instansi { font-size: 1.9rem; font-weight: 800; color: #1f2937; }
    .nav-pills {
      --bs-nav-link-color: #212529;
      --bs-nav-link-hover-color: var(--warna-utama);
      --bs-nav-pills-link-active-bg: var(--warna-utama);
    }
    .nav-pills .nav-link { font-size: 0.75rem; padding: 0.3rem 0.65rem; }
    .card { border: 0; border-radius: 0.4rem; overflow: hidden; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15); }
    .card-header { background-color: var(--warna-utama); color: #fff; text-align: center; font-size: 1rem; font-weight: 600; border: 0; }
    .table { font-size: 0.75rem; margin-bottom: 0; }
    .table > :not(caption) > * > * { padding: 0.5rem 0.6rem; }
    .btn-sm { --bs-btn-font-size: 0.7rem; --bs-btn-padding-y: 0.15rem; --bs-btn-padding-x: 0.45rem; }
  </style>
</head>
<body>
  <div class="container py-4">

    <h3 class="judul-instansi mb-2">Politeknik Negeri Jember</h3>

    <ul class="nav nav-pills mb-2">
      <li class="nav-item">
        <a class="nav-link active" href="index.php?url=mahasiswa/index">Data Mahasiswa</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="index.php?url=dosen/index">Data Dosen</a>
      </li>
    </ul>

    <div class="row">
      <div class="col-md-6 col-lg-5">
        <div class="card">
          <div class="card-header">Detail Mahasiswa</div>

          <?php if ($mhs !== null): ?>
            <div class="card-body">
              <table class="table table-borderless mb-0">
                <tr>
                  <th style="width: 22%">NIM</th>
                  <td>: <?= htmlspecialchars($mhs['nim']) ?></td>
                </tr>
                <tr>
                  <th>Nama</th>
                  <td>: <?= htmlspecialchars($mhs['nama']) ?></td>
                </tr>
                <tr>
                  <th>Prodi</th>
                  <td>: <?= htmlspecialchars($mhs['prodi']) ?></td>
                </tr>
              </table>
            </div>
          <?php else: ?>
            <div class="card-body">
              <div class="alert alert-warning mb-0">Data mahasiswa tidak ditemukan.</div>
            </div>
          <?php endif; ?>

          <div class="card-footer bg-white">
            <a href="index.php?url=mahasiswa/index" class="btn btn-secondary btn-sm">&larr; Kembali</a>
          </div>
        </div>
      </div>
    </div>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
