<?php
session_start();
include '../config/koneksi.php';

$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total_harga = 0;

require_once '../templates/header.php';
?>

<style>
/* Hide number input spinners for cart */
input.qty-input::-webkit-outer-spin-button,
input.qty-input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
input.qty-input {
  -moz-appearance: textfield;
}

/* Custom styling for cart items on mobile */
@media (max-width: 640px) {
    .cart-item-row {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 1rem !important;
    }
    .cart-item-actions {
        width: 100%;
        justify-content: space-between;
    }
    .cart-item-image {
        width: 100% !important;
        height: 120px !important;
    }
}
</style>

<div class="hero-block bg-pink" style="padding-top: 3rem; padding-bottom: 3rem;">
    <div class="container text-center">
        <h1 class="heading-hero" style="color: var(--white); margin-bottom: 1.5rem;">Keranjang Belanja</h1>
        <a href="user_home.php" class="btn-neo btn-neo-sm bg-white" style="color: var(--ink) !important;">Lanjut Belanja</a>
    </div>
</div>

<section class="section-py bg-canvas min-h-screen">
    <div class="container detail-layout-container" style="max-width: 68rem;">
        
        <?php if (empty($cart_items)): ?>
            <div style="width: 100%; text-align: center; padding: 6rem 2rem;">
                <div style="width: 120px; height: 120px; background-color: var(--accent-blue-soft); color: var(--link-blue); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 2rem;">
                    <i class="fa-solid fa-cart-shopping text-5xl" style="font-weight: 900;"></i>
                </div>
                <h3 class="font-black text-3xl mb-4" style="color: var(--ink); letter-spacing: -0.5px;">Wah, keranjang belanjaanmu kosong!</h3>
                <p class="text-lg mb-8" style="color: var(--body);">Daripada dianggurin, mending isi dengan produk kesehatan terbaik.<br>Yuk, cek promo dan kategori andalan kami.</p>
                <a href="user_home.php" class="btn-neo btn-neo-lg bg-blue" style="padding: 0 2.5rem; height: 3.5rem !important; font-size: 1.125rem;">Mulai Belanja</a>
            </div>
        <?php else: ?>
            
            <!-- Kiri: Daftar Keranjang -->
            <div class="detail-image-side" style="flex: 1.8;">
                <div class="flex items-center gap-4 mb-6" style="border-bottom: 2px solid var(--hairline-soft); padding-bottom: 1rem;">
                    <h3 class="font-black uppercase" style="font-size: 1.5rem; margin: 0; color: var(--ink);">Daftar Belanjaan</h3>
                </div>

                <div style="display: flex; flex-direction: column; gap: 1.25rem; margin-bottom: 2rem;">
                    <?php 
                    $ids = implode(',', array_keys($cart_items));
                    $q = mysqli_query($koneksi, "SELECT * FROM data_obat WHERE id_obat IN ($ids)");
                    while($row = mysqli_fetch_assoc($q)): 
                        $id = $row['id_obat'];
                        $qty = $cart_items[$id];
                        $subtotal = $row['harga'] * $qty;
                        $total_harga += $subtotal;
                        
                        $img_src = ($row['gambar'] && $row['gambar'] !== 'default.jpg') ? 'assets/img/' . $row['gambar'] : null;
                    ?>
                    <div class="neo-box-sm cart-item-row" style="display: flex; gap: 1.5rem; padding: 1.25rem; align-items: center; border-radius: 12px; background: var(--white); border-color: var(--hairline-soft) !important;">
                        <!-- Image -->
                        <div class="cart-item-image" style="width: 5.5rem; height: 5.5rem; background-color: var(--canvas); border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; overflow: hidden; border: 1px solid var(--hairline-soft);">
                            <?php if($img_src): ?>
                                <img src="<?= BASE_URL ?>/<?= $img_src ?>" alt="" style="width: 100%; height: 100%; object-fit: contain; padding: 0.25rem;">
                            <?php else: ?>
                                <i class="fa-solid fa-pills text-3xl text-gray-300"></i>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Info -->
                        <div style="flex: 1;">
                            <h4 class="font-bold text-lg" style="margin: 0 0 0.35rem 0; color: var(--ink); line-height: 1.3;">
                                <a href="detail.php?id=<?= $id ?>" style="color: inherit; text-decoration: none;" class="hover:text-blue-600 transition-colors"><?= htmlspecialchars($row['nama_obat']) ?></a>
                            </h4>
                            <p class="font-bold text-md" style="margin: 0 0 0.5rem 0; color: var(--primary);">Rp <?= number_format($row['harga'], 0, ',', '.') ?></p>
                            <?php if ($row['stok'] < 10): ?>
                                <p class="text-xs font-semibold m-0" style="color: var(--accent-red);">Sisa stok: <?= $row['stok'] ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Actions -->
                        <div class="cart-item-actions" style="display: flex; align-items: center; gap: 1.25rem;">
                            <div style="font-weight: 800; font-size: 1.125rem; text-align: right; color: var(--ink);" class="hidden md:block">
                                Rp <?= number_format($subtotal, 0, ',', '.') ?>
                            </div>

                            <form action="cart_action.php" method="POST" style="display: flex; align-items: center; margin: 0; background: var(--surface-card); border-radius: 6px; border: 1px solid var(--hairline);">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="id_obat" value="<?= $id ?>">
                                
                                <button type="button" onclick="this.parentNode.querySelector('input[type=number]').stepDown(); this.parentNode.submit();" style="border: none; background: transparent; padding: 0.5rem 0.75rem; cursor: pointer; color: var(--muted); border-right: 1px solid var(--hairline); transition: color 0.15s ease;" onmouseover="this.style.color='var(--ink)'" onmouseout="this.style.color='var(--muted)'">
                                    <i class="fa-solid fa-minus text-xs" style="font-weight: 900;"></i>
                                </button>
                                
                                <input type="number" name="qty" value="<?= $qty ?>" min="1" max="<?= $row['stok'] ?>" class="qty-input" style="width: 2.5rem; text-align: center; border: none; background: transparent; font-weight: 700; color: var(--ink); padding: 0; outline: none;">
                                
                                <button type="button" onclick="this.parentNode.querySelector('input[type=number]').stepUp(); this.parentNode.submit();" style="border: none; background: transparent; padding: 0.5rem 0.75rem; cursor: pointer; color: var(--muted); border-left: 1px solid var(--hairline); transition: color 0.15s ease;" onmouseover="this.style.color='var(--ink)'" onmouseout="this.style.color='var(--muted)'">
                                    <i class="fa-solid fa-plus text-xs" style="font-weight: 900;"></i>
                                </button>
                            </form>
                            
                            <a href="cart_action.php?action=remove&id_obat=<?= $id ?>" class="btn-neo btn-neo-sm bg-white" style="color: var(--muted); border-color: transparent !important; padding: 0.5rem;" title="Hapus Item" onmouseover="this.style.color='var(--accent-red)'; this.style.backgroundColor='var(--accent-red-soft)';" onmouseout="this.style.color='var(--muted)'; this.style.backgroundColor='var(--white)';">
                                <i class="fa-solid fa-trash" style="font-weight: 900;"></i>
                            </a>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>

            <!-- Kanan: Ringkasan Pesanan -->
            <div class="detail-info-side" style="flex: 1;">
                <div class="neo-box bg-white" style="padding: 1.5rem; position: sticky; top: 6rem; border-radius: 12px; border-color: var(--hairline-soft) !important; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03) !important;">
                    <h3 class="font-black uppercase mb-6" style="font-size: 1.25rem; border-bottom: 2px solid var(--hairline-soft); padding-bottom: 1rem; margin-top: 0; color: var(--ink);">Ringkasan Belanja</h3>
                    
                    <div class="flex justify-between font-medium text-sm" style="margin-bottom: 1rem; color: var(--body);">
                        <span>Total Harga (<?= array_sum($cart_items) ?> Barang)</span>
                        <span style="color: var(--ink); font-weight: 700;">Rp <?= number_format($total_harga, 0, ',', '.') ?></span>
                    </div>
                    
                    <div class="flex justify-between font-medium text-sm" style="margin-bottom: 1.5rem; color: var(--body);">
                        <span>Total Diskon Barang</span>
                        <span style="color: var(--accent-green); font-weight: 700;">- Rp 0</span>
                    </div>
                    
                    <div style="border-top: 2px dashed var(--hairline-soft); margin-bottom: 1.5rem; padding-top: 1.5rem;">
                        <div class="flex justify-between items-center mb-1">
                            <span class="font-bold text-lg" style="color: var(--ink);">Total Belanja</span>
                            <span class="font-black text-2xl" style="color: var(--ink);">Rp <?= number_format($total_harga, 0, ',', '.') ?></span>
                        </div>
                    </div>
                    
                    <a href="checkout.php" class="btn-neo btn-neo-lg bg-blue" style="width: 100%; display: flex; align-items: center; justify-content: center; font-size: 1.125rem; height: 3.5rem !important; border-radius: 8px !important;">
                        Beli (<?= array_sum($cart_items) ?>)
                    </a>
                </div>
            </div>
            
        <?php endif; ?>
    </div>
</section>

<?php require_once '../templates/footer.php'; ?>
