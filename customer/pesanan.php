<?php
session_start();
include "../config/koneksi.php";

if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

/* =========================================================
   QUERY PESANAN
========================================================= */

$query = mysqli_query($conn, "
    SELECT *
    FROM pesanan
    WHERE id_user = '$id_user'
    AND status IN (
        'diproses',
        'menunggu pembayaran'
    )
    ORDER BY id_pesanan DESC
");

include "../template/header.php";
include "../template/navbar_customer.php";
?>

<style>
body{
    background:#f5f5f5;
    font-family:'Segoe UI',sans-serif;
}

.order-card{
    background:white;
    border-radius:24px;
    padding:25px;
    margin-bottom:25px;
    box-shadow:0 3px 10px rgba(0,0,0,0.05);
}

.order-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}

.status-badge{
    background:#f39c12;
    color:white;
    padding:10px 18px;
    border-radius:30px;
    font-size:13px;
    font-weight:600;
}

.product-item{
    display:flex;
    align-items:center;
    gap:18px;
    padding:18px 0;
    border-bottom:1px solid #f1f1f1;
}

.product-img{
    width:75px;
    height:75px;
    border-radius:18px;
    overflow:hidden;
    border:1px solid #eee;
    flex-shrink:0;
}

.product-img img{
    width:100%;
    height:100%;
    object-fit:cover;
}

.total-box{
    background:#fafafa;
    border-radius:18px;
    padding:20px;
    margin-top:20px;
}

.btn-detail{
    background:#ff4d6d;
    color:white;
    border:none;
    border-radius:12px;
    padding:12px 20px;
    font-weight:600;
    text-decoration:none;
}

.btn-detail:hover{
    background:#ff355d;
    color:white;
}

.empty-box{
    background:white;
    padding:70px 30px;
    border-radius:25px;
    text-align:center;
    box-shadow:0 3px 10px rgba(0,0,0,0.05);
}

.empty-icon{
    font-size:90px;
    color:#ffccd5;
}

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
</style>

<div class="container py-4">

    <!-- BACK HOME -->
    <a href="dashboard.php" class="back-home-btn">
        <i class="bi bi-house-door-fill"></i>
    </a>

    <div class="mb-4">

        <h3 class="fw-bold">
            📦 Pesanan Saya
        </h3>

        <p class="text-muted mb-0">
            Semua pesanan yang sedang diproses muncul di sini
        </p>

    </div>

    <?php if(mysqli_num_rows($query) > 0) : ?>

        <?php while($pesanan = mysqli_fetch_assoc($query)) : ?>

        <?php

        $id_pesanan = $pesanan['id_pesanan'];

        /* =====================================================
           DETAIL PESANAN
        ===================================================== */

        $detail = mysqli_query($conn, "
            SELECT
                dp.*,
                p.nama_produk,
                p.harga,
                p.gambar
            FROM detail_pesanan dp
            JOIN produk p
            ON dp.id_produk = p.id_produk
            WHERE dp.id_pesanan = '$id_pesanan'
        ");

        ?>

        <div class="order-card">

            <!-- HEADER -->
            <div class="order-header">

                <div>

                    <h5 class="fw-bold mb-1">

                        Pesanan
                        #<?= $pesanan['nomor_pesanan']
                            ?? $pesanan['id_pesanan']; ?>

                    </h5>

                    <small class="text-muted">

                        <?= date(
                            'd M Y H:i',
                            strtotime($pesanan['tanggal_pesan'])
                        ); ?>

                    </small>

                </div>

                <span class="status-badge">

                    <?= ucfirst($pesanan['status']); ?>

                </span>

            </div>

            <?php

            $total_belanja = 0;

            while($item = mysqli_fetch_assoc($detail)) :

                $harga =
                intval($item['harga']);

                $qty =
                intval($item['qty']);

                $subtotal =
                $harga * $qty;

                $total_belanja += $subtotal;

            ?>

            <!-- PRODUK -->
            <div class="product-item">

                <div class="product-img">

                    <img
                        src="../admin/upload/<?= $item['gambar']; ?>"
                        onerror="this.src='../assets/img/no-image.png'"
                    >

                </div>

                <div class="flex-grow-1">

                    <div class="fw-bold fs-5">

                        <?= htmlspecialchars(
                            $item['nama_produk']
                        ); ?>

                    </div>

                    <small class="text-muted">

                        <?= $qty; ?>

                        x

                        Rp <?= number_format($harga); ?>

                        <?php if(!empty($item['warna'])) : ?>

                            • Warna:
                            <?= htmlspecialchars($item['warna']); ?>

                        <?php endif; ?>

                        <?php if(!empty($item['ukuran'])) : ?>

                            • Ukuran:
                            <?= htmlspecialchars($item['ukuran']); ?>

                        <?php endif; ?>

                    </small>

                </div>

                <div class="fw-bold text-danger fs-5">

                    Rp <?= number_format($subtotal); ?>

                </div>

            </div>

            <?php endwhile; ?>

            <!-- TOTAL -->
            <div class="total-box">

                <div class="d-flex justify-content-between mb-2">

                    <span class="text-muted">
                        Total Belanja
                    </span>

                    <span class="fw-bold">

                        Rp <?= number_format($total_belanja); ?>

                    </span>

                </div>

                <div class="d-flex justify-content-between mb-2">

                    <span class="text-muted">
                        Diskon Terpakai
                    </span>

                    <span class="text-success fw-bold">

                        - Rp <?= number_format(
                            $pesanan['diskon'] ?? 0
                        ); ?>

                    </span>

                </div>

                <hr>

                <div class="d-flex justify-content-between">

                    <span class="fw-bold">
                        Total Bayar
                    </span>

                    <span class="fw-bold text-danger fs-5">

                        Rp <?= number_format(
                            $pesanan['total_bayar']
                            ?? ($total_belanja - ($pesanan['diskon'] ?? 0))
                        ); ?>

                    </span>

                </div>

            </div>

            <!-- BUTTON -->
            <div class="mt-4 text-end">

                <a
                    href="detail_pesanan.php?id=<?= $pesanan['id_pesanan']; ?>"
                    class="btn-detail"
                >

                    Detail Pesanan

                </a>

            </div>

        </div>

        <?php endwhile; ?>

    <?php else : ?>

        <div class="empty-box">

            <div class="empty-icon">
                <i class="bi bi-box-seam"></i>
            </div>

            <h4 class="fw-bold mt-3">
                Belum Ada Pesanan
            </h4>

            <p class="text-muted">
                Pesanan yang diproses akan muncul di sini
            </p>

            <a
                href="dashboard.php"
                class="btn btn-danger rounded-pill px-4"
            >

                Belanja Sekarang

            </a>

        </div>

    <?php endif; ?>

</div>

<?php include "../template/footer.php"; ?>