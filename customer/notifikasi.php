<?php 
session_start();
include "../config/koneksi.php";

if(!isset($_SESSION['id_user'])){
    header("Location: ../auth/login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

/* =========================
   FIX: MARK NOTIF AS READ
   (hanya yang belum dibaca)
========================= */
mysqli_query($conn, "
    UPDATE pesanan
    SET is_read = 1
    WHERE id_user = '$id_user'
    AND is_read = 0
");

include "../template/header.php";
include "../template/navbar_customer.php";
?>

<style>
body{
    background:#f5f5f5;
    font-family:'Segoe UI',sans-serif;
}

.notif-card{
    background:white;
    border-radius:20px;
    padding:20px;
    margin-bottom:20px;
    box-shadow:0 3px 10px rgba(0,0,0,0.05);
    display:flex;
    gap:15px;
}

.notif-icon{
    width:60px;
    height:60px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    color:white;
    font-size:24px;
}

.bg-voucher{ background:#28a745; }
.bg-pesanan{ background:#0d6efd; }

.notif-time{
    font-size:12px;
    color:#999;
}

/* =========================
   HOME BUTTON FLOATING
========================= */
.back-home-btn{
    position: fixed;
    bottom: 25px;
    right: 25px;
    width: 48px;
    height: 48px;
    background: #ff4d6d;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 6px 15px rgba(0,0,0,0.15);
    text-decoration: none;
    z-index: 999;
    transition: 0.2s ease;
}

.back-home-btn:hover{
    transform: scale(1.08);
    background: #ff355d;
}
</style>

<div class="container py-5">

    <!-- HOME BUTTON -->
    <a href="dashboard.php" class="back-home-btn">
        <i class="bi bi-house-door-fill"></i>
    </a>

    <div class="mb-4">
        <h3 class="fw-bold">🔔 Notifikasi</h3>
        <p class="text-muted">
            Voucher dan update pesanan terbaru
        </p>
    </div>

    <!-- ======================
         NOTIF PESANAN
    ======================= -->
    <?php
    $pesanan = mysqli_query($conn,"
        SELECT *
        FROM pesanan
        WHERE id_user='$id_user'
        AND (
            status='Diproses'
            OR status='Dikirim'
            OR status='Selesai'
        )
        ORDER BY id_pesanan DESC
    ");

    if(mysqli_num_rows($pesanan) > 0):
        while($p = mysqli_fetch_assoc($pesanan)) :

        $icon = "bi-box";
        if($p['status'] == 'Dikirim') $icon = "bi-truck";
        if($p['status'] == 'Selesai') $icon = "bi-check-circle";
    ?>

    <div class="notif-card">

        <div class="notif-icon bg-pesanan">
            <i class="bi <?= $icon; ?>"></i>
        </div>

        <div class="flex-grow-1">
            <div class="d-flex justify-content-between">
                <h5 class="fw-bold mb-1">
                    Pesanan #<?= $p['id_pesanan']; ?>
                </h5>

                <div class="notif-time">
                    <?= date('d M Y', strtotime($p['tanggal_pesan'])); ?>
                </div>
            </div>

            <div class="text-muted">
                Status pesanan kamu sekarang:
                <b><?= $p['status']; ?></b>
            </div>
        </div>

    </div>

    <?php endwhile; endif; ?>


    <!-- ======================
         NOTIF VOUCHER
    ======================= -->
    <?php
    $voucher = mysqli_query($conn,"
        SELECT *
        FROM voucher
        WHERE status='aktif'
        ORDER BY id_voucher DESC
    ");

    if(mysqli_num_rows($voucher) > 0):
        while($v = mysqli_fetch_assoc($voucher)) :
    ?>

    <div class="notif-card">

        <div class="notif-icon bg-voucher">
            <i class="bi bi-ticket-perforated"></i>
        </div>

        <div class="flex-grow-1">
            <div class="d-flex justify-content-between">
                <h5 class="fw-bold mb-1">
                    Voucher <?= $v['kode_voucher']; ?>
                </h5>

                <div class="notif-time">
                    Exp <?= $v['expired']; ?>
                </div>
            </div>

            <div class="text-muted">
                Diskon Rp <?= number_format($v['diskon']); ?>
                minimal belanja Rp <?= number_format($v['minimal_belanja']); ?>
            </div>
        </div>

    </div>

    <?php endwhile; endif; ?>

</div>

<script>
setInterval(function(){
    location.reload();
},10000);
</script>

<?php include "../template/footer.php"; ?>