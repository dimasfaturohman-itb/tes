<?php
session_start();
include "../config/koneksi.php";

if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id_user   = $_SESSION['id_user'];

/* ================= INPUT ================= */
$id_produk = intval($_POST['id_produk'] ?? 0);
$id_varian = intval($_POST['id_varian'] ?? 0); // Jika 0, berarti tidak ada varian
$qty       = intval($_POST['qty'] ?? 1);

/* ================= VALIDASI STOK ================= */
if ($id_varian > 0) {
    // Validasi untuk produk DENGAN VARIAN
    $q = mysqli_query($conn, "SELECT stok FROM varian_produk WHERE id_varian='$id_varian' AND id_produk='$id_produk'");
    $data = mysqli_fetch_assoc($q);
    if (!$data) { echo "<script>alert('Varian tidak tersedia'); history.back();</script>"; exit; }
    $stok = intval($data['stok']);
} else {
    // Validasi untuk produk TANPA VARIAN
    $q = mysqli_query($conn, "SELECT stok FROM produk WHERE id_produk='$id_produk'");
    $data = mysqli_fetch_assoc($q);
    if (!$data) { echo "<script>alert('Produk tidak ditemukan'); history.back();</script>"; exit; }
    $stok = intval($data['stok']);
}

if ($stok <= 0) {
    echo "<script>alert('Stok habis'); history.back();</script>";
    exit;
}
if ($qty > $stok) $qty = $stok;

/* ================= ALUR SHOPEE (CART -> CHECKOUT) ================= */
// Cek apakah item ini sudah ada di cart
// Menggunakan IFNULL untuk memastikan produk non-varian (id_varian=0) terdeteksi dengan benar
$cek_cart = mysqli_query($conn, "
    SELECT id_cart, qty 
    FROM cart 
    WHERE id_user='$id_user' 
    AND id_produk='$id_produk' 
    AND IFNULL(id_varian, 0) = '$id_varian'
");

if(mysqli_num_rows($cek_cart) > 0) {
    $c = mysqli_fetch_assoc($cek_cart);
    $id_cart_terpilih = $c['id_cart'];
    $qty_baru = min($c['qty'] + $qty, $stok);
    
    mysqli_query($conn, "UPDATE cart SET qty='$qty_baru' WHERE id_cart='$id_cart_terpilih'");
} else {
    mysqli_query($conn, "
        INSERT INTO cart (id_user, id_produk, id_varian, qty) 
        VALUES ('$id_user', '$id_produk', '$id_varian', '$qty')
    ");
    $id_cart_terpilih = mysqli_insert_id($conn);
}
?>

<!DOCTYPE html>
<html>
<head><title>Memproses ke Checkout...</title></head>
<body>
    <form id="shopeeFlowForm" action="checkout.php" method="POST">
        <input type="hidden" name="checkout[]" value="<?= $id_cart_terpilih; ?>">
    </form>

    <script>
        document.getElementById('shopeeFlowForm').submit();
    </script>
</body>
</html>