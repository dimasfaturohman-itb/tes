<?php
include 'koneksi.php';

/*
|--------------------------------------------------------------------------
| UPDATE STATUS & AUTO-INSERT TRACKING (SINKRON DENGAN DETAIL_PESANAN)
|--------------------------------------------------------------------------
*/
if(isset($_GET['status']) && isset($_GET['id'])){

    $id        = mysqli_real_escape_string($conn, $_GET['id']);
    $status    = mysqli_real_escape_string($conn, $_GET['status']);
    $status_db = strtolower(trim($status)); // Disamakan menjadi lowercase agar konsisten (diproses, dikirim, selesai)

    // 1. Update status utama di tabel pesanan
    mysqli_query($conn, "UPDATE pesanan SET status='$status_db' WHERE id_pesanan='$id'");

    // 2. Buat keterangan log otomatis berdasarkan aksi tombol cepat
    $ket = "";
    if($status_db == 'diproses'){
        $ket = "Pesanan telah dikonfirmasi oleh admin dan sedang dalam proses pengemasan.";
    } elseif($status_db == 'dikirim'){
        $ket = "Paket telah diserahkan ke kurir internal/ekspedisi dan sedang dalam perjalanan ke alamat tujuan.";
    } elseif($status_db == 'selesai'){
        $ket = "Pesanan telah sukses diterima oleh pelanggan. Transaksi selesai.";
    }

    // 3. Masukkan ke tabel histori yang benar: tracking_pesanan dengan kolom 'status' dan 'keterangan'
    if($ket != ""){
        mysqli_query($conn, "INSERT INTO tracking_pesanan (id_pesanan, status, keterangan) VALUES ('$id', '$status_db', '$ket')");
    }

    // Menggunakan JavaScript redirect agar aman di berbagai environment server
    echo "<script>window.location.href='pesanan.php';</script>";
    exit;
}

