<?php
session_start();
if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/login.php");
    exit();
}
include '../config/koneksi.php';

$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
if (empty($cart_items)) {
    header("Location: cart.php");
    exit();
}

$id_user = $_SESSION['id_user'];
$ids = implode(',', array_keys($cart_items));
$q = mysqli_query($koneksi, "SELECT * FROM data_obat WHERE id_obat IN ($ids)");
$total_harga = 0;

require_once '../templates/header.php';
?>

<div class="hero-block bg-cyan" style="padding-top: 3rem; padding-bottom: 3rem;">
    <div class="container text-center">
        <h1 class="heading-hero" style="color: var(--black); text-shadow: 4px 4px 0 #FFF; margin-bottom: 1.5rem;">Checkout</h1>
        <a href="cart.php" class="btn-neo btn-neo-sm bg-white">Kembali ke Keranjang</a>
    </div>
</div>

<section class="section-py bg-white min-h-screen">
    <div class="container detail-layout-container" style="max-width: 64rem;">
        
        <!-- Form Pengiriman -->
        <div class="detail-image-side" style="flex: 1.5;">
            <div class="neo-box bg-white" style="padding: 2rem;">
                <div class="flex items-center gap-4 mb-8" style="border-bottom: 4px solid var(--black); padding-bottom: 1rem;">
                    <div class="badge-neo bg-yellow" style="font-size: 1.5rem; font-weight: 900; width: 3rem; height: 3rem; display: flex; align-items: center; justify-content: center; padding: 0;">1</div>
                    <h3 class="font-black uppercase" style="font-size: 1.75rem; margin: 0;">Info Pengiriman</h3>
                </div>
                
                <form action="process_checkout.php" method="POST" style="display: flex; flex-direction: column; gap: 1.5rem;">
                    <div>
                        <label class="block font-black text-xl mb-2 uppercase">Nama Lengkap</label>
                        <input type="text" value="<?= htmlspecialchars($_SESSION['nama_lengkap']) ?>" readonly class="input-neo" style="background-color: var(--gray-200); cursor: not-allowed;">
                    </div>
                    <div>
                        <label class="block font-black text-xl mb-2 uppercase">Nomor HP (WhatsApp)</label>
                        <input type="text" name="no_hp" required placeholder="Contoh: 08123456789" class="input-neo">
                    </div>
                    <div>
                        <label class="block font-black text-xl mb-2 uppercase">Alamat Lengkap</label>
                        <textarea name="alamat" rows="4" required placeholder="Jalan, RT/RW, Kelurahan, Kecamatan, Kota, Kode Pos" class="input-neo"></textarea>
                    </div>
                    
                    <div class="flex items-center gap-4 mb-6 mt-10" style="border-bottom: 4px solid var(--black); padding-bottom: 1rem;">
                        <div class="badge-neo bg-pink" style="font-size: 1.5rem; font-weight: 900; width: 3rem; height: 3rem; display: flex; align-items: center; justify-content: center; padding: 0;">2</div>
                        <h3 class="font-black uppercase" style="font-size: 1.75rem; margin: 0;">Pembayaran</h3>
                    </div>
                    
                    <div class="neo-box bg-green" style="padding: 1.5rem; display: flex; align-items: center; gap: 1rem;">
                        <i class="fa-solid fa-money-bill-wave text-3xl"></i>
                        <div>
                            <p class="text-xl uppercase font-black" style="margin: 0 0 0.25rem 0;">Transfer Bank / COD</p>
                            <p class="text-sm font-bold" style="margin: 0; color: var(--gray-700);">Pembayaran dilakukan secara manual setelah pesanan dikonfirmasi.</p>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-neo btn-neo-lg bg-blue" style="width: 100%; color: var(--white); font-size: 1.5rem; padding: 1.25rem; display: flex; align-items: center; justify-content: center; gap: 0.75rem; margin-top: 2rem;">
                        Buat Pesanan <i class="fa-solid fa-check-circle"></i>
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Ringkasan Pesanan -->
        <div class="detail-info-side" style="flex: 1;">
            <div class="neo-box bg-yellow" style="padding: 1.5rem; position: sticky; top: 8rem;">
                <h3 class="font-black uppercase mb-6" style="font-size: 1.5rem; border-bottom: 4px solid var(--black); padding-bottom: 1rem; margin-top: 0;">Ringkasan Pesanan</h3>
                
                <div style="display: flex; flex-direction: column; gap: 1rem; margin-bottom: 1.5rem;">
                    <?php 
                    while($row = mysqli_fetch_assoc($q)): 
                        $id = $row['id_obat'];
                        $qty = $cart_items[$id];
                        $subtotal = $row['harga'] * $qty;
                        $total_harga += $subtotal;
                    ?>
                    <div class="neo-box bg-white" style="padding: 1rem;">
                        <div class="flex justify-between font-bold" style="margin-bottom: 0.5rem; font-size: 1rem;">
                            <span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 10rem;"><?= htmlspecialchars($row['nama_obat']) ?></span>
                            <span><?= $qty ?>x</span>
                        </div>
                        <div style="text-align: right; font-size: 1.125rem; font-weight: 900;">Rp <?= number_format($subtotal, 0, ',', '.') ?></div>
                    </div>
                    <?php endwhile; ?>
                </div>
                
                <div style="border-top: 4px solid var(--black); padding-top: 1rem; margin-bottom: 1.5rem;">
                    <div class="flex justify-between font-bold" style="font-size: 1.125rem;">
                        <span>Ongkos Kirim</span>
                        <span class="badge-neo bg-green" style="padding: 0.25rem 0.5rem; font-size: 0.875rem;">GRATIS</span>
                    </div>
                </div>
                
                <div class="neo-box bg-black" style="color: var(--white); padding: 1.5rem; text-align: center; transform: rotate(1deg);">
                    <p class="font-bold uppercase text-sm" style="margin: 0 0 0.5rem 0; color: var(--gray-300);">Total Tagihan</p>
                    <p class="font-black text-3xl text-neo-yellow" style="margin: 0; color: var(--neo-yellow);">Rp <?= number_format($total_harga, 0, ',', '.') ?></p>
                </div>
            </div>
        </div>
        
    </div>
</section>

<?php require_once '../templates/footer.php'; ?>
