<?php
session_start();
include '../config/koneksi.php';

if (isset($_POST['register'])) {
    $nama_lengkap = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    $cek = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
    if(mysqli_num_rows($cek) > 0) {
        $error = "Username sudah digunakan!";
    } else {
        $query = "INSERT INTO users (nama_lengkap, username, password, role) VALUES ('$nama_lengkap', '$username', '$password', 'customer')";
        if (mysqli_query($koneksi, $query)) {
            echo "<script>alert('Pendaftaran berhasil! Silakan login.'); window.location='login.php';</script>";
            exit();
        } else {
            $error = "Gagal mendaftar!";
        }
    }
}

$hide_navbar = true;
$hide_footer = true;
require_once '../templates/header.php';
?>

<style>
    /* Disable hover/active motion animations on auth page */
    .btn-neo, .nav-brand-logo, .auth-card {
        transition: none !important;
        transform: none !important;
    }
    .btn-neo:hover, .btn-neo:active, .nav-brand-logo:hover {
        transform: none !important;
    }
</style>

<div class="auth-container" style="min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; background-color: transparent; padding: 2rem 1.5rem;">
    <!-- Back to home button -->
    <a href="../index.php" class="btn-neo btn-neo-sm bg-white text-black" style="margin-bottom: 2rem; display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none; align-self: center; font-size: 0.75rem; padding: 0.5rem 1rem;">
        <i class="fa-solid fa-arrow-left"></i> KEMBALI KE BERANDA
    </a>

    <div class="auth-card neo-box bg-white" style="width: 100%; max-width: 400px; padding: 2rem;">
        <!-- Logo Header -->
        <div style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; margin-bottom: 1.5rem;">
            <div class="nav-brand-logo bg-yellow" style="width: 2.25rem; height: 2.25rem; border-width: 2px; font-size: 0.875rem;">
                <i class="fa-solid fa-plus"></i>
            </div>
            <span class="font-black text-xl uppercase" style="letter-spacing: 0.05em; color: var(--black);">Apotik.Neo</span>
        </div>

        <h2 class="auth-card-title" style="font-size: 1.75rem; padding-bottom: 0.5rem; margin-bottom: 1.5rem; text-align: center; border-bottom: 3px solid var(--black);">DAFTAR AKUN</h2>
        
        <?php if(isset($error)): ?>
            <div class="badge-neo bg-pink" style="display: flex; align-items: center; gap: 0.75rem; width: 100%; margin-bottom: 1.5rem; padding: 0.75rem; border-width: 2px; box-shadow: 2px 2px 0px 0px rgba(0,0,0,1);">
                <i class="fa-solid fa-triangle-exclamation text-lg"></i>
                <p style="margin: 0; font-size: 0.875rem; font-weight: 700;"><?= $error ?></p>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="" style="display: flex; flex-direction: column; gap: 1.25rem;">
            <div>
                <label class="block font-black text-sm mb-1 uppercase" style="letter-spacing: 0.02em;">Nama Lengkap</label>
                <div style="position: relative;">
                    <span style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--gray-600);"><i class="fa-solid fa-id-card"></i></span>
                    <input type="text" name="nama_lengkap" required class="input-neo" style="width: 100%; padding-left: 2.75rem !important; box-sizing: border-box;">
                </div>
            </div>
            <div>
                <label class="block font-black text-sm mb-1 uppercase" style="letter-spacing: 0.02em;">Username</label>
                <div style="position: relative;">
                    <span style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--gray-600);"><i class="fa-solid fa-user"></i></span>
                    <input type="text" name="username" required class="input-neo" style="width: 100%; padding-left: 2.75rem !important; box-sizing: border-box;">
                </div>
            </div>
            <div>
                <label class="block font-black text-sm mb-1 uppercase" style="letter-spacing: 0.02em;">Password</label>
                <div style="position: relative;">
                    <span style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--gray-600);"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" name="password" required class="input-neo" style="width: 100%; padding-left: 2.75rem !important; box-sizing: border-box;">
                </div>
            </div>
            <button type="submit" name="register" class="btn-neo btn-neo-lg bg-blue" style="width: 100%; margin-top: 1rem; color: var(--white); display: flex; justify-content: center; align-items: center;">
                Daftar <i class="fa-solid fa-user-plus" style="margin-left: 0.5rem;"></i>
            </button>
        </form>
        
        <p class="text-center font-bold text-sm" style="margin-top: 2.5rem; margin-bottom: 0; color: var(--gray-700);">
            Sudah punya akun? <br>
            <a href="login.php" class="btn-neo btn-neo-sm bg-yellow" style="display: inline-flex; justify-content: center; align-items: center; margin-top: 0.75rem; width: 100%;">Login di Sini</a>
        </p>
    </div>
</div>

<?php require_once '../templates/footer.php'; ?>
