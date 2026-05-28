<?php
session_start();

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

/* =========================
   TOTAL PEMASUKAN
========================= */

$q_total = mysqli_query($conn,"
SELECT SUM(total_bayar) AS total
FROM pesanan
WHERE status_pembayaran='dibayar'
");

$data_total = mysqli_fetch_assoc($q_total);

$total_pemasukan =
$data_total['total'] ?? 0;

/* =========================
   PEMASUKAN HARI INI
========================= */

$q_hari = mysqli_query($conn,"
SELECT SUM(total_bayar) AS total
FROM pesanan
WHERE
status_pembayaran='dibayar'
AND DATE(tanggal_pesan)=CURDATE()
");

$data_hari = mysqli_fetch_assoc($q_hari);

$pemasukan_hari =
$data_hari['total'] ?? 0;

/* =========================
   PEMASUKAN BULAN INI
========================= */

$q_bulan = mysqli_query($conn,"
SELECT SUM(total_bayar) AS total
FROM pesanan
WHERE
status_pembayaran='dibayar'
AND MONTH(tanggal_pesan)=MONTH(CURDATE())
AND YEAR(tanggal_pesan)=YEAR(CURDATE())
");

$data_bulan = mysqli_fetch_assoc($q_bulan);

$pemasukan_bulan =
$data_bulan['total'] ?? 0;

/* =========================
   RIWAYAT TRANSAKSI
========================= */

$q_transaksi = mysqli_query($conn,"
SELECT *
FROM pesanan
WHERE
status_pembayaran IN (
    'menunggu_verifikasi',
    'dibayar'
)
ORDER BY id_pesanan DESC
");

?>

<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Data Pemasukan</title>

    <!-- Bootstrap -->

    <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet">

    <!-- Bootstrap Icons -->

    <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

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
            font-size:30px;
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
        }

        .dropdown-item:hover{
            background:#fff0f3;
            color:#ff4d6d;
        }

        /* CARD */

        .card-box{
            border:none;
            border-radius:22px;
            padding:25px;
            color:white;
            box-shadow:0 5px 15px rgba(0,0,0,0.08);
        }

        .bg-total{
            background:linear-gradient(135deg,#ff4d6d,#ff758f);
        }

        .bg-hari{
            background:linear-gradient(135deg,#36cfc9,#5cdbd3);
        }

        .bg-bulan{
            background:linear-gradient(135deg,#722ed1,#9254de);
        }

        /* TABLE */

        .table-card{
            background:white;
            border-radius:22px;
            padding:25px;
            box-shadow:0 5px 15px rgba(0,0,0,0.05);
        }

        .img-bukti{
            width:80px;
            height:80px;
            object-fit:cover;
            border-radius:12px;
            border:1px solid #eee;
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

        <!-- LOGO -->

        <a class="navbar-brand text-white"
           href="dashboard.php">

            Mochimo

        </a>

        <!-- TOGGLER -->

        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarAdmin">

            <span class="navbar-toggler-icon"></span>

        </button>

        <!-- MENU -->

        <div class="collapse navbar-collapse"
             id="navbarAdmin">

            <ul class="navbar-nav ms-auto align-items-lg-center">

                <!-- Dashboard -->

                <li class="nav-item">

                    <a class="nav-link"
                       href="dashboard.php">

                        <i class="bi bi-grid-fill"></i>
                        Dashboard

                    </a>

                </li>

                <!-- Master Data -->

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

                        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="tambah_tracking.php"><i class="bi bi-truck"></i> Update Tracking</a></li>
        

                    </ul>

                </li>

                <!-- Pesanan -->

                <li class="nav-item">

                    <a class="nav-link"
                       href="pesanan.php">

                        <i class="bi bi-cart-fill"></i>
                        Pesanan

                    </a>

                </li>

                <!-- Pemasukan -->

                <li class="nav-item">

                    <a class="nav-link active"
                       href="pemasukan.php">

                        <i class="bi bi-cash-stack"></i>
                        Pemasukan

                    </a>

                </li>

                <!-- Customer -->

                <li class="nav-item">

                    <a class="nav-link"
                       href="customer.php">

                        <i class="bi bi-people-fill"></i>
                        Customer

                    </a>

                </li>

                <!-- Logout -->

                <li class="nav-item ms-lg-3">

                    <a href="logout.php"
                       class="btn btn-light text-danger px-4 rounded-pill">

                        <i class="bi bi-box-arrow-right"></i>
                        Logout

                    </a>

                </li>

            </ul>

        </div>

    </div>

</nav>

<!-- CONTENT -->

<div class="container py-4">

    <div class="mb-4">

        <h3 class="fw-bold">
            💰 Data Pemasukan
        </h3>

        <p class="text-muted">
            Statistik pemasukan toko
        </p>

    </div>

    <!-- CARD -->

    <div class="row g-4 mb-4">

        <!-- TOTAL -->

        <div class="col-md-4">

            <div class="card-box bg-total">

                <h6>Total Pemasukan</h6>

                <h3 class="fw-bold mt-3">

                    Rp<?= number_format($total_pemasukan) ?>

                </h3>

            </div>

        </div>

        <!-- HARI INI -->

        <div class="col-md-4">

            <div class="card-box bg-hari">

                <h6>Pemasukan Hari Ini</h6>

                <h3 class="fw-bold mt-3">

                    Rp<?= number_format($pemasukan_hari) ?>

                </h3>

            </div>

        </div>

        <!-- BULAN INI -->

        <div class="col-md-4">

            <div class="card-box bg-bulan">

                <h6>Pemasukan Bulan Ini</h6>

                <h3 class="fw-bold mt-3">

                    Rp<?= number_format($pemasukan_bulan) ?>

                </h3>

            </div>

        </div>

    </div>

    <!-- TABLE -->

    <div class="table-card">

        <h5 class="fw-bold mb-4">
            Riwayat Transaksi
        </h5>

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead class="table-danger">

                    <tr>

                        <th>No</th>
                        <th>Invoice</th>
                        <th>Bukti</th>
                        <th>Tanggal</th>
                        <th>Metode</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>

                    </tr>

                </thead>

                <tbody>

                    <?php
                    $no = 1;

                    while($trx = mysqli_fetch_assoc($q_transaksi)):
                    ?>

                    <tr>

                        <td>

                            <?= $no++ ?>

                        </td>

                        <td>

                            <?= $trx['nomor_pembayaran']
                            ?: '#INV'.$trx['id_pesanan'] ?>

                        </td>

                        <!-- BUKTI -->

                        <td>

                            <?php if(!empty($trx['bukti_pembayaran'])): ?>

                                <a
                                href="../upload/bukti/<?= $trx['bukti_pembayaran'] ?>"
                                target="_blank"
                                >

                                    <img
                                    src="../upload/bukti/<?= $trx['bukti_pembayaran'] ?>"
                                    class="img-bukti">

                                </a>

                            <?php else: ?>

                                <span class="text-muted">
                                    Belum Upload
                                </span>

                            <?php endif; ?>

                        </td>

                        <!-- TANGGAL -->

                        <td>

                            <?= date(
                                'd M Y H:i',
                                strtotime($trx['tanggal_pesan'])
                            ) ?>

                        </td>

                        <!-- METODE -->

                        <td>

                            <?= strtoupper($trx['metode_pembayaran']) ?>

                        </td>

                        <!-- TOTAL -->

                        <td class="fw-bold text-success">

                            Rp<?= number_format(
                                $trx['total_bayar']
                            ) ?>

                        </td>

                        <!-- STATUS -->

                        <td>

                            <?php if(
                                $trx['status_pembayaran']
                                == 'dibayar'
                            ): ?>

                                <span class="badge bg-success">
                                    Dibayar
                                </span>

                            <?php else: ?>

                                <span class="badge bg-warning text-dark">
                                    Menunggu Verifikasi
                                </span>

                            <?php endif; ?>

                        </td>

                        <!-- AKSI -->

                        <td>

                            <?php if(
                                $trx['status_pembayaran']
                                == 'menunggu_verifikasi'
                            ): ?>

                                <a
                                href="verifikasi_pembayaran.php?id=<?= $trx['id_pesanan'] ?>"
                                class="btn btn-success btn-sm"
                                onclick="return confirm('Verifikasi pembayaran ini?')"
                                >

                                    <i class="bi bi-check-circle"></i>
                                    Verifikasi

                                </a>

                            <?php else: ?>

                                <button
                                class="btn btn-secondary btn-sm"
                                disabled
                                >

                                    Sudah Valid

                                </button>

                            <?php endif; ?>

                        </td>

                    </tr>

                    <?php endwhile; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<!-- JS -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>


<?php include 'footer.php'; ?>