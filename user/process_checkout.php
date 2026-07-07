<?php
session_start();
if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/login.php");
    exit();
}
include '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
    if (empty($cart_items)) {
        header("Location: cart.php");
        exit();
    }

    $id_user = $_SESSION['id_user'];
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $no_hp = mysqli_real_escape_string($koneksi, $_POST['no_hp']);
    
    // Hitung total harga
    $ids = implode(',', array_keys($cart_items));
    $q = mysqli_query($koneksi, "SELECT * FROM data_obat WHERE id_obat IN ($ids)");
    $total_harga = 0;
    $produk_data = [];
    
    while ($row = mysqli_fetch_assoc($q)) {
        $id = $row['id_obat'];
        $qty = $cart_items[$id];
        
        // Cek stok kembali sebelum checkout
        if ($qty > $row['stok']) {
            die("Stok untuk {$row['nama_obat']} tidak mencukupi (Sisa: {$row['stok']}). Silakan kembali dan kurangi jumlahnya.");
        }
        
        $subtotal = $row['harga'] * $qty;
        $total_harga += $subtotal;
        
        $produk_data[] = [
            'id_obat' => $id,
            'qty' => $qty,
            'harga_satuan' => $row['harga']
        ];
    }
    
    // Insert Order
    $sql_order = "INSERT INTO orders (id_user, total_harga, status, alamat, no_hp) VALUES ('$id_user', '$total_harga', 'pending', '$alamat', '$no_hp')";
    if (mysqli_query($koneksi, $sql_order)) {
        $id_order = mysqli_insert_id($koneksi);
        
        // Insert Order Details dan Kurangi Stok
        foreach ($produk_data as $pd) {
            $id_obat = $pd['id_obat'];
            $qty = $pd['qty'];
            $harga = $pd['harga_satuan'];
            
            mysqli_query($koneksi, "INSERT INTO order_details (id_order, id_obat, jumlah, harga_satuan) VALUES ('$id_order', '$id_obat', '$qty', '$harga')");
            mysqli_query($koneksi, "UPDATE data_obat SET stok = stok - $qty WHERE id_obat = '$id_obat'");
        }
        
        // Bersihkan Keranjang
        unset($_SESSION['cart']);
        
        // Tampilkan halaman sukses
        require_once '../templates/header.php';
        ?>
        <div class="bg-[#F0F0F0] py-20 px-6 min-h-[70vh] flex items-center justify-center relative overflow-hidden">
            <!-- Background pattern -->
            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 2px 2px, black 2px, transparent 0); background-size: 20px 20px;"></div>
            
            <div class="bg-white border-4 border-black p-8 md:p-12 shadow-[8px_8px_0_0_#000] text-center max-w-2xl mx-auto transform -rotate-1 relative z-10">
                <i class="fa-solid fa-circle-check text-7xl md:text-8xl text-neo-green drop-shadow-[4px_4px_0_rgba(0,0,0,1)] mb-6"></i>
                <h1 class="text-3xl md:text-5xl font-black uppercase mb-4 leading-tight">Pesanan Berhasil<br>Dibuat!</h1>
                <p class="text-lg md:text-xl font-bold mb-8 text-gray-800">
                    Terima kasih, <span class="bg-neo-yellow px-2 border-2 border-black inline-block transform rotate-2"><?= htmlspecialchars($_SESSION['nama_lengkap']) ?></span>.<br>
                    Pesanan Anda (ID: <span class="font-black text-neo-blue">#<?= $id_order ?></span>) telah masuk dan sedang menunggu pembayaran.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="/Apotik/user/riwayat_pesanan.php" class="bg-neo-cyan border-4 border-black px-6 py-4 text-lg font-black uppercase shadow-neo hover:bg-black hover:text-white transition-colors btn-neo flex items-center justify-center gap-2">
                        <i class="fa-solid fa-clipboard-list"></i> Lihat Pesanan
                    </a>
                    <a href="/Apotik/index.php" class="bg-neo-pink border-4 border-black px-6 py-4 text-lg font-black uppercase shadow-neo hover:bg-black hover:text-white transition-colors btn-neo flex items-center justify-center gap-2">
                        <i class="fa-solid fa-store"></i> Kembali Belanja
                    </a>
                </div>
            </div>
        </div>
        <?php require_once '../templates/footer.php'; ?>
        <?php
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
} else {
    header("Location: cart.php");
    exit();
}
?>
