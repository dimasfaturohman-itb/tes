<?php
session_start();

$_SESSION['notif_dibaca'] = "sudah";

if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/login.php");
    exit;
}

include "../template/header.php";
include "../template/navbar_customer.php";
?>
<style>

body{
    background:#f5f5f5;
    font-family:'Segoe UI', sans-serif;
}

/* TITLE */
.page-title{
    font-size:28px;
    font-weight:700;
}

/* NOTIF CARD */
.notif-card{
    background:white;
    border-radius:20px;
    padding:20px;
    box-shadow:0 2px 10px rgba(0,0,0,0.05);
    transition:0.2s;
}

.notif-card:hover{
    transform:translateY(-3px);
}

.notif-icon{
    width:55px;
    height:55px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:24px;
}

/* BACK BUTTON */
.back-btn{
    position:fixed;
    top:90px;
    left:20px;
    width:48px;
    height:48px;
    background:white;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    box-shadow:0 4px 12px rgba(0,0,0,0.12);
    color:#ff4d6d;
    text-decoration:none;
    font-size:22px;
    z-index:999;
}

.back-btn:hover{
    background:#ff4d6d;
    color:white;
}

</style>

<!-- BACK -->
<a href="../customer/dashboard.php"
   class="back-btn">

    <i class="bi bi-arrow-left"></i>

</a>

<div class="container py-4">

    <!-- TITLE -->
    <div class="mb-4">

        <div class="page-title">
            🔔 Notifikasi
        </div>

        <small class="text-muted">
            Informasi promo dan pesanan terbaru
        </small>

    </div>

    <!-- NOTIF 1 -->
    <div class="notif-card mb-3">

        <div class="d-flex align-items-start gap-3">

            <div class="notif-icon bg-danger text-white">

                <i class="bi bi-ticket-perforated"></i>

            </div>

            <div class="flex-grow-1">

                <h6 class="fw-bold mb-1">
                    Voucher Baru Untukmu 🎟
                </h6>

                <p class="text-muted mb-1">

                    Dapatkan diskon Rp20.000
                    untuk minimal pembelian Rp100.000

                </p>

                <small class="text-secondary">
                    5 menit lalu
                </small>

            </div>

        </div>

    </div>

    <!-- NOTIF 2 -->
    <div class="notif-card mb-3">

        <div class="d-flex align-items-start gap-3">

            <div class="notif-icon bg-success text-white">

                <i class="bi bi-truck"></i>

            </div>

            <div class="flex-grow-1">

                <h6 class="fw-bold mb-1">
                    Pesanan Sedang Diproses 🚚
                </h6>

                <p class="text-muted mb-1">

                    Pesanan kamu sedang dikemas oleh seller.

                </p>

                <small class="text-secondary">
                    1 jam lalu
                </small>

            </div>

        </div>

    </div>

    <!-- NOTIF 3 -->
    <div class="notif-card mb-3">

        <div class="d-flex align-items-start gap-3">

            <div class="notif-icon bg-warning text-dark">

                <i class="bi bi-fire"></i>

            </div>

            <div class="flex-grow-1">

                <h6 class="fw-bold mb-1">
                    Flash Sale Dimulai 🔥
                </h6>

                <p class="text-muted mb-1">

                    Banyak produk Miniso diskon hingga 50%.

                </p>

                <small class="text-secondary">
                    Hari ini
                </small>

            </div>

        </div>

    </div>

    <!-- EMPTY -->
    <div class="bg-white rounded-4 p-5 text-center shadow-sm mt-4">

        <i class="bi bi-bell fs-1 text-muted"></i>

        <h5 class="fw-bold mt-3">
            Semua Notifikasi Sudah Dibaca
        </h5>

        <p class="text-muted">

            Notifikasi terbaru akan muncul di sini ✨

        </p>

    </div>

</div>

<?php include "../template/footer.php"; ?>