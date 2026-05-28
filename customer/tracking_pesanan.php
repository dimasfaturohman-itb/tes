<?php
session_start();
include "../config/koneksi.php";

if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

// Ambil pesanan yang berstatus dikirim atau selesai
$query = mysqli_query($conn, "
    SELECT * FROM pesanan 
    WHERE id_user = '$id_user' 
    AND status IN ('dikirim', 'selesai') 
    ORDER BY id_pesanan DESC
");

include "../template/header.php";
include "../template/navbar_customer.php";
?>

<style>
    body { background: #f5f5f5; font-family: 'Segoe UI', sans-serif; }
    .tracking-card { background: white; border-radius: 12px; padding: 20px; margin-bottom: 20px; box-shadow: 0 1px 6px rgba(0,0,0,0.08); border: 1px solid #e8e8e8; }
    .bg-shopee-orange { background-color: #ee4d2d !important; color: white; }
    .status-badge { color: #ee4d2d; font-weight: 700; text-transform: uppercase; font-size: 13px; }
    /* Tombol Back Home */
    .back-home-btn {
        position: fixed; bottom: 25px; right: 25px; width: 48px; height: 48px;
        background: #ff4d6d; color: white; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 6px 15px rgba(0,0,0,0.15); text-decoration: none;
        z-index: 999; transition: 0.2s ease;
    }
    .back-home-btn:hover { transform: scale(1.08); background: #ff355d; color: white; }
    
    /* Timeline Styling - Diperbaiki agar lebih rapi */
    .shopee-timeline { position: relative; padding-left: 20px; margin-top: 20px; }
    .shopee-timeline::before { content: ''; position: absolute; left: 5px; top: 5px; bottom: 5px; width: 2px; background: #e8e8e8; }
    .shopee-timeline-item { position: relative; padding-bottom: 20px; }
    .shopee-marker { position: absolute; left: -26px; top: 2px; width: 14px; height: 14px; border-radius: 50%; background: #ccc; border: 3px solid #fff; z-index: 1; }
    .shopee-timeline-item:first-child .shopee-marker { background: #26a69a; box-shadow: 0 0 0 3px rgba(38, 166, 154, 0.2); }
    .shopee-timeline-item:first-child .text-dark { color: #26a69a !important; }
</style>

<div class="container py-4" style="max-width: 700px;">
    <h4 class="fw-bold mb-4">🚚 Lacak Pengiriman</h4>

    <?php if(mysqli_num_rows($query) > 0) : ?>
        <?php while($pesanan = mysqli_fetch_assoc($query)) : 
            $id_pesanan = $pesanan['id_pesanan'];
            $tracking = mysqli_query($conn, "SELECT * FROM tracking WHERE id_pesanan = '$id_pesanan' ORDER BY waktu DESC");
        ?>
        <div class="tracking-card">
            <div class="d-flex justify-content-between align-items-start border-bottom pb-3 mb-3">
                <div>
                    <div class="fw-bold text-dark">No. Pesanan: #<?= $id_pesanan; ?></div>
                    <div class="small mt-1">Status: <span class="status-badge"><?= $pesanan['status']; ?></span></div>
                </div>

                <div>
                    <?php 
                    // Logika tombol: Muncul jika ada tracking 'Tujuan' dan status belum 'selesai'
                    $check_sampai = mysqli_query($conn, "SELECT id_tracking FROM tracking WHERE id_pesanan = '$id_pesanan' AND LOWER(lokasi) = 'tujuan'");
                    
                    if(mysqli_num_rows($check_sampai) > 0 && $pesanan['status'] !== 'selesai') : ?>
                        <form action="proses_konfirmasi_pesanan.php" method="POST">
                            <input type="hidden" name="id_pesanan" value="<?= $id_pesanan; ?>">
                            <button type="submit" name="konfirmasi" class="btn btn-sm bg-shopee-orange rounded-pill px-3 shadow-sm">Konfirmasi Diterima</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

            <div class="shopee-timeline">
                <?php while($t = mysqli_fetch_assoc($tracking)) : ?>
                <div class="shopee-timeline-item">
                    <div class="shopee-marker"></div>
                    <div class="ms-1">
                        <div class="fw-bold text-dark"><?= htmlspecialchars($t['lokasi']); ?></div>
                        <div class="small text-muted"><?= htmlspecialchars($t['keterangan']); ?></div>
                        <div class="small text-secondary" style="font-size: 10px; margin-top: 2px;"><?= date('d M Y, H:i', strtotime($t['waktu'])); ?> WIB</div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
        <?php endwhile; ?>
    <?php else : ?>
        <div class="text-center py-5">
            <i class="bi bi-box-seam display-4 text-muted"></i>
            <h5 class="mt-3">Belum ada paket dikirim.</h5>
        </div>
    <?php endif; ?>
</div>

<a href="dashboard.php" class="back-home-btn">
    <i class="bi bi-house-door-fill"></i>
</a>

<?php include "../template/footer.php"; ?>