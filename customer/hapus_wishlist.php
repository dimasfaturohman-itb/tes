<?php
session_start();
include "../config/koneksi.php";

if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id_user     = $_SESSION['id_user'];
$id_wishlist = intval($_GET['id'] ?? 0);

if ($id_wishlist) {
    // Validasi kepemilikan sebelum hapus
    mysqli_query($conn, "
        DELETE FROM wishlist
        WHERE id_wishlist='$id_wishlist' AND id_user='$id_user'
    ");
}

header("Location: wishlist.php");
exit;
