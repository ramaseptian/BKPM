<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Daftar Mahasiswa</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <nav class="navbar navbar-dark bg-dark">
    <div class="container">
      <span class="navbar-brand mb-0 h1">SI Akademik</span>
    </div>
  </nav>

  <div class="container mt-4">
    <h1 class="mb-4">Daftar Mahasiswa</h1>
    <a href="create.php" class="btn btn-primary mb-3">Tambah Mahasiswa</a>

    <div class="table-responsive">
      <table class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>NIM</th>
            <th>Nama</th>
            <th>Prodi</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <!-- Data akan diisi dari Controller (pertemuan berikutnya).
               Baris di bawah hanya contoh statis untuk tampilan. -->
          <tr>
            <td>E41230001</td>
            <td>Contoh Mahasiswa</td>
            <td>Teknik Informatika</td>
            <td>
              <a href="#" class="btn btn-sm btn-warning">Edit</a>
              <a href="#" class="btn btn-sm btn-danger">Hapus</a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
