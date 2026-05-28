<?php
include 'koneksi.php';

$id = $_GET['id'];

$data = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT * FROM voucher WHERE id_voucher='$id'")
);

if(isset($_POST['update'])){

    $kode   = $_POST['kode_voucher'];
    $diskon = $_POST['diskon'];
    $min    = $_POST['minimal_belanja'];
    $exp    = $_POST['expired'];
    $status = $_POST['status'];

    $update = mysqli_query($conn, "
        UPDATE voucher SET
            kode_voucher='$kode',
            diskon='$diskon',
            minimal_belanja='$min',
            expired='$exp',
            status='$status'
        WHERE id_voucher='$id'
    ");

    if($update){
        echo "<script>alert('Berhasil update');window.location='voucher.php';</script>";
    } else {
        echo "<script>alert('Gagal update');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Voucher</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h3>Edit Voucher</h3>

    <form method="POST">

        <input type="text" name="kode_voucher" class="form-control mb-2" value="<?= $data['kode_voucher']; ?>" required>

        <input type="number" name="diskon" class="form-control mb-2" value="<?= $data['diskon']; ?>" required>

        <input type="number" name="minimal_belanja" class="form-control mb-2" value="<?= $data['minimal_belanja']; ?>" required>

        <input type="date" name="expired" class="form-control mb-2" value="<?= $data['expired']; ?>" required>

        <select name="status" class="form-control mb-3">
            <option value="aktif" <?= $data['status']=='aktif'?'selected':''; ?>>Aktif</option>
            <option value="nonaktif" <?= $data['status']=='nonaktif'?'selected':''; ?>>Nonaktif</option>
        </select>

        <button name="update" class="btn btn-warning">Update</button>
        <a href="voucher.php" class="btn btn-secondary">Kembali</a>

    </form>
</div>

</body>
</html>

<?php include 'footer.php'; ?>