/*
|--------------------------------------------------------------------------
| AMBIL DATA PESANAN (JOIN DETAIL PRODUK - SHOPEE STYLE QUERY)
|--------------------------------------------------------------------------
*/
$query = mysqli_query($conn, "
    SELECT p.*, u.nama AS nama_pembeli,
           SUM(dp.qty) as qty_barang,
           SUM(dp.subtotal) as total_bayar_riil,
           GROUP_CONCAT(CONCAT(pr.nama_produk, ' (x', dp.qty, ')') SEPARATOR '|||') as list_produk,
           GROUP_CONCAT(pr.gambar SEPARATOR '|||') as list_foto
    FROM pesanan p
    LEFT JOIN users u ON p.id_user = u.id_user
    LEFT JOIN detail_pesanan dp ON p.id_pesanan = dp.id_pesanan
    LEFT JOIN produk pr ON dp.id_produk = pr.id_produk
    GROUP BY p.id_pesanan
    ORDER BY p.id_pesanan DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pesanan - Shopee Admin Style</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            background: #f6f6f6;
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, Roboto, sans-serif;
        }

        /* Navbar Custom */
        .navbar {
            background: linear-gradient(135deg, #ff4d6d, #ff758f);
            padding: 15px 25px;
        }

        .navbar-brand {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: 0.5px;
        }

        .nav-link {
            color: white !important;
            margin-right: 10px;
            transition: 0.3s;
            font-weight: 500;
        }

        .nav-link:hover {
            transform: translateY(-2px);
        }

        /* Shopee Card Layout Style */
        .order-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.04);
            border: 1px solid #eef0f2;
            margin-bottom: 20px;
            transition: 0.2s ease-in-out;
        }

        .order-card:hover {
            box-shadow: 0 4px 18px rgba(0,0,0,0.08);
        }

        .order-header {
            padding: 16px 20px;
            border-bottom: 1px solid #f8f9fa;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .order-body {
            padding: 20px;
        }

        .order-footer {
            padding: 16px 20px;
            background: #fafafa;
            border-top: 1px solid #f4f5f6;
            border-bottom-left-radius: 12px;
            border-bottom-right-radius: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        /* Thumbnail Image List */
        .product-img-wrapper {
            position: relative;
            width: 70px;
            height: 70px;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #eef1f4;
            background: #fafafa;
        }

        .product-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Badge Status */
        .shopee-badge {
            padding: 6px 14px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
        }

        .price-text {
            color: #ee4d2d;
            font-size: 20px;
            font-weight: 700;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand text-white" href="#">Mochimo</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAdmin">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarAdmin">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item"><a class="nav-link" href="dashboard.php"><i class="bi bi-grid-fill"></i> Dashboard</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"><i class="bi bi-folder-fill"></i> Master Data</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="produk.php"><i class="bi bi-box-seam"></i> Produk</a></li>
                        <li><a class="dropdown-item" href="kategori.php"><i class="bi bi-tags-fill"></i> Kategori</a></li>
                        <li><a class="dropdown-item" href="voucher.php"><i class="bi bi-ticket-perforated-fill"></i> Voucher</a></li>
                        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="tambah_tracking.php"><i class="bi bi-truck"></i> Update Tracking</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link active" href="pesanan.php"><i class="bi bi-cart-fill"></i> Pesanan</a></li>
                <li class="nav-item"><a class="nav-link" href="pemasukan.php"><i class="bi bi-cash-stack"></i> Pemasukan</a></li>
                <li class="nav-item"><a class="nav-link" href="customer.php"><i class="bi bi-people-fill"></i> Customer</a></li>
                <li class="nav-item ms-lg-3"><a href="logout.php" class="btn btn-light text-danger rounded-pill px-4"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold m-0 text-dark">Data Transaksi Pesanan</h3>
            <p class="text-muted small mb-0">Kelola operasional pengiriman, update status pelacakan logistik produk pembeli.</p>
        </div>
        <div class="bg-white px-3 py-2 border rounded shadow-sm text-end">
            <small class="text-muted d-block">Total Pesanan Masuk</small>
            <strong class="fs-5 text-dark"><?= mysqli_num_rows($query); ?> Nota</strong>
        </div>
    </div>

    <?php if (mysqli_num_rows($query) > 0) : ?>
        <?php while($data = mysqli_fetch_assoc($query)) : 
            $total_tampil = ($data['total_bayar_riil'] > 0) ? $data['total_bayar_riil'] : $data['total_bayar'];
            $status_raw = strtolower(trim($data['status']));

            // Setup Badge Class Shopee Style
            $badge_class = "bg-light text-secondary border";
            if($status_raw == 'selesai') $badge_class = "bg-success text-white";
            if($status_raw == 'diproses') $badge_class = "bg-warning text-dark";
            if($status_raw == 'dikirim') $badge_class = "bg-primary text-white";
            if($status_raw == 'dibatalkan') $badge_class = "bg-danger text-white";

            // Ekstrak List Data Produk Gabungan dari Query SQL
            $arr_produk = !empty($data['list_produk']) ? explode('|||', $data['list_produk']) : [];
            $arr_foto   = !empty($data['list_foto']) ? explode('|||', $data['list_foto']) : [];
        ?>
            <div class="order-card">
                <div class="order-header">
                    <div>
                        <span class="fw-bold text-dark me-2">#ORD<?= $data['id_pesanan']; ?></span>
                        <span class="text-muted small border-start ps-2"><?= date('d M Y, H:i', strtotime($data['tanggal_pesan'])); ?></span>
                    </div>
                    <div>
                        <span class="badge shopee-badge <?= $badge_class; ?>">
                            <i class="bi bi-info-circle-fill me-1"></i> <?= htmlspecialchars($data['status']); ?>
                        </span>
                    </div>
                </div>

                <div class="order-body">
                    <div class="row align-items-center g-3">
                        <div class="col-md-7 border-end">
                            <h6 class="text-muted small fw-bold text-uppercase mb-3"><i class="bi bi-box-seam me-1"></i> Rincian Item Barang</h6>
                            
                            <div class="d-flex flex-column gap-3">
                                <?php if(!empty($arr_produk)) : ?>
                                    <?php for($i = 0; $i < count($arr_produk); $i++) : 
                                        $nama_barang = $arr_produk[$i] ?? 'Produk Terhapus';
                                        $foto_barang = !empty($arr_foto[$i]) ? $arr_foto[$i] : 'no-image.png';
                                    ?>
                                        <div class="d-flex align-items-center">
                                            <div class="product-img-wrapper flex-shrink-0 me-3">
                                                <img src="upload/<?= htmlspecialchars($foto_barang); ?>" class="product-img" onerror="this.src='../assets/img/no-image.png';">
                                            </div>
                                            <div>
                                                <p class="mb-0 fw-bold text-dark fs-6"><?= htmlspecialchars($nama_barang); ?></p>
                                                <small class="text-muted italic">Kategori Logistik Utama</small>
                                            </div>
                                        </div>
                                    <?php endfor; ?>
                                <?php else: ?>
                                    <p class="text-muted small mb-0">Item produk tidak tercatat di detail_pesanan.</p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-md-5 ps-md-4">
                            <h6 class="text-muted small fw-bold text-uppercase mb-2"><i class="bi bi-geo-alt-fill me-1"></i> Informasi Pengiriman</h6>
                            <p class="mb-1 text-dark fw-bold"><i class="bi bi-person-circle me-1"></i> <?= htmlspecialchars($data['nama_pembeli'] ?? 'User ID: #'.$data['id_user']); ?></p>
                            <p class="mb-2 text-muted small"><i class="bi bi-truck me-1"></i> Metode: <span class="badge bg-light text-dark border"><?= htmlspecialchars($data['metode_pembayaran']); ?></span></p>
                            <div class="p-2 border rounded bg-light">
                                <small class="text-dark d-block style-alamat text-truncate" style="max-width: 100%;"><?= htmlspecialchars($data['alamat_pengiriman']); ?></small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="order-footer">
                    <div>
                        <span class="text-muted small">Total Pesanan (<?= $data['qty_barang'] ?? 0; ?> Item):</span>
                        <div class="price-text">Rp <?= number_format($total_tampil ?? 0); ?></div>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="detail_pesanan.php?id=<?= $data['id_pesanan']; ?>" class="btn btn-outline-secondary btn-sm px-3 rounded">
                            <i class="bi bi-eye-fill me-1"></i> Lihat Detail & Input Resi
                        </a>

                        <?php if($status_raw == 'pending' || $status_raw == '') : ?>
                            <a href="?id=<?= $data['id_pesanan']; ?>&status=diproses" class="btn btn-warning btn-sm px-3 rounded fw-bold text-dark">
                                <i class="bi bi-gear-fill me-1"></i> Konfirmasi & Proses
                            </a>
                        <?php elseif($status_raw == 'diproses') : ?>
                            <a href="?id=<?= $data['id_pesanan']; ?>&status=dikirim" class="btn btn-primary btn-sm px-3 rounded fw-bold">
                                <i class="bi bi-truck me-1"></i> Serahkan Ke Kurir
                            </a>
                        <?php elseif($status_raw == 'dikirim') : ?>
                            <a href="?id=<?= $data['id_pesanan']; ?>&status=selesai" class="btn btn-success btn-sm px-3 rounded fw-bold">
                                <i class="bi bi-check-circle-fill me-1"></i> Selesaikan Pesanan
                            </a>
                        <?php else : ?>
                            <button class="btn btn-light btn-sm text-muted rounded border" disabled>
                                <i class="bi bi-lock-fill me-1"></i> Transaksi Selesai
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else : ?>
        <div class="text-center bg-white p-5 rounded border shadow-sm">
            <i class="bi bi-cart-x text-muted" style="font-size: 60px;"></i>
            <h5 class="fw-bold mt-3 text-dark">Belum Ada Riwayat Pesanan Masuk</h5>
            <p class="text-muted small">Semua transaksi belanja dari customer aplikasi toko Anda akan terdata lengkap di halaman ini.</p>
        </div>
    <?php endif; ?>
</div>

<a href="dashboard.php" class="btn btn-primary rounded-circle shadow-lg" 
   style="position: fixed; bottom: 30px; right: 30px; width: 60px; height: 60px; 
          display: flex; align-items: center; justify-content: center; 
          background-color: #ff4d6d; border: none; z-index: 9999;">
    <i class="bi bi-house-door-fill" style="font-size: 24px; color: white;"></i>
</a>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


<?php include 'footer.php'; ?>