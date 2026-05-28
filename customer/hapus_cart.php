<?php
session_start();
include "../config/koneksi.php";

if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id_user = $_SESSION['id_user'];
$id_cart = $_GET['id'];

// hapus data (pakai id_cart biar aman)
mysqli_query($conn, "
    DELETE FROM cart
    WHERE id_cart='$id_cart'
    AND id_user='$id_user'
");

header("Location: cart.php");
exit;
?>