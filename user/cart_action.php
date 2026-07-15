<?php
session_start();
include '../config/koneksi.php';


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
            }
        } else {
            $_SESSION['cart'][$id_obat] = 1;
        }
    }
    
    // Redirect silently back to where the user came from
    $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '../index.php';
    header("Location: " . $referer);
    exit();
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
