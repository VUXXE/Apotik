<?php
session_start();
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}
include '../config/koneksi.php';

// Handle Update Status
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $id_order = (int)$_POST['id_order'];
    $status_baru = mysqli_real_escape_string($koneksi, $_POST['status']);
    mysqli_query($koneksi, "UPDATE orders SET status = '$status_baru' WHERE id_order = '$id_order'");
    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                alert('Status pesanan #$id_order berhasil diperbarui!');
            });
          </script>";
}

// Hide standard top navbar
$hide_navbar = true;

require_once '../templates/header.php';

// Ambil semua pesanan
$q = mysqli_query($koneksi, "SELECT orders.*, users.nama_lengkap 
                             FROM orders 
                             JOIN users ON orders.id_user = users.id_user 
                             ORDER BY orders.id_order DESC");
?>

<div class="admin-wrapper" style="display: flex; min-height: 100vh; background-color: #FDF9F1;">
    
    <!-- Sidebar Left -->
    <?php require_once '../templates/admin_sidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="admin-main" style="flex: 1; display: flex; flex-direction: column; min-width: 0;">
        
        <!-- Top Header -->
        <header class="admin-header">
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <span class="font-black text-lg uppercase tracking-tight" style="color: var(--black); margin-bottom: 0;">Dashboard</span>
                <span class="text-gray-400 font-bold">/</span>
                <span class="text-gray-500 font-bold text-xs uppercase" style="margin-bottom: 0;">Pesanan</span>
            </div>
        </header>

        <!-- Content Area -->
        <div class="admin-content" style="padding: 2rem; flex: 1;">
            
            <?php if(mysqli_num_rows($q) == 0): ?>
                <div class="neo-box bg-white" style="padding: 4rem 2rem; text-align: center; max-width: 36rem; margin: 2rem auto; border-width: 6px; box-shadow: 8px 8px 0 var(--black);">
                    <div style="background-color: var(--neo-pink); width: 5rem; height: 5rem; border-radius: 9999px; display: inline-flex; align-items: center; justify-content: center; border: 3px solid var(--black); box-shadow: 3px 3px 0 var(--black); margin-bottom: 1.5rem; transform: rotate(-10deg);">
                        <i class="fa-solid fa-inbox text-3xl"></i>
                    </div>
                    <h3 class="font-black uppercase" style="font-size: 1.75rem; margin-bottom: 0.75rem; margin-top: 0; line-height: 1.1;">Belum Ada Pesanan!</h3>
                    <p class="font-bold text-gray-700" style="margin: 0; font-size: 1.05rem;">Apotek Anda belum menerima pesanan apa pun dari pelanggan.</p>
                </div>
            <?php else: ?>
                <div style="margin-bottom: 1.5rem;">
                    <h3 class="font-black uppercase" style="font-size: 1.75rem; margin: 0; border-bottom: 4px solid var(--black); padding-bottom: 0.5rem; display: inline-block;">Daftar Transaksi Masuk</h3>
                </div>
                
                <div class="table-container neo-box" style="border-width: 4px; box-shadow: 6px 6px 0px var(--black);">
                    <table class="table-neo">
                        <thead>
                            <tr class="bg-black" style="color: var(--white); font-size: 0.85rem; border-bottom: 4px solid var(--black);">
                                <th style="width: 11rem; border-right: 4px solid var(--black); padding: 1rem;">ID / Tanggal</th>
                                <th style="border-right: 4px solid var(--black); padding: 1rem;">Pembeli & Pengiriman</th>
                                <th style="border-right: 4px solid var(--black); padding: 1rem;">Detail Item</th>
                                <th style="border-right: 4px solid var(--black); width: 13rem; padding: 1rem;">Status</th>
                                <th style="text-align: center; width: 7rem; padding: 1rem;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = mysqli_fetch_assoc($q)): 
                                $status_color = 'bg-yellow';
                                if ($row['status'] == 'pending') $status_color = 'bg-yellow';
                                if ($row['status'] == 'dibayar') $status_color = 'bg-cyan';
                                if ($row['status'] == 'dikirim') $status_color = 'bg-blue text-white';
                                if ($row['status'] == 'selesai') $status_color = 'bg-green';
                                if ($row['status'] == 'batal') $status_color = 'bg-pink text-white';
                            ?>
                            <tr style="font-weight: 700; vertical-align: top;">
                                <td style="border-right: 4px solid var(--black); padding: 1rem;">
                                    <span class="block text-xl font-black uppercase" style="margin-bottom: 0.25rem; letter-spacing: -0.01em;">#<?= $row['id_order'] ?></span>
                                    <span class="text-xs font-bold" style="color: var(--gray-500); display: block; margin-bottom: 0.75rem;"><?= date('d M Y, H:i', strtotime($row['tanggal_order'])) ?></span>
                                    <div style="margin-top: 0.75rem; padding-top: 0.5rem; border-top: 2px dashed var(--black);">
                                        <span class="text-xs uppercase" style="color: var(--gray-500); display: block; margin-bottom: 0.15rem; font-weight: 900;">Total Bayar:</span>
                                        <span class="text-md font-black" style="color: var(--neo-blue);">Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></span>
                                    </div>
                                </td>
                                
                                <td style="border-right: 4px solid var(--black); min-w: 250px; padding: 1rem;">
                                    <p class="font-black text-lg" style="margin-top: 0; margin-bottom: 0.5rem; letter-spacing: -0.01em; display: flex; align-items: center; gap: 0.5rem;">
                                        <i class="fa-solid fa-user text-neo-pink"></i> <?= htmlspecialchars($row['nama_lengkap']) ?>
                                    </p>
                                    <div class="bg-gray-50" style="border: 2.5px solid var(--black); border-radius: 8px; padding: 0.75rem; font-size: 0.825rem; display: flex; flex-direction: column; gap: 0.4rem; box-shadow: 2px 2px 0 var(--black);">
                                        <p style="margin: 0; font-weight: 700;"><i class="fa-solid fa-phone text-gray-500" style="margin-right: 0.4rem;"></i> <?= htmlspecialchars($row['no_hp'] ?? '-') ?></p>
                                        <p style="margin: 0; border-top: 2px dashed var(--gray-300); padding-top: 0.4rem; font-weight: 700; line-height: 1.4;"><i class="fa-solid fa-map-location-dot text-gray-500" style="margin-right: 0.4rem;"></i> <?= htmlspecialchars($row['alamat'] ?? '-') ?></p>
                                    </div>
                                </td>
                                
                                <td style="border-right: 4px solid var(--black); min-w: 200px; padding: 1rem;">
                                    <ul style="display: flex; flex-direction: column; gap: 0.5rem; font-size: 0.825rem; margin: 0; padding: 0; list-style-type: none;">
                                        <?php
                                        $id_order = $row['id_order'];
                                        $q_det = mysqli_query($koneksi, "SELECT od.*, o.nama_obat FROM order_details od JOIN data_obat o ON od.id_obat = o.id_obat WHERE od.id_order = '$id_order'");
                                        while($det = mysqli_fetch_assoc($q_det)) {
                                            echo "<li style='display: flex; align-items: start; gap: 0.5rem; border-bottom: 2px solid var(--gray-100); padding-bottom: 0.35rem; font-weight: 700;'>
                                                    <span class='badge-neo bg-yellow' style='padding: 0 0.25rem; font-size: 0.7rem; border-width: 1px; border-color: var(--black); box-shadow: 1px 1px 0 var(--black); font-weight: 900;'>{$det['jumlah']}x</span>
                                                    <span style='margin-top: 1px;'>{$det['nama_obat']}</span>
                                                  </li>";
                                        }
                                        ?>
                                    </ul>
                                </td>
                                
                                <td style="border-right: 4px solid var(--black); padding: 1rem;">
                                    <form method="POST" style="display: flex; flex-direction: column; gap: 0.5rem; margin: 0;">
                                        <input type="hidden" name="id_order" value="<?= $row['id_order'] ?>">
                                        <div class="badge-neo <?= $status_color ?>" style="color: var(--black); display: block; text-align: center; padding: 0.5rem; font-weight: 900; margin-bottom: 0.5rem; border-color: var(--black); box-shadow: 2px 2px 0 var(--black);">
                                            <?= strtoupper($row['status']) ?>
                                        </div>
                                        <select name="status" class="input-neo" style="width: 100%; text-transform: uppercase; border-width: 3px; padding: 0.5rem; font-size: 0.875rem; box-shadow: 2px 2px 0 var(--black);">
                                            <option value="pending" <?= $row['status']=='pending'?'selected':'' ?>>Pending</option>
                                            <option value="dibayar" <?= $row['status']=='dibayar'?'selected':'' ?>>Dibayar</option>
                                            <option value="dikirim" <?= $row['status']=='dikirim'?'selected':'' ?>>Dikirim</option>
                                            <option value="selesai" <?= $row['status']=='selesai'?'selected':'' ?>>Selesai</option>
                                            <option value="batal" <?= $row['status']=='batal'?'selected':'' ?>>Batal</option>
                                        </select>
                                </td>
                                
                                <td style="text-align: center; vertical-align: middle; padding: 1rem;">
                                        <button type="submit" name="update_status" class="btn-neo btn-neo-sm bg-green" style="width: 100%; display: flex; flex-direction: row; align-items: center; justify-content: center; gap: 0.4rem; padding: 0.5rem 0.75rem; color: var(--black); border-width: 2px; box-shadow: 2px 2px 0 var(--black);">
                                            <i class="fa-solid fa-floppy-disk text-md"></i>
                                            <span style="font-size: 0.75rem; font-weight: 900; text-transform: uppercase;">Update</span>
                                        </button>
                                    </form>
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

<?php require_once '../templates/footer.php'; ?>
