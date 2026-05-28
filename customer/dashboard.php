<?php 
session_start();
if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/koneksi.php';
include '../template/header.php';
include '../template/navbar_customer.php';

/* --- QUERY DATA --- */
$kategori_list = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");

// 1. Tentukan Judul dan Query Dasar
$judul_halaman = "Semua Produk";
$query_sql = "SELECT produk.*, kategori.nama_kategori FROM produk 
              LEFT JOIN kategori ON produk.id_kategori = kategori.id_kategori";

// 2. Tambahkan Filter Jika Ada
if(isset($_GET['kategori']) && !empty($_GET['kategori'])) {
    $kategori_filter = mysqli_real_escape_string($conn, $_GET['kategori']);
    $query_sql .= " WHERE kategori.nama_kategori = '$kategori_filter'";
    $judul_halaman = "Kategori: " . htmlspecialchars($kategori_filter);
}

// 3. Eksekusi Query Tunggal
$query_produk = mysqli_query($conn, $query_sql);

// Query Voucher
$voucher = mysqli_query($conn, "SELECT * FROM voucher WHERE status='aktif' ORDER BY id_voucher DESC LIMIT 1");
$v = mysqli_fetch_assoc($voucher);

$where = "WHERE 1=1"; 

// 2. Filter Kategori (jika ada)
if(isset($_GET['kategori']) && !empty($_GET['kategori'])) {
    $kategori_filter = mysqli_real_escape_string($conn, $_GET['kategori']);
    $where .= " AND kategori.nama_kategori = '$kategori_filter'";
}

// 3. Filter Pencarian (LOGIKA SEARCH)
if(isset($_GET['cari']) && !empty($_GET['cari'])) {
    $cari = mysqli_real_escape_string($conn, $_GET['cari']);
    // Mencari berdasarkan nama produk
    $where .= " AND produk.nama_produk LIKE '%$cari%'";
}

// 4. Query Utama (Menggabungkan kondisi filter)
$query_sql = "SELECT produk.*, kategori.nama_kategori FROM produk 
              LEFT JOIN kategori ON produk.id_kategori = kategori.id_kategori 
              $where";

$query_produk = mysqli_query($conn, $query_sql);
?>

<style>
    body{ background:#f5f5f5; }
    .main-slider img{ height:260px; object-fit:cover; }
    .product-card{ border:1px solid #e8e8e8; border-radius:4px; overflow:hidden; transition:0.2s; background:white; height:100%; position:relative; display:block; text-decoration:none; color:inherit; }
    .product-card:hover{ transform:translateY(-3px); box-shadow:0 6px 15px rgba(0,0,0,0.1); border-color:#ee4d2d; }
    .product-img{ height:160px; width:100%; object-fit:cover; }
    .card-body { padding: 10px; }
    .product-title { font-size: 13px; color: #333; height: 36px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; margin-bottom: 5px; line-height: 1.4; }
    .product-price { color: #ee4d2d; font-size: 15px; font-weight: 700; }
    .section-title { font-size: 1.2rem; font-weight: 700; color: #333; margin: 30px 0 15px; border-left: 4px solid #ee4d2d; padding-left: 10px; }
    .kategori-wrapper{ background:white; border-radius:18px; padding:20px; box-shadow:0 2px 10px rgba(0,0,0,0.04); }
    .voucher-box{ background:white; border-radius:18px; padding:18px; box-shadow:0 2px 10px rgba(0,0,0,0.05); }
</style>

<div class="container py-4">
    <?php if(!isset($_GET['kategori'])) { ?>
    <div id="homeSlider" class="carousel slide main-slider mb-4 rounded-4 overflow-hidden shadow-sm" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active"><img src="../admin/upload/cute.png" class="d-block w-100"></div>
            <div class="carousel-item"><img src="../admin/upload/tls.png" class="d-block w-100"></div>
        </div>
    </div>
    <?php } ?>

    <?php if($v) { ?>
    <div class="voucher-box mb-4">
        <div class="d-flex align-items-center">
            <div class="me-3 fs-2">🎟</div>
            <div>
                <h6 class="fw-bold text-danger mb-1"><?= $v['kode_voucher']; ?></h6>
                <small class="text-muted">Min. Belanja Rp <?= number_format($v['minimal_belanja']); ?></small>
            </div>
        </div>
    </div>
    <?php } ?>

    <div class="kategori-wrapper mb-5">
        <h5 class="fw-bold mb-3">Kategori Pilihan</h5>
        <div class="row g-2 text-center">
        <?php while($k = mysqli_fetch_assoc($kategori_list)) { ?>
            <div class="col-4 col-md-2">
                <a href="dashboard.php?kategori=<?= urlencode($k['nama_kategori']); ?>" class="text-decoration-none text-dark">
                    <img src="../admin/upload/<?= $k['ikon']; ?>" style="width:50px; height:50px; object-fit:contain;">
                    <div class="small mt-2"><?= $k['nama_kategori']; ?></div>
                </a>
            </div>
        <?php } ?>
        </div>
    </div>

    <h4 class="section-title"><?= $judul_halaman; ?></h4>
    
    <?php 
    if(mysqli_num_rows($query_produk) > 0) {
        echo '<div class="row g-0">';
        while($p = mysqli_fetch_assoc($query_produk)) { ?>
            <div class="col-6 col-md-3 col-lg-2 px-1 mb-2">
                <a href="detail_produk.php?id=<?= $p['id_produk']; ?>" class="product-card">
                    <img src="../admin/upload/<?= $p['gambar']; ?>" class="product-img" onerror="this.src='../assets/img/no-image.png'">
                    <div class="card-body">
                        <div class="product-title"><?= $p['nama_produk']; ?></div>
                        <div class="product-price">Rp <?= number_format($p['harga']); ?></div>
                    </div>
                </a>
            </div>
        <?php } 
        echo '</div>';
    } else { 
        echo '<p class="text-muted ps-3">Belum ada produk di kategori ini.</p>'; 
    } 
    ?>
</div>

<?php include '../template/footer.php'; ?>