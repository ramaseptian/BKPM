<?php
// Auth: pembuatan dan pemeriksaan token (format JWT, algoritma HS256) tanpa
// pustaka pihak ketiga. Memakai hash_hmac() dan base64 versi URL.
class Auth {
    // Kunci rahasia penanda tangan token. Pada sistem nyata disimpan di
    // konfigurasi/environment, bukan di kode.
    const SECRET = 'kunci_rahasia_workshop_2026';

    // Masa berlaku token dalam detik: 300 = 5 menit (Tugas Mandiri 2).
    const TOKEN_LIFETIME = 300;

    private static function base64urlEncode(string $data): string {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private static function base64urlDecode(string $data) {
        $b64  = strtr($data, '-_', '+/');
        $sisa = strlen($b64) % 4;
        if ($sisa) {
            $b64 .= str_repeat('=', 4 - $sisa);
        }
        return base64_decode($b64, true);
    }

    // Membuat token: header.payload.signature
    public static function generateToken(array $payload): string {
        $header         = self::base64urlEncode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
        $payload['exp'] = time() + self::TOKEN_LIFETIME;
        $payloadEnc     = self::base64urlEncode(json_encode($payload));
        $signature      = self::base64urlEncode(hash_hmac('sha256', "$header.$payloadEnc", self::SECRET, true));

        return "$header.$payloadEnc.$signature";
    }

    // Mengembalikan isi payload jika token asli dan belum kedaluwarsa,
    // atau false jika tidak.
    public static function validateToken(string $token) {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return false;
        }

        [$header, $payload, $signature] = $parts;

        // Tanda tangan dihitung ulang; token yang isinya diubah akan berbeda.
        $valid = self::base64urlEncode(hash_hmac('sha256', "$header.$payload", self::SECRET, true));
        if (!hash_equals($valid, $signature)) {
            return false;
        }

        $json = self::base64urlDecode($payload);
        if ($json === false) {
            return false;
        }

        $data = json_decode($json, true);
        if (!is_array($data) || !isset($data['exp']) || $data['exp'] < time()) {
            return false;
        }

        return $data;
    }

    // Middleware sederhana: membaca header "Authorization: Bearer <token>".
    // Jika token tidak ada / tidak valid / kedaluwarsa, request dihentikan (401).
    public static function requireAuth(): array {
        $headers    = function_exists('getallheaders') ? array_change_key_case(getallheaders(), CASE_LOWER) : [];
        $authHeader = $headers['authorization'] ?? ($_SERVER['HTTP_AUTHORIZATION'] ?? '');

        if (!preg_match('/^Bearer\s+(\S+)$/i', $authHeader, $m)) {
            Response::json(401, false, 'Token tidak ditemukan');
        }

        $payload = self::validateToken($m[1]);
        if (!$payload) {
            Response::json(401, false, 'Token tidak valid atau kedaluwarsa');
        }

        return $payload;
    }

    // Otorisasi berbasis role (RBAC), Acara 25: selain harus login (token sah),
    // role pada token harus "admin". Role user ditolak dengan 403 Forbidden.
    // 401 = identitas belum terbukti (belum login), 403 = sudah login tetapi tidak berhak.
    public static function requireAdmin(): array {
        $payload = self::requireAuth();

        if (($payload['role'] ?? '') !== 'admin') {
            Response::json(403, false, 'Akses ditolak, hanya admin yang diizinkan');
        }

        return $payload;
    }
}
