<?php
session_start();
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}
include '../config/koneksi.php';

// Proses Tambah Data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_obat = mysqli_real_escape_string($koneksi, $_POST['nama_obat']);
    $id_kategori = (int)$_POST['id_kategori'];
    $harga = (int)$_POST['harga'];
    $stok = (int)$_POST['stok'];
    $tanggal_kadaluarsa = mysqli_real_escape_string($koneksi, $_POST['tanggal_kadaluarsa']);
    
    // Proses Upload Gambar
    $gambar = 'default.jpg';
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, $allowed)) {
            $gambar = 'obat_' . time() . '.' . $ext;
            if (!is_dir('../assets/img')) {
                mkdir('../assets/img', 0777, true);
            }
            move_uploaded_file($_FILES['gambar']['tmp_name'], '../assets/img/' . $gambar);
        }
    }

    $query = "INSERT INTO data_obat (nama_obat, id_kategori, harga, stok, tanggal_kadaluarsa, gambar) 
              VALUES ('$nama_obat', '$id_kategori', '$harga', '$stok', '$tanggal_kadaluarsa', '$gambar')";
    
    if (mysqli_query($koneksi, $query)) {
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    alert('Obat berhasil ditambahkan!');
                    window.location.href = 'admin_dashboard.php?page=obat';
                });
              </script>";
    } else {
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    alert('Gagal menambahkan obat!');
                });
              </script>";
    }
}

// Ambil Metrik Dashboard
$q_total_obat = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(id_obat) as total FROM data_obat"))['total'];
$q_stok_menipis = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(id_obat) as total FROM data_obat WHERE stok < 10"))['total'];
$q_total_pesanan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(id_order) as total FROM orders"))['total'];
$q_pendapatan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT SUM(total_harga) as total FROM orders WHERE status != 'dibatalkan'"))['total'] ?? 0;

$page_param = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// Hide standard top navbar
$hide_navbar = true;

require_once '../templates/header.php';
?>

