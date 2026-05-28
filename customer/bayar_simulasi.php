<?php
session_start();
include "../config/koneksi.php";

// validasi id
if (!isset($_GET['id'])) {
    die("ID tidak ditemukan");
}

$id = intval($_GET['id']);

// update status (FIX: pakai 'dibayar', bukan 'lunas')
$query = mysqli_query($conn, "
UPDATE pesanan
SET status='diproses',
    status_pembayaran='dibayar'
WHERE id_pesanan='$id'
");

// cek berhasil atau tidak
if(!$query){
    die("Gagal update: " . mysqli_error($conn));
}

// redirect
header("Location: detail_pesanan.php?id=".$id);
exit;