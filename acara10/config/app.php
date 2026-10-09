<?php
// config/app.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
define('BASE_URL', rtrim($scriptDir, '/'));

function base_url(string $path = ''): string
{
    $path = ltrim($path, '/');
    return BASE_URL . '/' . $path;
}

function redirect(string $path): void
{
    header('Location: ' . base_url($path));
    exit;
}

// ---- Flash message (Tugas Mandiri Acara 6) ----
// Pesan yang disimpan sebentar di session, ditampilkan SATU KALI saja
// pada halaman berikutnya, lalu otomatis dihapus dari session.
function flash_set(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function flash_get(): ?array
{
    if (empty($_SESSION['flash'])) {
        return null;
    }
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}
