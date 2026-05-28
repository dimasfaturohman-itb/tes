<?php
session_start();
include '../config/koneksi.php';
include '../template/header.php';
include '../template/navbar_customer.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// 1. Query Data Produk Utama
$query = mysqli_query($conn, "SELECT * FROM produk WHERE id_produk='$id'");
$p = mysqli_fetch_assoc($query);

if (!$p) { 
    echo "<script>alert('Produk tidak ditemukan!'); window.location='dashboard.php';</script>"; 
    exit; 
}

// 2. Query Varian Produk dengan filter ketat
$qVarian = mysqli_query($conn, "SELECT * FROM varian_produk WHERE id_produk='$id'");
$varianData = []; 
$adaVarianValid = false;

while($v = mysqli_fetch_assoc($qVarian)){
    if (!empty(trim($v['ukuran'])) || !empty(trim($v['warna']))) {
        $adaVarianValid = true;
    }
    $varianData[] = $v;
}

// Query data galeri foto tambahan
$qGaleri = mysqli_query($conn, "SELECT * FROM galeri_produk WHERE id_produk='$id'");
$hasVarian = $adaVarianValid;

// 3. Logika Penentuan Harga Tampilan Awal
$hargaUtama = (int)($p['harga'] ?? 0);

if ($hasVarian) {
    $h = array_column($varianData, 'harga_varian');
    $minH = min(array_map('intval', $h));
    $maxH = max(array_map('intval', $h));
    $hargaTampil = ($minH == $maxH) ? "Rp " . number_format($minH, 0, ',', '.') : "Rp " . number_format($minH, 0, ',', '.') . " - Rp " . number_format($maxH, 0, ',', '.');
} else {
    $hargaTampil = "Rp " . number_format($hargaUtama, 0, ',', '.');
}
?>

<style>
    .thumb-image {
        width: 75px; 
        height: 75px; 
        object-fit: cover; 
        cursor: pointer;
        transition: 0.2s;
    }
    .thumb-image.active-border {
        border-color: #dc3545 !important;
        box-shadow: 0 0 0 2px rgba(220, 53, 69, 0.2);
    }
    .btn-varian-item {
        border: 1px solid #dee2e6;
        background-color: #fff;
        color: #212529;
        padding: 6px 16px;
        transition: all 0.2s;
    }
    .btn-varian-item:hover {
        border-color: #dc3545;
        color: #dc3545;
        background-color: #fff5f5;
    }
    .btn-varian-item.active-varian {
        border-color: #dc3545 !important;
        color: #dc3545 !important;
        background-color: #fff5f5 !important;
        font-weight: 600;
        box-shadow: 0 0 0 1px #dc3545;
    }
</style>

