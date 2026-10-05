<!-- app/Views/mahasiswa/index.php -->
<h1 class="mb-4">Daftar Mahasiswa</h1>
<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>NIM</th>
            <th>Nama</th>
            <th>Prodi</th>
            <th>Angkatan</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($daftarMahasiswa as $i => $mhs): ?>
        <tr>
            <td><?php echo $i + 1; ?></td>
            <td><?php echo htmlspecialchars($mhs->getNim()); ?></td>
            <td><?php echo htmlspecialchars($mhs->getNama()); ?></td>
            <td><?php echo htmlspecialchars($mhs->getProdi()); ?></td>
            <td><?php echo htmlspecialchars($mhs->getAngkatan()); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>