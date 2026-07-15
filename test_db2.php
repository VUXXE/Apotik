<?php
include 'config/koneksi.php';
$res = mysqli_query($koneksi, "DESCRIBE users;");
while ($row = mysqli_fetch_assoc($res)) {
    print_r($row);
}
