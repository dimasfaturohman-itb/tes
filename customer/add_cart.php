<?php
session_start();
include "../config/koneksi.php";

if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id_user   = $_SESSION['id_user'];
$id_produk = intval($_POST['id_produk']);
// Jika tidak ada varian, id_varian akan bernilai 0 atau kosong
$id_varian = isset($_POST['id_varian']) ? intval($_POST['id_varian']) : 0; 
$qty       = isset($_POST['qty']) ? intval($_POST['qty']) : 1;

// 1. Cek stok berdasarkan ada/tidaknya varian
if ($id_varian > 0) {
    // KASUS DENGAN VARIAN
    $query = mysqli_query($conn, "SELECT stok FROM varian_produk WHERE id_varian='$id_varian'");
    $data = mysqli_fetch_assoc($query);
    $stok_tersedia = $data['stok'] ?? 0;
} else {
    // KASUS TANPA VARIAN (ambil dari tabel produk utama)
    $query = mysqli_query($conn, "SELECT stok FROM produk WHERE id_produk='$id_produk'");
    $data = mysqli_fetch_assoc($query);
    $stok_tersedia = $data['stok'] ?? 0;
}

if ($qty > $stok_tersedia) {
    echo "<script>alert('Stok tidak mencukupi!'); history.back();</script>";
    exit;
}

// 2. Cek apakah sudah ada di keranjang 
// Menggunakan IFNULL agar 0 atau NULL dianggap sama jika tidak punya varian
$cek = mysqli_query($conn, "SELECT * FROM cart WHERE id_user='$id_user' AND id_produk='$id_produk' AND IFNULL(id_varian, 0) = '$id_varian'");

if (mysqli_num_rows($cek) > 0) {
    $c = mysqli_fetch_assoc($cek);
    $qty_baru = $c['qty'] + $qty;

    if ($qty_baru > $stok_tersedia) {
        $qty_baru = $stok_tersedia;
    }

    mysqli_query($conn, "UPDATE cart SET qty='$qty_baru' WHERE id_cart='".$c['id_cart']."'");
} else {
    // 3. INSERT ke cart
    // Masukkan 0 ke id_varian jika produk tidak punya varian
    mysqli_query($conn, "INSERT INTO cart (id_user, id_produk, id_varian, qty) 
                         VALUES ('$id_user', '$id_produk', '$id_varian', '$qty')");
}

header("Location: cart.php");
exit;
?>