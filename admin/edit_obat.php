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
    $deskripsi = isset($_POST['deskripsi']) ? mysqli_real_escape_string($koneksi, $_POST['deskripsi']) : '';

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
                tanggal_kadaluarsa = '$tanggal_kadaluarsa',
                deskripsi = '$deskripsi'
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
            <div class="container" style="max-width: 64rem; margin: 0 auto; width: 100%;">
                <div class="flex items-center gap-3 mb-6">
                    <div class="badge-neo bg-pink" style="width: 3rem; height: 3rem; display: flex; align-items: center; justify-content: center; padding: 0; font-size: 1.5rem; box-shadow: 2px 2px 0 var(--black);">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </div>
                    <h3 class="font-black uppercase" style="font-size: 1.75rem; margin: 0; letter-spacing: -0.01em;">Edit Produk</h3>
                </div>

                <form method="POST" action="" enctype="multipart/form-data" style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; align-items: start;">
                    
                    <!-- KOLOM KIRI (Informasi & Harga) -->
                    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                        <!-- Panel Informasi -->
                        <div class="neo-box bg-white" style="padding: 2rem; border-width: 4px; box-shadow: 6px 6px 0px var(--black);">
                            <h4 class="font-black uppercase mb-4 text-lg" style="margin-top: 0; border-bottom: 4px solid var(--black); padding-bottom: 0.5rem;">Informasi Dasar</h4>
                            <div style="display: flex; flex-direction: column; gap: 1.25rem;">
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
                                            echo "<option value='" . $k['id_kategori'] . "' $selected>" . strtoupper(htmlspecialchars($k['nama_kategori'])) . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="block font-black text-xs mb-1 uppercase tracking-widest text-gray-700">Deskripsi Obat</label>
                                    <textarea name="deskripsi" class="input-neo" rows="4" style="border-width: 3px; padding: 0.75rem; font-size: 1rem; width: 100%; resize: vertical;"><?= htmlspecialchars($data['deskripsi'] ?? '') ?></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Panel Harga & Stok -->
                        <div class="neo-box bg-white" style="padding: 2rem; border-width: 4px; box-shadow: 6px 6px 0px var(--black);">
                            <h4 class="font-black uppercase mb-4 text-lg" style="margin-top: 0; border-bottom: 4px solid var(--black); padding-bottom: 0.5rem;">Harga & Stok</h4>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                                <div>
                                    <label class="block font-black text-xs mb-1 uppercase tracking-widest text-gray-700">Harga</label>
                                    <div class="flex" style="margin: 0;">
                                        <span class="badge-neo bg-gray-200" style="padding: 0.75rem; border-right-width: 0; border-radius: 0; font-weight: 900; font-size: 1rem; border-width: 3px;">Rp</span>
                                        <input type="number" name="harga" value="<?= $data['harga'] ?>" required class="input-neo" style="border-top-left-radius: 0; border-bottom-left-radius: 0; border-width: 3px; padding: 0.75rem; font-size: 1rem;">
                                    </div>
                                </div>
                                <div>
                                    <label class="block font-black text-xs mb-1 uppercase tracking-widest text-gray-700">Stok Tersedia</label>
                                    <input type="number" name="stok" value="<?= $data['stok'] ?>" required class="input-neo" style="border-width: 3px; padding: 0.75rem; font-size: 1rem;">
                                </div>
                                <div style="grid-column: span 2;">
                                    <label class="block font-black text-xs mb-1 uppercase tracking-widest text-gray-700">Tanggal Kadaluarsa</label>
                                    <input type="date" name="tanggal_kadaluarsa" value="<?= $data['tanggal_kadaluarsa'] ?>" required class="input-neo" style="border-width: 3px; padding: 0.75rem; font-size: 1rem;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- KOLOM KANAN (Media & Aksi) -->
                    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                        <!-- Panel Media -->
                        <div class="neo-box bg-yellow" style="padding: 2rem; border-width: 4px; box-shadow: 6px 6px 0px var(--black);">
                            <h4 class="font-black uppercase mb-4 text-lg" style="margin-top: 0; border-bottom: 4px solid var(--black); padding-bottom: 0.5rem;">Media Produk</h4>
                            
                            <?php
                            $img_src = ($data['gambar'] && $data['gambar'] !== 'default.jpg') ? '../assets/img/' . $data['gambar'] : null;
                            ?>
                            <div style="display: flex; flex-direction: column; gap: 1rem; align-items: center; text-align: center;">
                                <?php if($img_src): ?>
                                    <div class="neo-box bg-white" style="width: 100%; aspect-ratio: 1/1; padding: 0; display: flex; align-items: center; justify-content: center; overflow: hidden; border-width: 4px; box-shadow: 4px 4px 0 var(--black);">
                                        <img src="<?= $img_src ?>" style="width: 100%; height: 100%; object-fit: cover;" alt="Preview">
                                    </div>
                                <?php else: ?>
                                    <div class="neo-box bg-white" style="width: 100%; aspect-ratio: 1/1; padding: 0; display: flex; align-items: center; justify-content: center; border-width: 4px; box-shadow: 4px 4px 0 var(--black); flex-direction: column; gap: 0.5rem;">
                                        <i class="fa-solid fa-image text-gray-400" style="font-size: 3rem;"></i>
                                        <span class="text-xs font-bold text-gray-400 uppercase">Belum Ada Gambar</span>
                                    </div>
                                <?php endif; ?>
                                
                                <div style="width: 100%; text-align: left;">
                                    <label class="block font-black text-xs mb-1 uppercase tracking-widest text-gray-800">Ubah Gambar</label>
                                    <input type="file" name="gambar" accept="image/*" class="input-neo" style="border-width: 3px; padding: 0.5rem; font-size: 0.85rem; height: auto !important; background-color: var(--white); cursor: pointer; width: 100%;">
                                    <p class="text-[0.75rem] font-bold text-gray-700 mt-2 leading-tight" style="font-size: 0.75rem;">*Pilih file baru untuk mengganti gambar. Biarkan kosong jika tidak diubah.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Panel Aksi -->
                        <div class="neo-box bg-white" style="padding: 1.5rem; border-width: 4px; box-shadow: 6px 6px 0px var(--black); display: flex; flex-direction: column; gap: 1rem;">
                            <button type="submit" class="btn-neo bg-green" style="font-size: 1.15rem; padding: 1rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem; color: var(--black); border-width: 3px; box-shadow: 4px 4px 0 var(--black);">
                                <i class="fa-solid fa-floppy-disk"></i> Simpan
                            </button>
                            <a href="admin_dashboard.php?page=obat" class="btn-neo bg-gray-200" style="font-size: 1rem; padding: 0.8rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem; color: var(--black); border-width: 3px; font-weight: 900;">
                                <i class="fa-solid fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<?php require_once '../templates/footer.php'; ?>