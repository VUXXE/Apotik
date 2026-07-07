<?php
include 'config/koneksi.php';
$res = mysqli_query($koneksi, "DESCRIBE data_obat");
while($row = mysqli_fetch_assoc($res)) {
    print_r($row);
}
?>
