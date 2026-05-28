<?php
session_start();
include "../config/koneksi.php";

// Pastikan user sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: auth/login.php");
    exit;
}

// Periksa apakah tombol 'konfirmasi' ditekan
if(isset($_POST['konfirmasi'])) {
    
    // Ambil id_pesanan dari form
    $id_pesanan = mysqli_real_escape_string($conn, $_POST['id_pesanan']);
    
    // 1. Update status pesanan di database menjadi 'selesai'
// Tambahkan kondisi status='dikirim' agar jika sudah selesai tidak bisa diproses lagi
    $update = mysqli_query($conn, "UPDATE pesanan SET status = 'selesai' WHERE id_pesanan = '$id_pesanan' AND status = 'dikirim'");    
    if($update) {
        // 2. Tambahkan record ke tracking sebagai bukti
        mysqli_query($conn, "INSERT INTO tracking (id_pesanan, lokasi, keterangan, waktu) VALUES ('$id_pesanan', 'Customer', 'Pesanan telah dikonfirmasi diterima oleh pembeli', NOW())");
        
        echo "<script>
                alert('Terima kasih! Pesanan telah selesai.'); 
                window.location='riwayat.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal mengupdate status!'); 
                history.back();
              </script>";
    }
} else {
    // Jika file diakses langsung tanpa tombol, arahkan ke halaman pelacakan
    header("Location: tracking_pesanan.php");
}
?>