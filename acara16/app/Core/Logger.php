<?php
// app/Core/Logger.php
// Logging sederhana (BKPM Acara 14, langkah 6). Setiap kejadian penting atau
// error dicatat ke storage/logs/app.log dengan format:
//   2026-10-08 10:30:15 - ERROR - Konteks | NamaException: pesan (File.php:baris)
//
// Aturan keamanan:
// - Detail error HANYA masuk ke log, tidak pernah ditampilkan ke pengguna.
// - Stack trace TIDAK dicatat, karena trace PHP dapat memuat argumen fungsi
//   (misalnya password database pada new PDO(...)). Hanya kelas, pesan, nama
//   file, dan nomor baris yang ditulis.
// - Karakter baris baru dibuang dari pesan agar masukan pengguna tidak dapat
//   menyisipkan baris palsu ke dalam log (log injection).

final class Logger
{
    public static function path(): string
    {
        return __DIR__ . '/../../storage/logs/app.log';
    }

    public static function error(string $message, ?Throwable $e = null): void
    {
        self::write('ERROR', $message, $e);
    }

    public static function warning(string $message, ?Throwable $e = null): void
    {
        self::write('WARNING', $message, $e);
    }

    public static function info(string $message): void
    {
        self::write('INFO', $message, null);
    }

    private static function write(string $level, string $message, ?Throwable $e): void
    {
        $line = date('Y-m-d H:i:s') . ' - ' . $level . ' - ' . $message;

        if ($e !== null) {
            $line .= ' | ' . get_class($e) . ': ' . $e->getMessage()
                   . ' (' . basename($e->getFile()) . ':' . $e->getLine() . ')';
        }

        $line = str_replace(["\r", "\n"], ' ', $line) . PHP_EOL;

        $dir = dirname(self::path());
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        // Tipe 3 = tambahkan (append) ke file yang ditentukan.
        error_log($line, 3, self::path());
    }
}
