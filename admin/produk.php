<?php
session_start();
if(!isset($_SESSION['admin'])){ header("Location: login.php"); exit; }
include 'koneksi.php';

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

$query = mysqli_query($conn,"
    SELECT produk.*, kategori.nama_kategori
    FROM produk
    JOIN kategori ON produk.id_kategori = kategori.id_kategori
    WHERE produk.nama_produk LIKE '%$search%'
    ORDER BY produk.id_produk DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk Admin - MiniShop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background: #f5f5f5; font-family: 'Segoe UI', sans-serif; }
        
        .navbar { 
            background: linear-gradient(135deg, #ff4d6d, #ff758f); 
            padding: 15px 25px; 
            z-index: 1050; 
        }
        .navbar-brand { font-size: 32px; font-weight: 800; }
        .nav-link { color: white !important; font-weight: 500; }
        
        .dropdown-menu { border-radius: 18px; border: none; box-shadow: 0 5px 20px rgba(0,0,0,0.08); }
        .dropdown-item:hover { background: #fff0f3; color: #ff4d6d; }

        .sticky-topbox {
            position: sticky; top: 20px; z-index: 1000;
            background: white; border-radius: 24px;
            padding: 20px 25px; margin: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            display: flex; justify-content: space-between; align-items: center;
        }

        .fab-dashboard {
            position: fixed; bottom: 30px; right: 30px;
            width: 60px; height: 60px;
            background: #ff4d6d; color: white;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 10px 20px rgba(255, 77, 109, 0.4);
            z-index: 9999; text-decoration: none; transition: 0.3s;
        }
        .fab-dashboard:hover { transform: translateY(-5px); color: white; background: #ff758f; }

        .content { padding: 0 30px 30px 30px; }
        .search-card, .table-box { background: white; border-radius: 24px; padding: 25px; margin-bottom: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .btn-add { background: linear-gradient(135deg, #ff4d6d, #ff758f); color: white; border-radius: 14px; padding: 10px 20px; text-decoration: none; }
        .product-image { width: 75px; height: 75px; border-radius: 16px; object-fit: cover; }
        .variant-item { display: inline-block; background: #f5f5f5; padding: 5px 10px; border-radius: 20px; font-size: 11px; margin: 2px; }
    </style>
</head>
<body>

    <a href="dashboard.php" class="fab-dashboard" title="Kembali ke Dashboard">
        <i class="bi bi-house-door-fill" style="font-size: 24px;"></i>
    </a>

    <nav class="navbar navbar-expand-lg navbar-dark shadow">
        <div class="container-fluid">
            <a class="navbar-brand text-white" href="dashboard.php">MochimoSS</a>
            <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarAdmin"><span class="navbar-toggler-icon"></span></button>
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
                    <li class="nav-item"><a class="nav-link" href="pesanan.php"><i class="bi bi-cart-fill"></i> Pesanan</a></li>
                    <li class="nav-item"><a class="nav-link" href="customer.php"><i class="bi bi-people-fill"></i> Customer</a></li>
                    <li class="nav-item"><a class="nav-link" href="pemasukan.php"><i class="bi bi-cash-stack"></i> Pemasukan</a></li>
                    <li class="nav-item ms-lg-3"><a href="logout.php" class="btn btn-light text-danger rounded-pill px-4"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="sticky-topbox">
        <h3 class="fw-bold mb-0">Data Produk</h3>
        <a href="tambah_produk.php" class="btn-add"><i class="bi bi-plus-circle"></i> Tambah Produk</a>
    </div>

    <div class="content">
        <div class="search-card">
            <form method="GET" class="row">
                <div class="col-md-10"><input type="text" name="search" class="form-control" placeholder="Cari produk..." value="<?= $search; ?>"></div>
                <div class="col-md-2 mt-2 mt-md-0"><button class="btn btn-danger w-100">Cari</button></div>
            </form>
        </div>

        <div class="table-box">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead><tr><th>Produk</th><th>Harga</th><th>Stok</th><th>Varian</th><th>Aksi</th></tr></thead>
                    <tbody>
                        <?php while($data = mysqli_fetch_assoc($query)) : 
                            $id_produk = $data['id_produk'];
                            
                            // Mulai hitung dengan mengambil Stok Utama dari tabel produk
                            $total_stok = intval($data['stok']); 
                            
                            $varian_query = mysqli_query($conn,"SELECT * FROM varian_produk WHERE id_produk='$id_produk'");
                            $varian_html = '';
                            
                            // Tambahkan stok dari setiap varian ke variabel total_stok
                            while($v = mysqli_fetch_assoc($varian_query)){
                                $total_stok += intval($v['stok']);
                                $varian_html .= "<span class='variant-item'>".$v['ukuran']." / ".$v['warna']."</span>";
                            }
                        ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="upload/<?= $data['gambar']; ?>" class="product-image me-3">
                                    <div><div class="fw-bold"><?= $data['nama_produk']; ?></div><small class="text-muted"><?= $data['nama_kategori']; ?></small></div>
                                </div>
                            </td>
                            <td>Rp <?= number_format($data['harga']); ?></td>
                            <td><span class="badge <?= $total_stok > 0 ? 'bg-success' : 'bg-danger' ?>"><?= $total_stok; ?></span></td>
                            <td><?= $varian_html; ?></td>
                            <td>
                                <a href="edit_produk.php?id=<?= $data['id_produk']; ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></a>
                                <a href="hapus_produk.php?id=<?= $data['id_produk']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin?')"><i class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


<?php include 'footer.php'; ?>