<div class="container my-5">
    <div class="product-wrapper shadow-sm p-4 bg-white rounded">
        <div class="row">
            <div class="col-md-5">
                <img src="../admin/upload/<?= htmlspecialchars($p['gambar']); ?>" class="main-image w-100 rounded border" id="mainImage" style="height:450px; object-fit:cover;" onerror="this.src='../assets/img/no-image.png';">
                
                <div class="d-flex gap-2 mt-3 overflow-auto pb-2" id="galleryContainer">
                    <img src="../admin/upload/<?= htmlspecialchars($p['gambar']); ?>" class="thumb-image border p-1 rounded active-border" onclick="changeImage(this.src, this)">
                    <?php while($g = mysqli_fetch_assoc($qGaleri)): ?>
                        <img src="../admin/upload/<?= htmlspecialchars($g['nama_file']); ?>" class="thumb-image border p-1 rounded" onclick="changeImage(this.src, this)">
                    <?php endwhile; ?>
                </div>
            </div>

            <div class="col-md-7 ps-md-4">
                <h2 class="fw-bold text-dark"><?= htmlspecialchars($p['nama_produk']); ?></h2>

                <div class="my-4 p-3 bg-light rounded border-start border-danger border-4">
                    <span class="fs-2 fw-bold text-danger" id="hargaText"><?= $hargaTampil; ?></span>
                </div>
                
                <form method="POST" id="formOrder" onsubmit="return checkForm(event)">
                    <input type="hidden" name="id_produk" value="<?= $p['id_produk']; ?>">

                    <?php if($hasVarian): ?>
                        <input type="hidden" name="id_varian" id="inputVarian" value="">
                        
                        <div class="mb-4">
                            <label class="fw-bold text-dark mb-2">Pilihan Varian:</label>
                            <div class="d-flex gap-2 flex-wrap">
                                <?php foreach($varianData as $v): 
                                    if (!empty(trim($v['ukuran'])) || !empty(trim($v['warna']))): ?>
                                    <button type="button" class="btn btn-sm btn-varian-item rounded" 
                                            onclick="selectVarian(<?= htmlspecialchars(json_encode($v), ENT_QUOTES, 'UTF-8') ?>, this)">
                                        <?= htmlspecialchars(($v['warna'] != '' ? $v['warna'] : '') . ($v['warna'] != '' && $v['ukuran'] != '' ? ' - ' : '') . $v['ukuran']); ?>
                                    </button>
                                <?php endif; endforeach; ?>
                            </div>
                        </div>
                        <div class="mb-4">Stok Tersedia: <span id="stokText" class="fw-bold text-muted">Pilih varian dahulu</span></div>
                    <?php else: ?>
                        <div class="mb-4">Stok Tersedia: <span class="fw-bold text-dark"><?= intval($p['stok']); ?></span></div>
                    <?php endif; ?>

                    <div class="d-flex gap-3">
                        <button type="button" onclick="submitForm('add_cart.php')" class="btn btn-outline-danger px-4 py-2 fw-semibold">🛒 Keranjang</button>
                        <button type="button" onclick="submitForm('beli_sekarang.php')" class="btn btn-danger px-4 py-2 fw-semibold">Beli Sekarang</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row mt-5 border-top pt-4">
            <div class="col-12">
                <h5 class="fw-bold mb-3">Deskripsi Produk</h5>
                <p class="text-secondary" style="line-height: 1.7; text-align: justify;"><?= nl2br(htmlspecialchars($p['deskripsi'])); ?></p>
            </div>
        </div>
    </div>
</div>

<script>
let hasVarian = <?= $hasVarian ? 'true' : 'false' ?>;

function selectVarian(v, btn) {
    document.getElementById('hargaText').innerText = 'Rp ' + parseInt(v.harga_varian).toLocaleString('id-ID');
    document.getElementById('stokText').innerText = v.stok + ' pcs';
    document.getElementById('stokText').className = "fw-bold text-dark"; 
    document.getElementById('inputVarian').value = v.id_varian;
    
    document.querySelectorAll('.btn-varian-item').forEach(b => {
        b.classList.remove('active-varian');
    });
    btn.classList.add('active-varian');

    // PERBAIKAN DI SINI: v.gambar_varian (disesuaikan dengan nama field asli di database Anda)
    if(v.gambar_varian && v.gambar_varian.trim() !== "") {
        let pathFotoVarian = "../admin/upload/" + v.gambar_varian;
        document.getElementById('mainImage').src = pathFotoVarian;
        
        // Hapus border aktif dari semua thumbnail galeri bawah jika foto varian sedang aktif mendominasi
        document.querySelectorAll('.thumb-image').forEach(img => img.classList.remove('active-border'));
    }
}

function changeImage(src, element) { 
    document.getElementById('mainImage').src = src; 
    document.querySelectorAll('.thumb-image').forEach(img => img.classList.remove('active-border'));
    element.classList.add('active-border');
}

function submitForm(targetAction) {
    if (hasVarian && document.getElementById('inputVarian').value === "") {
        alert("Mohon pilih varian produk terlebih dahulu!");
        return false;
    }
    let form = document.getElementById('formOrder');
    form.action = targetAction;
    form.submit();
}

function checkForm(event) {
    if (hasVarian && document.getElementById('inputVarian').value === "") {
        event.preventDefault();
        alert("Mohon pilih varian produk terlebih dahulu!");
        return false;
    }
    return true;
}
</script>

<?php include '../template/footer.php'; ?>

<?php include 'footer.php'; ?>