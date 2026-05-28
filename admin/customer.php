<?php
include 'koneksi.php';

/*
|--------------------------------------------------------------------------
| AMBIL DATA CUSTOMER (HANYA ROLE CUSTOMER)
|--------------------------------------------------------------------------
*/
$query = mysqli_query($conn, "
    SELECT * FROM users 
    WHERE role='customer'
    ORDER BY id_user DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Data Customer</title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>

body{
    background: #f5f5f5;
    font-family: Arial, sans-serif;
}

/* Navbar */
.navbar{
    background: linear-gradient(135deg, #ff4d6d, #ff758f);
    padding: 12px 20px;
}

.navbar-brand{
    font-size: 28px;
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

/* Table */
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
<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark shadow">

<div class="container-fluid">

    <a class="navbar-brand text-white">Mochimo</a>

    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarAdmin">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarAdmin">

        <ul class="navbar-nav ms-auto align-items-lg-center">

            <!-- Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="dashboard.php">
                    <i class="bi bi-grid-fill"></i> Dashboard
                </a>
            </li>

            <!-- Master Data -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bi bi-folder-fill"></i> Master Data
                </a>

                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="produk.php">
                        <i class="bi bi-box-seam"></i> Produk
                    </a></li>

                    <li><a class="dropdown-item" href="kategori.php">
                        <i class="bi bi-tags-fill"></i> Kategori
                    </a></li>

                    <li><a class="dropdown-item" href="voucher.php">
                        <i class="bi bi-ticket-perforated-fill"></i> Voucher
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="tambah_tracking.php"><i class="bi bi-truck"></i> Update Tracking</a></li>
        

                </ul>
            </li>

            <!-- Pesanan -->
            <li class="nav-item">
                <a class="nav-link" href="pesanan.php">
                    <i class="bi bi-cart-fill"></i> Pesanan
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

            <!-- Customer (ACTIVE) -->
            <li class="nav-item">
                <a class="nav-link active" href="customer.php">
                    <i class="bi bi-people-fill"></i> Customer
                </a>
            </li>

            <!-- Logout -->
            <li class="nav-item ms-lg-3">
                <a href="logout.php" class="btn btn-light text-danger px-4 rounded-pill">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </li>

        </ul>

    </div>

</div>

</nav>

<!-- CONTENT -->
<div class="container">

<div class="container-box">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">Data Customer</h3>
            <p class="text-muted mb-0">Daftar customer yang terdaftar</p>
        </div>

    </div>

    <div class="table-responsive">

        <table class="table table-bordered table-hover align-middle">

            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No HP</th>
                    <th>Alamat</th>
                    <th>Tanggal Daftar</th>
                    <th width="15%">Aksi</th>
                </tr>
            </thead>

            <tbody>

            <?php 
            $no = 1;

            if(mysqli_num_rows($query) > 0) :
                while($data = mysqli_fetch_assoc($query)) :
            ?>

                <tr>

                    <td><?= $no++; ?></td>

                    <td><?= $data['nama']; ?></td>

                    <td><?= $data['email']; ?></td>

                    <td><?= $data['no_hp']; ?></td>

                    <td><?= $data['alamat']; ?></td>

                    <td><?= $data['created_at']; ?></td>

                    <td>

                        <a 
                            href="hapus_customer.php?id=<?= $data['id_user']; ?>"
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('Yakin hapus customer ini?')"
                        >
                            <i class="bi bi-trash"></i> Hapus
                        </a>

                    </td>

                </tr>

            <?php 
                endwhile;
            else :
            ?>

                <tr>
                    <td colspan="7" class="text-center text-muted">
                        Belum ada data customer
                    </td>
                </tr>

            <?php endif; ?>

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