<div class="admin-wrapper" style="display: flex; min-height: 100vh; background-color: #FDF9F1;">
    
    <!-- Sidebar Left -->
    <?php require_once '../templates/admin_sidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="admin-main" style="flex: 1; display: flex; flex-direction: column; min-width: 0;">
        
        <!-- Top Header Dashboard -->
        <header class="admin-header">
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <span class="font-black text-lg uppercase tracking-tight" style="color: var(--black); margin-bottom: 0;">Dashboard</span>
                <span class="text-gray-400 font-bold">/</span>
                <span class="text-gray-500 font-bold text-xs uppercase" style="margin-bottom: 0;"><?= htmlspecialchars($page_param) ?></span>
            </div>
            
            <div style="display: flex; align-items: center; gap: 1rem;">
                <!-- Quick Create button -->
                <?php if($page_param === 'obat'): ?>
                    <button id="open-modal-btn" class="btn-neo bg-yellow" style="font-weight: 900; font-size: 0.75rem; box-shadow: 2px 2px 0 var(--black); border-width: 2.5px; padding: 0.45rem 1rem; color: var(--black); margin: 0;">
                        <i class="fa-solid fa-plus" style="margin-right: 0.25rem;"></i> Tambah Obat
                    </button>
                <?php else: ?>
                    <a href="admin_dashboard.php?page=obat&open_modal=true" class="btn-neo bg-black-flat" style="font-weight: 900; font-size: 0.75rem; box-shadow: 2px 2px 0 var(--black); border-width: 2.5px; padding: 0.45rem 1rem; margin: 0;">
                        <i class="fa-solid fa-square-plus" style="margin-right: 0.25rem; color: var(--neo-yellow);"></i> Quick Add
                    </a>
                <?php endif; ?>
            </div>
        </header>

        <!-- Admin Content Area -->
        <div class="admin-content" style="padding: 2rem; flex: 1;">
            
            <?php if($page_param === 'dashboard'): ?>
                
                <!-- ROW 1: OVERVIEW METRIC CARDS -->
                <div class="grid grid-cols-4 gap-6 mb-8">
                    <!-- Card 1 -->
                    <div class="metric-card-neo bg-cyan" style="padding: 1.5rem;">
                        <div class="flex justify-between items-start" style="margin-bottom: 0.5rem;">
                            <p class="font-black text-xs uppercase tracking-wider" style="margin: 0; color: var(--black); opacity: 0.85;">Total Produk</p>
                            <div style="background-color: var(--black); color: var(--white); width: 2rem; height: 2rem; border-radius: 9999px; display: flex; align-items: center; justify-content: center; border: 2px solid var(--black); box-shadow: 1.5px 1.5px 0 var(--white);">
                                <i class="fa-solid fa-box text-xs"></i>
                            </div>
                        </div>
                        <div class="flex flex-col justify-end" style="margin-top: 0.5rem;">
                            <span class="text-4xl font-black" style="line-height: 1.1; margin-bottom: 0.25rem;"><?= $q_total_obat ?></span>
                            <span class="text-gray-600 font-bold" style="font-size: 0.65rem; text-transform: uppercase;">Obat Aktif</span>
                        </div>
                    </div>
                    <!-- Card 2 -->
                    <div class="metric-card-neo bg-pink" style="padding: 1.5rem;">
                        <div class="flex justify-between items-start" style="margin-bottom: 0.5rem;">
                            <p class="font-black text-xs uppercase tracking-wider" style="margin: 0; color: var(--black); opacity: 0.85;">Stok Menipis</p>
                            <div style="background-color: var(--black); color: var(--white); width: 2rem; height: 2rem; border-radius: 9999px; display: flex; align-items: center; justify-content: center; border: 2px solid var(--black); box-shadow: 1.5px 1.5px 0 var(--white);">
                                <i class="fa-solid fa-triangle-exclamation text-xs"></i>
                            </div>
                        </div>
                        <div class="flex flex-col justify-end" style="margin-top: 0.5rem;">
                            <span class="text-4xl font-black" style="line-height: 1.1; margin-bottom: 0.25rem;"><?= $q_stok_menipis ?></span>
                            <span class="text-gray-600 font-bold" style="font-size: 0.65rem; text-transform: uppercase;">Butuh Restock</span>
                        </div>
                    </div>
                    <!-- Card 3 -->
                    <div class="metric-card-neo bg-green" style="padding: 1.5rem;">
                        <div class="flex justify-between items-start" style="margin-bottom: 0.5rem;">
                            <p class="font-black text-xs uppercase tracking-wider" style="margin: 0; color: var(--black); opacity: 0.85;">Total Pesanan</p>
                            <div style="background-color: var(--black); color: var(--white); width: 2rem; height: 2rem; border-radius: 9999px; display: flex; align-items: center; justify-content: center; border: 2px solid var(--black); box-shadow: 1.5px 1.5px 0 var(--white);">
                                <i class="fa-solid fa-clipboard-check text-xs"></i>
                            </div>
                        </div>
                        <div class="flex flex-col justify-end" style="margin-top: 0.5rem;">
                            <span class="text-4xl font-black" style="line-height: 1.1; margin-bottom: 0.25rem;"><?= $q_total_pesanan ?></span>
                            <span class="text-gray-600 font-bold" style="font-size: 0.65rem; text-transform: uppercase;">Order Masuk</span>
                        </div>
                    </div>
                    <!-- Card 4 -->
                    <div class="metric-card-neo bg-yellow" style="padding: 1.5rem;">
                        <div class="flex justify-between items-start" style="margin-bottom: 0.5rem;">
                            <p class="font-black text-xs uppercase tracking-wider" style="margin: 0; color: var(--black); opacity: 0.85;">Total Omzet</p>
                            <div style="background-color: var(--black); color: var(--white); width: 2rem; height: 2rem; border-radius: 9999px; display: flex; align-items: center; justify-content: center; border: 2px solid var(--black); box-shadow: 1.5px 1.5px 0 var(--white);">
                                <i class="fa-solid fa-wallet text-xs"></i>
                            </div>
                        </div>
                        <div class="flex flex-col justify-end" style="margin-top: 0.5rem;">
                            <span class="text-xl font-black" style="line-height: 1.1; margin-bottom: 0.25rem; white-space: nowrap;">Rp <?= number_format($q_pendapatan, 0, ',', '.') ?></span>
                            <span class="text-gray-600 font-bold" style="font-size: 0.65rem; text-transform: uppercase;">Pendapatan Bersih</span>
                        </div>
                    </div>
                </div>

                <!-- ROW 2: TWO EQUAL COLUMN MAIN PANELS (PESANAN TERBARU & OBAT TERPOPULER) -->
                <div class="grid grid-cols-2 gap-6" style="align-items: start;">
                    
                    <!-- Left Column: Pesanan Terbaru -->
                    <div class="neo-box bg-white" style="padding: 1.75rem; border-width: 4px; box-shadow: 6px 6px 0 var(--black);">
                        <h4 class="font-black text-md uppercase mb-4 pb-2" style="border-bottom: 3px solid var(--black); margin-top: 0; display: flex; justify-content: space-between; align-items: center;">
                            Pesanan Terbaru
                            <i class="fa-solid fa-clock-rotate-left text-neo-cyan"></i>
                        </h4>
                        
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            <?php
                            $q_recent_orders = mysqli_query($koneksi, "SELECT orders.*, users.nama_lengkap FROM orders JOIN users ON orders.id_user = users.id_user ORDER BY orders.id_order DESC LIMIT 4");
                            if(mysqli_num_rows($q_recent_orders) == 0):
                                echo '<p class="text-xs font-bold text-gray-400 text-center py-4">Belum ada order masuk</p>';
                            else:
                                while($ro_row = mysqli_fetch_assoc($q_recent_orders)):
                                    $ro_status_color = 'bg-yellow';
                                    if ($ro_row['status'] == 'dibayar') $ro_status_color = 'bg-cyan';
                                    if ($ro_row['status'] == 'dikirim') $ro_status_color = 'bg-blue text-white';
                                    if ($ro_row['status'] == 'selesai') $ro_status_color = 'bg-green';
                                    if ($ro_row['status'] == 'batal') $ro_status_color = 'bg-pink text-white';
                            ?>
                            <div style="display: flex; flex-direction: column; gap: 0.35rem; padding-bottom: 0.75rem; border-bottom: 2px dashed #E5E7EB;">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <span class="font-black text-sm uppercase truncate" style="max-width: 15rem; color: var(--black);">#<?= $ro_row['id_order'] ?> - <?= htmlspecialchars($ro_row['nama_lengkap']) ?></span>
                                    <span class="badge-neo <?= $ro_status_color ?>" style="font-size: 0.6rem; padding: 0.15rem 0.4rem; box-shadow: 2px 2px 0 var(--black); border-width: 1.5px; color: var(--black); font-weight: 900;"><?= strtoupper($ro_row['status']) ?></span>
                                </div>
                                <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.8rem; font-weight: 700; color: var(--gray-600);">
                                    <span><?= date('d M Y, H:i', strtotime($ro_row['tanggal_order'])) ?></span>
                                    <span class="font-black text-sm" style="color: var(--black);">Rp <?= number_format($ro_row['total_harga'], 0, ',', '.') ?></span>
                                </div>
                            </div>
                            <?php endwhile; endif; ?>
                        </div>
                    </div>
                    
                    <!-- Right Column: Obat Terpopuler -->
                    <div class="neo-box bg-white" style="padding: 1.75rem; border-width: 4px; box-shadow: 6px 6px 0 var(--black);">
                        <h4 class="font-black text-md uppercase mb-4 pb-2" style="border-bottom: 3px solid var(--black); margin-top: 0; display: flex; justify-content: space-between; align-items: center;">
                            Obat Terpopuler
                            <i class="fa-solid fa-fire text-neo-pink"></i>
                        </h4>
                        
                        <div class="flex flex-col gap-4">
                            <?php
                            $q_popular = mysqli_query($koneksi, "SELECT data_obat.*, kategori_obat.nama_kategori, COALESCE((SELECT SUM(jumlah) FROM order_details WHERE id_obat = data_obat.id_obat), 0) as terjual FROM data_obat LEFT JOIN kategori_obat ON data_obat.id_kategori = kategori_obat.id_kategori ORDER BY terjual DESC, id_obat DESC LIMIT 4");
                            if(mysqli_num_rows($q_popular) == 0):
                                echo '<p class="text-xs font-bold text-gray-400 text-center py-4">Belum ada obat terjual</p>';
                            else:
                                while($p_row = mysqli_fetch_assoc($q_popular)):
                                    $p_img = ($p_row['gambar'] && $p_row['gambar'] !== 'default.jpg') ? '../assets/img/' . $p_row['gambar'] : null;
                            ?>
                            <div style="display: flex; align-items: center; gap: 1rem; padding-bottom: 0.5rem; border-bottom: 2px dashed #E5E7EB;">
                                <?php if($p_img): ?>
                                    <div class="neo-box-sm" style="width: 2.75rem; height: 2.75rem; border-width: 2px; box-shadow: 2px 2px 0 var(--black); overflow: hidden; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        <img src="<?= $p_img ?>" style="width: 100%; height: 100%; object-fit: cover;" alt="img">
                                    </div>
                                <?php else: ?>
                                    <div class="neo-box-sm bg-gray-100" style="width: 2.75rem; height: 2.75rem; border-width: 2px; box-shadow: 2px 2px 0 var(--black); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        <i class="fa-solid fa-image text-gray-400" style="font-size: 1rem;"></i>
                                    </div>
                                <?php endif; ?>
                                <div style="flex: 1; min-width: 0;">
                                    <p class="font-black text-xs uppercase truncate" style="margin: 0;"><?= htmlspecialchars($p_row['nama_obat']) ?></p>
                                    <p class="text-gray-500 font-bold truncate" style="font-size: 0.65rem; margin: 0;"><?= htmlspecialchars($p_row['nama_kategori']) ?></p>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <p class="font-black text-xs" style="margin: 0; color: var(--neo-blue);">Rp <?= number_format($p_row['harga'], 0, ',', '.') ?></p>
                                    <p class="text-gray-500 font-bold" style="font-size: 0.6rem; margin: 0;"><?= $p_row['terjual'] ?> Terjual</p>
                                </div>
                            </div>
                            <?php endwhile; endif; ?>
                        </div>
                    </div>

                </div>

            <?php elseif($page_param === 'obat'): ?>
                
                <!-- VIEW 2: MEDICINE CATALOG TABLE -->
                <div style="margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                    <h3 class="font-black uppercase" style="font-size: 1.75rem; margin: 0; display: inline-flex; align-items: center; gap: 0.75rem; letter-spacing: -0.01em;">
                        Daftar Obat Tersedia
                        <span class="badge-neo bg-yellow" id="product-count-badge" style="font-size: 1.125rem; padding: 0.25rem 0.75rem; border-radius: 9999px; box-shadow: 2px 2px 0 var(--black);">0</span>
                    </h3>
                </div>
                
                <!-- Dashboard Action Tools (Search & Filter) -->
                <div class="dashboard-actions-row">
                    <!-- Search Bar -->
                    <div class="dashboard-search-box">
                        <input type="text" id="search-obat" placeholder="Cari nama obat..." class="dashboard-search-input">
                        <i class="fa-solid fa-magnifying-glass dashboard-search-icon"></i>
                    </div>
                    
                    <!-- Category Filters -->
                    <div class="filters-container">
                        <button class="filter-btn active" data-category="all">Semua</button>
                        <button class="filter-btn" data-category="low-stock">Stok Menipis (<10)</button>
                        <?php
                        $categories = mysqli_query($koneksi, "SELECT * FROM kategori_obat");
                        while($c = mysqli_fetch_assoc($categories)) {
                            echo '<button class="filter-btn" data-category="'.htmlspecialchars($c['nama_kategori']).'">'.strtoupper(htmlspecialchars($c['nama_kategori'])).'</button>';
                        }
                        ?>
                    </div>
                </div>
                
                <div class="table-container neo-box" style="border-width: 4px; box-shadow: 6px 6px 0px var(--black);">
                    <table class="table-neo">
                        <thead>
                            <tr class="bg-black" style="color: var(--white); font-size: 0.85rem; border-bottom: 4px solid var(--black);">
                                <th style="width: 4.5rem; text-align: center; border-right: 4px solid var(--black);">Img</th>
                                <th style="border-right: 4px solid var(--black);">Nama Obat</th>
                                <th style="border-right: 4px solid var(--black); width: 10rem;">Kategori</th>
                                <th style="border-right: 4px solid var(--black); width: 8.5rem;">Harga</th>
                                <th style="border-right: 4px solid var(--black); width: 5.5rem; text-align: center;">Stok</th>
                                <th style="text-align: center; width: 8rem;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT data_obat.*, kategori_obat.nama_kategori 
                                      FROM data_obat 
                                      LEFT JOIN kategori_obat ON data_obat.id_kategori = kategori_obat.id_kategori 
                                      ORDER BY id_obat DESC";
                            $result = mysqli_query($koneksi, $query);
                            while($row = mysqli_fetch_assoc($result)):
                                $img_src = ($row['gambar'] && $row['gambar'] !== 'default.jpg') ? '../assets/img/' . $row['gambar'] : null;
                            ?>
                            <tr class="obat-row" data-name="<?= htmlspecialchars(strtolower($row['nama_obat'])) ?>" data-category="<?= htmlspecialchars($row['nama_kategori']) ?>" data-stok="<?= $row['stok'] ?>" style="font-weight: 700;">
                                <td style="border-right: 4px solid var(--black); text-align: center; padding: 0.75rem 0.5rem;">
                                    <?php if($img_src): ?>
                                        <div class="neo-box-sm" style="width: 2.75rem; height: 2.75rem; padding: 0; display: inline-flex; align-items: center; justify-content: center; overflow: hidden; border-width: 2px; box-shadow: 2px 2px 0 var(--black);">
                                            <img src="<?= $img_src ?>" style="width: 100%; height: 100%; object-fit: cover;" alt="img">
                                        </div>
                                    <?php else: ?>
                                        <div class="neo-box-sm" style="width: 2.75rem; height: 2.75rem; padding: 0; background-color: var(--gray-200); display: inline-flex; align-items: center; justify-content: center; border-width: 2px; box-shadow: 2px 2px 0 var(--black);">
                                            <i class="fa-solid fa-image text-gray-400" style="font-size: 1.15rem;"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td style="border-right: 4px solid var(--black); font-size: 1.05rem; padding: 0.75rem 1rem;">
                                    <?= htmlspecialchars($row['nama_obat']) ?>
                                </td>
                                <td style="border-right: 4px solid var(--black); white-space: nowrap; padding: 0.75rem 1rem;">
                                    <span class="badge-neo bg-white" style="padding: 0.25rem 0.6rem; font-size: 0.725rem; font-weight: 900; box-shadow: 2px 2px 0 var(--black);">
                                        <?= htmlspecialchars($row['nama_kategori']) ?>
                                    </span>
                                </td>
                                <td style="border-right: 4px solid var(--black); white-space: nowrap; font-size: 1.05rem; padding: 0.75rem 1rem;">
                                    Rp <?= number_format($row['harga'], 0, ',', '.') ?>
                                </td>
                                <td style="border-right: 4px solid var(--black); text-align: center; font-size: 1.15rem; padding: 0.75rem 1rem;">
                                    <?php if($row['stok'] < 10): ?>
                                        <span class="badge-neo bg-pink" style="padding: 0.25rem 0.6rem; font-size: 0.9rem; font-weight: 900; box-shadow: 2px 2px 0 var(--black); color: var(--black);">
                                            <?= $row['stok'] ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="badge-neo bg-green" style="padding: 0.25rem 0.6rem; font-size: 0.9rem; font-weight: 900; box-shadow: 2px 2px 0 var(--black); color: var(--black);">
                                            <?= $row['stok'] ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: center; padding: 0.75rem 0.5rem;">
                                    <div class="flex justify-center gap-2" style="margin: 0;">
                                        <a href="edit_obat.php?id=<?= $row['id_obat'] ?>" class="btn-neo btn-neo-sm bg-yellow" style="padding: 0.4rem 0.6rem; box-shadow: 2px 2px 0 var(--black); border-width: 2px;" title="Edit">
                                            <i class="fa-solid fa-pen" style="font-size: 0.875rem;"></i>
                                        </a>
                                        <a href="hapus_obat.php?id=<?= $row['id_obat'] ?>" class="btn-neo btn-neo-sm bg-pink" style="padding: 0.4rem 0.6rem; box-shadow: 2px 2px 0 var(--black); border-width: 2px; color: var(--black);" onclick="return confirm('Yakin hapus obat ini?')" title="Hapus">
                                            <i class="fa-solid fa-trash" style="font-size: 0.875rem;"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

            <?php endif; ?>

        </div>
    </main>
