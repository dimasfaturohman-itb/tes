<?php
session_start();
include "../config/koneksi.php";

if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

if (!isset($_GET['id'])) {
    header("Location: pesanan.php");
    exit;
}

$id_pesanan = intval($_GET['id']);

/*
|--------------------------------------------------------------------------
| CEK PESANAN + VALIDASI
|--------------------------------------------------------------------------
*/
$q = mysqli_query($conn,"
SELECT status, status_pembayaran 
FROM pesanan
WHERE id_pesanan='$id_pesanan'
AND id_user='$id_user'
");

if(mysqli_num_rows($q) == 0){
    die("Pesanan tidak ditemukan");
}

$data = mysqli_fetch_assoc($q);

/*
|--------------------------------------------------------------------------
| VALIDASI STATUS (BIAR REALISTIS)
|--------------------------------------------------------------------------
*/
if($data['status'] == 'Selesai'){
    die("Pesanan sudah diselesaikan sebelumnya");
}

if($data['status'] != 'dikirim'){
    die("Pesanan belum bisa diselesaikan (belum dikirim)");
}

/*
|--------------------------------------------------------------------------
| UPDATE STATUS
|--------------------------------------------------------------------------
*/
mysqli_query($conn,"
UPDATE pesanan
SET 
    status='Selesai',
    status_pembayaran='dibayar'
WHERE id_pesanan='$id_pesanan'
");

/*
|--------------------------------------------------------------------------
| INSERT TRACKING (JIKA ADA)
|--------------------------------------------------------------------------
*/
$cek_tracking = mysqli_query($conn, "SHOW TABLES LIKE 'tracking'");

if(mysqli_num_rows($cek_tracking) > 0){

    mysqli_query($conn,"
    INSERT INTO tracking(
        id_pesanan,
        keterangan,
        lokasi,
        waktu
    )
    VALUES(
        '$id_pesanan',
        'Pesanan telah diterima oleh pembeli',
        'Alamat tujuan',
        NOW()
    )
    ");
}

/*
|--------------------------------------------------------------------------
| REDIRECT
|--------------------------------------------------------------------------
*/
echo "<script>
alert('Pesanan berhasil diselesaikan');
window.location='detail_pesanan.php?id=$id_pesanan';
</script>";
?>