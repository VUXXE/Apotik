<?php
require 'config/koneksi.php';

$check_column = mysqli_query($koneksi, "SHOW COLUMNS FROM data_obat LIKE 'gambar'");
if (mysqli_num_rows($check_column) == 0) {
    echo "Kolom 'gambar' tidak ditemukan. Menambahkan kolom 'gambar' ke tabel data_obat...<br>";
    mysqli_query($koneksi, "ALTER TABLE data_obat ADD COLUMN gambar VARCHAR(255) DEFAULT 'default.jpg'");
    echo "Kolom 'gambar' berhasil ditambahkan!<br><br>";
}

$updates = [
    1 => 'obat_paracetamol_1782949861671.png',
    2 => 'obat_amoxicillin_1782949878042.png',
    3 => 'obat_vitamin_1782949891101.png',
    4 => 'obat_sirup_1782949904210.png',
    5 => 'obat_paracetamol_1782949861671.png',
    6 => 'obat_amoxicillin_1782949878042.png',
    7 => 'obat_vitamin_1782949891101.png',
    8 => 'obat_sirup_1782949904210.png',
];

echo "<h3>Memperbarui Gambar di Database...</h3>";
foreach ($updates as $id => $img) {
    mysqli_query($koneksi, "UPDATE data_obat SET gambar = '$img' WHERE id_obat = $id");
    echo "Obat ID $id diperbarui dengan gambar: $img <br>";
}
echo "<br><b>Selesai!</b> Silakan kembali ke halaman utama.";
?>