</div>

<!-- ==================== OVERLAY MODAL: TAMBAH OBAT ==================== -->
<div class="modal-overlay-neo" id="modal-tambah-obat">
    <div class="modal-neo">
        <button class="modal-close-neo" id="close-modal-btn">&times;</button>
        
        <div class="flex flex-col items-center gap-2 mb-6 text-center" style="border-bottom: 4px solid var(--black); padding-bottom: 1rem;">
            <div class="nav-brand-logo bg-yellow" style="width: 3.5rem; height: 3.5rem; border-radius: 9999px; transform: rotate(-12deg); margin-bottom: 0.25rem; border-width: 3px; box-shadow: 2px 2px 0 var(--black);">
                <i class="fa-solid fa-pills text-2xl" style="margin-top: 0.6rem;"></i>
            </div>
            <h3 class="font-black uppercase" style="font-size: 1.5rem; margin: 0; line-height: 1.1; letter-spacing: -0.01em;">Tambah Produk</h3>
            <p class="badge-neo bg-black" style="color: var(--white); font-size: 0.7rem; padding: 0.2rem 0.6rem; margin-top: 0.25rem; display: inline-block;">Katalog Baru</p>
        </div>
        
        <form method="POST" action="admin_dashboard.php" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 1.15rem;">
            <div>
                <label class="block font-black text-xs mb-1 uppercase tracking-widest text-gray-700">Nama Obat</label>
                <input type="text" name="nama_obat" required class="input-neo" style="border-width: 3px; padding: 0.6rem 0.8rem; font-size: 0.95rem;" placeholder="Contoh: Paracetamol 500mg">
            </div>
            
            <div>
                <label class="block font-black text-xs mb-1 uppercase tracking-widest text-gray-700">Kategori</label>
                <select name="id_kategori" required class="input-neo" style="width: 100%; border-width: 3px; padding: 0.6rem 0.8rem; font-size: 0.95rem;">
                    <option value="">-- Pilih Kategori --</option>
                    <?php
                    $kat = mysqli_query($koneksi, "SELECT * FROM kategori_obat");
                    while($k = mysqli_fetch_assoc($kat)) {
                        echo "<option value='".$k['id_kategori']."'>".strtoupper($k['nama_kategori'])."</option>";
                    }
                    ?>
                </select>
            </div>
            
            <div style="display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 0.75rem;">
                <div>
                    <label class="block font-black text-xs mb-1 uppercase tracking-widest text-gray-700">Harga</label>
                    <div class="flex" style="margin: 0;">
                        <span class="badge-neo bg-gray-200" style="padding: 0.6rem 0.75rem; border-right-width: 0; border-radius: 0; font-weight: 900; font-size: 0.95rem; border-width: 3px;">Rp</span>
                        <input type="number" name="harga" required class="input-neo" style="border-top-left-radius: 0; border-bottom-left-radius: 0; border-width: 3px; padding: 0.6rem 0.8rem; font-size: 0.95rem;" placeholder="0">
                    </div>
                </div>
                <div>
                    <label class="block font-black text-xs mb-1 uppercase tracking-widest text-gray-700">Stok</label>
                    <input type="number" name="stok" required class="input-neo" style="border-width: 3px; padding: 0.6rem 0.8rem; font-size: 0.95rem;" placeholder="0">
                </div>
            </div>
            
            <div>
                <label class="block font-black text-xs mb-1 uppercase tracking-widest text-gray-700">Tgl Kadaluarsa</label>
                <input type="date" name="tanggal_kadaluarsa" required class="input-neo" style="border-width: 3px; padding: 0.6rem 0.8rem; font-size: 0.95rem;">
            </div>
            
            <div>
                <label class="block font-black text-xs mb-1 uppercase tracking-widest text-gray-700">Gambar Produk</label>
                <div class="upload-zone-neo">
                    <i class="fa-solid fa-cloud-arrow-up text-2xl mb-2" style="display: block; color: var(--black); margin-bottom: 0.4rem;"></i>
                    <p class="font-black text-xs uppercase" style="margin: 0; letter-spacing: 0.02em;">Klik & Upload Foto</p>
                    <p class="text-gray-500" style="font-size: 0.65rem; margin: 0.2rem 0 0 0; font-weight: 700;">JPG, PNG, WEBP (Max 2MB)</p>
                    <input type="file" name="gambar" id="gambar-input" accept="image/*" style="position: absolute; inset: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;">
                </div>
            </div>
            
            <button type="submit" class="btn-neo bg-green" style="width: 100%; font-size: 1.15rem; padding: 0.9rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem; margin-top: 0.75rem; color: var(--black); border-width: 3px; box-shadow: 4px 4px 0 var(--black);">
                Simpan Obat <i class="fa-solid fa-floppy-disk"></i>
            </button>
        </form>
    </div>
