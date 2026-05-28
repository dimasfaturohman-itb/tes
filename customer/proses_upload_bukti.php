<?php
session_start();
include "../config/koneksi.php";

if(!isset($_SESSION['id_user'])){
    header("Location: ../auth/login.php");
    exit;
}

$id_user    = $_SESSION['id_user'];
$id_pesanan = intval($_POST['id_pesanan'] ?? 0);

if ($id_pesanan <= 0) {
    echo "<script>alert('ID Pesanan tidak valid!'); window.location='dashboard.php';</script>";
    exit;
}

/* =========================
   VALIDASI FILE
========================= */
if(!isset($_FILES['bukti']) || $_FILES['bukti']['error'] == 4){
    echo "<script>alert('Silakan pilih file gambar terlebih dahulu!'); window.history.back();</script>";
    exit;
}

$file = $_FILES['bukti'];

/* =========================
   VALIDASI EXTENSION
========================= */
$allowed = ['jpg','jpeg','png'];
$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

if(!in_array($ext, $allowed)){
    echo "<script>alert('Format file tidak didukung! Hanya diperbolehkan JPG, JPEG, dan PNG.'); window.history.back();</script>";
    exit;
}

/* =========================
   VALIDASI UKURAN FILE (Maksimal 2MB)
========================= */
if($file['size'] > 2 * 1024 * 1024) {
    echo "<script>alert('Ukuran file terlalu besar! Maksimal berukuran 2MB.'); window.history.back();</script>";
    exit;
}

/* =========================
   BUAT NAMA FILE BARU
========================= */
$nama_file = 'bukti_' . time() . '_' . rand(1000,9999) . '.' . $ext;

/* =========================
   FOLDER UPLOAD
========================= */
$folder = "../upload/bukti/";

/* =========================
   BUAT FOLDER JIKA BELUM ADA
========================= */
if(!is_dir($folder)){
    mkdir($folder, 0777, true);
}

/* =========================
   PROSES UPLOAD FILE
========================= */
if(move_uploaded_file($file['tmp_name'], $folder . $nama_file)){
    
    /* =========================
       UPDATE DATABASE
    ========================= */
    $update = mysqli_query($conn, "
        UPDATE pesanan
        SET
            bukti_pembayaran='$nama_file',
            status_pembayaran='menunggu_verifikasi'
        WHERE id_pesanan='$id_pesanan' AND id_user='$id_user'
    ");

    if($update) {
        // NOTIFIKASI JIKA BERHASIL
        echo "
        <script>
        alert('Bukti pembayaran berhasil di-upload! Menunggu verifikasi admin.');
        window.location='detail_pesanan.php?id=$id_pesanan';
        </script>
        ";
        exit;
    } else {
        echo "<script>alert('Gagal memperbarui status di database: " . mysqli_error($conn) . "'); window.history.back();</script>";
        exit;
    }

} else {
    echo "<script>alert('Gagal mengunggah file gambar ke server directory.'); window.history.back();</script>";
    exit;
}
?>