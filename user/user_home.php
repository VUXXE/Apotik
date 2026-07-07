<?php
session_start();
if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/login.php");
    exit();
}
include '../config/koneksi.php';
require_once '../config/functions.php';

$kategori_aktif = isset($_GET['kategori']) ? $_GET['kategori'] : '';
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

$list_kategori = get_semua_kategori($koneksi);
$list_obat = get_daftar_obat($koneksi, $kategori_aktif, $search_query);

require_once '../templates/header.php';
?>

<div class="hero-block bg-blue" style="padding-top: 3rem; padding-bottom: 3rem;">
    <div class="container text-center">
        <h1 class="heading-hero" style="color: var(--white); text-shadow: 4px 4px 0 #000; margin-bottom: 1rem;">Portal Belanja</h1>
        <p class="badge-neo bg-yellow" style="font-size: 1.25rem; padding: 0.5rem 1.5rem; display: inline-block;">Silakan pilih obat yang ingin Anda beli.</p>
    </div>
</div>

<section class="section-py bg-white min-h-screen">
    <div class="container">
        <div class="flex justify-between items-center mb-12 wrap gap-4">
            <h2 class="heading-section m-0">Katalog Obat</h2>
            
            <form method="GET" action="user_home.php" class="flex items-center gap-2">
                <select name="kategori" class="input-neo" style="width: auto; padding: 0.75rem 2.5rem 0.75rem 1rem;">
                    <option value="">Semua Kategori</option>
                    <?php foreach($list_kategori as $kat): ?>
                        <option value="<?= $kat['id_kategori'] ?>" <?= ($kategori_aktif == $kat['id_kategori']) ? 'selected' : '' ?>>
                            <?= strtoupper(htmlspecialchars($kat['nama_kategori'])) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn-neo bg-yellow">
                    FILTER
                </button>
            </form>
        </div>

        <div class="grid grid-cols-4 gap-8">
            <?php 
            if (!empty($list_obat)) {
                foreach ($list_obat as $row) {
                    include '../templates/product_card.php';
                }
            } else {
                echo '<div style="grid-column: span 4; text-align: center; padding: 3rem 0;"><p class="font-black text-2xl uppercase" style="color: var(--gray-400);">Obat tidak ditemukan</p></div>';
            }
            ?>
        </div>
    </div>
</section>

<?php require_once '../templates/footer.php'; ?>
