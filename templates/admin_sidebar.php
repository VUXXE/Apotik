<?php
$current_page = basename($_SERVER['PHP_SELF']);
$page_param = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
?>
<aside class="admin-sidebar">
    <!-- Logo -->
    <div class="admin-sidebar-logo">
        <div class="nav-brand-logo">
            <i class="fa-solid fa-plus text-lg"></i>
        </div>
        <span class="font-black text-xl uppercase tracking-tight brand-text">Apotik.Neo</span>
    </div>
    
    <!-- Navigation Links -->
    <nav class="admin-sidebar-nav">
        <a href="<?= BASE_URL ?>/admin/admin_dashboard.php?page=dashboard" class="sidebar-link <?= ($current_page == 'admin_dashboard.php' && $page_param == 'dashboard') ? 'active' : '' ?>">
            <i class="fa-solid fa-house"></i> Dashboard
        </a>
        <a href="<?= BASE_URL ?>/admin/admin_dashboard.php?page=obat" class="sidebar-link <?= ($current_page == 'admin_dashboard.php' && $page_param == 'obat') ? 'active' : '' ?>">
            <i class="fa-solid fa-pills"></i> Data Obat
        </a>
        <a href="<?= BASE_URL ?>/admin/kelola_pesanan.php" class="sidebar-link <?= ($current_page == 'kelola_pesanan.php') ? 'active' : '' ?>">
            <i class="fa-solid fa-clipboard-list"></i> Pesanan
        </a>
        <a href="<?= BASE_URL ?>/index.php" class="sidebar-link">
            <i class="fa-solid fa-globe"></i> Beranda Toko
        </a>
    </nav>
    
    <!-- Bottom Section -->
    <div class="admin-sidebar-footer">
        <div class="admin-profile-section">
            <div class="user-avatar-neo admin-avatar">
                <i class="fa-solid fa-user-tie"></i>
            </div>
            <div class="profile-info">
                <p class="font-black text-xs uppercase truncate profile-name"><?= htmlspecialchars($_SESSION['nama_lengkap'] ?? 'Admin') ?></p>
                <p class="profile-role">ADMINISTRATOR</p>
            </div>
        </div>
        <a href="<?= BASE_URL ?>/auth/logout.php" class="sidebar-link logout-btn">
            <i class="fa-solid fa-right-from-bracket"></i> Logout
        </a>
    </div>
</aside>
