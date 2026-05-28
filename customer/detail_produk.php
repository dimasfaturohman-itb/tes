<?php
session_start();
include '../config/koneksi.php';
include '../template/header.php';
include '../template/navbar_customer.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$query = mysqli_query($conn, "SELECT * FROM produk WHERE id_produk='$id'");
$p = mysqli_fetch_assoc($query);

if (!$p) { 
    echo "<script>alert('Produk tidak ditemukan!'); window.location='dashboard.php';</script>"; 
    exit; 
}

$gambarUtamaProduk = htmlspecialchars($p['gambar'] ?? '');
$qVarian = mysqli_query($conn, "SELECT * FROM varian_produk WHERE id_produk='$id'");
$varianData = []; 
$hasVarian = mysqli_num_rows($qVarian) > 0;

while($v = mysqli_fetch_assoc($qVarian)){
    $varianData[] = array_change_key_case($v, CASE_LOWER);
}

$qGaleri = mysqli_query($conn, "SELECT * FROM galeri_produk WHERE id_produk='$id'");

$hargaUtama = (int)($p['harga'] ?? 0);
if ($hasVarian) {
    $h = array_column($varianData, 'harga_varian');
    $hClean = array_map(function($val) use ($hargaUtama) { return (int)$val > 0 ? (int)$val : $hargaUtama; }, $h);
    $minH = min($hClean); $maxH = max($hClean);
    $hargaTampil = ($minH == $maxH) ? "Rp " . number_format($minH, 0, ',', '.') : "Rp " . number_format($minH, 0, ',', '.') . " - Rp " . number_format($maxH, 0, ',', '.');
} else {
    $hargaTampil = "Rp " . number_format($hargaUtama, 0, ',', '.');
}
?>

<style>
    .main-image { height:400px; width: 100%; object-fit:contain; background: #fcfcfc; transition: 0.3s; }
    .thumb-container { display: flex; gap: 10px; overflow-x: auto; padding: 15px 0; white-space: nowrap; }
    .thumb-image { width: 75px; height: 75px; object-fit: cover; cursor: pointer; border: 2px solid transparent; flex-shrink: 0; }
    .thumb-image.active-border { border-color: #ff4d6d !important; }
    .btn-varian-item { border: 1.5px solid #e0e0e0; background: #fff; padding: 5px 12px; border-radius: 6px; transition: 0.2s; display: flex; align-items: center; cursor: pointer; }
    .btn-varian-item.active-varian { border-color: #ff4d6d !important; color: #ff4d6d !important; background: #fff5f7 !important; font-weight: 600; }
    
    /* Tombol Back Home (Konsisten dengan halaman lain) */
    .back-home-btn {
        position: fixed; bottom: 25px; right: 25px; width: 48px; height: 48px;
        background: #ff4d6d; color: white; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 6px 15px rgba(0,0,0,0.15); text-decoration: none;
        z-index: 999; transition: 0.2s ease;
    }
    .back-home-btn:hover { transform: scale(1.08); background: #ff355d; color: white; }
</style>

<div class="container my-5">
    <a href="dashboard.php" class="back-home-btn">
        <i class="bi bi-house-door-fill"></i>
    </a>

    <div class="product-wrapper shadow-sm p-4 bg-white rounded">
        <div class="row">
            <div class="col-md-5">
                <img src="../admin/upload/<?= $gambarUtamaProduk; ?>" class="main-image rounded border" id="mainImage">
                <?php if (mysqli_num_rows($qGaleri) > 0): ?>
                <div class="thumb-container">
                    <?php while($g = mysqli_fetch_assoc($qGaleri)): if(!empty($g['nama_file'])): ?>
                        <img src="../admin/upload/<?= htmlspecialchars($g['nama_file']); ?>" class="thumb-image rounded" onclick="changeImage(this.src, this)">
                    <?php endif; endwhile; ?>
                </div>
                <?php endif; ?>
            </div>

            <div class="col-md-7 ps-md-4">
                <h2 class="fw-bold text-dark"><?= htmlspecialchars($p['nama_produk']); ?></h2>
                <div class="my-3 p-3 bg-light rounded border-start border-danger border-4">
                    <span class="fs-2 fw-bold text-danger" id="hargaText"><?= $hargaTampil; ?></span>
                </div>
                
                <form method="POST" id="formOrder">
                    <input type="hidden" name="id_produk" value="<?= $p['id_produk']; ?>">
                    
                    <?php if($hasVarian): ?>
                        <input type="hidden" name="id_varian" id="inputVarian" value="">
                        <div class="mb-4">
                            <label class="fw-bold mb-2">Pilih Varian:</label>
                            <div class="d-flex gap-2 flex-wrap">
                                <?php foreach($varianData as $v): 
                                    $namaTombol = !empty($v['nama_varian']) ? $v['nama_varian'] : trim(($v['warna'] ?? '') . ' ' . ($v['ukuran'] ?? ''));
                                    $gambarVarian = !empty($v['gambar_varian']) ? "../admin/upload/" . $v['gambar_varian'] : "../admin/upload/" . $gambarUtamaProduk;
                                ?>
                                    <button type="button" class="btn btn-varian-item gap-2" onclick="selectVarian('<?= $v['id_varian']; ?>', '<?= (int)$v['harga_varian'] ?: $hargaUtama; ?>', '<?= $v['stok']; ?>', '<?= htmlspecialchars($v['gambar_varian'] ?? ''); ?>', this)">
                                        <img src="<?= $gambarVarian; ?>" style="width:30px; height:30px; object-fit:cover; border-radius:4px;">
                                        <span><?= htmlspecialchars($namaTombol); ?></span>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="mb-3 text-secondary">Stok: <span id="stokText" class="fw-bold text-dark">-</span></div>
                    <?php else: ?>
                        <div class="mb-3 text-secondary">Stok Tersedia: <span class="fw-bold text-dark"><?= intval($p['stok']); ?> pcs</span></div>
                    <?php endif; ?>

                    <button type="button" onclick="submitForm('add_cart.php')" class="btn btn-outline-danger px-4 py-2">🛒 Keranjang</button>
                    <button type="button" onclick="submitForm('beli_sekarang.php')" class="btn btn-danger px-4 py-2">Beli Sekarang</button>
                </form>
            </div>
        </div>
    </div>

    <div class="mt-4 p-4 bg-white shadow-sm rounded">
        <h5 class="fw-bold mb-3">Deskripsi Produk</h5>
        <div class="text-muted"><?= nl2br(htmlspecialchars($p['deskripsi'])); ?></div>
    </div>
</div>

<script>
function changeImage(src, el) { 
    document.getElementById('mainImage').src = src; 
    document.querySelectorAll('.thumb-image').forEach(i => i.classList.remove('active-border'));
    el.classList.add('active-border');
}
function selectVarian(id, harga, stok, img, btn) {
    document.getElementById('hargaText').innerText = 'Rp ' + parseInt(harga).toLocaleString('id-ID');
    document.getElementById('stokText').innerText = stok + ' pcs';
    document.getElementById('inputVarian').value = id;
    if(img) document.getElementById('mainImage').src = "../admin/upload/" + img;
    document.querySelectorAll('.btn-varian-item').forEach(b => b.classList.remove('active-varian'));
    btn.classList.add('active-varian');
}
function submitForm(action) {
    let f = document.getElementById('formOrder'); f.action = action; f.submit();
}
</script>
<?php include '../template/footer.php'; ?>