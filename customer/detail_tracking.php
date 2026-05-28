<?php
session_start();
include "../config/koneksi.php";

if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

if (!isset($_GET['id'])) {
    header("Location: tracking.php");
    exit;
}

$id_pesanan = mysqli_real_escape_string($conn, $_GET['id']);

/* =========================
   PESANAN DITERIMA
========================= */

if(isset($_POST['pesanan_diterima'])){

    mysqli_query($conn,"
    UPDATE pesanan
    SET status='Selesai'
    WHERE id_pesanan='$id_pesanan'
    ");

    /* TAMBAH TRACKING */
    mysqli_query($conn,"
    INSERT INTO tracking(
        id_pesanan,
        lokasi,
        keterangan
    ) VALUES (
        '$id_pesanan',
        'Pesanan Selesai',
        'Pesanan telah diterima oleh customer'
    )
    ");

    header("Location: detail_tracking.php?id=".$id_pesanan);
    exit;
}

/* =========================
   AMBIL PESANAN
========================= */

$query_pesanan = mysqli_query($conn,"
SELECT *
FROM pesanan
WHERE id_pesanan='$id_pesanan'
AND id_user='$id_user'
");

$pesanan = mysqli_fetch_assoc($query_pesanan);

if(!$pesanan){
    header("Location: tracking.php");
    exit;
}

/* =========================
   DETAIL PRODUK
========================= */

$detail = mysqli_query($conn,"
SELECT dp.*, p.nama_produk, p.gambar
FROM detail_pesanan dp
JOIN produk p
ON dp.id_produk = p.id_produk
WHERE dp.id_pesanan='$id_pesanan'
");

/* =========================
   TRACKING LOG
========================= */

$tracking = mysqli_query($conn,"
SELECT *
FROM tracking
WHERE id_pesanan='$id_pesanan'
ORDER BY waktu DESC
");

/* =========================
   STATUS
========================= */

$status = strtolower($pesanan['status']);

$progress = 25;

if($status == "diproses"){
    $progress = 25;
}
elseif($status == "dikirim"){
    $progress = 75;
}
elseif($status == "selesai"){
    $progress = 100;
}

include "../template/header.php";
include "../template/navbar_customer.php";
?>

<style>

body{
    background:#f5f5f5;
    font-family:'Segoe UI',sans-serif;
}

/* CARD */

.tracking-card{
    background:white;
    border-radius:24px;
    padding:28px;
    margin-bottom:25px;
    box-shadow:0 3px 10px rgba(0,0,0,0.05);
}

/* PROGRESS */

.progress-wrapper{
    margin-top:20px;
}

.progress{
    height:12px;
    border-radius:20px;
    overflow:hidden;
    background:#eee;
}

.progress-bar{
    background:linear-gradient(90deg,#ff4d6d,#ff758f);
}

.progress-label{
    display:flex;
    justify-content:space-between;
    margin-top:10px;
    font-size:13px;
    font-weight:600;
}

/* STATUS */

.status-box{
    background:#fff0f3;
    border-radius:18px;
    padding:18px;
}

.status-title{
    font-weight:700;
    color:#ff4d6d;
}

/* TIMELINE */

.timeline{
    position:relative;
    margin-top:20px;
    padding-left:30px;
}

.timeline::before{
    content:'';
    position:absolute;
    left:8px;
    top:0;
    width:2px;
    height:100%;
    background:#ffd6de;
}

.timeline-item{
    position:relative;
    margin-bottom:30px;
}

.timeline-item::before{
    content:'';
    position:absolute;
    left:-27px;
    top:5px;
    width:16px;
    height:16px;
    border-radius:50%;
    background:#ff4d6d;
    border:3px solid #fff;
    box-shadow:0 0 0 3px #ffd6de;
}

.timeline-time{
    font-size:12px;
    color:#999;
    margin-bottom:4px;
}

.timeline-location{
    font-weight:700;
    font-size:15px;
}

.timeline-desc{
    color:#666;
    font-size:14px;
}

/* PRODUCT */

.product-item{
    display:flex;
    align-items:center;
    gap:15px;
    padding:15px 0;
    border-bottom:1px solid #f1f1f1;
}

.product-item img{
    width:80px;
    height:80px;
    border-radius:15px;
    object-fit:cover;
    border:1px solid #eee;
}

/* BUTTON BACK */

.back-btn{
    width:45px;
    height:45px;
    border-radius:50%;
    background:white;
    display:flex;
    align-items:center;
    justify-content:center;
    position:fixed;
    top:90px;
    left:20px;
    text-decoration:none;
    color:#ff4d6d;
    box-shadow:0 2px 10px rgba(0,0,0,0.1);
    z-index:999;
    transition:0.2s;
}

.back-btn:hover{
    background:#ff4d6d;
    color:white;
}

/* BUTTON */

.btn-success{
    border:none;
    padding:12px 24px;
    font-weight:600;
}

</style>

<!-- BACK -->
<a href="dashboard.php" class="back-btn">
    <i class="bi bi-arrow-left"></i>
</a>

<div class="container py-4">

    <!-- HEADER -->
    <div class="text-center mb-4">

        <h3 class="fw-bold">
            🚚 Lacak Pesanan
        </h3>

        <p class="text-muted">

            Pesanan #<?= $pesanan['id_pesanan']; ?>

        </p>

    </div>

    <!-- STATUS -->
    <div class="tracking-card">

        <div class="status-box">

            <div class="status-title mb-1">

                <?php if($status == "diproses"){ ?>
                    Paket Sedang Diproses
                <?php } ?>

                <?php if($status == "dikirim"){ ?>
                    Paket Sedang Dikirim
                <?php } ?>

                <?php if($status == "selesai"){ ?>
                    Paket Sudah Sampai
                <?php } ?>

            </div>

            <div class="text-muted">

                Estimasi sampai:
                <?= date('d M Y', strtotime('+3 day')); ?>

            </div>

        </div>

        <!-- PROGRESS -->
        <div class="progress-wrapper">

            <div class="progress">

                <div class="progress-bar"
                     style="width:<?= $progress; ?>%">

                </div>

            </div>

            <div class="progress-label">

                <span>Dikemas</span>
                <span>Dikirim</span>
                <span>Selesai</span>

            </div>

        </div>

    </div>

    <!-- BUTTON SELESAI -->

    <?php if($status == "dikirim") : ?>

    <div class="tracking-card text-center">

        <h5 class="fw-bold mb-2">

            Paket Sudah Sampai?

        </h5>

        <p class="text-muted">

            Klik tombol di bawah jika pesanan sudah diterima

        </p>

        <form method="POST">

            <button type="submit"
                    name="pesanan_diterima"
                    class="btn btn-success rounded-pill">

                <i class="bi bi-check-circle"></i>
                Pesanan Diterima

            </button>

        </form>

    </div>

    <?php endif; ?>

    <!-- TRACKING -->
    <div class="tracking-card">

        <h5 class="fw-bold mb-4">

            📍 Posisi Paket

        </h5>

        <div class="timeline">

            <?php if(mysqli_num_rows($tracking) > 0) : ?>

                <?php while($t = mysqli_fetch_assoc($tracking)) : ?>

                <div class="timeline-item">

                    <div class="timeline-time">

                        <?= date('d M Y H:i', strtotime($t['waktu'])); ?>

                    </div>

                    <div class="timeline-location">

                        <?= $t['lokasi']; ?>

                    </div>

                    <div class="timeline-desc">

                        <?= $t['keterangan']; ?>

                    </div>

                </div>

                <?php endwhile; ?>

            <?php else : ?>

                <div class="text-muted">

                    Paket sedang disiapkan seller

                </div>

            <?php endif; ?>

        </div>

    </div>

    <!-- PRODUK -->
    <div class="tracking-card">

        <h5 class="fw-bold mb-4">

            🛍 Produk Pesanan

        </h5>

        <?php while($d = mysqli_fetch_assoc($detail)) : ?>

        <div class="product-item">

            <img src="../admin/upload/<?= $d['gambar']; ?>">

            <div class="flex-grow-1">

                <div class="fw-bold">

                    <?= $d['nama_produk']; ?>

                </div>

                <small class="text-muted">

                    <?= $d['qty']; ?> item

                </small>

            </div>

            <div class="fw-bold text-danger">

                Rp <?= number_format($d['subtotal']); ?>

            </div>

        </div>

        <?php endwhile; ?>

        <div class="d-flex justify-content-between mt-4">

            <span class="fw-bold">
                Total Pembayaran
            </span>

            <span class="fw-bold text-danger fs-5">

                Rp <?= number_format($pesanan['total']); ?>

            </span>

        </div>

    </div>

</div>

<?php include "../template/footer.php"; ?>