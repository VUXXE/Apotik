<?php
session_start();
if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/login.php");
    exit();
}
include '../config/koneksi.php';

$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total_harga = 0;

require_once '../templates/header.php';
?>

<div class="hero-block bg-pink" style="padding-top: 3rem; padding-bottom: 3rem;">
    <div class="container text-center">
        <h1 class="heading-hero" style="color: var(--white); text-shadow: 4px 4px 0 #000; margin-bottom: 1.5rem;">Keranjang Belanja</h1>
        <a href="user_home.php" class="btn-neo btn-neo-sm bg-white">Kembali Belanja</a>
    </div>
</div>

<section class="section-py bg-white min-h-screen">
    <div class="container" style="max-width: 56rem;">
        
        <?php if (empty($cart_items)): ?>
            <div class="neo-box bg-yellow" style="padding: 3rem; text-align: center;">
                <i class="fa-solid fa-cart-shopping text-6xl mb-6" style="display: block; margin: 0 auto 1.5rem;"></i>
                <h3 class="font-black uppercase" style="font-size: 2rem; margin-bottom: 1rem; margin-top: 0;">Keranjang Anda Kosong!</h3>
                <p class="font-bold text-lg" style="margin-bottom: 2rem;">Yuk mulai belanja obat untuk kesehatan Anda.</p>
                <a href="user_home.php" class="btn-neo btn-neo-lg bg-black" style="color: var(--white);">Belanja Sekarang</a>
            </div>
        <?php else: ?>
            <div class="table-container neo-box" style="margin-bottom: 2rem;">
                <table class="table-neo">
                    <thead>
                        <tr class="bg-cyan">
                            <th style="border-right: 4px solid var(--black); width: 40%;">Produk</th>
                            <th style="border-right: 4px solid var(--black); width: 20%;">Harga Satuan</th>
                            <th style="border-right: 4px solid var(--black); width: 20%;">Jumlah</th>
                            <th style="border-right: 4px solid var(--black); width: 10%;">Subtotal</th>
                            <th style="width: 10%; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $ids = implode(',', array_keys($cart_items));
                        $q = mysqli_query($koneksi, "SELECT * FROM data_obat WHERE id_obat IN ($ids)");
                        while($row = mysqli_fetch_assoc($q)): 
                            $id = $row['id_obat'];
                            $qty = $cart_items[$id];
                            $subtotal = $row['harga'] * $qty;
                            $total_harga += $subtotal;
                        ?>
                        <tr style="font-weight: 700;">
                            <td style="border-right: 4px solid var(--black);">
                                <div class="flex items-center gap-3">
                                    <div class="nav-brand-logo bg-[#F0F0F0]" style="width: 2.5rem; height: 2.5rem; border-width: 2px; flex-shrink: 0; box-shadow: none;">
                                        <i class="fa-solid fa-pills text-gray-500"></i>
                                    </div>
                                    <span class="text-lg"><?= htmlspecialchars($row['nama_obat']) ?></span>
                                </div>
                            </td>
                            <td style="border-right: 4px solid var(--black); white-space: nowrap;">Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                            <td style="border-right: 4px solid var(--black);">
                                <form action="cart_action.php" method="POST" class="flex items-center gap-2" style="margin: 0;">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="id_obat" value="<?= $id ?>">
                                    <input type="number" name="qty" value="<?= $qty ?>" min="1" class="input-neo" style="width: 4rem; padding: 0.5rem; text-align: center;">
                                    <button type="submit" class="btn-neo btn-neo-sm bg-green" style="padding: 0.5rem 0.75rem;">
                                        <i class="fa-solid fa-rotate"></i>
                                    </button>
                                </form>
                            </td>
                            <td style="border-right: 4px solid var(--black); white-space: nowrap; font-size: 1.125rem;">Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
                            <td style="text-align: center;">
                                <a href="cart_action.php?action=remove&id_obat=<?= $id ?>" class="btn-neo btn-neo-sm bg-pink" style="padding: 0.5rem 0.75rem;">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <div class="flex justify-between items-center wrap gap-6">
                <h3 class="badge-neo bg-yellow" style="font-size: 1.5rem; padding: 1rem 1.5rem; margin: 0;">
                    Total: Rp <?= number_format($total_harga, 0, ',', '.') ?>
                </h3>
                <a href="checkout.php" class="btn-neo btn-neo-lg bg-blue" style="color: var(--white); display: flex; align-items: center; gap: 0.5rem;">
                    Checkout <span class="badge-neo bg-white" style="font-size: 1rem; color: var(--black); padding: 0.25rem 0.5rem; margin-left: 0.5rem;"><?= array_sum($cart_items) ?></span>
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once '../templates/footer.php'; ?>
