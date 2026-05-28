<?php

include 'koneksi.php';

/*
|--------------------------------------------------------------------------
| AMBIL DATA VOUCHER
|--------------------------------------------------------------------------
*/

$query = mysqli_query(
    $conn,
    "SELECT * FROM voucher ORDER BY id_voucher DESC"
);

?>

<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Voucher</title>

    <!-- Bootstrap 5 -->
    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- Bootstrap Icons -->
    <link 
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <style>

        body{
            background: #f5f5f5;
            font-family: Arial, sans-serif;
        }

        /* Navbar */
        .navbar{
            background: linear-gradient(135deg, #ff4d6d, #ff758f);
            padding: 15px 25px;
        }

        .navbar-brand{
            font-size: 30px;
            font-weight: bold;
        }

        .nav-link{
            color: white !important;
            margin-right: 10px;
            font-weight: 500;
        }

        /* Box */
        .container-box{
            background: white;
            padding: 30px;
            border-radius: 20px;
            margin-top: 40px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .table thead{
            background: #ff4d6d;
            color: white;
        }

        .btn{
            border-radius: 10px;
        }

        /* FAB TOMBOL BACK (Rumah) */
.fab-dashboard {
    position: fixed; 
    bottom: 30px; 
    right: 30px;
    width: 60px; 
    height: 60px;
    background: #ff4d6d; 
    color: white; 
    border-radius: 50%;
    display: flex; 
    align-items: center; 
    justify-content: center;
    box-shadow: 0 10px 20px rgba(255, 77, 109, 0.4);
    z-index: 9999; 
    text-decoration: none; 
    transition: 0.3s;
}
.fab-dashboard:hover { 
    transform: translateY(-5px); 
    color: white; 
    background: #ff758f;
}

    </style>

</head>
<body>


<a href="dashboard.php" class="fab-dashboard" title="Kembali ke Dashboard">
    <i class="bi bi-house-door-fill" style="font-size: 24px;"></i>
</a>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark shadow">

    <div class="container-fluid">

        <!-- Logo -->
        <a class="navbar-brand text-white" href="#">
            Mochimo
        </a>

        <!-- Toggle Mobile -->
        <button 
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarAdmin"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu -->
        <div class="collapse navbar-collapse" id="navbarAdmin">

            <ul class="navbar-nav ms-auto align-items-lg-center">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a class="nav-link active" href="dashboard.php">

                        <i class="bi bi-grid-fill"></i>
                        Dashboard

                    </a>
                </li>

                <!-- MASTER DATA DROPDOWN -->
                <li class="nav-item dropdown">

                    <a 
                        class="nav-link dropdown-toggle"
                        href="#"
                        role="button"
                        data-bs-toggle="dropdown"
                    >

                        <i class="bi bi-folder-fill"></i>
                        Master Data

                    </a>

                    <ul class="dropdown-menu">

                        <li>
                            <a class="dropdown-item" href="produk.php">

                                <i class="bi bi-box-seam"></i>
                                Produk

                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item" href="kategori.php">

                                <i class="bi bi-tags-fill"></i>
                                Kategori

                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item" href="voucher.php">

                                <i class="bi bi-ticket-perforated-fill"></i>
                                Voucher

                            </a>
                        </li>

                        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="tambah_tracking.php"><i class="bi bi-truck"></i> Update Tracking</a></li>
        

                    </ul>

                </li>

                <!-- Pesanan -->
                <li class="nav-item">
                    <a class="nav-link" href="pesanan.php">

                        <i class="bi bi-cart-fill"></i>
                        Pesanan

                    </a>
                </li>

                <!-- PEMASUKAN -->
                <li class="nav-item">

                    <a class="nav-link"
                    href="pemasukan.php">

                        <i class="bi bi-cash-stack"></i>
                        Pemasukan

                    </a>

                </li>

                <!-- Customer -->
                <li class="nav-item">
                    <a class="nav-link" href="customer.php">

                        <i class="bi bi-people-fill"></i>
                        Customer

                    </a>
                </li>

                <!-- Logout -->
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

<!-- Content -->
<div class="container">

    <div class="container-box">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h3 class="fw-bold mb-1">
                    Data Voucher
                </h3>

                <p class="text-muted mb-0">
                    Kelola voucher promo ecommerce
                </p>

            </div>

            <!-- Button -->
            <a 
                href="tambah_voucher.php"
                class="btn btn-danger"
            >

                <i class="bi bi-plus-circle"></i>

                Tambah Voucher

            </a>

        </div>

        <!-- Table -->
        <div class="table-responsive">

            <table class="table table-bordered table-hover align-middle">

                <thead>

                    <tr>

                        <th>No</th>
                        <th>Kode Voucher</th>
                        <th>Diskon</th>
                        <th>Minimal Belanja</th>
                        <th>Expired</th>
                        <th>Status</th>
                        <th width="20%">Aksi</th>

                    </tr>

                </thead>

                <tbody>

                    <?php
                    $no = 1;

                    while($data = mysqli_fetch_assoc($query)) :
                    ?>

                    <tr>

                        <!-- No -->
                        <td>
                            <?php echo $no++; ?>
                        </td>

                        <!-- Kode -->
                        <td>

                            <strong>
                                <?php echo $data['kode_voucher']; ?>
                            </strong>

                        </td>

                        <!-- Diskon -->
                        <td>

                            <?php echo $data['diskon']; ?>%

                        </td>

                        <!-- Minimal Belanja -->
                        <td>

                            Rp <?php echo number_format($data['minimal_belanja']); ?>

                        </td>

                        <!-- Expired -->
                        <td>

                            <?php echo $data['expired']; ?>

                        </td>

                        <!-- Status -->
                        <td>

                            <?php if($data['status'] == 'aktif') : ?>

                                <span class="badge bg-success">

                                    Aktif

                                </span>

                            <?php else : ?>

                                <span class="badge bg-danger">

                                    Nonaktif

                                </span>

                            <?php endif; ?>

                        </td>

                        <!-- Aksi -->
                        <td>

                            <!-- Edit -->
                            <a 
                                href="edit_voucher.php?id=<?php echo $data['id_voucher']; ?>"
                                class="btn btn-warning btn-sm"
                            >

                                <i class="bi bi-pencil-square"></i>

                                Edit

                            </a>

                            <!-- Hapus -->
                            <a 
                                href="hapus_voucher.php?id=<?php echo $data['id_voucher']; ?>"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Yakin ingin menghapus voucher ini?')"
                            >

                                <i class="bi bi-trash"></i>

                                Hapus

                            </a>

                        </td>

                    </tr>

                    <?php endwhile; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>


<?php include 'footer.php'; ?>