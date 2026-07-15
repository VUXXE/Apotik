<?php
session_start();
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}
include '../config/koneksi.php';

$id_obat = (int) $_GET['id'];

// Proses Edit Data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_obat = mysqli_real_escape_string($koneksi, $_POST['nama_obat']);
    $id_kategori = (int) $_POST['id_kategori'];
    $harga = (int) $_POST['harga'];
    $stok = (int) $_POST['stok'];
    $tanggal_kadaluarsa = mysqli_real_escape_string($koneksi, $_POST['tanggal_kadaluarsa']);

    // Handle gambar
    $query_gambar = "";
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, $allowed)) {
            $gambar = 'obat_' . time() . '.' . $ext;
            if (!is_dir('../assets/img')) {
                mkdir('../assets/img', 0777, true);
            }
            if (move_uploaded_file($_FILES['gambar']['tmp_name'], '../assets/img/' . $gambar)) {
                $query_gambar = ", gambar = '$gambar'";
            }
        }
    }

    $query = "UPDATE data_obat SET 
                nama_obat = '$nama_obat', 
                id_kategori = '$id_kategori', 
                harga = '$harga', 
                stok = '$stok', 
                tanggal_kadaluarsa = '$tanggal_kadaluarsa' 
                $query_gambar 
                WHERE id_obat = $id_obat";

    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Obat berhasil diupdate!'); window.location='admin_dashboard.php?page=obat';</script>";
        exit();
    } else {
        echo "<script>alert('Gagal mengupdate obat!');</script>";
    }
}

// Ambil data untuk form edit
$q_data = mysqli_query($koneksi, "SELECT * FROM data_obat WHERE id_obat = $id_obat");
$data = mysqli_fetch_assoc($q_data);

// Hide standard top navbar
$hide_navbar = true;

require_once '../templates/header.php';
?>

<div class="admin-wrapper" style="display: flex; min-height: 100vh; background-color: #FDF9F1;">
    
    <!-- Sidebar Left -->
    <?php require_once '../templates/admin_sidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="admin-main" style="flex: 1; display: flex; flex-direction: column; min-width: 0;">
        
        <!-- Top Header -->
        <header class="admin-header">
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <span class="font-black text-lg uppercase tracking-tight" style="color: var(--black); margin-bottom: 0;">Dashboard</span>
                <span class="text-gray-400 font-bold">/</span>
                <span class="text-gray-500 font-bold text-xs uppercase" style="margin-bottom: 0;">Edit Obat</span>
            </div>
        </header>

        <!-- Content Area -->
        <div class="admin-content" style="padding: 2rem; flex: 1; display: flex; justify-content: center; align-items: center;">
            <div class="container" style="max-width: 38rem; margin: 0;">
                <div class="neo-box bg-white" style="padding: 2.5rem; border-width: 6px; box-shadow: 8px 8px 0px var(--black);">
                    <div class="flex items-center gap-3 mb-8" style="border-bottom: 4px solid var(--black); padding-bottom: 1.25rem;">
                        <div class="badge-neo bg-pink" style="width: 3rem; height: 3rem; display: flex; align-items: center; justify-content: center; padding: 0; font-size: 1.5rem; box-shadow: 2px 2px 0 var(--black);">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </div>
                        <h3 class="font-black uppercase" style="font-size: 1.75rem; margin: 0; letter-spacing: -0.01em;">Edit Data Obat</h3>
                    </div>

                    <form method="POST" action="" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 1.25rem;">
                        <div>
                            <label class="block font-black text-xs mb-1 uppercase tracking-widest text-gray-700">Nama Obat</label>
                            <input type="text" name="nama_obat" value="<?= htmlspecialchars($data['nama_obat']) ?>" required class="input-neo" style="border-width: 3px; padding: 0.75rem; font-size: 1rem;">
                        </div>
                        <div>
                            <label class="block font-black text-xs mb-1 uppercase tracking-widest text-gray-700">Kategori</label>
                            <select name="id_kategori" required class="input-neo" style="width: 100%; border-width: 3px; padding: 0.75rem; font-size: 1rem;">
                                <option value="">-- Pilih --</option>
                                <?php
                                $kat = mysqli_query($koneksi, "SELECT * FROM kategori_obat");
                                while ($k = mysqli_fetch_assoc($kat)) {
                                    $selected = ($k['id_kategori'] == $data['id_kategori']) ? 'selected' : '';
                                    echo "<option value='" . $k['id_kategori'] . "' $selected>" . strtoupper($k['nama_kategori']) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div style="display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 1rem;">
                            <div>
                                <label class="block font-black text-xs mb-1 uppercase tracking-widest text-gray-700">Harga</label>
                                <div class="flex" style="margin: 0;">
                                    <span class="badge-neo bg-gray-200" style="padding: 0.75rem; border-right-width: 0; border-radius: 0; font-weight: 900; font-size: 1rem; border-width: 3px;">Rp</span>
                                    <input type="number" name="harga" value="<?= $data['harga'] ?>" required class="input-neo" style="border-top-left-radius: 0; border-bottom-left-radius: 0; border-width: 3px; padding: 0.75rem; font-size: 1rem;">
                                </div>
                            </div>
                            <div>
                                <label class="block font-black text-xs mb-1 uppercase tracking-widest text-gray-700">Stok</label>
                                <input type="number" name="stok" value="<?= $data['stok'] ?>" required class="input-neo" style="border-width: 3px; padding: 0.75rem; font-size: 1rem;">
                            </div>
                        </div>
                        <div>
                            <label class="block font-black text-xs mb-1 uppercase tracking-widest text-gray-700">Tanggal Kadaluarsa</label>
                            <input type="date" name="tanggal_kadaluarsa" value="<?= $data['tanggal_kadaluarsa'] ?>" required class="input-neo" style="border-width: 3px; padding: 0.75rem; font-size: 1rem;">
                        </div>
                        
                        <div>
                            <label class="block font-black text-xs mb-1 uppercase tracking-widest text-gray-700">Gambar (Biarkan kosong jika tidak diubah)</label>
                            <input type="file" name="gambar" accept="image/*" class="input-neo" style="border-width: 3px; padding: 0.5rem; font-size: 1rem; height: auto !important; background-color: var(--gray-50);">
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 1rem;">
                            <a href="admin_dashboard.php?page=obat" class="btn-neo bg-white" style="font-size: 1.15rem; padding: 0.9rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem; color: var(--black); border-width: 3px; box-shadow: 4px 4px 0 var(--black); font-weight: 900;">
                                Batal <i class="fa-solid fa-xmark"></i>
                            </a>
                            <button type="submit" class="btn-neo bg-green" style="font-size: 1.15rem; padding: 0.9rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem; color: var(--black); border-width: 3px; box-shadow: 4px 4px 0 var(--black);">
                                Simpan <i class="fa-solid fa-floppy-disk"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require_once '../templates/footer.php'; ?>