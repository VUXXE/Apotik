<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Deteksi lingkungan secara otomatis (Localhost vs Production Hosting)
$is_local = in_array($_SERVER['HTTP_HOST'] ?? 'localhost', ['localhost', '127.0.0.1']) || strpos($_SERVER['HTTP_HOST'] ?? 'localhost', '.local') !== false;

// Tentukan BASE_URL secara dinamis
if (!defined('BASE_URL')) {
    define('BASE_URL', $is_local ? '/Apotik' : '');
}

// Parser file .env sederhana (Tanpa dependensi luar)
$env_path = __DIR__ . '/../.env';
if (file_exists($env_path)) {
    $lines = file($env_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        // Lewati baris komentar
        if (empty($line) || strpos($line, '#') === 0) {
            continue;
        }
        // Bagi key dan value
        if (strpos($line, '=') !== false) {
            list($key, $val) = explode('=', $line, 2);
            $key = trim($key);
            $val = trim($val);
            // Bersihkan tanda kutip jika ada
            $val = trim($val, '"\'');
            $_ENV[$key] = $val;
            putenv("{$key}={$val}");
        }
    }
}

// Baca konfigurasi database dengan fallback cerdas (Lokal vs Production)
$host_default = $is_local ? 'localhost' : 'sql301.infinityfree.com';
$user_default = $is_local ? 'root' : 'if0_42356381';
$pass_default = $is_local ? '' : '8fFAG4YnAp';
$db_default   = $is_local ? 'db_apotek' : 'if0_42356381_db_apotek';

$host = $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?: $host_default;
$user = $_ENV['DB_USER'] ?? getenv('DB_USER') ?: $user_default;
$pass = $_ENV['DB_PASS'] ?? getenv('DB_PASS') ?: $pass_default;
$db   = $_ENV['DB_NAME'] ?? getenv('DB_NAME') ?: $db_default;

// Membuat koneksi
$koneksi = mysqli_connect($host, $user, $pass, $db);

// Mengecek koneksi
if (!$koneksi) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>
