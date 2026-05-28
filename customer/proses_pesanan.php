<?php
// Paksa PHP menampilkan error jika ada kegagalan query tersembunyi
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include "../config/koneksi.php";

if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

// 1. VALIDASI INPUT POST (Dibuat lebih aman & fleksibel)
if (!isset($_POST['checkout']) || empty($_POST['checkout'])) {
    echo "<script>alert('Keranjang belanja kosong atau akses tidak sah!'); window.location='cart.php';</script>";
    exit;
}

$alamat_final = mysqli_real_escape_string($conn, $_POST['alamat_final'] ?? 'Alamat belum diisi');
$metode_pembayaran = mysqli_real_escape_string($conn, $_POST['metode_pembayaran'] ?? 'COD');
$bank_tujuan = mysqli_real_escape_string($conn, $_POST['nama_bank'] ?? '');
$total_bayar = intval($_POST['total_akhir'] ?? 0);
$diskon = intval($_POST['diskon'] ?? 0);
$id_cart_array = $_POST['checkout']; // Array ID Cart dari input hidden

// 2. TENTUKAN BATAS PEMBAYARAN & INVOICE
// AMAN STRICT MODE: Jika COD, gunakan keyword NULL (tanpa tanda petik) di SQL
$batas_pembayaran = "NULL"; 
$nomor_pembayaran = "";

if ($metode_pembayaran == 'BANK' || $metode_pembayaran == 'QRIS') {
    $prefix = ($metode_pembayaran == 'BANK') ? 'TRF' : 'QRS';
    $nomor_pembayaran = $prefix . '-' . date('Ymd') . '-' . rand(100000, 999999);
    
    // Dibungkus tanda petik karena berupa string datetime untuk query
    $batas_pembayaran = "'" . date("Y-m-d H:i:s", strtotime("+1 day")) . "'"; 
}

// 3. INSERT KE TABEL UTAMA (pesanan)
// $batas_pembayaran dipasang TANPA tanda petik tunggal agar MySQL bisa membaca keyword NULL secara murni
$query_pesanan = "INSERT INTO pesanan 
    (id_user, nomor_pembayaran, metode_pembayaran, bank_tujuan, total_bayar, diskon, alamat_pengiriman, status, status_pembayaran, batas_pembayaran, tanggal_pesan) 
    VALUES 
    ('$id_user', '$nomor_pembayaran', '$metode_pembayaran', '$bank_tujuan', '$total_bayar', '$diskon', '$alamat_final', 'diproses', 'pending', $batas_pembayaran, NOW())";

$insert_pesanan = mysqli_query($conn, $query_pesanan);

if (!$insert_pesanan) {
    die("<div style='padding:20px; background:#fff0f2; color:#ff4d6d; border:1px solid #ffccd5; border-radius:8px;'>
            <h3>Gagal Membuat Pesanan Utama 😭</h3>
            <p>Error: " . mysqli_error($conn) . "</p>
         </div>");
}

// Ambil ID pesanan barusan untuk relasi detail_pesanan
$id_pesanan_baru = mysqli_insert_id($conn);

// 4. PINDAHKAN BARANG DARI CART KE DETAIL PESANAN
foreach ($id_cart_array as $id_cart) {
    $id_cart = intval($id_cart);
    
    // Ambil data barang spesifik dari cart user
    $cart_item_q = mysqli_query($conn, "SELECT * FROM cart WHERE id_cart='$id_cart' AND id_user='$id_user'");
    $cart_item = mysqli_fetch_assoc($cart_item_q);
    
    if ($cart_item) {
        $id_produk = $cart_item['id_produk'];
        $qty = $cart_item['qty'];
        $ukuran = mysqli_real_escape_string($conn, $cart_item['ukuran'] ?? 'Standard');
        
        // Ambil harga asli produk terbaru dari tabel produk
        $produk_q = mysqli_query($conn, "SELECT harga FROM produk WHERE id_produk='$id_produk'");
        $prod = mysqli_fetch_assoc($produk_q);
        $harga = $prod['harga'] ?? 0;
        
        // Masukkan ke detail_pesanan
        mysqli_query($conn, "INSERT INTO detail_pesanan (id_pesanan, id_produk, qty, harga, ukuran) VALUES ('$id_pesanan_baru', '$id_produk', '$qty', '$harga', '$ukuran')");
        
        // Hapus item yang sudah dibeli dari keranjang
        mysqli_query($conn, "DELETE FROM cart WHERE id_cart='$id_cart' AND id_user='$id_user'");
    }
}

// 5. REDIRECT BERSIH & AMAN
echo "<script>
    alert('Pesanan kamu berhasil dibuat! ✨ Silakan cek detailnya.');
    window.location.href = 'detail_pesanan.php?id=$id_pesanan_baru';
</script>";

exit; // <--- KUNCI UTAMA: Menghentikan sisa kompilasi server agar tidak bocor ke browser
?>