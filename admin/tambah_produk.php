<?php
session_start();
if(!isset($_SESSION['admin'])){ header("Location: login.php"); exit; }
include 'koneksi.php';

$kategori = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");

if(isset($_POST['simpan'])){
    $nama_produk = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $id_kategori = intval($_POST['id_kategori']);
    $harga = intval($_POST['harga']);
    $stok_utama = intval($_POST['stok_utama']); 
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    if(empty($_FILES['gambar_utama']['name'])){
        echo "<script>alert('Gambar utama wajib diupload!');window.history.back();</script>"; exit;
    }
    
    $namaAwalUtama = time().'_utama_'.preg_replace("/[^a-zA-Z0-9.-]/", "_", $_FILES['gambar_utama']['name']);
    move_uploaded_file($_FILES['gambar_utama']['tmp_name'], "upload/".$namaAwalUtama);

    $insert = mysqli_query($conn, "INSERT INTO produk(nama_produk,id_kategori,harga,stok,deskripsi,gambar) VALUES('$nama_produk','$id_kategori','$harga','$stok_utama','$deskripsi','$namaAwalUtama')");

    if($insert){
        $id_produk = mysqli_insert_id($conn);
        
        if(!empty($_FILES['galeri']['name'][0])){
            foreach($_FILES['galeri']['tmp_name'] as $key => $tmp_name){
                if ($_FILES['galeri']['error'][$key] == 0) {
                    $namaGaleri = time().'_galeri_'.$key.'_'.preg_replace("/[^a-zA-Z0-9.-]/", "_", $_FILES['galeri']['name'][$key]);
                    if(move_uploaded_file($tmp_name, "upload/".$namaGaleri)){
                        mysqli_query($conn, "INSERT INTO galeri_produk(id_produk, nama_file) VALUES('$id_produk', '$namaGaleri')");
                    }
                }
            }
        }
        
        if(isset($_POST['varian_index']) && is_array($_POST['varian_index'])){
            foreach($_POST['varian_index'] as $i){
                $nv = mysqli_real_escape_string($conn, $_POST['nama_varian'][$i]);
                $u = mysqli_real_escape_string($conn, $_POST['ukuran'][$i]);
                $w = mysqli_real_escape_string($conn, $_POST['warna'][$i]);
                $s = intval($_POST['stok'][$i]);
                $hv = intval($_POST['harga_varian'][$i]);
                $gambarVarian = '';
                
                if(!empty($_FILES['gambar_varian_'.$i]['name']) && $_FILES['gambar_varian_'.$i]['error'] == 0){
                    $namaVarian = time().'_variant_'.$i.'_'.preg_replace("/[^a-zA-Z0-9.-]/", "_", $_FILES['gambar_varian_'.$i]['name']);
                    if(move_uploaded_file($_FILES['gambar_varian_'.$i]['tmp_name'], "upload/".$namaVarian)){
                        $gambarVarian = $namaVarian;
                    }
                }
                
                mysqli_query($conn, "INSERT INTO varian_produk(id_produk,nama_varian,ukuran,warna,stok,harga_varian,gambar_varian) VALUES('$id_produk','$nv','$u','$w','$s','$hv', " . ($gambarVarian != '' ? "'$gambarVarian'" : "NULL") . ")");
            }
        }
        echo "<script>alert('Produk berhasil ditambahkan');window.location='produk.php';</script>";
        exit;
    }
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
        .navbar-brand{ font-size:32px; font-weight:800; }
        .nav-link{ color:white !important; margin-right:10px; font-weight:500; transition:0.3s; }
        .nav-link:hover{ transform:translateY(-2px); }
        .dropdown-menu{ border:none; border-radius:18px; padding:10px; box-shadow:0 5px 20px rgba(0,0,0,0.08); }
        .dropdown-item{ padding:10px 15px; border-radius:12px; transition:0.2s; }
        .dropdown-item:hover{ background:#fff0f3; color:#ff4d6d; }
        .btn-pink { background-color: #ff4d6d; color: white; border: none; }
        .btn-pink:hover { background-color: #ff758f; color: white; }
        .btn-delete { background: #fee2e2; color: #dc3545; border: none; padding: 5px 10px; border-radius: 8px; }
        .btn-delete:hover { background: #dc3545; color: white; }
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

<div class="container py-5">
    <div class="main-box">
        <h3 class="fw-bold mb-4"><i class="bi bi-plus-circle-fill text-danger"></i> Tambah Produk Baru</h3>
        <form method="POST" enctype="multipart/form-data">
            <div class="section-box">
                <label class="fw-bold mb-2">Informasi Produk Utama:</label>
                <input type="text" name="nama_produk" class="form-control mb-3" placeholder="Nama Produk" required>
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <select name="id_kategori" class="form-select" required>
                            <option value="">Pilih Kategori</option>
                            <?php while($k=mysqli_fetch_assoc($kategori)): ?>
                                <option value="<?= $k['id_kategori']; ?>"><?= htmlspecialchars($k['nama_kategori']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-4"><input type="number" name="harga" class="form-control" placeholder="Harga Dasar" required></div>
                    <div class="col-md-4"><input type="number" name="stok_utama" class="form-control" placeholder="Stok Utama" required></div>
                </div>
                <textarea name="deskripsi" class="form-control" rows="4" placeholder="Deskripsi lengkap..." required></textarea>
            </div>

            <div class="section-box">
                <label class="fw-bold mb-2">Media Produk:</label>
                <input type="file" name="gambar_utama" class="form-control mb-2" accept="image/*" required>
                <input type="file" name="galeri[]" class="form-control" multiple accept="image/*">
            </div>

            <div class="section-box">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">Atur Varian</h5>
                    <button type="button" class="btn btn-pink btn-sm" onclick="tambahVarian()"><i class="bi bi-plus-lg"></i> Tambah Baris</button>
                </div>
                <div id="container-varian">
                    <div class="variant-box row g-2 align-items-center">
                        <input type="hidden" name="varian_index[0]" value="0">
                        <div class="col"><input type="text" name="nama_varian[0]" class="form-control form-control-sm" placeholder="Nama Varian"></div>
                        <div class="col"><input type="text" name="ukuran[0]" class="form-control form-control-sm" placeholder="Ukuran"></div>
                        <div class="col"><input type="text" name="warna[0]" class="form-control form-control-sm" placeholder="Warna"></div>
                        <div class="col"><input type="number" name="stok[0]" class="form-control form-control-sm" placeholder="Stok"></div>
                        <div class="col"><input type="number" name="harga_varian[0]" class="form-control form-control-sm" placeholder="Harga"></div>
                        <div class="col-md-2"><input type="file" name="gambar_varian_0" class="form-control form-control-sm" accept="image/*"></div>
                        <div class="col-auto"><button type="button" class="btn-delete" onclick="this.closest('.variant-box').remove()"><i class="bi bi-trash"></i></button></div>
                    </div>
                </div>
            </div>

            

<div class="row g-2 mt-4 pt-3 border-top">
    <div class="col-md-3">
        <a href="produk.php" class="btn btn-outline-secondary w-100 py-3 fw-bold">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
    <div class="col-md-9">
        <button type="submit" name="simpan" class="btn btn-pink w-100 py-3 fw-bold shadow-sm">
            <i class="bi bi-check2-circle"></i> Simpan Produk Baru
        </button>
    </div>
</div>        </form>
    </div>
</div>

<script>
let varian_count = 1;
function tambahVarian(){
    let html = `
    <div class="variant-box row g-2 align-items-center">
        <input type="hidden" name="varian_index[${varian_count}]" value="${varian_count}">
        <div class="col"><input type="text" name="nama_varian[${varian_count}]" class="form-control form-control-sm" placeholder="Nama Varian"></div>
        <div class="col"><input type="text" name="ukuran[${varian_count}]" class="form-control form-control-sm" placeholder="Ukuran"></div>
        <div class="col"><input type="text" name="warna[${varian_count}]" class="form-control form-control-sm" placeholder="Warna"></div>
        <div class="col"><input type="number" name="stok[${varian_count}]" class="form-control form-control-sm" placeholder="Stok"></div>
        <div class="col"><input type="number" name="harga_varian[${varian_count}]" class="form-control form-control-sm" placeholder="Harga"></div>
        <div class="col-md-2"><input type="file" name="gambar_varian_${varian_count}" class="form-control form-control-sm" accept="image/*"></div>
        <div class="col-auto"><button type="button" class="btn-delete" onclick="this.closest('.variant-box').remove()"><i class="bi bi-trash"></i></button></div>
    </div>`;
    document.getElementById('container-varian').insertAdjacentHTML('beforeend', html);
    varian_count++;
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>



<?php include 'footer.php'; ?>