<?php
session_start();

include "koneksi.php";

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

$id = intval($_GET['id'] ?? 0);

/* =========================
   UPDATE STATUS
========================= */

mysqli_query($conn,"
UPDATE pesanan
SET
    status_pembayaran='dibayar',
    status='diproses'
WHERE id_pesanan='$id'
");

header("Location: pemasukan.php");
exit;
?>