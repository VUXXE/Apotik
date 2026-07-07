<?php
$current_page = basename($_SERVER['PHP_SELF']);
$page_param = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
?>
<aside class="admin-sidebar" style="width: 16rem; border-right: 4px solid var(--black); background-color: var(--white); display: flex; flex-direction: column; padding: 2rem 1.25rem; position: sticky; top: 0; height: 100vh; z-index: 40; flex-shrink: 0;">
    <!-- Logo -->
    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 2.5rem;">
        <div class="nav-brand-logo bg-yellow" style="width: 2.5rem; height: 2.5rem; border-width: 2.5px; box-shadow: 2px 2px 0 var(--black); display: flex; align-items: center; justify-content: center;">
            <i class="fa-solid fa-plus text-lg"></i>
        </div>
        <span class="font-black text-xl uppercase tracking-tight" style="color: var(--black);">Apotik.Neo</span>
    </div>
    
    <!-- Navigation Links -->
    <nav style="display: flex; flex-direction: column; gap: 0.75rem; flex: 1;">
        <a href="/Apotik/admin/admin_dashboard.php?page=dashboard" class="sidebar-link <?= ($current_page == 'admin_dashboard.php' && $page_param == 'dashboard') ? 'active' : '' ?>">
            <i class="fa-solid fa-house"></i> Dashboard
        </a>
        <a href="/Apotik/admin/admin_dashboard.php?page=obat" class="sidebar-link <?= ($current_page == 'admin_dashboard.php' && $page_param == 'obat') ? 'active' : '' ?>">
            <i class="fa-solid fa-pills"></i> Data Obat
        </a>
        <a href="/Apotik/admin/kelola_pesanan.php" class="sidebar-link <?= ($current_page == 'kelola_pesanan.php') ? 'active' : '' ?>">
            <i class="fa-solid fa-clipboard-list"></i> Pesanan
        </a>
        <a href="/Apotik/index.php" class="sidebar-link">
            <i class="fa-solid fa-globe"></i> Beranda Toko
        </a>
    </nav>
    
    <!-- Bottom Section -->
    <div style="margin-top: auto; border-top: 4px dashed var(--black); padding-top: 1.5rem; display: flex; flex-direction: column; gap: 0.75rem;">
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <div class="neo-box-sm bg-pink" style="width: 2.5rem; height: 2.5rem; border-radius: 9999px; display: flex; align-items: center; justify-content: center; font-weight: 900; box-shadow: 2px 2px 0 var(--black); border-width: 2px; flex-shrink: 0;">
                <i class="fa-solid fa-user-tie text-black" style="font-size: 1.15rem;"></i>
            </div>
            <div style="min-width: 0;">
                <p class="font-black text-xs uppercase truncate" style="margin: 0; line-height: 1.2;"><?= htmlspecialchars($_SESSION['nama_lengkap'] ?? 'Admin') ?></p>
                <p class="text-gray-500 font-bold" style="font-size: 0.65rem; margin: 0; letter-spacing: 0.02em;">ADMINISTRATOR</p>
            </div>
        </div>
        <a href="/Apotik/auth/logout.php" class="sidebar-link logout-btn" style="margin-top: 0.25rem;">
            <i class="fa-solid fa-right-from-bracket"></i> Logout
        </a>
    </div>
</aside>
