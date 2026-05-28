<?php

include 'koneksi.php';

$id = $_GET['id'];

$hapus = mysqli_query($conn, "DELETE FROM voucher WHERE id_voucher='$id'");

if($hapus){
    echo "<script>alert('Voucher dihapus');window.location='voucher.php';</script>";
}else{
    echo "<script>alert('Gagal hapus');</script>";
}