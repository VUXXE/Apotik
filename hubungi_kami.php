<?php
session_start();
include 'config/koneksi.php';
require_once 'templates/header.php';
?>

<div class="hero-block contact-header">
    <div class="container text-center">
        <div class="badge-neo bg-black-flat contact-badge">
            Kontak Kami
        </div>
        <h1 class="heading-hero contact-title">
            Hubungi Kami
        </h1>
        <p class="font-bold bg-white contact-subtitle" style="display: block; margin: 0 auto; text-align: center;">
            Ada pertanyaan atau ingin konsultasi obat? Kirim pesan Anda sekarang!
        </p>
    </div>
</div>

<section class="section-py bg-white min-h-screen-70">
    <div class="container contact-container">
        <div class="detail-layout-container">

            <!-- Info Kontak -->
            <div class="detail-info-side contact-info-list">
                <div class="neo-box bg-yellow contact-info-card">
                    <h3 class="font-black uppercase mb-4 contact-info-title">
                        <i class="fa-brands fa-whatsapp" style="color: #25D366; font-size: 1.875rem;"></i> WhatsApp
                    </h3>
                    <p class="font-bold text-gray-800 contact-info-text" style="margin-bottom: 1rem;">
                        Chat langsung apoteker siaga kami untuk konsultasi obat cepat 24 jam.
                    </p>
                    <a href="https://wa.me/6285157839155" target="_blank" class="btn-neo btn-neo-sm bg-black-flat"
                        style="color: var(--white); display: flex; align-items: center; justify-content: center; width: 100%; gap: 0.5rem; margin-top: 1rem;">
                        <i class="fa-brands fa-whatsapp" style="font-size: 1.25rem;"></i> Hubungi via WA
                    </a>
                </div>

                <div class="neo-box bg-cyan contact-info-card">
                    <h3 class="font-black uppercase mb-4 contact-info-title">
                        <i class="fa-solid fa-envelope" style="font-size: 1.875rem;"></i> Email Resmi
                    </h3>
                    <p class="font-bold text-gray-800 contact-info-text">
                        Pertanyaan kerja sama atau komplain pelanggan resmi kirimkan ke:
                    </p>
                    <p class="font-black text-black underline contact-info-text"
                        style="font-size: 1.25rem; margin-top: 0.5rem;">
                        support@apotikneo.com
                    </p>
                </div>

                <div class="neo-box bg-green contact-info-card">
                    <h3 class="font-black uppercase mb-4 contact-info-title">
                        <i class="fa-solid fa-clock" style="font-size: 1.875rem;"></i> Jam Operasional
                    </h3>
                    <p class="font-bold text-gray-800 contact-info-text">
                        Toko Fisik: 07.00 - 22.00 WIB<br>
                        Layanan Online & WA: 24 Jam Non-Stop
                    </p>
                </div>
            </div>

            <!-- Form Kontak -->
            <div class="detail-image-side contact-form-side">
                <div class="neo-box bg-pink contact-form-card">
                    <h3 class="font-black uppercase mb-6 contact-form-title">
                        <i class="fa-solid fa-paper-plane text-neo-pink" style="font-size: 1.875rem;"></i> Kirim Pesan
                    </h3>

                    <form id="contactForm" class="contact-form">
                        <div class="contact-form-row">
                            <div class="contact-form-group">
                                <label class="contact-form-label">Nama Lengkap</label>
                                <input type="text" required class="input-neo">
                            </div>
                            <div class="contact-form-group">
                                <label class="contact-form-label">Email Anda</label>
                                <input type="email" required class="input-neo">
                            </div>
                        </div>

                        <div class="contact-form-group">
                            <label class="contact-form-label">Subjek Pesan</label>
                            <input type="text" required class="input-neo">
                        </div>

                        <div class="contact-form-group">
                            <label class="contact-form-label">Isi Pesan Anda</label>
                            <textarea required rows="5" class="input-neo"></textarea>
                        </div>

                        <button type="submit" class="btn-neo btn-neo-lg bg-cyan text-black contact-submit-button"
                            style="margin-top: 1rem;">
                            Kirim Sekarang <i class="fa-solid fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>

<script>
    document.getElementById('contactForm').addEventListener('submit', function (e) {
        e.preventDefault();
        alert('Pesan berhasil terkirim! Tim apoteker kami akan membalas via email secepatnya.');
        this.reset();
    });
</script>

<?php require_once 'templates/footer.php'; ?>