<?php
session_start();
$host = "localhost";
$user = "root";       // Username default XAMPP/MySQL
$pass = "";           // Password default XAMPP (kosong)
$db   = "db_apotek";  // Nama database

// Membuat koneksi
$koneksi = mysqli_connect($host, $user, $pass, $db);

// Mengecek koneksi
if (!$koneksi) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>
