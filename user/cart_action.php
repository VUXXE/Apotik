<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['id_user'])) {
    echo "<script>alert('Anda harus login terlebih dahulu!'); window.location='../auth/login.php';</script>";
    exit();
}

$action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : '');

if ($action == 'add') {
    $id_obat = (int)$_POST['id_obat'];
    
    $q = mysqli_query($koneksi, "SELECT stok FROM data_obat WHERE id_obat = $id_obat");
    $data = mysqli_fetch_assoc($q);
    
    if($data['stok'] > 0) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        if (isset($_SESSION['cart'][$id_obat])) {
            if ($_SESSION['cart'][$id_obat] < $data['stok']) {
                $_SESSION['cart'][$id_obat]++;
                echo "<script>alert('Kuantitas obat berhasil ditambah di keranjang!'); window.history.back();</script>";
            } else {
                echo "<script>alert('Stok tidak mencukupi!'); window.history.back();</script>";
            }
        } else {
            $_SESSION['cart'][$id_obat] = 1;
            echo "<script>alert('Obat berhasil dimasukkan ke keranjang!'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Stok obat habis!'); window.history.back();</script>";
    }
}
elseif ($action == 'remove') {
    $id_obat = (int)$_GET['id_obat'];
    if (isset($_SESSION['cart'][$id_obat])) {
        unset($_SESSION['cart'][$id_obat]);
    }
    header("Location: cart.php");
}
elseif ($action == 'update') {
    $id_obat = (int)$_POST['id_obat'];
    $qty = (int)$_POST['qty'];
    
    $q = mysqli_query($koneksi, "SELECT stok FROM data_obat WHERE id_obat = $id_obat");
    $data = mysqli_fetch_assoc($q);
    
    if ($qty > 0 && $qty <= $data['stok']) {
        $_SESSION['cart'][$id_obat] = $qty;
    } elseif ($qty == 0) {
        unset($_SESSION['cart'][$id_obat]);
    } else {
        echo "<script>alert('Jumlah melebihi stok yang tersedia!'); window.location='cart.php';</script>";
        exit();
    }
    header("Location: cart.php");
}
else {
    header("Location: user_home.php");
}
?>
