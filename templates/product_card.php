<?php
/**
 * Templat Komponen Kartu Produk
 * Ekspektasi variabel: $row (array dari database)
 */
$img_src = ($row['gambar'] && $row['gambar'] !== 'default.jpg') ? 'assets/img/' . $row['gambar'] : null;

// Tentukan base path untuk URL (jika diakses dari folder user/)
$base_path = (basename($_SERVER['PHP_SELF']) == 'user_home.php') ? '../' : '';
$detail_url = $base_path . 'detail.php?id=' . $row['id_obat'];

// Tentukan action cart (jika di user_home maka 'cart_action.php', jika di index maka 'user/cart_action.php')
$cart_action = (basename($_SERVER['PHP_SELF']) == 'user_home.php') ? 'cart_action.php' : 'user/cart_action.php';
?>
<div class="product-card">
    <div class="product-card-image-box">
        <div class="badge-neo bg-pink product-card-badge">
            <?= strtoupper(htmlspecialchars($row['nama_kategori'])) ?>
        </div>
        <?php if($img_src): ?>
            <!-- Note: if called from user_home.php, the path to assets is ../assets/img, so we use absolute path /Apotik/ -->
            <img src="/Apotik/<?= $img_src ?>" alt="<?= htmlspecialchars($row['nama_obat']) ?>" class="product-card-image">
        <?php else: ?>
            <i class="fa-solid fa-pills text-6xl text-gray-400 drop-shadow-[2px_2px_0_rgba(0,0,0,1)]"></i>
        <?php endif; ?>
    </div>
    <div class="product-card-content">
        <h3 class="product-card-title">
            <a href="<?= $detail_url ?>"><?= htmlspecialchars($row['nama_obat']) ?></a>
        </h3>
        <p class="product-card-stock">Stok: <?= $row['stok'] ?> Pcs</p>
        
        <div class="product-card-footer">
            <span class="product-card-price">Rp <?= number_format($row['harga'], 0, ',', '.') ?></span>
            
            <?php if($row['stok'] > 0): ?>
                <form action="<?= $cart_action ?>" method="POST">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="id_obat" value="<?= $row['id_obat'] ?>">
                    <button type="submit" class="btn-neo btn-neo-sm bg-yellow">
                        <i class="fa-solid fa-cart-plus text-xl"></i>
                    </button>
                </form>
            <?php else: ?>
                <button disabled class="btn-disabled btn-neo-sm">
                    <i class="fa-solid fa-ban text-xl"></i>
                </button>
            <?php endif; ?>
        </div>
    </div>
</div>
