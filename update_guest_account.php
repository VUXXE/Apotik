<?php
require 'config/koneksi.php';

echo "<h2>Apotik Neo - Guest Account Injector</h2>";

// Cek apakah akun tamu sudah ada
$q = mysqli_query($koneksi, "SELECT * FROM users WHERE username = 'guest_user'");
if (mysqli_num_rows($q) > 0) {
    echo "<strong style='color: blue;'>Akun Guest Virtual sudah terdaftar di sistem. Anda aman!</strong><br><br>";
} else {
    // Generate random un-guessable password
    $random_password = password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT);
    
    $insert = "INSERT INTO users (nama_lengkap, username, password, role) VALUES ('Guest Pembeli', 'guest_user', '$random_password', 'customer')";
    if (mysqli_query($koneksi, $insert)) {
        echo "<strong style='color: green;'>Sukses! Akun Guest Virtual berhasil ditanamkan ke database. Guest Checkout kini bisa digunakan.</strong><br><br>";
    } else {
        echo "<strong style='color: red;'>Gagal menambahkan akun guest: " . mysqli_error($koneksi) . "</strong><br><br>";
    }
}

echo "<a href='index.php'>Kembali ke Beranda</a>";
?>
