<?php
session_start();
include 'config/koneksi.php';
require_once 'templates/header.php';
?>

<div class="hero-block faq-header">
    <div class="container text-center">
        <div class="badge-neo bg-black-flat faq-badge">
            Pusat Bantuan
        </div>
        <h1 class="heading-hero faq-title">
            Frequently Asked Questions
        </h1>
        <p class="font-bold bg-white inline-block faq-subtitle">
            Punya pertanyaan seputar layanan kami? Cari jawabannya dengan cepat di bawah ini.
        </p>
    </div>
</div>

<section class="section-py bg-white min-h-screen-60">
    <div class="container faq-container">
            
            <details class="accordion-neo" open>
                <summary>
                    <span>APAKAH SEMUA OBAT DI APOTIK.NEO DIJAMIN ASLI?</span>
                    <span class="icon-box">
                        <i class="fa-solid fa-plus"></i>
                    </span>
                </summary>
                <div class="content-box">
                    Tentu saja! Kami hanya bekerja sama langsung dengan distributor farmasi resmi (*Pedagang Besar Farmasi*) yang berlisensi. Semua obat memiliki nomor registrasi resmi dari **BPOM** (Badan Pengawas Obat dan Makanan).
                </div>
            </details>

            <details class="accordion-neo">
                <summary>
                    <span>BAGAIMANA CARA MEMBELI OBAT YANG MEMBUTUHKAN RESEP DOKTER?</span>
                    <span class="icon-box">
                        <i class="fa-solid fa-plus"></i>
                    </span>
                </summary>
                <div class="content-box">
                    Untuk menebus resep dokter, Anda dapat menekan tombol **"Foto Resep"** di halaman beranda untuk langsung mengirimkan foto resep ke apoteker kami melalui WhatsApp. Apoteker kami akan memverifikasi dan memproses pesanan Anda secara manual.
                </div>
            </details>

            <details class="accordion-neo">
                <summary>
                    <span>BERAPA LAMA WAKTU PENGIRIMAN PESANAN SAYA?</span>
                    <span class="icon-box">
                        <i class="fa-solid fa-plus"></i>
                    </span>
                </summary>
                <div class="content-box">
                    Pesanan instan dalam kota dikirim menggunakan kurir kilat dan tiba dalam **1-3 jam** setelah pembayaran dikonfirmasi. Untuk pengiriman luar kota, kami bekerja sama dengan ekspedisi tepercaya (JNE/J&T) dengan estimasi **1-3 hari kerja**.
                </div>
            </details>

            <details class="accordion-neo">
                <summary>
                    <span>BAGAIMANA CARA MELAKUKAN PEMBAYARAN DI WEBSITE INI?</span>
                    <span class="icon-box">
                        <i class="fa-solid fa-plus"></i>
                    </span>
                </summary>
                <div class="content-box">
                    Saat ini kami mendukung metode pembayaran transfer bank ke rekening resmi Apotik.Neo. Detail nomor rekening dan jumlah yang harus dibayarkan akan ditampilkan di halaman sukses setelah Anda menyelesaikan proses checkout belanja.
                </div>
            </details>

            <details class="accordion-neo">
                <summary>
                    <span>APAKAH SAYA BISA MEMBATALKAN ATAU MERETUR OBAT?</span>
                    <span class="icon-box">
                        <i class="fa-solid fa-plus"></i>
                    </span>
                </summary>
                <div class="content-box">
                    Obat yang sudah dibeli tidak dapat ditukar atau dikembalikan, kecuali terjadi kesalahan pengiriman jenis obat dari pihak kami atau obat diterima dalam kondisi rusak/melewati tanggal kadaluarsa. Harap rekam video unboxing saat membuka paket untuk mengajukan klaim.
                </div>
            </details>

    </div>
</section>

<?php require_once 'templates/footer.php'; ?>
