<?php

session_start();

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

/*
|--------------------------------------------------------------------------
| DATA REAL
|--------------------------------------------------------------------------
*/

// total produk
$produk = mysqli_query($conn, "
    SELECT COUNT(*) as total
    FROM produk
");
$total_produk = mysqli_fetch_assoc($produk)['total'];

// total pesanan
$pesanan = mysqli_query($conn, "
    SELECT COUNT(*) as total
    FROM pesanan
");
$total_pesanan = mysqli_fetch_assoc($pesanan)['total'];

// total customer
$customer = mysqli_query($conn, "
    SELECT COUNT(*) as total
    FROM users
    WHERE role='customer'
");
$total_customer = mysqli_fetch_assoc($customer)['total'];

// total pendapatan
$pendapatan = mysqli_query($conn, "
    SELECT SUM(total_bayar) as total
    FROM pesanan
    WHERE status='selesai'
");
$data_pendapatan = mysqli_fetch_assoc($pendapatan);
$total_pendapatan = $data_pendapatan['total'] ?? 0;

// pesanan terbaru (JOIN dengan tabel users hanya mengambil kolom u.nama yang valid)
$pesanan_terbaru = mysqli_query($conn, "
    SELECT p.*, u.nama 
    FROM pesanan p
    LEFT JOIN users u ON p.id_user = u.id_user
    ORDER BY p.id_pesanan DESC
    LIMIT 5
");

?>

<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body{
            background:#f5f5f5;
            font-family:'Segoe UI',sans-serif;
        }

        /* NAVBAR */
        .navbar{
            background:linear-gradient(135deg,#ff4d6d,#ff758f);
            padding:15px 25px;
        }

        .navbar-brand{
            font-size:32px;
            font-weight:800;
        }

        .nav-link{
            color:white !important;
            margin-right:10px;
            font-weight:500;
            transition:0.3s;
        }

        .nav-link:hover{
            transform:translateY(-2px);
        }

        .dropdown-menu{
            border:none;
            border-radius:18px;
            padding:10px;
            box-shadow:0 5px 20px rgba(0,0,0,0.08);
        }

        .dropdown-item{
            padding:10px 15px;
            border-radius:12px;
            transition:0.2s;
        }

        .dropdown-item:hover{
            background:#fff0f3;
            color:#ff4d6d;
        }

        /* CONTENT */
        .content{
            padding:30px;
        }

        .topbox{
            background:white;
            border-radius:24px;
            padding:25px;
            margin-bottom:30px;
            box-shadow:0 5px 15px rgba(0,0,0,0.05);
        }

        /* CARD */
        .dashboard-card{
            border:none;
            border-radius:24px;
            color:white;
            padding:25px;
            position:relative;
            overflow:hidden;
            transition:0.3s;
            min-height:160px;
        }

        .dashboard-card:hover{
            transform:translateY(-5px);
        }

        .dashboard-card i{
            position:absolute;
            right:20px;
            bottom:10px;
            font-size:55px;
            opacity:0.2;
        }

        .bg1{ background:linear-gradient(135deg,#667eea,#764ba2); }
        .bg2{ background:linear-gradient(135deg,#43cea2,#185a9d); }
        .bg3{ background:linear-gradient(135deg,#f7971e,#ffd200); }
        .bg4{ background:linear-gradient(135deg,#ff416c,#ff4b2b); }

        /* TABLE */
        .table-box{
            background:white;
            border-radius:24px;
            padding:25px;
            margin-top:30px;
            box-shadow:0 5px 15px rgba(0,0,0,0.05);
        }

        .badge-status{
            padding:8px 14px;
            border-radius:30px;
            font-size:12px;
        }
    </style>

</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark shadow">
    <div class="container-fluid">
        <a class="navbar-brand text-white" href="dashboard.php">Mochimo</a>

        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarAdmin">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarAdmin">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link active" href="dashboard.php">
                        <i class="bi bi-grid-fill"></i> Dashboard
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-folder-fill"></i> Master Data
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="produk.php"><i class="bi bi-box-seam"></i> Produk</a></li>
                        <li><a class="dropdown-item" href="kategori.php"><i class="bi bi-tags-fill"></i> Kategori</a></li>
                        <li><a class="dropdown-item" href="voucher.php"><i class="bi bi-ticket-perforated-fill"></i> Voucher</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="tambah_tracking.php"><i class="bi bi-truck"></i> Update Tracking</a></li>
        
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="pesanan.php"><i class="bi bi-cart-fill"></i> Pesanan</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="pemasukan.php"><i class="bi bi-cash-stack"></i> Pemasukan</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="customer.php"><i class="bi bi-people-fill"></i> Customer</a>
                </li>

                <li class="nav-item ms-lg-3">
                    <a href="logout.php" class="btn btn-light text-danger rounded-pill px-4">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="content">

    <div class="topbox d-flex justify-content-between align-items-center">
        <div>
            <h3 class="fw-bold mb-1">Dashboard Admin</h3>
            <p class="text-muted mb-0">Selamat datang kembali 👋</p>
        </div>
        <div>
            <strong class="text-danger">
                <?= htmlspecialchars($_SESSION['admin']); ?>
            </strong>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-3">
            <div class="dashboard-card bg1">
                <h6>Total Produk</h6>
                <h2><?= number_format($total_produk); ?></h2>
                <i class="bi bi-box-seam"></i>
            </div>
        </div>

        <div class="col-md-3">
            <div class="dashboard-card bg2">
                <h6>Total Pesanan</h6>
                <h2><?= number_format($total_pesanan); ?></h2>
                <i class="bi bi-cart-fill"></i>
            </div>
        </div>

        <div class="col-md-3">
            <div class="dashboard-card bg3">
                <h6>Total Customer</h6>
                <h2><?= number_format($total_customer); ?></h2>
                <i class="bi bi-people-fill"></i>
            </div>
        </div>

        <div class="col-md-3">
            <div class="dashboard-card bg4">
                <h6>Total Pendapatan</h6>
                <h4>Rp <?= number_format($total_pendapatan); ?></h4>
                <i class="bi bi-cash-stack"></i>
            </div>
        </div>
    </div>


    <div class="table-box">
        <h5 class="fw-bold mb-4">Pesanan Terbaru</h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-danger">
                    <tr>
                        <th>ID Pesanan</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (mysqli_num_rows($pesanan_terbaru) > 0) : ?>
                    <?php while($data = mysqli_fetch_assoc($pesanan_terbaru)) : ?>
                        <tr>
                            <td>#ORD<?= $data['id_pesanan']; ?></td>
                            <td>
                                <?= htmlspecialchars($data['nama'] ?? 'User #'.$data['id_user']); ?>
                            </td>
                            <td>Rp <?= number_format($data['total_bayar'] ?? 0); ?></td>
                            <td>
                                <?php
                                $status = strtolower(trim($data['status']));
                                $badge = "bg-secondary";

                                if($status == 'diproses'){ $badge = "bg-warning text-dark"; }
                                elseif($status == 'dikirim'){ $badge = "bg-primary"; }
                                elseif($status == 'selesai'){ $badge = "bg-success"; }
                                elseif($status == 'dibatalkan'){ $badge = "bg-danger"; }
                                ?>
                                <span class="badge <?= $badge; ?> badge-status">
                                    <?= ucfirst($status); ?>
                                </span>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted py-3">Belum ada data pesanan terbaru.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php include 'footer.php'; ?>