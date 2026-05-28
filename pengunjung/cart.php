<?php
session_start();

/* CEK LOGIN */
if (!isset($_SESSION['id_user'])) {

    header("Location: ../auth/register.php");
    exit;

}

include '../config/koneksi.php';
include '../template/header.php';
include '../template/navbar_customer.php';
?>

<div class="container py-5">

    <div class="alert alert-success rounded-4 shadow-sm">

        <h5 class="fw-bold mb-2">
            Keranjang Saya
        </h5>

        Selamat datang di halaman keranjang ✨

    </div>

</div>

<?php include '../template/footer.php'; ?>