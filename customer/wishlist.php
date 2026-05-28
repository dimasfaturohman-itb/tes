<?php
session_start();
include "../config/koneksi.php";

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
}

/* =========================
   FLOAT BACK BUTTON
========================= */

.back-btn{
    position:fixed;
    top:90px;
    left:20px;
    width:45px;
    height:45px;
    background:white;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    box-shadow:0 2px 10px rgba(0,0,0,0.12);
    color:#ff4d6d;
    font-size:20px;
    text-decoration:none;
    z-index:999;
    transition:0.2s;
}

.back-btn:hover{
    background:#ff4d6d;
    color:white;
}

/* =========================
   EMPTY WISHLIST
========================= */

.empty-wishlist{
    background:white;
    border-radius:24px;
    padding:70px 30px;
    text-align:center;
    box-shadow:0 2px 10px rgba(0,0,0,0.05);
}

.empty-icon{
    font-size:90px;
    color:#ffb3c1;
}

.empty-title{
    font-size:28px;
    font-weight:700;
    margin-top:18px;
}

.empty-text{
    color:#888;
    margin-top:10px;
    margin-bottom:30px;
    font-size:15px;
}

/* BUTTON */
.shop-btn{
    border-radius:50px;
    padding:12px 28px;
    font-weight:600;
}

</style>

<!-- =========================
     BACK BUTTON
========================= -->

<a href="../customer/dashboard.php"
   class="back-btn">

    <i class="bi bi-arrow-left"></i>

</a>

<!-- =========================
     CONTENT
========================= -->

<div class="container py-5">

    <!-- TITLE -->
    <div class="text-center mb-4">

        <h3 class="fw-bold">
            ❤️ Wishlist Saya
        </h3>

        <p class="text-muted">
            Produk favorit yang kamu simpan akan muncul di sini
        </p>

    </div>

    <!-- EMPTY STATE -->
    <div class="empty-wishlist">

        <!-- ICON -->
        <div class="empty-icon">

            <i class="bi bi-heart"></i>

        </div>

        <!-- TITLE -->
        <div class="empty-title">

            Wishlist Masih Kosong

        </div>

        <!-- TEXT -->
        <div class="empty-text">

            Kamu belum menambahkan produk favorit apapun.
            Yuk cari barang lucu favoritmu ✨

        </div>

        <!-- BUTTON -->
        <a href="../customer/dashboard.php"
           class="btn btn-danger shop-btn">

            Cari Produk

        </a>

    </div>

</div>

<?php include "../template/footer.php"; ?>