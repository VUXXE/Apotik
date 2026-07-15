    <!-- Footer -->
    <?php if (!isset($hide_footer) || !$hide_footer): ?>
        <?php 
        $is_admin_page = (strpos($_SERVER['PHP_SELF'], '/admin/') !== false);
        if ($is_admin_page): 
        ?>
            <!-- No footer in admin mode -->
        <?php else: ?>
            <footer class="footer-neo-block">
                <div class="container footer-grid">
                    <div style="grid-column: span 1;">
                        <div class="flex items-center gap-2 mb-6">
                            <div class="nav-brand-logo bg-white" style="color: var(--black);">
                                <i class="fa-solid fa-plus"></i>
                            </div>
                            <span class="font-black text-4xl uppercase">Apotik.Neo</span>
                        </div>
                        <p class="font-bold mb-6" style="font-size: 1.25rem; max-w: 28rem;">Solusi kesehatan nomor satu dengan gaya. Anti ribet, anti mahal.</p>
                    </div>
                    
                    <div>
                        <h4 class="footer-heading bg-yellow">Menu</h4>
                        <ul class="footer-list">
                            <li><a href="#" class="footer-link-pink">Tentang Kami</a></li>
                            <li><a href="<?= BASE_URL ?>/index.php" class="footer-link-pink">Katalog Obat</a></li>
                            <li><a href="#" class="footer-link-pink">Promo Hari Ini</a></li>
                        </ul>
                    </div>
                    
                    <div>
                        <h4 class="footer-heading bg-cyan">Bantuan</h4>
                        <ul class="footer-list">
                            <li><a href="<?= BASE_URL ?>/faq.php" class="footer-link-cyan">FAQ</a></li>
                            <li><a href="<?= BASE_URL ?>/syarat_ketentuan.php" class="footer-link-cyan">Syarat & Ketentuan</a></li>
                            <li><a href="<?= BASE_URL ?>/hubungi_kami.php" class="footer-link-cyan">Hubungi Kami</a></li>
                        </ul>
                    </div>
                </div>
                
                <div class="container footer-bottom-bar">
                    <p class="font-bold">&copy; <?= date('Y') ?> APOTIK.NEO - HANAN TAQIYYA</p>
                </div>
            </footer>
        <?php endif; ?>

        <!-- Sticky WhatsApp CTA -->
        <a href="https://wa.me/6285157839155?text=Halo%20Apotik%20Neo,%20saya%20butuh%20bantuan" target="_blank" class="whatsapp-floating">
            <i class="fa-brands fa-whatsapp text-3xl"></i>
            <span class="font-black uppercase text-lg hidden md:block">Chat Kami</span>
        </a>
    <?php endif; ?>

    <!-- Interactive Scripts -->
    <script>
        function toggleMobileSearch() {
            const searchDiv = document.getElementById('mobile-search');
            if (searchDiv.classList.contains('hidden')) {
                searchDiv.classList.remove('hidden');
            } else {
                searchDiv.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
