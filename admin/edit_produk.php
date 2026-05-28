<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }
include 'koneksi.php';

$id = intval($_GET['id']);
$produk = mysqli_query($conn, "SELECT * FROM produk WHERE id_produk='$id'");
$data = mysqli_fetch_assoc($produk);

if (!$data) { echo "<script>alert('Produk tidak ditemukan');window.location='produk.php';</script>"; exit; }

$kategori = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
$varian = mysqli_query($conn, "SELECT * FROM varian_produk WHERE id_produk='$id'");
$galeri = mysqli_query($conn, "SELECT * FROM galeri_produk WHERE id_produk='$id'");

if (isset($_POST['update'])) {
    $nama_produk = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $id_kategori = intval($_POST['id_kategori']);
    $harga = intval($_POST['harga']);
    $stok_utama = intval($_POST['stok_utama']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    // 1. Proses Gambar Utama
    $gambar = $data['gambar'];
    if (!empty($_FILES['gambar_utama']['name'])) {
        $gambar = time() . '_utama_' . preg_replace("/[^a-zA-Z0-9.-]/", "_", $_FILES['gambar_utama']['name']);
        if (move_uploaded_file($_FILES['gambar_utama']['tmp_name'], "upload/" . $gambar)) {
            if (!empty($data['gambar']) && file_exists("upload/".$data['gambar'])) {
                unlink("upload/".$data['gambar']);
            }
        }
    }

    // Update Data Produk Utama
    mysqli_query($conn, "UPDATE produk SET nama_produk='$nama_produk', id_kategori='$id_kategori', harga='$harga', stok='$stok_utama', deskripsi='$deskripsi', gambar='$gambar' WHERE id_produk='$id'");

    // 2. Proses Tambahan Galeri Foto Detail
    if (!empty($_FILES['galeri']['name'][0])) {
        foreach ($_FILES['galeri']['name'] as $key => $val) {
            if ($_FILES['galeri']['error'][$key] == 0) {
                $nama_galeri = time() . '_galeri_' . $key . '_' . preg_replace("/[^a-zA-Z0-9.-]/", "_", $_FILES['galeri']['name'][$key]);
                if (move_uploaded_file($_FILES['galeri']['tmp_name'][$key], "upload/" . $nama_galeri)) {
                    mysqli_query($conn, "INSERT INTO galeri_produk (id_produk, nama_file) VALUES ('$id', '$nama_galeri')");
                }
            }
        }
    }

    // 3. Update Varian
    mysqli_query($conn, "DELETE FROM varian_produk WHERE id_produk='$id'");
    
    if (isset($_POST['stok']) && is_array($_POST['stok'])) {
        for ($i = 0; $i < count($_POST['stok']); $i++) {
            $nv = mysqli_real_escape_string($conn, $_POST['nama_varian'][$i]);
            $u = mysqli_real_escape_string($conn, $_POST['ukuran'][$i]);
            $w = mysqli_real_escape_string($conn, $_POST['warna'][$i]);
            $s = intval($_POST['stok'][$i]);
            $hv = intval($_POST['harga_varian'][$i]);
            $g_var = $_POST['gambar_lama'][$i] ?? '';
            
            if (isset($_FILES['gambar_varian']['name'][$i]) && $_FILES['gambar_varian']['error'][$i] == 0) {
                $namaFile = time() . '_variant_' . $i . '_' . preg_replace("/[^a-zA-Z0-9.-]/", "_", $_FILES['gambar_varian']['name'][$i]);
                if (move_uploaded_file($_FILES['gambar_varian']['tmp_name'][$i], "upload/" . $namaFile)) {
                    if (!empty($g_var) && file_exists("upload/" . $g_var)) {
                        unlink("upload/" . $g_var);
                    }
                    $g_var = $namaFile;
                }
            }
            
            mysqli_query($conn, "INSERT INTO varian_produk(id_produk, nama_varian, ukuran, warna, stok, harga_varian, gambar_varian) VALUES('$id', '$nv', '$u', '$w', '$s', '$hv', '$g_var')");
        }
    }
    
    echo "<script>alert('Produk berhasil diupdate');window.location='produk.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Edit Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body{ background:#f5f5f5; font-family:'Segoe UI',sans-serif; }
        .navbar{ background:linear-gradient(135deg,#ff4d6d,#ff758f); padding:15px 25px; }
        .btn-pink { background-color: #ff4d6d; color: white; border: none; }
        .btn-delete { background: #fee2e2; color: #dc3545; border: none; padding: 5px 10px; border-radius: 8px; }
        .variant-box { background: #f8f9fa; padding: 15px; border-radius: 12px; border: 1px solid #e9ecef; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark shadow">
    <div class="container-fluid">
        <a class="navbar-brand text-white" href="dashboard.php">MiniShop</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAdmin">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarAdmin">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item"><a class="nav-link" href="dashboard.php"><i class="bi bi-grid-fill"></i> Dashboard</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle active" data-bs-toggle="dropdown" role="button"><i class="bi bi-folder-fill"></i> Master Data</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="produk.php"><i class="bi bi-box-seam"></i> Produk</a></li>
                        <li><a class="dropdown-item" href="kategori.php"><i class="bi bi-tags-fill"></i> Kategori</a></li>
                        <li><a class="dropdown-item" href="voucher.php"><i class="bi bi-ticket-perforated-fill"></i> Voucher</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link" href="pesanan.php"><i class="bi bi-cart-fill"></i> Pesanan</a></li>
                <li class="nav-item"><a class="nav-link" href="pemasukan.php"><i class="bi bi-cash-stack"></i> Pemasukan</a></li>
                <li class="nav-item"><a class="nav-link" href="customer.php"><i class="bi bi-people-fill"></i> Customer</a></li>
                <li class="nav-item ms-lg-3"><a href="logout.php" class="btn btn-light text-danger rounded-pill px-4"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container pb-5 mt-4">
    <div class="card shadow-sm border-0 rounded-4 p-4">
        <h3 class="fw-bold mb-4"><i class="bi bi-pencil-square text-danger"></i> Edit Detail & Varian Produk</h3>
        <form method="POST" enctype="multipart/form-data">
            
            <div class="row">
                <div class="col-md-12">
                    <label class="fw-bold mb-1">Nama Produk:</label>
                    <input type="text" name="nama_produk" class="form-control mb-3" value="<?= htmlspecialchars($data['nama_produk']) ?>" required>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="fw-bold mb-1">Kategori:</label>
                    <select name="id_kategori" class="form-select" required>
                        <?php while($k = mysqli_fetch_assoc($kategori)): ?>
                            <option value="<?= $k['id_kategori'] ?>" <?= $k['id_kategori'] == $data['id_kategori'] ? 'selected' : '' ?>><?= htmlspecialchars($k['nama_kategori']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="fw-bold mb-1">Harga Dasar:</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="harga" class="form-control" value="<?= $data['harga'] ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="fw-bold mb-1">Stok Utama (Single):</label>
                    <input type="number" name="stok_utama" class="form-control" value="<?= $data['stok'] ?>" required>
                </div>
            </div>
            
            <label class="fw-bold mb-1">Deskripsi Lengkap:</label>
            <textarea name="deskripsi" class="form-control mb-3" rows="4" required><?= htmlspecialchars($data['deskripsi']) ?></textarea>
            
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold m-0"><i class="bi bi-layers-fill"></i> Varian Spesifikasi Produk (Opsional)</h5>
                <button type="button" class="btn btn-dark btn-sm" onclick="tambahVarian()"><i class="bi bi-plus-lg"></i> Tambah Kolom Varian</button>
            </div>
            
            <div id="container-varian" class="mb-4">
                <?php while($v = mysqli_fetch_assoc($varian)): ?>
                <div class="variant-box row g-2 align-items-center mb-2">
                    <input type="hidden" name="gambar_lama[]" value="<?= htmlspecialchars($v['gambar_varian']) ?>">
                    <div class="col"><input type="text" name="nama_varian[]" class="form-control form-control-sm" value="<?= htmlspecialchars($v['nama_varian'] ?? '') ?>" placeholder="Nama Varian"></div>
                    <div class="col"><input type="text" name="ukuran[]" class="form-control form-control-sm" value="<?= htmlspecialchars($v['ukuran']) ?>" placeholder="Ukuran"></div>
                    <div class="col"><input type="text" name="warna[]" class="form-control form-control-sm" value="<?= htmlspecialchars($v['warna']) ?>" placeholder="Warna"></div>
                    <div class="col"><input type="number" name="stok[]" class="form-control form-control-sm" value="<?= $v['stok'] ?>" placeholder="Stok"></div>
                    <div class="col"><input type="number" name="harga_varian[]" class="form-control form-control-sm" value="<?= $v['harga_varian'] ?>" placeholder="Harga"></div>
                    <div class="col-md-2"><input type="file" name="gambar_varian[]" class="form-control form-control-sm" accept="image/*"></div>
                    <div class="col-auto"><button type="button" class="btn-delete" onclick="this.closest('.variant-box').remove()"><i class="bi bi-trash"></i></button></div>
                </div>
                <?php endwhile; ?>
            </div>

            <div class="row g-2 mt-4 pt-3 border-top">
                <div class="col-md-3">
                    <a href="produk.php" class="btn btn-outline-secondary w-100 py-3 fw-bold"><i class="bi bi-arrow-left"></i> Kembali</a>
                </div>
                <div class="col-md-9">
                    <button type="submit" name="update" class="btn btn-pink w-100 py-3 fw-bold shadow-sm"><i class="bi bi-check2-circle"></i> Simpan Semua Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function tambahVarian(){
    let html = `
    <div class="variant-box row g-2 align-items-center mb-2">
        <input type="hidden" name="gambar_lama[]" value="">
        <div class="col"><input type="text" name="nama_varian[]" class="form-control form-control-sm" placeholder="Nama Varian"></div>
        <div class="col"><input type="text" name="ukuran[]" class="form-control form-control-sm" placeholder="Ukuran"></div>
        <div class="col"><input type="text" name="warna[]" class="form-control form-control-sm" placeholder="Warna"></div>
        <div class="col"><input type="number" name="stok[]" class="form-control form-control-sm" placeholder="Stok"></div>
        <div class="col"><input type="number" name="harga_varian[]" class="form-control form-control-sm" placeholder="Harga Varian"></div>
        <div class="col-md-2"><input type="file" name="gambar_varian[]" class="form-control form-control-sm" accept="image/*"></div>
        <div class="col-auto"><button type="button" class="btn-delete" onclick="this.closest('.variant-box').remove()"><i class="bi bi-trash"></i></button></div>
    </div>`;
    document.getElementById('container-varian').insertAdjacentHTML('beforeend', html);
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include 'footer.php'; ?>