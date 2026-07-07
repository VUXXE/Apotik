<?php
session_start();
include 'config/koneksi.php';
require_once 'templates/header.php';
?>

<div class="hero-block terms-header">
    <div class="container text-center">
        <div class="badge-neo bg-black-flat terms-badge">
            Legal & Regulasi
        </div>
        <h1 class="heading-hero terms-title">
            Syarat & Ketentuan
        </h1>
        <p class="font-bold bg-white inline-block terms-subtitle">
            Aturan penggunaan dan kesepakatan transaksi belanja obat di Apotik.Neo.
        </p>
    </div>
</div>

<section class="section-py bg-white min-h-screen-60">
    <div class="container terms-container">
        
        <!-- Peringatan Obat Keras -->
        <div class="neo-box terms-alert-box">
            <div class="neo-box-sm terms-alert-icon-box">
                <i class="fa-solid fa-triangle-exclamation" style="font-size: 1.875rem;"></i>
            </div>
            <div>
                <h3 class="font-black uppercase terms-alert-title">Peringatan Penting Obat Keras!</h3>
                <p class="font-bold style-gray-800 terms-alert-text">
                    Sesuai dengan regulasi Kementerian Kesehatan Republik Indonesia, pembelian kategori obat keras berlogo lingkaran merah (huruf K) **WAJIB** melampirkan resep dokter yang sah dan akan diverifikasi langsung oleh apoteker kami sebelum obat diserahkan.
                </p>
            </div>
        </div>

        <!-- Ketentuan Terstruktur -->
        <div class="terms-list">
            
            <div class="neo-box terms-card">
                <h2 class="font-black uppercase terms-card-title pink">1. Ketentuan Umum</h2>
                <p style="margin-bottom: 1rem;">
                    Dengan mengakses dan melakukan pemesanan di Apotik.Neo, Anda secara otomatis menyetujui seluruh ketentuan yang kami buat. Pengguna wajib berusia minimal 18 tahun untuk dapat membuat akun belanja mandiri.
                </p>
                <p style="margin: 0;">
                    Setiap data akun yang didaftarkan wajib menggunakan identitas yang asli untuk keperluan pelacakan dan jaminan pengiriman kurir.
                </p>
            </div>

            <div class="neo-box terms-card">
                <h2 class="font-black uppercase terms-card-title blue">2. Transaksi & Pembayaran</h2>
                <ul class="terms-bullet-list">
                    <li>Semua harga produk yang tertera di website adalah harga nett dan sudah termasuk pajak.</li>
                    <li>Pembayaran wajib diselesaikan dalam jangka waktu maksimal 1x24 jam setelah nomor transaksi dibuat.</li>
                    <li>Apotik.Neo berhak membatalkan pesanan secara sepihak jika stok obat habis secara mendadak atau terjadi kegagalan sistem sinkronisasi stok.</li>
                </ul>
            </div>

            <div class="neo-box terms-card">
                <h2 class="font-black uppercase terms-card-title green">3. Pengiriman & Retur</h2>
                <ul class="terms-bullet-list">
                    <li>Pengiriman kilat instant hanya menjangkau radius wilayah operational cabang terdekat kami (maksimal 25 km).</li>
                    <li>Barang yang sudah dibeli dan diserahkan ke kurir pengirim tidak dapat dibatalkan ataupun ditukar kecuali karena kelalaian pengemasan jenis obat oleh tim kami.</li>
                    <li>Komplain kerusakan fisik paket saat diterima hanya dilayani jika melampirkan rekaman video pembukaan segel (*unboxing video*) yang jelas tanpa terputus.</li>
                </ul>
            </div>

            <div class="neo-box terms-card">
                <h2 class="font-black uppercase terms-card-title yellow">4. Kebijakan Privasi</h2>
                <p style="margin: 0;">
                    Semua riwayat resep medis, konsultasi apoteker, dan informasi data diri pribadi Anda dijamin kerahasiaannya dan tidak akan pernah disalahgunakan ataupun dijual ke pihak ketiga mana pun di luar tujuan layanan operasional kesehatan kami.
                </p>
            </div>

        </div>
    </div>
</section>

<?php require_once 'templates/footer.php'; ?>
