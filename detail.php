<?php
session_start();
include 'config/koneksi.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id_obat = (int) $_GET['id'];
$q = mysqli_query($koneksi, "SELECT data_obat.*, kategori_obat.nama_kategori 
                                FROM data_obat 
                                LEFT JOIN kategori_obat ON data_obat.id_kategori = kategori_obat.id_kategori 
                                WHERE data_obat.id_obat = $id_obat");

if (mysqli_num_rows($q) == 0) {
    echo "<div class='p-12 text-center'><h1 class='text-4xl font-black'>Produk tidak ditemukan.</h1><a href='index.php' class='text-blue-500 underline'>Kembali</a></div>";
    exit();
}

$obat = mysqli_fetch_assoc($q);
require_once 'templates/header.php';
?>

<div class="hero-block detail-header">
    <div class="container">
        <a href="index.php" class="btn-neo btn-neo-sm detail-back-button">
            <i class="fa-solid fa-arrow-left"></i> Katalog Obat
        </a>
    </div>
</div>

<section class="section-py bg-white min-h-screen-70">
    <div class="container detail-container">
        <div class="detail-layout-container">

            <!-- Product Image -->
            <?php $img_src = ($obat['gambar'] && $obat['gambar'] !== 'default.jpg') ? 'assets/img/' . $obat['gambar'] : null; ?>
            <div class="detail-image-side">
                <div class="detail-image-box">
                    <div class="badge-neo bg-yellow product-card-badge detail-image-badge">
                        <?= htmlspecialchars($obat['nama_kategori']) ?>
                    </div>
                    <?php if ($img_src): ?>
                        <img src="/Apotik/<?= $img_src ?>" alt="<?= htmlspecialchars($obat['nama_obat']) ?>" class="product-card-image detail-image">
                    <?php else: ?>
                        <i class="fa-solid fa-pills text-gray-300 detail-image-icon"></i>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Product Info -->
            <div class="detail-info-side">
                <h1 class="font-black uppercase mb-6 detail-title">
                    <?= htmlspecialchars($obat['nama_obat']) ?>
                </h1>

                <div class="detail-price-box">
                    <span class="font-black detail-price-text">Rp <?= number_format($obat['harga'], 0, ',', '.') ?></span>
                </div>

                <div class="detail-spec-list">
                    <div class="detail-spec-item">
                        <div class="detail-spec-icon-box bg-green">
                            <i class="fa-solid fa-boxes-stacked"></i>
                        </div>
                        <div>
                            <p class="detail-spec-label">Stok Tersedia</p>
                            <p class="font-black detail-spec-value"><?= $obat['stok'] ?> Pcs</p>
                        </div>
                    </div>
                    <div class="detail-spec-item">
                        <div class="detail-spec-icon-box bg-yellow">
                            <i class="fa-solid fa-calendar-xmark"></i>
                        </div>
                        <div>
                            <p class="detail-spec-label">Tanggal Kadaluarsa</p>
                            <p class="font-black detail-spec-value"><?= date('d M Y', strtotime($obat['tanggal_kadaluarsa'])) ?></p>
                        </div>
                    </div>
                    <div style="padding-top: 1rem;">
                        <p class="detail-spec-label">Deskripsi Produk</p>
                        <p class="neo-box detail-desc-box">
                            <?= nl2br(htmlspecialchars($obat['deskripsi'] ?? 'Tidak ada deskripsi lengkap untuk produk ini.')) ?>
                        </p>
                    </div>
                </div>

                <div class="detail-action-container">
                    <?php if ($obat['stok'] > 0): ?>
                        <?php if (isset($_SESSION['id_user'])): ?>
                            <form action="user/cart_action.php" method="POST">
                                <input type="hidden" name="action" value="add">
                                <input type="hidden" name="id_obat" value="<?= $obat['id_obat'] ?>">
                                <button type="submit" class="btn-neo btn-neo-lg bg-blue detail-action-button" style="color: var(--white);">
                                    Masukkan ke Keranjang <i class="fa-solid fa-cart-arrow-down"></i>
                                </button>
                            </form>
                        <?php else: ?>
                            <a href="auth/login.php" class="btn-neo btn-neo-lg bg-yellow detail-action-button" style="color: var(--black); text-align: center; display: block;">
                                Login untuk Membeli <i class="fa-solid fa-arrow-right" style="margin-left: 0.5rem;"></i>
                            </a>
                        <?php endif; ?>
                    <?php else: ?>
                        <button disabled class="btn-disabled detail-action-button">
                            Stok Habis <i class="fa-solid fa-ban"></i>
                        </button>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</section>
<?php require_once 'templates/footer.php'; ?>