<?php
session_start();
include '../config/koneksi.php';

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];

    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
    if (mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_assoc($query);
        if (password_verify($password, $data['password'])) {
            $_SESSION['id_user'] = $data['id_user'];
            $_SESSION['nama_lengkap'] = $data['nama_lengkap'];
            $_SESSION['role'] = $data['role'];
            
            if ($data['role'] == 'admin') {
                header("Location: ../admin/admin_dashboard.php");
            } else {
                header("Location: ../user/user_home.php");
            }
            exit();
        } else {
            $error = "Password yang Anda masukkan salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
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

<div class="auth-container bg-pink" style="min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; background-color: var(--neo-pink); padding: 2rem 1.5rem;">
    <!-- Back to home button -->
    <a href="../index.php" class="btn-neo btn-neo-sm bg-white" style="margin-bottom: 2rem; display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none; color: var(--black); align-self: center; font-size: 0.75rem; padding: 0.5rem 1rem;">
        <i class="fa-solid fa-arrow-left"></i> KEMBALI KE BERANDA
    </a>

    <div class="auth-card" style="box-shadow: 6px 6px 0px 0px rgba(0,0,0,1);">
        <!-- Logo Header -->
        <div style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; margin-bottom: 1.5rem;">
            <div class="nav-brand-logo bg-yellow" style="width: 2.25rem; height: 2.25rem; border-width: 2px; font-size: 0.875rem;">
                <i class="fa-solid fa-plus"></i>
            </div>
            <span class="font-black text-xl uppercase" style="letter-spacing: 0.05em; color: var(--black);">Apotik.Neo</span>
        </div>

        <h2 class="auth-card-title" style="font-size: 1.75rem; padding-bottom: 0.5rem; margin-bottom: 1.5rem; text-align: center; border-bottom: 3px solid var(--black);">LOGIN AKUN</h2>
        
        <?php if(isset($error)): ?>
            <div class="badge-neo bg-yellow" style="display: flex; align-items: center; gap: 0.75rem; width: 100%; margin-bottom: 1.5rem; padding: 0.75rem; border-width: 2px; box-shadow: 2px 2px 0px 0px rgba(0,0,0,1);">
                <i class="fa-solid fa-triangle-exclamation text-lg"></i>
                <p style="margin: 0; font-size: 0.875rem; font-weight: 700;"><?= $error ?></p>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="" style="display: flex; flex-direction: column; gap: 1.25rem;">
            <div>
                <label class="block font-black text-sm mb-1 uppercase" style="letter-spacing: 0.02em;">Username</label>
                <div style="position: relative;">
                    <span style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--gray-600);"><i class="fa-solid fa-user"></i></span>
                    <input type="text" name="username" required class="input-neo" style="width: 100%; padding-left: 2.5rem; box-sizing: border-box;">
                </div>
            </div>
            <div>
                <label class="block font-black text-sm mb-1 uppercase" style="letter-spacing: 0.02em;">Password</label>
                <div style="position: relative;">
                    <span style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--gray-600);"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" name="password" required class="input-neo" style="width: 100%; padding-left: 2.5rem; box-sizing: border-box;">
                </div>
            </div>
            <button type="submit" name="login" class="btn-neo btn-neo-lg bg-green" style="width: 100%; margin-top: 1rem; box-shadow: 4px 4px 0px 0px rgba(0,0,0,1);">
                Masuk <i class="fa-solid fa-right-to-bracket" style="margin-left: 0.5rem;"></i>
            </button>
        </form>
        
        <p class="text-center font-bold text-sm" style="margin-top: 2.5rem; margin-bottom: 0; color: var(--gray-700);">
            Belum punya akun? <br>
            <a href="register.php" class="btn-neo btn-neo-sm bg-yellow" style="display: inline-block; margin-top: 0.75rem; color: var(--black); font-size: 0.75rem; padding: 0.5rem 1rem; box-shadow: 2px 2px 0px 0px rgba(0,0,0,1);">Daftar sekarang</a>
        </p>
    </div>
</div>

<?php require_once '../templates/footer.php'; ?>
