<?php
session_start();
include "../config/koneksi.php";

if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

$query = mysqli_query($conn, "
    SELECT * FROM pesanan 
    WHERE id_user = '$id_user'
    AND status = 'Selesai'
    ORDER BY tanggal_pesan DESC
");

include "../template/header.php";
include "../template/navbar_customer.php";
?>

<style>

body{
    background:#f5f5f5;
    font-family:'Segoe UI',sans-serif;
}

/* ========================= */
/* BACK HOME BUTTON (KONSISTEN SEMUA HALAMAN) */
/* ========================= */
.back-home-btn{
    position: fixed;
    bottom: 25px;
    right: 25px;
    width: 48px;
    height: 48px;
    background: #ff4d6d;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 6px 15px rgba(0,0,0,0.15);
    text-decoration: none;
    z-index: 999;
    transition: 0.2s ease;
}

.back-home-btn:hover{
    transform: scale(1.08);
    background: #ff355d;
}

/* CARD */
.history-card{
    border:none;
    border-radius:24px;
    overflow:hidden;
    background:white;
    box-shadow:0 3px 12px rgba(0,0,0,0.05);
    transition:0.2s;
}

.history-card:hover{
    transform:translateY(-3px);
}

.status-badge{
    padding:8px 16px;
    border-radius:30px;
    font-size:12px;
    font-weight:600;
}

.total-price{
    font-size:24px;
    font-weight:700;
    color:#ff4d6d;
}

.btn-detail{
    border-radius:30px;
    padding:10px 20px;
    font-weight:600;
}

.empty-box{
    background:white;
    border-radius:30px;
    padding:70px 30px;
    text-align:center;
    box-shadow:0 3px 12px rgba(0,0,0,0.05);
}

.empty-icon{
    font-size:90px;
    color:#ddd;
}

.info-label{
    font-size:13px;
    color:#999;
}

</style>

<!-- BACK HOME ICON -->
<a href="dashboard.php" class="back-home-btn">
    <i class="bi bi-house-door-fill"></i>
</a>

<div class="container py-5">

    <div class="mb-4">

        <h3 class="fw-bold">

            <i class="bi bi-clock-history"></i>
            Riwayat Pesanan

        </h3>

        <p class="text-muted mb-0">
            Semua pesanan yang sudah selesai
        </p>

    </div>

    <?php if(mysqli_num_rows($query) == 0) : ?>

        <div class="empty-box">

            <div class="empty-icon">
                <i class="bi bi-bag-x"></i>
            </div>

            <h4 class="fw-bold mt-3">
                Belum Ada Riwayat
            </h4>

            <p class="text-muted">
                Belum ada pesanan yang selesai
            </p>

            <a href="dashboard.php"
               class="btn btn-danger rounded-pill px-4">
                Belanja Sekarang
            </a>

        </div>

    <?php else : ?>

        <div class="row">

            <?php while($p = mysqli_fetch_assoc($query)) : ?>

            <?php
                $tanggal = !empty($p['tanggal_pesan'])
                    ? date('d M Y', strtotime($p['tanggal_pesan']))
                    : '-';

                $nomor = $p['nomor_pesanan'] ?? ('INV-' . $p['id_pesanan']);
            ?>

            <div class="col-12 mb-4">

                <div class="card history-card">

                    <div class="card-body p-4">

                        <!-- HEADER -->
                        <div class="d-flex justify-content-between align-items-center mb-4">

                            <div>

                                <span class="badge bg-light text-dark border me-2">
                                    <?= $tanggal ?>
                                </span>

                                <span class="fw-bold text-danger small">
                                    <?= $nomor ?>
                                </span>

                            </div>

                            <span class="badge bg-success status-badge">
                                Selesai
                            </span>

                        </div>

                        <!-- CONTENT -->
                        <div class="row align-items-center">

                            <div class="col-md-8">

                                <div class="info-label mb-1">
                                    Total Pembayaran
                                </div>

                                <div class="total-price mb-2">
                                    Rp <?= number_format($p['total'] ?? 0) ?>
                                </div>

                                <div class="text-muted small">
                                    <i class="bi bi-credit-card"></i>
                                    <?= $p['metode_pembayaran'] ?? '-' ?>
                                </div>

                            </div>

                            <div class="col-md-4 text-md-end mt-4 mt-md-0">

                                <a href="detail_tracking.php?id=<?= $p['id_pesanan'] ?>"
                                   class="btn btn-outline-dark btn-detail">

                                    <i class="bi bi-eye"></i>
                                    Lihat Detail

                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <?php endwhile; ?>

        </div>

    <?php endif; ?>

</div>

<?php include "../template/footer.php"; ?>