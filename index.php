<?php
session_start();
include 'config/koneksi.php';
require_once 'config/functions.php';

$kategori_aktif = isset($_GET['kategori']) ? $_GET['kategori'] : '';
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

$list_kategori = get_semua_kategori($koneksi);
$list_obat = get_daftar_obat($koneksi, $kategori_aktif, $search_query, 8);

require_once 'templates/header.php';
?>

<!-- Hero Section -->
<header class="hero-block hero-section">
    <div class="container grid grid-cols-2-md gap-8 items-center">
        <div class="hero-content">
            <div class="badge-neo bg-yellow hero-discount-badge">
                Diskon s/d 50% Hari Ini!
            </div>
            <h1 class="heading-hero hero-title">
                SEHAT NGGAK<br>
                <span class="hero-title-highlight">HARUS MAHAL!</span>
            </h1>
            <p class="hero-subtitle">
                Beli obat asli, dikirim secepat kilat.
            </p>
            <button class="btn-neo btn-neo-lg bg-pink hero-cta-button" onclick="document.getElementById('products').scrollIntoView({behavior: 'smooth'})">
                <i class="fa-solid fa-pills" style="margin-right: 0.5rem;"></i> Beli Obat Sekarang
            </button>
        </div>

        <div class="hero-image-container">
            <div class="neo-box bg-white hero-image-box">
                <i class="fa-solid fa-truck-medical text-neo-blue hero-image-icon"></i>
                <h3 class="badge-neo bg-yellow hero-image-badge">
                    Siap Antar!
                </h3>
            </div>
        </div>
    </div>
</header>

<!-- Marquee Banner -->
<div class="marquee-container">
    <div class="marquee-content">
        <span style="margin: 0 1.5rem;"><i class="fa-solid fa-star" style="color: var(--black);"></i> GRATIS ONGKIR MIN BELANJA 50RB</span>
        <span style="margin: 0 1.5rem;"><i class="fa-solid fa-star" style="color: var(--black);"></i> 100% OBAT ORIGINAL</span>
        <span style="margin: 0 1.5rem;"><i class="fa-solid fa-star" style="color: var(--black);"></i> KONSULTASI APOTEKER GRATIS</span>
        <span style="margin: 0 1.5rem;"><i class="fa-solid fa-star" style="color: var(--black);"></i> BUKA 24 JAM</span>
        <span style="margin: 0 1.5rem;"><i class="fa-solid fa-star" style="color: var(--black);"></i> GRATIS ONGKIR MIN BELANJA 50RB</span>
        <span style="margin: 0 1.5rem;"><i class="fa-solid fa-star" style="color: var(--black);"></i> 100% OBAT ORIGINAL</span>
    </div>
</div>

<!-- Products Section -->
<section id="products" class="section-py bg-white">
    <div class="container">
        <div class="flex justify-between items-center mb-12 wrap gap-4">
            <h2 class="heading-section m-0">Katalog Obat</h2>

            <form method="GET" action="index.php#products" class="flex items-center gap-2">
                <select name="kategori" class="input-neo" style="width: auto; padding: 0.75rem 2.5rem 0.75rem 1rem;">
                    <option value="">Semua Kategori</option>
                    <?php foreach ($list_kategori as $kat): ?>
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
                    include 'templates/product_card.php';
                }
            } else {
                echo '<div style="grid-column: span 4; text-align: center; padding: 3rem 0;"><p class="font-black text-2xl uppercase" style="color: var(--gray-400);">Obat tidak ditemukan</p></div>';
            }
            ?>
        </div>
    </div>
</section>

<!-- Info Banner / CTA -->
<section class="hero-block cta-section">
    <div class="container grid grid-cols-2-md gap-8 items-center">
        <div class="cta-content">
            <h2 class="heading-hero cta-title">
                Butuh resep dokter?<br>
                <span class="cta-title-highlight">Upload di sini!</span>
            </h2>
            <p class="cta-subtitle">
                Apoteker kami siap memproses pesanan Anda.
            </p>
            <div class="cta-buttons-grid">
                <a href="https://wa.me/6285157839155?text=Halo%20Apotik%20Neo,%20saya%20ingin%20mengirimkan%20foto%20resep%20dokter"
                    target="_blank" class="btn-neo bg-pink cta-button">
                    <i class="fa-solid fa-camera"></i> Foto Resep
                </a>
                <a href="https://wa.me/6285157839155?text=Halo%20Apotik%20Neo,%20saya%20ingin%20konsultasi%20dengan%20apoteker"
                    target="_blank" class="btn-neo bg-white text-black cta-button">
                    <i class="fa-brands fa-whatsapp" style="color: #25D366; font-size: 1.5rem;"></i> Chat Apoteker
                </a>
            </div>
        </div>

        <div class="cta-image-container">
            <a href="https://wa.me/6285157839155?text=Halo%20Apotik%20Neo,%20saya%20ingin%20mengirimkan%20foto%20resep%20dokter"
                target="_blank" class="neo-box bg-white cta-image-box neo-box-hover">
                <img src="https://assets-a1.kompasiana.com/items/album/2016/10/20/215168-resep-jelek-5808c2b1f592735e28c47260.jpg" alt="Resep Dokter" class="cta-image">
            </a>
        </div>
    </div>
    <!-- Brutalist pattern background -->
    <div class="absolute inset-0 opacity-10"
        style="background-image: radial-gradient(circle at 2px 2px, black 2px, transparent 0); background-size: 20px 20px; pointer-events: none;">
    </div>
</section>

<?php require_once 'templates/footer.php'; ?>