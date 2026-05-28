<?php
include 'koneksi.php';

$id = $_GET['id'];

$query = mysqli_query($conn,
    "SELECT * FROM kategori WHERE id_kategori='$id'"
);

$data = mysqli_fetch_assoc($query);

if(isset($_POST['update'])){

    $nama_kategori = mysqli_real_escape_string($conn, $_POST['nama_kategori']);

    $ikon = $data['ikon'];

    if(!empty($_FILES['ikon']['name'])){

        $namaFile = time().'_'.$_FILES['ikon']['name'];

        move_uploaded_file(
            $_FILES['ikon']['tmp_name'],
            "upload/".$namaFile
        );

        $ikon = $namaFile;
    }

    $update = mysqli_query($conn,
        "UPDATE kategori 
         SET nama_kategori='$nama_kategori',
             ikon='$ikon'
         WHERE id_kategori='$id'"
    );

    if($update){
        echo "<script>
            alert('Kategori berhasil diupdate');
            window.location='kategori.php';
        </script>";
    }else{
        echo "<script>alert('Gagal update');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Edit Kategori</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>

body{
    background:#f5f5f5;
    font-family:'Segoe UI',sans-serif;
}

.navbar{
    background:linear-gradient(135deg,#ff4d6d,#ff758f);
}

.form-box{
    background:white;
    padding:35px;
    border-radius:24px;
    margin-top:60px;
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
}

.upload-box{
    border:2px dashed #ff4d6d;
    border-radius:16px;
    padding:20px;
    text-align:center;
    cursor:pointer;
    background:#fff7f9;
}

.preview img{
    width:90px;
    height:90px;
    object-fit:cover;
    border-radius:14px;
    margin-top:10px;
}

.btn-pink{
    background:linear-gradient(135deg,#ff4d6d,#ff758f);
    border:none;
    color:white;
    font-weight:600;
}

.action-box{
    position:sticky;
    bottom:0;
    background:white;
    padding-top:15px;
}

</style>

</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark shadow">

    <div class="container-fluid">

        <!-- LOGO -->
        <a class="navbar-brand text-white"
           href="dashboard.php">

            MiniShop

        </a>

        <!-- TOGGLER -->
        <button class="navbar-toggler"
                data-bs-toggle="collapse"
                data-bs-target="#navbarAdmin">

            <span class="navbar-toggler-icon"></span>

        </button>

        <!-- MENU -->
        <div class="collapse navbar-collapse"
             id="navbarAdmin">

            <ul class="navbar-nav ms-auto align-items-lg-center">

                <!-- DASHBOARD -->
                <li class="nav-item">

                    <a class="nav-link active"
                       href="dashboard.php">

                        <i class="bi bi-grid-fill"></i>
                        Dashboard

                    </a>

                </li>

                <!-- MASTER DATA -->
                <li class="nav-item dropdown">

                    <a class="nav-link dropdown-toggle"
                       data-bs-toggle="dropdown">

                        <i class="bi bi-folder-fill"></i>
                        Master Data

                    </a>

                    <ul class="dropdown-menu">

                        <li>

                            <a class="dropdown-item"
                               href="produk.php">

                                <i class="bi bi-box-seam"></i>
                                Produk

                            </a>

                        </li>

                        <li>

                            <a class="dropdown-item"
                               href="kategori.php">

                                <i class="bi bi-tags-fill"></i>
                                Kategori

                            </a>

                        </li>

                        <li>

                            <a class="dropdown-item"
                               href="voucher.php">

                                <i class="bi bi-ticket-perforated-fill"></i>
                                Voucher

                            </a>

                        </li>


                    </ul>

                </li>

                <!-- PESANAN -->
                <li class="nav-item">

                    <a class="nav-link"
                       href="pesanan.php">

                        <i class="bi bi-cart-fill"></i>
                        Pesanan

                    </a>

                </li>

                <!-- CUSTOMER -->
                <li class="nav-item">

                    <a class="nav-link"
                       href="customer.php">

                        <i class="bi bi-people-fill"></i>
                        Customer

                    </a>

                </li>

                <!-- LOGOUT -->
                <li class="nav-item ms-lg-3">

                    <a href="logout.php"
                       class="btn btn-light text-danger rounded-pill px-4">

                        <i class="bi bi-box-arrow-right"></i>
                        Logout

                    </a>

                </li>

            </ul>

        </div>

    </div>

</nav>

<div class="container">
<div class="row justify-content-center">

<div class="col-md-6">

<div class="form-box">

    <h3 class="text-center fw-bold mb-4">Edit Kategori</h3>

    <form method="POST" enctype="multipart/form-data">

        <label class="form-label fw-semibold">Nama Kategori</label>
        <input type="text" name="nama_kategori"
               class="form-control mb-3"
               value="<?= $data['nama_kategori']; ?>">

        <label class="form-label fw-semibold">Ikon</label>

        <label class="upload-box w-100 mb-2">
            <i class="bi bi-image fs-1 text-danger"></i>
            <input type="file" name="ikon" hidden onchange="previewIcon(event)">
        </label>

        <div class="preview">
            <?php if($data['ikon']): ?>
                <img src="upload/<?= $data['ikon']; ?>">
            <?php endif; ?>
        </div>

        <div class="action-box d-flex gap-2 mt-3">

            <button type="submit" name="update" class="btn btn-pink w-100">
                Update
            </button>

            <a href="kategori.php" class="btn btn-secondary w-100">
                Kembali
            </a>

        </div>

    </form>

</div>

</div>

</div>
</div>

<script>
function previewIcon(event){
    const reader = new FileReader();
    reader.onload = e => {
        document.querySelector('.preview').innerHTML =
        `<img src="${e.target.result}">`;
    }
    reader.readAsDataURL(event.target.files[0]);
}
</script>

</body>
</html>


<?php include 'footer.php'; ?>