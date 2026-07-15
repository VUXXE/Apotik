<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'config/koneksi.php';

try {
    $nama_lengkap = 'Test User';
    $username = 'test' . rand(1, 1000);
    $password = password_hash('password', PASSWORD_DEFAULT);
    
    $query = "INSERT INTO users (nama_lengkap, username, password, role) VALUES ('$nama_lengkap', '$username', '$password', 'user')";
    if (mysqli_query($koneksi, $query)) {
        echo "Success";
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage();
}
