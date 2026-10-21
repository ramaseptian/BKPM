<?php
// Konfigurasi aplikasi.

// Mode aplikasi: 'development' (pengembangan) atau 'production'.
// JSON dibuat rapi (pretty print) hanya pada mode development.
define('APP_ENV', 'development');

// Panjang NIM yang dianggap valid. Data pada database acara_7 memakai
// NIM 7 digit (contoh: 2401001).
define('NIM_LENGTH', 7);
