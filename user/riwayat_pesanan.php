<?php
session_start();
if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/login.php");
    exit();
}
include '../config/koneksi.php';
require_once '../templates/header.php';

$id_user = $_SESSION['id_user'];
$q = mysqli_query($koneksi, "SELECT * FROM orders WHERE id_user = '$id_user' ORDER BY id_order DESC");
?>

<div class="hero-block bg-green" style="padding-top: 3rem; padding-bottom: 3rem;">
    <div class="container text-center">
        <h1 class="heading-hero" style="color: var(--black); text-shadow: 4px 4px 0 #FFF; margin-bottom: 1.5rem;">Riwayat Pesanan</h1>
        <a href="user_home.php" class="btn-neo btn-neo-sm bg-white">Kembali Belanja</a>
    </div>
</div>

<section class="section-py bg-white min-h-screen">
    <div class="container" style="max-width: 64rem;">
        
        <?php if(mysqli_num_rows($q) == 0): ?>
            <div class="neo-box bg-yellow" style="padding: 3rem; text-align: center;">
                <i class="fa-solid fa-box-open text-6xl mb-6" style="display: block; margin: 0 auto 1.5rem;"></i>
                <h3 class="font-black uppercase" style="font-size: 2rem; margin-bottom: 1rem; margin-top: 0;">Belum Ada Pesanan!</h3>
                <p class="font-bold text-lg" style="margin: 0;">Anda belum pernah melakukan transaksi.</p>
            </div>
        <?php else: ?>
            <div style="display: flex; flex-direction: column; gap: 2.5rem;">
                <?php while($row = mysqli_fetch_assoc($q)): 
                    $status_color = 'bg-yellow';
                    if ($row['status'] == 'pending') $status_color = 'bg-yellow';
                    if ($row['status'] == 'dibayar') $status_color = 'bg-cyan';
                    if ($row['status'] == 'dikirim') $status_color = 'bg-blue text-white';
                    if ($row['status'] == 'selesai') $status_color = 'bg-green';
                    if ($row['status'] == 'batal') $status_color = 'bg-pink text-white';
                ?>
                <div class="neo-box" style="overflow: hidden; padding: 0;">
                    <div class="flex justify-between items-center wrap gap-4" style="background-color: var(--black); color: var(--white); padding: 1.5rem; border-bottom: 4px solid var(--black);">
                        <div>
                            <span class="font-black text-2xl uppercase tracking-tight">ORDER #<?= $row['id_order'] ?></span>
                            <p class="font-bold text-sm" style="color: var(--gray-400); margin: 0.25rem 0 0 0;"><?= date('d M Y, H:i', strtotime($row['tanggal_order'])) ?></p>
                        </div>
                        <div class="badge-neo <?= $status_color ?>" style="color: var(--black); font-size: 1.125rem; font-weight: 900; padding: 0.5rem 1.5rem; border-color: var(--white);">
                            <?= strtoupper($row['status']) ?>
                        </div>
                    </div>
                    
                    <div style="padding: 2rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem;">
                        <div>
                            <h4 class="font-black uppercase" style="font-size: 1.25rem; border-bottom: 4px solid var(--black); padding-bottom: 0.5rem; display: inline-block; margin-bottom: 1.5rem; margin-top: 0;">Item Pembelian</h4>
                            <ul style="display: flex; flex-direction: column; gap: 0.75rem; font-weight: 700; margin: 0; padding: 0; list-style-type: none;">
                                <?php
                                $id_order = $row['id_order'];
                                $q_det = mysqli_query($koneksi, "SELECT od.*, o.nama_obat FROM order_details od JOIN data_obat o ON od.id_obat = o.id_obat WHERE od.id_order = '$id_order'");
                                while($det = mysqli_fetch_assoc($q_det)) {
                                    echo "<li class='flex items-center gap-2'>
                                            <i class='fa-solid fa-check text-neo-green text-xl' style='filter: drop-shadow(1px 1px 0 #000);'></i> 
                                            {$det['jumlah']}x {$det['nama_obat']}
                                          </li>";
                                }
                                ?>
                            </ul>
                            
                            <div style="margin-top: 2rem; padding-top: 1rem; border-top: 4px dashed var(--black);">
                                <p class="font-bold uppercase text-sm mb-1" style="color: var(--gray-500);">Total Tagihan:</p>
                                <p class="font-black text-3xl" style="margin: 0;">Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></p>
                            </div>
                        </div>
                        
                        <div class="neo-box" style="background-color: #F0F0F0; padding: 1.5rem; position: relative;">
                            <i class="fa-solid fa-truck-fast text-gray-300" style="position: absolute; top: 1rem; right: 1rem; font-size: 2.5rem;"></i>
                            <h4 class="font-black text-lg uppercase mb-4" style="margin-top: 0;">Info Pengiriman</h4>
                            <div class="font-bold space-y-4" style="display: flex; flex-direction: column; gap: 1rem;">
                                <div>
                                    <p style="font-size: 0.75rem; text-transform: uppercase; color: var(--gray-500); margin: 0 0 0.25rem 0;">Penerima</p>
                                    <p class="bg-white" style="border: 2px solid var(--black); border-radius: 6px; padding: 0.5rem; margin: 0; font-size: 1rem; font-weight: 700;"><?= htmlspecialchars($_SESSION['nama_lengkap']) ?></p>
                                </div>
                                <div>
                                    <p style="font-size: 0.75rem; text-transform: uppercase; color: var(--gray-500); margin: 0 0 0.25rem 0;">Nomor HP</p>
                                    <p class="bg-white" style="border: 2px solid var(--black); border-radius: 6px; padding: 0.5rem; margin: 0; font-size: 1rem; font-weight: 700;"><?= htmlspecialchars($row['no_hp'] ?? '-') ?></p>
                                </div>
                                <div>
                                    <p style="font-size: 0.75rem; text-transform: uppercase; color: var(--gray-500); margin: 0 0 0.25rem 0;">Alamat Tujuan</p>
                                    <p class="bg-white" style="border: 2px solid var(--black); border-radius: 6px; padding: 0.5rem; margin: 0; font-size: 1rem; font-weight: 700;"><?= htmlspecialchars($row['alamat'] ?? '-') ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
        
    </div>
</section>

<?php require_once '../templates/footer.php'; ?>
