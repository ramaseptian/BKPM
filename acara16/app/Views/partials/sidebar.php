<?php $active = $active ?? ''; ?>
<div class="sidebar">
    <div class="sidebar-brand">
        <i class="bi bi-mortarboard-fill"></i>
        <span>SI Akademik Polije</span>
    </div>
    <nav class="sidebar-nav">
        <a href="<?= base_url('dashboard') ?>" class="<?= $active === 'dashboard' ? 'active' : '' ?>">
            <i class="bi bi-grid-1x2-fill"></i> Dashboard
        </a>
        <a href="<?= base_url('mahasiswa') ?>" class="<?= $active === 'mahasiswa' ? 'active' : '' ?>">
            <i class="bi bi-people-fill"></i> Data Mahasiswa
        </a>
        <a href="<?= base_url('prodi') ?>" class="<?= $active === 'prodi' ? 'active' : '' ?>">
            <i class="bi bi-diagram-3-fill"></i> Program Studi
        </a>
        <a href="<?= base_url('matakuliah') ?>" class="<?= $active === 'matakuliah' ? 'active' : '' ?>">
            <i class="bi bi-journal-bookmark-fill"></i> Mata Kuliah
        </a>
        <a href="<?= base_url('dosen') ?>" class="<?= $active === 'dosen' ? 'active' : '' ?>">
            <i class="bi bi-person-workspace"></i> Data Dosen
        </a>
    </nav>
    <div class="sidebar-footer">
        <a href="<?= base_url('logout') ?>" class="logout-link">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </div>
</div>
