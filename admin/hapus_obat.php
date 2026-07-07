<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$id_obat = (int)$_GET['id'];
mysqli_query($koneksi, "DELETE FROM data_obat WHERE id_obat = $id_obat");
echo "<script>alert('Obat berhasil dihapus!'); window.location='admin_dashboard.php';</script>";
?>
