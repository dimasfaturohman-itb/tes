<?php
include "../config/koneksi.php";

$id_pesanan = $_POST['id_pesanan'];
$nama       = $_POST['nama'];
$bank       = $_POST['bank'];
$jumlah     = $_POST['jumlah'];

$nama_file = $_FILES['bukti']['name'];
$tmp       = $_FILES['bukti']['tmp_name'];

move_uploaded_file($tmp, "../upload/bukti/".$nama_file);

mysqli_query($conn, "
INSERT INTO konfirmasi_pembayaran
(id_pesanan, nama_pengirim, bank_pengirim, jumlah_transfer, bukti_transfer, tanggal_konfirmasi)
VALUES
('$id_pesanan','$nama','$bank','$jumlah','$nama_file',NOW())
");

echo "<script>
alert('Konfirmasi berhasil dikirim!');
window.location='history_pesanan.php';
</script>";