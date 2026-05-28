<?php
session_start();
include "../config/koneksi.php";

/* ================= CEK LOGIN ================= */
if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

/* ================= CEK ID ================= */
if (!isset($_GET['id'])) {
    die("ID tidak ditemukan");
}

$id = intval($_GET['id']);

/* ================= VALIDASI PESANAN ================= */
$cek = mysqli_query($conn,"
SELECT * FROM pesanan 
WHERE id_pesanan='$id' AND id_user='$id_user'
");

$data = mysqli_fetch_assoc($cek);

if (!$data) {
    die("Pesanan tidak valid");
}

/* ================= UPDATE STATUS ================= */
if ($data['status_pembayaran'] != 'dibayar') {
    mysqli_query($conn,"
    UPDATE pesanan 
    SET status_pembayaran='dibayar',
        status='diproses'
    WHERE id_pesanan='$id'
    ");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pembayaran Berhasil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background:#f5f5f5;">

<div class="container d-flex justify-content-center align-items-center" style="height:100vh;">

    <div class="card text-center p-5 shadow-sm" style="border-radius:20px; max-width:400px; width:100%;">

        <h2 class="text-success mb-3">✅ Pembayaran Berhasil</h2>

        <p class="text-muted mb-4">
            Pesanan kamu sedang diproses
        </p>

        <!-- 🔥 tombol ke detail -->
        <a href="detail_pesanan.php?id=<?= $id ?>"
           class="btn btn-primary mb-2">
           Lihat Detail Pesanan
        </a>

        <!-- 🔥 tombol ke tracking -->
        <a href="tracking_pesanan.php"
           class="btn btn-outline-secondary">
           Lihat Tracking
        </a>

    </div>

</div>

</body>
</html>