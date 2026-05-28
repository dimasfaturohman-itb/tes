<?php

if(session_status() == PHP_SESSION_NONE){
    session_start();
}

include "../config/koneksi.php";

$id_user = $_SESSION['id_user'] ?? 0;

/* =========================
   AMBIL DATA USER
========================= */

$queryUser = mysqli_query($conn,"
SELECT * FROM users
WHERE id_user='$id_user'
");

$user = mysqli_fetch_assoc($queryUser ?? []);

/* =========================
   FOTO PROFILE
========================= */

$fotoProfile = (!empty($user['foto']))
    ? "../assets/profile/" . $user['foto']
    : "../assets/img/user.png";

/* =========================
   TOTAL CART
========================= */

$queryCart = mysqli_query($conn,"
SELECT COUNT(*) as total
FROM cart
WHERE id_user='$id_user'
");

$dataCart = mysqli_fetch_assoc($queryCart);
$total_cart = $dataCart['total'] ?? 0;

/* =========================
   TOTAL PESANAN
========================= */

$queryPesanan = mysqli_query($conn,"
SELECT COUNT(*) as total
FROM pesanan
WHERE id_user='$id_user'
AND status IN ('Diproses','Menunggu pembayaran')
");

$dataPesanan = mysqli_fetch_assoc($queryPesanan);
$total_pesanan = $dataPesanan['total'] ?? 0;

/* =========================
   TOTAL TRACKING
========================= */

$queryTracking = mysqli_query($conn,"
SELECT COUNT(*) as total
FROM pesanan
WHERE id_user='$id_user'
AND status='Dikirim'
");

$dataTracking = mysqli_fetch_assoc($queryTracking);
$total_tracking = $dataTracking['total'] ?? 0;

/* =========================
   🔥 FIX NOTIF (INI YANG BENAR)
========================= */

$queryNotif = mysqli_query($conn,"
SELECT COUNT(*) as total
FROM pesanan
WHERE id_user='$id_user'
AND is_read = 0
");

$dataNotif = mysqli_fetch_assoc($queryNotif);
$total_notif = $dataNotif['total'] ?? 0;

/* --- LOGIKA FILTER KATEGORI & SEARCH --- */
$where = "WHERE 1=1"; // Gunakan 1=1 agar mudah menambahkan kondisi AND

// Filter Kategori
if(isset($_GET['kategori']) && !empty($_GET['kategori'])) {
    $kategori_filter = mysqli_real_escape_string($conn, $_GET['kategori']);
    $where .= " AND kategori.nama_kategori = '$kategori_filter'";
}

// Filter Pencarian (SEARCH)
if(isset($_GET['cari']) && !empty($_GET['cari'])) {
    $cari = mysqli_real_escape_string($conn, $_GET['cari']);
    $where .= " AND produk.nama_produk LIKE '%$cari%'";
}

// Query Utama (Gunakan variabel $where)
$query_sql = "SELECT produk.*, kategori.nama_kategori FROM produk 
              LEFT JOIN kategori ON produk.id_kategori = kategori.id_kategori 
              $where";

$query_produk = mysqli_query($conn, $query_sql);

?>
<style>

/* NAVBAR */
.navbar-custom{
    background:white;
    box-shadow:0 2px 10px rgba(0,0,0,0.05);
    padding:14px 0;
    z-index:999;
}

.logo-text{
    font-weight:800;
    font-size:32px;
    color:#ff4d6d !important;
    text-decoration:none;
}

.search-input{
    border-radius:50px;
    padding:12px 20px;
    border:1px solid #eee;
    background:#fafafa;
}

.search-input:focus{
    box-shadow:none;
    border-color:#ff4d6d;
    background:white;
}

.menu-wrapper{
    display:flex;
    align-items:center;
    gap:24px;
}

.menu-icon{
    color:#333;
    position:relative;
    transition:0.2s;
    display:flex;
    flex-direction:column;
    align-items:center;
    text-decoration:none;
}

.menu-icon i{
    font-size:22px;
}

.menu-icon small{
    font-size:11px;
    margin-top:4px;
    font-weight:500;
}

.menu-icon:hover{
    color:#ff4d6d;
    transform:translateY(-2px);
}

.badge-custom{
    position:absolute;
    top:-6px;
    right:-8px;
    font-size:10px;
    min-width:18px;
    height:18px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
}

.profile-navbar{
    padding-left:20px;
    border-left:1px solid #eee;
}

.profile-img{
    width:42px;
    height:42px;
    border-radius:50%;
    object-fit:cover;
    border:2px solid #eee;
}

.profile-name{
    font-size:14px;
    font-weight:700;
    margin-bottom:0;
}

.profile-text{
    font-size:12px;
    color:#888;
}

</style>

<nav class="navbar navbar-expand-lg navbar-light navbar-custom sticky-top">

<div class="container d-flex align-items-center">

    <!-- LOGO -->
    <a href="../customer/dashboard.php" class="logo-text me-4">
        Mochimo
    </a>

    <!-- SEARCH -->
    <form class="d-flex flex-grow-1 me-4 search-wrapper"
          method="GET"
          action="../customer/dashboard.php">

        <input type="search"
               name="cari"
               class="form-control search-input"
               placeholder="Cari produk..."
               autocomplete="off">
    </form>

    <!-- MENU -->
    <div class="menu-wrapper me-3">

        <!-- KERANJANG -->
        <a href="../customer/cart.php" class="menu-icon">
            <i class="bi bi-cart3"></i>
            <?php if($total_cart > 0){ ?>
                <span class="badge bg-danger badge-custom">
                    <?= $total_cart; ?>
                </span>
            <?php } ?>
            <small>Keranjang</small>
        </a>

        <!-- PESANAN -->
        <a href="../customer/pesanan.php" class="menu-icon">
            <i class="bi bi-bag-check"></i>
            <?php if($total_pesanan > 0){ ?>
                <span class="badge bg-danger badge-custom">
                    <?= $total_pesanan; ?>
                </span>
            <?php } ?>
            <small>Pesanan</small>
        </a>

        <!-- TRACKING -->
        <a href="../customer/tracking_pesanan.php" class="menu-icon">
            <i class="bi bi-truck"></i>
            <?php if($total_tracking > 0){ ?>
                <span class="badge bg-danger badge-custom">
                    <?= $total_tracking; ?>
                </span>
            <?php } ?>
            <small>Tracking</small>
        </a>

        <!-- RIWAYAT -->
        <a href="../customer/riwayat.php" class="menu-icon">
            <i class="bi bi-clock-history"></i>
            <small>Riwayat</small>
        </a>

        <!-- NOTIF -->
        <a href="../customer/notifikasi.php" class="menu-icon">
            <i class="bi bi-bell"></i>
            <?php if($total_notif > 0){ ?>
                <span class="badge bg-danger badge-custom">
                    <?= $total_notif; ?>
                </span>
            <?php } ?>
            <small>Notif</small>
        </a>

    </div>

    <!-- PROFILE -->
    <div class="d-flex align-items-center profile-navbar">

        <a href="../customer/profil.php"
           class="d-flex align-items-center text-decoration-none text-dark gap-2">

            <img src="<?= $fotoProfile; ?>" class="profile-img">

            <div>
                <div class="profile-name">
                    <?= isset($user['nama']) ? $user['nama'] : 'Customer'; ?>
                </div>
                <div class="profile-text">
                    Lihat Profil
                </div>
            </div>
        </a>

        <a href="../auth/logout.php" class="text-danger ms-3">
            <i class="bi bi-box-arrow-right fs-5"></i>
        </a>

    </div>

</div>

</nav>