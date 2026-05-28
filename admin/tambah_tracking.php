<?php
session_start();
if(!isset($_SESSION['admin'])){ header("Location: login.php"); exit; }
include '../config/koneksi.php';

// Definisi Data: 4 Opsi Jalur
$jalur_tracking = [
    'Opsi 1 (Reguler)' => [
        ['Hub Cirebon', 'Paket telah diterima di Hub Cirebon'],
        ['Sortir Bandung', 'Paket dalam proses sortir di Bandung'],
        ['Kurir Transit', 'Paket dalam perjalanan ke gudang terdekat'],
        ['Tujuan', 'Paket sampai di alamat tujuan']
    ],
    'Opsi 2 (Kilat)' => [
        ['Bandara Cirebon', 'Paket diproses kargo bandara'],
        ['Bandara Jakarta', 'Paket transit di bandara'],
        ['Kurir Express', 'Paket segera diantar oleh kurir'],
        ['Tujuan', 'Paket diterima oleh penerima']
    ],
    'Opsi 3 (Ekonomi)' => [
        ['Gudang Induk', 'Paket dikumpulkan di gudang induk'],
        ['Perjalanan Darat', 'Paket dalam perjalanan via darat'],
        ['Hub Tujuan', 'Paket tiba di hub kota tujuan'],
        ['Tujuan', 'Paket diterima pembeli']
    ],
    'Opsi 4 (Lokal)' => [
        ['Gerai A', 'Paket diproses di gerai terdekat'],
        ['Gerai B', 'Paket pindah ke gerai hub'],
        ['Kurir Lokal', 'Kurir sedang menuju lokasi'],
        ['Tujuan', 'Paket sampai di alamat tujuan']
    ]
];

if (isset($_POST['simpan'])) {
    $id_pesanan = mysqli_real_escape_string($conn, $_POST['id_pesanan']);
    $nama_opsi = $_POST['nama_opsi'];
    $index_tahap = (int)$_POST['tahap_lokasi']; 

    $lokasi = $jalur_tracking[$nama_opsi][$index_tahap][0];
    $keterangan = $jalur_tracking[$nama_opsi][$index_tahap][1];

    // Cukup simpan ke tabel tracking. 
    // Status pesanan DIHAPUS agar tidak berubah otomatis menjadi 'selesai' oleh sistem admin.
    if(mysqli_query($conn, "INSERT INTO tracking (id_pesanan, lokasi, keterangan, waktu) VALUES ('$id_pesanan', '$lokasi', '$keterangan', NOW())")) {
        echo "<script>alert('Tracking Berhasil Diperbarui!'); window.location='tambah_tracking.php';</script>";
    } else {
        echo "<script>alert('Gagal Update Tracking!');</script>";
    }
}
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


<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card p-4">
                <h4 class="mb-4 fw-bold"><i class="bi bi-truck"></i> Update Real-Time Tracking</h4>
                
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih Pesanan</label>
                        <select name="id_pesanan" class="form-select" required>
                            <option value="">-- Pilih Pesanan (Status: Dikirim) --</option>
                            <?php
                            $q = mysqli_query($conn, "SELECT * FROM pesanan WHERE status='dikirim'");
                            while($p = mysqli_fetch_assoc($q)) {
                                echo "<option value='".$p['id_pesanan']."'>#ORD".$p['id_pesanan']." | User: ".$p['id_user']."</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih Jalur Pengiriman:</label>
                        <select name="nama_opsi" class="form-select">
                            <?php foreach($jalur_tracking as $nama => $tahap) echo "<option value='$nama'>$nama</option>"; ?>
                        </select>
                    </div>

                    <label class="form-label fw-bold">Pilih Tahap Lokasi:</label>
                    <div class="row g-2 mb-4">
                        <div class="col-3"><input type="radio" class="btn-check" name="tahap_lokasi" value="0" id="t1" required><label class="btn btn-outline-primary w-100" for="t1">Tahap 1</label></div>
                        <div class="col-3"><input type="radio" class="btn-check" name="tahap_lokasi" value="1" id="t2" required><label class="btn btn-outline-primary w-100" for="t2">Tahap 2</label></div>
                        <div class="col-3"><input type="radio" class="btn-check" name="tahap_lokasi" value="2" id="t3" required><label class="btn btn-outline-primary w-100" for="t3">Tahap 3</label></div>
                        <div class="col-3"><input type="radio" class="btn-check" name="tahap_lokasi" value="3" id="t4" required><label class="btn btn-outline-danger w-100" for="t4">Tahap 4</label></div>
                    </div>

                    <button type="submit" name="simpan" class="btn btn-primary w-100 rounded-pill py-2">Update Tracking</button>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>


<?php include 'footer.php'; ?>