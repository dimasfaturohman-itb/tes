<?php

include 'koneksi.php';

/*
|--------------------------------------------------------------------------
| SIMPAN VOUCHER
|--------------------------------------------------------------------------
*/

if(isset($_POST['simpan'])){

    $kode_voucher   = $_POST['kode_voucher'];
    $diskon         = $_POST['diskon'];
    $minimal        = $_POST['minimal_belanja'];
    $expired        = $_POST['expired'];
    $status         = $_POST['status'];

    $simpan = mysqli_query(
        $conn,
        "INSERT INTO voucher(
            kode_voucher,
            diskon,
            minimal_belanja,
            expired,
            status
        ) VALUES(
            '$kode_voucher',
            '$diskon',
            '$minimal',
            '$expired',
            '$status'
        )"
    );

    if($simpan){
        echo "
        <script>
            alert('Voucher berhasil ditambahkan');
            window.location='voucher.php';
        </script>
        ";
    }else{
        echo "
        <script>
            alert('Voucher gagal ditambahkan');
        </script>
        ";
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Voucher</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>

        body{
            background: #f5f5f5;
        }

        .form-box{
            background: white;
            padding: 30px;
            border-radius: 20px;
            margin-top: 50px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

    </style>

</head>
<body>

<div class="container">

    <div class="row justify-content-center">

        <div class="col-md-6">

            <div class="form-box">

                <h3 class="fw-bold mb-4">
                    Tambah Voucher
                </h3>

                <form method="POST">

                    <!-- Kode -->
                    <div class="mb-3">
                        <label>Kode Voucher</label>
                        <input type="text" name="kode_voucher" class="form-control" required>
                    </div>

                    <!-- Diskon -->
                    <div class="mb-3">
                        <label>Diskon (%)</label>
                        <input type="number" name="diskon" class="form-control" required>
                    </div>

                    <!-- Minimal Belanja -->
                    <div class="mb-3">
                        <label>Minimal Belanja</label>
                        <input type="number" name="minimal_belanja" class="form-control" required>
                    </div>

                    <!-- Expired -->
                    <div class="mb-3">
                        <label>Tanggal Expired</label>
                        <input type="date" name="expired" class="form-control" required>
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label>Status</label>
                        <select name="status" class="form-select">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>

                    <!-- Button -->
                    <button type="submit" name="simpan" class="btn btn-danger w-100">
                        Simpan Voucher
                    </button>

                    <a href="voucher.php" class="btn btn-secondary w-100 mt-2">
                        Kembali
                    </a>

                </form>

            </div>

        </div>

    </div>

</div>

</body>
</html>


<?php include 'footer.php'; ?>