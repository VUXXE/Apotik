<?php
session_start();

// Deteksi lingkungan secara otomatis (Localhost vs Production Hosting)
$is_local = in_array($_SERVER['HTTP_HOST'] ?? 'localhost', ['localhost', '127.0.0.1']) || strpos($_SERVER['HTTP_HOST'] ?? 'localhost', '.local') !== false;

if ($is_local) {
    // Pengaturan database Lokal (XAMPP)
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db   = "db_apotek";
} else {
    // Pengaturan database Production (InfinityFree)
    $host = "sql301.infinityfree.com";
    $user = "if0_42356381";
    $pass = "8fFAG4YnAp";
    $db   = "if0_42356381_db_apotek";
}

// Membuat koneksi
$koneksi = mysqli_connect($host, $user, $pass, $db);

// Mengecek koneksi
if (!$koneksi) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>
