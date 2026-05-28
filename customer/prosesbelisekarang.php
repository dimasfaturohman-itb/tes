<?php
session_start();
include "../config/koneksi.php";

if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id_user = $_SESSION['id_user'];
$id_produk = intval($_POST['id_produk'] ?? 0);
$qty = intval($_POST['qty'] ?? 1);
$ukuran = trim($_POST['ukuran'] ?? '');
$warna = trim($_POST['warna'] ?? '');
$alamat = mysqli_real_escape_string($conn, $_POST['alamat'] ?? '');
$metode = strtoupper(trim($_POST['metode'] ?? $_POST['metode_pembayaran'] ?? 'COD'));

// 1. Ambil Data Produk
$q = mysqli_query($conn, "SELECT * FROM produk WHERE id_produk='$id_produk'");
if (!$q) {
    // JIKA QUERY ERROR, TAMPILKAN POP-UP DAN BERHENTI
    echo "<script>alert('Error Ambil Produk: " . mysqli_real_escape_string($conn, mysqli_error($conn)) . "'); history.back();</script>";
    exit;
}
$p = mysqli_fetch_assoc($q);

if (!$p) {
    echo "<script>alert('Produk tidak ditemukan'); window.location='dashboard.php';</script>";
    exit;
}

// 2. Logika Cek Stok & Harga
$stok = 0;
$harga_satuan = $p['harga']; // Menggunakan harga dari tabel produk karena tabel varian tidak punya kolom harga_varian
$pakaiVarian = false;
$id_varian_terpilih = 0;

if(!empty($ukuran) || !empty($warna)){
    $qVarian = mysqli_query($conn, "SELECT * FROM varian_produk WHERE id_produk='$id_produk' AND ukuran='$ukuran' AND warna='$warna' LIMIT 1");
    
    if(!$qVarian){
        // JIKA QUERY VARIAN ERROR, TAMPILKAN DI SINI
        echo "<script>alert('Error Query Varian: " . mysqli_real_escape_string($conn, mysqli_error($conn)) . "'); history.back();</script>";
        exit;
    }
    
    $varian = mysqli_fetch_assoc($qVarian);

    if($varian){
        $stok = intval($varian['stok']);
        $id_varian_terpilih = intval($varian['id_varian']);
        $pakaiVarian = true;
    } else {
        echo "<script>alert('Varian tidak tersedia'); history.back();</script>";
        exit;
    }
} else {
    $stok = intval($p['stok']);
}

// 3. Validasi Stok
if($stok <= 0){
    echo "<script>alert('Maaf, stok habis.'); history.back();</script>";
    exit;
}
if($qty > $stok) { $qty = $stok; }

// 4. Hitung Total
$total = $harga_satuan * $qty;

// 5. Insert Pesanan
$insertPesanan = mysqli_query($conn, "INSERT INTO pesanan (id_user, tanggal_pesan, total, total_bayar, status, alamat_pengiriman, metode_pembayaran) VALUES ('$id_user', NOW(), '$total', '$total', '$metode', '$alamat', '$metode')");

if(!$insertPesanan){
    // JIKA GAGAL INSERT PESANAN UTAMA, TAMPILKAN ERROR DATABASE DI SINI
    echo "<script>alert('Error Insert Pesanan: " . mysqli_real_escape_string($conn, mysqli_error($conn)) . "'); history.back();</script>";
    exit;
}

$id_pesanan = mysqli_insert_id($conn);

// 6. Insert Detail Pesanan
$insertDetail = mysqli_query($conn, "INSERT INTO detail_pesanan (id_pesanan, id_produk, ukuran, warna, qty, harga, subtotal) VALUES ('$id_pesanan', '$id_produk', '$ukuran', '$warna', '$qty', '$harga_satuan', '$total')");

if(!$insertDetail){
    // JIKA GAGAL INSERT DETAIL, TAMPILKAN ERROR DATABASE DI SINI
    echo "<script>alert('Error Insert Detail Pesanan: " . mysqli_real_escape_string($conn, mysqli_error($conn)) . "'); history.back();</script>";
    exit;
}

// 7. Update Stok
if($pakaiVarian){
    mysqli_query($conn, "UPDATE varian_produk SET stok = stok - $qty WHERE id_varian='$id_varian_terpilih'");
    mysqli_query($conn, "UPDATE produk SET stok = stok - $qty WHERE id_produk='$id_produk'");
} else {
    mysqli_query($conn, "UPDATE produk SET stok = stok - $qty WHERE id_produk='$id_produk'");
}

echo "<script>alert('Pesanan berhasil dibuat!'); window.location='detail_pesanan.php?id=$id_pesanan';</script>";
?>