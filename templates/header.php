<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$is_logged_in = isset($_SESSION['id_user']);
$is_admin = $is_logged_in && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
$nama_user = $is_logged_in ? $_SESSION['nama_lengkap'] : '';
$cart_count = 0;
if(isset($_SESSION['cart'])) {
    foreach($_SESSION['cart'] as $qty) {
        $cart_count += $qty;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apotik.Neo - Sehat Nggak Harus Mahal</title>
    <!-- Fonts & Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Native CSS Stylesheet -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css?v=<?= filemtime(__DIR__ . '/../assets/css/style.css') ?>">
</head>
<body>

    <!-- Navigation -->
    <?php if (!isset($hide_navbar) || !$hide_navbar): ?>
    <nav class="nav-neo">
        <div class="container nav-container" style="display: flex; align-items: center; justify-content: space-between; gap: 1rem;">
            <!-- Left Section: Logo & Menus -->
            <div style="display: flex; align-items: center; gap: 1.5rem; flex-shrink: 0;">
                <!-- Logo -->
                <a href="<?= BASE_URL ?>/index.php" style="display: flex; align-items: center; gap: 0.5rem; flex-shrink: 0;">
                    <div class="nav-brand-logo">
                        <i class="fa-solid fa-plus"></i>
                    </div>
                    <span class="font-black text-2xl uppercase hidden sm:block" style="margin: 0;">Apotik.Neo</span>
                </a>
                
                <!-- Navigation Links -->
                <div class="nav-links">
                    <a href="<?= BASE_URL ?>/index.php" class="nav-link">Beranda</a>
                    
                    <?php if($is_logged_in): ?>
                        <?php if($is_admin): ?>
                            <a href="<?= BASE_URL ?>/index.php#products" class="nav-link">Kategori</a>
                            <a href="#" class="nav-link">Promo</a>
                        <?php else: ?>
                            <a href="<?= BASE_URL ?>/user/user_home.php" class="nav-link">Produk</a>
                            <a href="<?= BASE_URL ?>/user/riwayat_pesanan.php" class="nav-link">Riwayat</a>
                            <a href="#" class="nav-link">Konsultasi</a>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>/index.php#products" class="nav-link">Kategori</a>
                        <a href="#" class="nav-link">Promo</a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Center Section: Search Bar -->
            <?php if (!$is_admin): ?>
            <div class="search-container" style="flex: 1; max-width: 18rem; display: flex; justify-content: center; margin: 0 1rem;">
                <form action="<?= $is_logged_in && !$is_admin ? BASE_URL . '/user/user_home.php' : BASE_URL . '/index.php' ?>" method="GET" class="search-form" style="width: 100%; position: relative; display: flex; margin: 0;">
                    <input type="text" name="search" placeholder="Cari obat..." class="search-input" style="width: 100%; margin: 0;">
                    <button type="submit" class="search-button">
                        <i class="fa-solid fa-search"></i>
                    </button>
                </form>
            </div>
            <?php else: ?>
            <!-- Spacer to push admin actions to the right -->
            <div style="flex: 1;"></div>
            <?php endif; ?>

            <!-- Right Section: Auth & Cart -->
            <div style="display: flex; align-items: center; gap: 0.75rem; flex-shrink: 0;">
                <?php if(!$is_admin): ?>
                    <a href="<?= BASE_URL ?>/user/cart.php" class="btn-neo btn-neo-sm bg-white" style="padding: 0.5rem 0.75rem; position: relative; margin: 0; margin-right: 0.25rem;">
                        <i class="fa-solid fa-cart-shopping text-lg"></i>
                        <span class="badge-neo bg-yellow" style="position: absolute; top: -10px; right: -10px; border-radius: 9999px; padding: 2px 6px; font-size: 10px; font-weight: 900;"><?= $cart_count ?></span>
                    </a>
                <?php endif; ?>

                <?php if($is_logged_in): ?>
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <?php if(!$is_admin): ?>
                            <div class="user-avatar-neo">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            <span class="font-black text-xs uppercase hidden md:inline" style="letter-spacing: 0.02em; color: var(--black);"><?= htmlspecialchars($nama_user) ?></span>
                        <?php else: ?>
                            <a href="<?= BASE_URL ?>/admin/admin_dashboard.php?page=dashboard" class="btn-neo btn-neo-sm bg-yellow" style="margin: 0; font-weight: 900; font-size: 0.75rem; padding: 0.5rem 0.75rem; display: flex; align-items: center; gap: 0.35rem;">
                                <i class="fa-solid fa-gauge"></i> PANEL ADMIN
                            </a>
                        <?php endif; ?>
                        
                        <a href="<?= BASE_URL ?>/auth/logout.php" class="btn-neo btn-neo-sm bg-black-flat" style="margin: 0;">
                            LOGOUT
                        </a>
                    </div>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/auth/login.php" class="btn-neo btn-neo-sm bg-black-flat" style="margin: 0;">
                        MASUK
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <?php endif; ?>