</div>

<!-- Scripts for Dynamic Controls -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // 1. Modal Overlay Open/Close Controls
    const modal = document.getElementById('modal-tambah-obat');
    const openBtn = document.getElementById('open-modal-btn');
    const closeBtn = document.getElementById('close-modal-btn');
    
    if(openBtn && modal) {
        openBtn.addEventListener('click', function() {
            modal.style.display = 'flex';
        });
    }
    
    if(closeBtn && modal) {
        closeBtn.addEventListener('click', function() {
            modal.style.display = 'none';
        });
        
        // Close modal when clicking outside content area
        modal.addEventListener('click', function(e) {
            if(e.target === modal) {
                modal.style.display = 'none';
            }
        });
    }

    // Auto open modal if requested in URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    if(urlParams.get('open_modal') === 'true' && modal) {
        modal.style.display = 'flex';
    }

    // 2. Image Selection Preview
    const fileInput = document.getElementById('gambar-input');
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                const parent = this.parentElement;
                reader.onload = function(event) {
                    let prev = parent.querySelector('.preview-img');
                    if (prev) prev.remove();
                    
                    const img = document.createElement('img');
                    img.src = event.target.result;
                    img.className = 'preview-img';
                    img.style.width = '100%';
                    img.style.height = '100%';
                    img.style.objectFit = 'contain';
                    img.style.position = 'absolute';
                    img.style.top = '0';
                    img.style.left = '0';
                    img.style.backgroundColor = 'var(--white)';
                    img.style.border = '2px solid var(--black)';
                    img.style.pointerEvents = 'none';
                    img.style.zIndex = '5';
                    parent.appendChild(img);
                    
                    parent.style.borderStyle = 'solid';
                    parent.style.borderColor = 'var(--neo-green)';
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // 3. Catalog Live Filtering (only active on 'obat' page)
    const searchInput = document.getElementById('search-obat');
    const filterButtons = document.querySelectorAll('.filter-btn');
    const rows = document.querySelectorAll('.obat-row');
    const countBadge = document.getElementById('product-count-badge');
    const tableBody = document.querySelector('.table-neo tbody');
    
    if (rows.length > 0) {
        let activeFilter = 'all';
        let searchQuery = '';

        function updateTable() {
            let visibleCount = 0;
            rows.forEach(row => {
                const name = row.getAttribute('data-name');
                const category = row.getAttribute('data-category');
                const stok = parseInt(row.getAttribute('data-stok'));
                
                let matchesSearch = name.includes(searchQuery);
                let matchesFilter = false;
                
                if (activeFilter === 'all') {
                    matchesFilter = true;
                } else if (activeFilter === 'low-stock') {
                    matchesFilter = stok < 10;
                } else {
                    matchesFilter = category === activeFilter;
                }
                
                if (matchesSearch && matchesFilter) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });
            
            if (countBadge) {
                countBadge.textContent = visibleCount;
            }

            const emptyRow = document.getElementById('empty-row');
            if (visibleCount === 0) {
                if (!emptyRow) {
                    const tr = document.createElement('tr');
                    tr.id = 'empty-row';
                    tr.innerHTML = `<td colspan="6" style="text-align: center; padding: 3.5rem; background-color: var(--white); color: var(--gray-500);">
                        <i class="fa-solid fa-face-frown text-5xl mb-3" style="display: block; color: var(--neo-pink);"></i>
                        <span class="font-black uppercase" style="font-size: 1.15rem; color: var(--black);">Obat tidak ditemukan!</span>
                        <p class="text-xs font-bold text-gray-500" style="margin: 0.5rem 0 0 0;">Coba cari dengan kata kunci atau kategori lain</p>
                    </td>`;
                    tableBody.appendChild(tr);
                }
            } else {
                if (emptyRow) emptyRow.remove();
            }
        }
        
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                searchQuery = this.value.toLowerCase().trim();
                updateTable();
            });
        }
        
        filterButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                filterButtons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                activeFilter = this.getAttribute('data-category');
                updateTable();
            });
        });
        
        updateTable();
    }
});
</script>

