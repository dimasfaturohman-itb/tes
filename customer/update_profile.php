<?php
session_start();
include "../config/koneksi.php";

if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/login.php");
    exit;
}

/* =========================
   AMBIL DATA
========================= */

$id_user = $_POST['id_user'];
$nama    = $_POST['nama'];
$email   = $_POST['email'];
$no_hp   = $_POST['no_hp'];
$alamat  = $_POST['alamat'];
$gender  = $_POST['gender'];

/* =========================
   UPLOAD FOTO
========================= */

if(isset($_FILES['foto']) && $_FILES['foto']['error'] == 0){

    $namaFile = $_FILES['foto']['name'];
    $tmpFile  = $_FILES['foto']['tmp_name'];

    /* nama random */
    $foto = time().'_'.$namaFile;

    /* folder */
    $uploadPath = "../assets/profile/".$foto;

    move_uploaded_file($tmpFile, $uploadPath);

    /* update + foto */
    mysqli_query($conn,"
        UPDATE users SET
        nama='$nama',
        email='$email',
        no_hp='$no_hp',
        alamat='$alamat',
        gender='$gender',
        foto='$foto'
        WHERE id_user='$id_user'
    ");

}else{

    /* update tanpa foto */
    mysqli_query($conn,"
        UPDATE users SET
        nama='$nama',
        email='$email',
        no_hp='$no_hp',
        alamat='$alamat',
        gender='$gender'
        WHERE id_user='$id_user'
    ");

}

/* update session */
$_SESSION['nama'] = $nama;

/* redirect */
header("Location: profil.php?success=1");
exit;
?>