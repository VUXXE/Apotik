<?php
require 'config/koneksi.php';

echo "<h2>Apotik Neo - Database Updater (Deskripsi)</h2>";

$check_column = mysqli_query($koneksi, "SHOW COLUMNS FROM data_obat LIKE 'deskripsi'");
if (mysqli_num_rows($check_column) == 0) {
    echo "Kolom 'deskripsi' tidak ditemukan. Menambahkan kolom 'deskripsi' ke tabel data_obat...<br>";
    if (mysqli_query($koneksi, "ALTER TABLE data_obat ADD COLUMN deskripsi TEXT")) {
        echo "<strong style='color: green;'>Kolom 'deskripsi' berhasil ditambahkan!</strong><br><br>";
    } else {
        echo "<strong style='color: red;'>Gagal menambahkan kolom: " . mysqli_error($koneksi) . "</strong><br><br>";
    }
} else {
    echo "<strong style='color: blue;'>Kolom 'deskripsi' sudah ada di database Anda. Aman!</strong><br><br>";
}

echo "<a href='index.php'>Kembali ke Beranda</a>";
?>
