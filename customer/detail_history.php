<?php
session_start();
include "../config/koneksi.php";

if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

$alamat_final = mysqli_real_escape_string($conn, $_POST['alamat_final'] ?? '');
$metode_bayar = strtoupper($_POST['metode_pembayaran'] ?? '');
$bank         = mysqli_real_escape_string($conn, $_POST['nama_bank'] ?? '');

$diskon       = intval($_POST['diskon'] ?? 0);
$total_akhir  = intval($_POST['total_akhir'] ?? 0);

if (!$alamat_final || !$metode_bayar) {
    die("Data tidak lengkap");
}

$total_awal = $total_akhir + $diskon;
$tanggal = date("Y-m-d H:i:s");

/*
|--------------------------------------------------------------------------
| STATUS
|--------------------------------------------------------------------------
*/
$status = ($metode_bayar == "COD") ? "diproses" : "menunggu pembayaran";
$status_pembayaran = ($metode_bayar == "COD") ? "dibayar" : "pending";

/*
|--------------------------------------------------------------------------
| GENERATE NOMOR PEMBAYARAN + BATAS WAKTU
|--------------------------------------------------------------------------
*/
$nomor_pembayaran = null;
$batas_pembayaran = null;

if($metode_bayar != "COD"){

    // contoh: INV-20260514-8392
    $nomor_pembayaran = "INV-" . date("Ymd") . "-" . rand(1000,9999);

    // batas bayar 24 jam
    $batas_pembayaran = date("Y-m-d H:i:s", strtotime("+1 day"));
}

/*
|--------------------------------------------------------------------------
| INSERT PESANAN (FIX LENGKAP)
|--------------------------------------------------------------------------
*/
mysqli_query($conn, "
INSERT INTO pesanan (
    id_user,
    tanggal_pesan,
    total,
    diskon,
    total_bayar,
    status,
    alamat_pengiriman,
    metode_pembayaran,
    bank_tujuan,
    status_pembayaran,
    nomor_pembayaran,
    batas_pembayaran
) VALUES (
    '$id_user',
    '$tanggal',
    '$total_awal',
    '$diskon',
    '$total_akhir',
    '$status',
    '$alamat_final',
    '$metode_bayar',
    '$bank',
    '$status_pembayaran',
    ".($nomor_pembayaran ? "'$nomor_pembayaran'" : "NULL").",
    ".($batas_pembayaran ? "'$batas_pembayaran'" : "NULL")."
)
");

$id_pesanan = mysqli_insert_id($conn);

/*
|--------------------------------------------------------------------------
| INSERT DETAIL PESANAN
|--------------------------------------------------------------------------
*/
foreach($_POST['checkout'] as $id_cart){

    $id_cart = intval($id_cart);

    $q = mysqli_query($conn,"
        SELECT c.*, p.harga
        FROM cart c
        JOIN produk p ON c.id_produk=p.id_produk
        WHERE c.id_cart='$id_cart'
    ");

    $d = mysqli_fetch_assoc($q);

    $subtotal = $d['harga'] * $d['qty'];

    mysqli_query($conn,"
        INSERT INTO detail_pesanan
        (id_pesanan,id_produk,qty,harga,subtotal)
        VALUES
        ('$id_pesanan','$d[id_produk]','$d[qty]','$d[harga]','$subtotal')
    ");

    // update stok
    mysqli_query($conn,"UPDATE produk SET stok = stok - $d[qty] WHERE id_produk='$d[id_produk]'");

    // hapus cart
    mysqli_query($conn,"DELETE FROM cart WHERE id_cart='$id_cart'");
}

/*
|--------------------------------------------------------------------------
| REDIRECT
|--------------------------------------------------------------------------
*/
echo "<script>
alert('Pesanan berhasil dibuat!');
window.location='detail_pesanan.php?id=$id_pesanan';
</script>";