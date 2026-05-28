<?php
// Paksa PHP menampilkan error jika ada masalah query tersembunyi
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include "../config/koneksi.php";

if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/login.php");
    exit;
}

include "../template/header.php";
include "../template/navbar_customer.php";

$id_user = $_SESSION['id_user'];

if (!isset($_GET['id'])) {
    die("<div class='container py-5'><div class='alert alert-danger'>ID pesanan tidak ditemukan</div></div>");
}

$id_pesanan = intval($_GET['id']);

/* =========================================================
   USER
========================================================= */
$qUser = mysqli_query($conn,"SELECT * FROM users WHERE id_user='$id_user'");
$user = mysqli_fetch_assoc($qUser);
$nama_user = $user['nama_lengkap'] ?? $user['nama'] ?? $user['username'] ?? 'User';

/* =========================================================
   AUTO EXPIRED (Sudah Diperbaiki Menggunakan LENGTH)
========================================================= */
mysqli_query($conn,"
UPDATE pesanan
SET status='dibatalkan',
    status_pembayaran='expired'
WHERE id_pesanan='$id_pesanan'
    AND status_pembayaran='pending'
    AND batas_pembayaran IS NOT NULL 
    AND LENGTH(batas_pembayaran) > 0
    AND NOW() > batas_pembayaran
");

/* =========================================================
   AMBIL PESANAN
========================================================= */
$q = mysqli_query($conn,"SELECT * FROM pesanan WHERE id_pesanan='$id_pesanan' AND id_user='$id_user'");
if(!$q) {
    die("<div class='container py-5'><div class='alert alert-danger'>SQL Error Ambil Pesanan: " . mysqli_error($conn) . "</div></div>");
}
$data = mysqli_fetch_assoc($q);

if (!$data) {
    die("<div class='container py-5'><div class='alert alert-warning'>Pesanan tidak ditemukan atau bukan milik Anda.</div></div>");
}

/* =========================================================
   DETAIL PESANAN
========================================================= */
$q_detail = mysqli_query($conn,"
SELECT
    dp.*,
    p.nama_produk,
    p.gambar
FROM detail_pesanan dp
JOIN produk p
ON dp.id_produk = p.id_produk
WHERE dp.id_pesanan='$id_pesanan'
");

if(!$q_detail) {
    die("<div class='container py-5'><div class='alert alert-danger'>SQL Error Detail Produk: " . mysqli_error($conn) . "</div></div>");
}

$items = [];
$total = 0;

while($d = mysqli_fetch_assoc($q_detail)){
    $subtotal = $d['harga'] * $d['qty'];
    $d['subtotal'] = $subtotal;
    $total += $subtotal;
    $items[] = $d;
}

/* =========================================================
   DATA PESANAN
========================================================= */
$metode = strtoupper($data['metode_pembayaran'] ?? 'COD');
$total_bayar = intval($data['total'] ?? 0);
$diskon = intval($data['diskon'] ?? 0);
$alamat = $data['alamat_pengiriman'] ?? '';
$status = $data['status'] ?? 'diproses';
$status_pembayaran = $data['status_pembayaran'] ?? 'pending';
$batas = $data['batas_pembayaran'] ?? null;
$invoice = $data['nomor_pembayaran'] ?? '';
$bank = strtoupper($data['bank_tujuan'] ?? 'BCA');

/* =========================================================
   GENERATE INVOICE & BATAS PEMBAYARAN JIKA KOSONG
========================================================= */
if(empty($invoice) && ($metode == 'BANK' || $metode == 'QRIS')){
    $prefix = 'INV';
    if($metode == 'BANK'){ $prefix = 'TRF'; }
    if($metode == 'QRIS'){ $prefix = 'QRS'; }

    $invoiceBaru = $prefix . '-' . date('Ymd') . '-' . rand(100000,999999);
    $batas_baru = date("Y-m-d H:i:s", strtotime("+1 day"));
    
    mysqli_query($conn,"UPDATE pesanan SET nomor_pembayaran='$invoiceBaru', batas_pembayaran='$batas_baru' WHERE id_pesanan='$id_pesanan'");
    
    $invoice = $invoiceBaru;
    $batas = $batas_baru;
}

/* =========================================================
   REKENING
========================================================= */
$rekening = [
    'BCA'      => '1234567890',
    'BRI'      => '0987654321',
    'BNI'      => '1122334455',
    'MANDIRI'  => '5566778899',
    'BSI'      => '7788990011'
];
$no_rek = $rekening[$bank] ?? '1234567890';

/* =========================================================
   LINK QR
========================================================= */
$link_qr = "QRIS-" . $invoice . "-" . $total_bayar;

/* =========================================================
   COUNTDOWN
========================================================= */
$sisa = 0;
if(!empty($batas) && strtotime($batas)){
    $sisa = strtotime($batas) - time();
    if($sisa < 0){ $sisa = 0; }
}
?>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
body {
    background-color: #fff5f6;
    font-family: 'Poppins', sans-serif;
    color: #4a4a4a;
}
.shopee-status-banner {
    background: linear-gradient(90deg, #ff4d6d, #ff758f);
    color: white;
    padding: 30px 25px;
    border-radius: 16px;
    margin-bottom: 16px;
    box-shadow: 0 6px 15px rgba(255, 77, 109, 0.15);
}
.shopee-card {
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(255, 77, 109, 0.05);
    border: 1px solid #ffe3e7;
    margin-bottom: 16px;
    overflow: hidden;
}
.alamat-header-border {
    height: 4px;
    width: 100%;
    background-image: repeating-linear-gradient(45deg, #ff4d6d, #ff4d6d 33px, transparent 0, transparent 41px, #a2d2ff 0, #a2d2ff 74px, transparent 0, transparent 82px);
}
.alamat-title {
    color: #ff4d6d;
    font-size: 16px;
    font-weight: 600;
}
.item-produk-row {
    padding: 16px 0;
    border-bottom: 1px dashed #ffe3e7;
}
.item-produk-row:last-child {
    border-bottom: none;
}
.summary-row {
    display: flex;
    justify-content: flex-end;
    padding: 6px 20px;
    font-size: 14px;
    color: #666;
}
.summary-value {
    width: 200px;
    text-align: right;
    color: #333;
}
.summary-total-row {
    border-top: 2px dashed #ffccd5;
    padding-top: 15px;
    margin-top: 10px;
}
.payment-instruction-box {
    background-color: #fff0f2;
    border: 1px solid #ffccd5;
    border-radius: 12px;
    padding: 20px;
}
.btn-shopee-primary {
    background: linear-gradient(135deg, #ff4d6d, #ff758f);
    color: white;
    border: none;
    border-radius: 30px;
    padding: 10px 24px;
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(255, 77, 109, 0.2);
    transition: 0.2s;
}
.btn-shopee-primary:hover {
    background: linear-gradient(135deg, #ff2a55, #ff5c7a);
    color: white;
    transform: translateY(-2px);
}
.badge-shopee-method {
    background-color: #ffccd5;
    color: #ff4d6d;
    border: 1px solid #ffb3c1;
    padding: 4px 12px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 12px;
}
.qr-img-shopee {
    width: 220px;
    height: 220px;
    padding: 8px;
    border: 3px solid #ffccd5;
    border-radius: 16px;
    background: #fff;
}

/* Tombol Back Home */
    .back-home-btn {
        position: fixed; bottom: 25px; right: 25px; width: 48px; height: 48px;
        background: #ff4d6d; color: white; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 6px 15px rgba(0,0,0,0.15); text-decoration: none;
        z-index: 999; transition: 0.2s ease;
    }
    .back-home-btn:hover { transform: scale(1.08); background: #ff355d; color: white; }
.copy-icon-btn {
    cursor: pointer;
    color: #ff4d6d;
    font-weight: 600;
    margin-left: 8px;
    text-decoration: none;
}
.text-pink-primary { color: #ff4d6d !important; }
.text-muted-pink { color: #a38f91; }
</style>

<div class="container py-4" style="max-width: 850px;">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="dashboard.php" class="text-decoration-none text-pink-primary small fw-medium">
            <i class="bi bi-chevron-left"></i> Kembali ke Dashboard
        </a>
        <span class="text-muted small">No. Pesanan: <b class="text-pink-primary"><?= htmlspecialchars($invoice ?: 'INV-'.$id_pesanan); ?></b></span>
    </div>

    <div class="shopee-status-banner d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h4 class="fw-bold mb-1">
                <?php 
                if($status_pembayaran == 'expired') { echo '❌ Pesanan Dibatalkan'; }
                elseif($status_pembayaran == 'pending') { echo '⏳ Menunggu Pembayaran'; }
                elseif($status == 'diproses') { echo '📦 Pesanan Sedang Diproses'; }
                elseif($status == 'dikirim') { echo '🚚 Pesanan Sedang Dikirim'; }
                else { echo '✅ Pesanan Selesai'; }
                ?>
            </h4>
            <p class="mb-0 small opacity-90">
                <?php if($status_pembayaran == 'pending'): ?>
                    Yuk manis, segera selesaikan pembayaranmu sebelum waktunya habis!
                <?php elseif($status_pembayaran == 'expired'): ?>
                    Waktu pembayaran telah melewati batas aman sistem.
                <?php else: ?>
                    Terima kasih banyak sudah berbelanja produk gemas di Mochimo Store!
                <?php endif; ?>
            </p>
        </div>
        <div class="fs-1 opacity-85">
            <?php 
            if($status_pembayaran == 'expired') { echo '<i class="bi bi-heartbreak-fill"></i>'; }
            elseif($status_pembayaran == 'pending') { echo '<i class="bi bi-wallet2"></i>'; }
            else { echo '<i class="bi bi-balloon-heart-fill"></i>'; }
            ?>
        </div>
    </div>

    <div class="card shopee-card">
        <div class="alamat-header-border"></div>
        <div class="card-body p-4">
            <div class="alamat-title mb-3">
                <i class="bi bi-geo-alt-fill"></i> Alamat Pengiriman Paket
            </div>
            <div class="row">
                <div class="col-md-3">
                    <strong class="text-dark d-block fs-6"><?= htmlspecialchars($nama_user); ?></strong>
                    <span class="text-muted small">Pelanggan Setia</span>
                </div>
                <div class="col-md-9 text-secondary small pt-1">
                    <?= nl2br(htmlspecialchars($alamat ?: 'Alamat belum diisi dengan lengkap.')); ?>
                </div>
            </div>
        </div>
    </div>

    <?php if($status_pembayaran == 'pending'): ?>
    <div class="card shopee-card">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                <h5 class="fw-bold mb-0 text-pink-primary">Detail Pembayaran</h5>
                <span class="badge-shopee-method"><i class="bi bi-stars"></i> <?= $metode; ?></span>
            </div>

            <?php if($metode == 'BANK'): ?>
            <div class="payment-instruction-box">
                <div class="row align-items-center">
                    <div class="col-md-7 border-end pr-4" style="border-color: #ffccd5 !important;">
                        <div class="mb-3">
                            <span class="text-muted small d-block">Nama Bank</span>
                            <span class="fw-bold text-dark fs-5"><?= $bank; ?> <small class="text-muted-pink fw-normal">(Dicek Otomatis)</small></span>
                        </div>
                        <div class="mb-3">
                            <span class="text-muted small d-block">Nomor Rekening Tujuan</span>
                            <span class="fw-bold text-pink-primary fs-4" id="noRekText"><?= $no_rek; ?></span>
                            <span class="copy-icon-btn small" onclick="navigator.clipboard.writeText('<?= $no_rek; ?>'); alert('Nomor Rekening disalin! 💕');">
                                <i class="bi bi-copy"></i> Salin
                            </span>
                        </div>
                        <div class="mb-1">
                            <span class="text-muted small d-block">Atas Nama Penerima</span>
                            <span class="fw-semibold text-dark">Mochimo Store</span>
                        </div>
                    </div>
                    <div class="col-md-5 text-center mt-3 mt-md-0">
                        <span class="text-muted small d-block mb-1">Sisa Waktu Kamu</span>
                        <div id="countdown_bank" class="text-danger fw-bold fs-4 mb-3">00:00:00</div>
                        <a href="upload_bukti.php?id=<?= $id_pesanan ?>" class="btn btn-shopee-primary w-100">
                            <i class="bi bi-cloud-arrow-up-fill"></i> Upload Bukti Bayar
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if($metode == 'QRIS'): ?>
            <div class="payment-instruction-box text-center">
                <p class="text-muted small mb-3">Pindai kode QRIS di bawah ini menggunakan aplikasi e-wallet andalanmu (Dana, OVO, Gopay, atau QR Mobile Banking)</p>
                <div class="mb-3">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=<?= urlencode($link_qr) ?>" class="qr-img-shopee shadow-sm">
                </div>
                <div class="mb-2 small text-muted">Batas Penggunaan QR Code</div>
                <div id="countdown_qris" class="text-danger fw-bold fs-4 mb-3">00:00:00</div>
                
                <a href="upload_bukti.php?id=<?= $id_pesanan ?>" class="btn btn-shopee-primary px-5">
                    Saya Sudah Transfer ✨
                </a>
            </div>
            <?php endif; ?>

            <?php if($metode == 'COD'): ?>
            <div class="alert border-0 p-3 mb-0" style="background-color: #fff0f2; color: #ff4d6d; border: 1px solid #ffccd5 !important;">
                <i class="bi bi-heart-fill me-2"></i> 
                Pesanan dikirim via <b>Cash On Delivery (COD)</b>. Mohon siapkan dana sebesar <b>Rp<?= number_format($total_bayar) ?></b> untuk diserahkan ke kurir saat paket tiba ya!
            </div>
            <?php endif; ?>

        </div>
    </div>
    <?php endif; ?>

    <div class="card shopee-card">
        <div class="card-body p-4">
            <h6 class="fw-bold mb-3 pb-2 border-bottom text-pink-primary">
                <i class="bi bi-bag-heart-fill me-1"></i> Rincian Produk Dipesan
            </h6>

            <?php foreach($items as $item): ?>
            <div class="item-produk-row d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <img src="../admin/upload/<?= $item['gambar'] ?>" class="rounded-3 border" style="width:68px; height:68px; object-fit:cover; margin-right:15px; border-color: #ffe3e7 !important;" onerror="this.src='../assets/img/no-image.png'">
                    <div>
                        <span class="text-dark fw-medium d-block text-truncate" style="max-width: 380px;">
                            <?= htmlspecialchars($item['nama_produk']) ?>
                        </span>
                        <small class="text-muted d-block">
                            Ukuran/Variasi: <span class="text-pink-primary fw-medium"><?= htmlspecialchars($item['ukuran'] ?: 'Standard'); ?></span>
                        </small>
                        <small class="text-dark opacity-75">
                            Jumlah: <b>x<?= $item['qty'] ?></b>
                        </small>
                    </div>
                </div>
                <div class="text-end">
                    <span class="fw-bold text-dark fs-5">
                        Rp<?= number_format($item['harga']) ?>
                    </span>
                </div>
            </div>
            <?php endforeach; ?>

            <div class="mt-4 pt-2">
                <div class="summary-row">
                    <div>Total Harga Produk:</div>
                    <div class="summary-value">Rp<?= number_format($total) ?></div>
                </div>
                <div class="summary-row">
                    <div>Potongan Diskon:</div>
                    <div class="summary-value text-pink-primary">- Rp<?= number_format($diskon) ?></div>
                </div>
                <div class="summary-row">
                    <div>Ongkos Kirim Ke Rumah:</div>
                    <div class="summary-value text-success fw-medium">Gratis Ongkir ✨</div>
                </div>
                
                <div class="summary-row summary-total-row align-items-center">
                    <div class="fs-6 text-dark fw-medium">Total Seluruhnya:</div>
                    <div class="summary-value text-pink-primary fs-3 fw-bold">
                        Rp<?= number_format($total_bayar) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
let sisa = <?= intval($sisa) ?>;

function countdown(){
    let bank = document.getElementById("countdown_bank");
    let qris = document.getElementById("countdown_qris");

    if(sisa <= 0){
        if(bank){ bank.innerHTML = "Waktu pembayaran habis 😭"; }
        if(qris){ qris.innerHTML = "Waktu pembayaran habis 😭"; }
        return;
    }

    let jam   = Math.floor(sisa / 3600);
    let menit = Math.floor((sisa % 3600) / 60);
    let detik = sisa % 60;

    let waktu = String(jam).padStart(2,'0') + ":" + 
                String(menit).padStart(2,'0') + ":" + 
                String(detik).padStart(2,'0');

    if(bank){ bank.innerHTML = waktu; }
    if(qris){ qris.innerHTML = waktu; }

    sisa--;
}

if(sisa > 0 && (document.getElementById("countdown_bank") || document.getElementById("countdown_qris"))) {
    countdown();
    setInterval(countdown, 1000);
}
</script>

<a href="dashboard.php" class="back-home-btn">
    <i class="bi bi-house-door-fill"></i>
</a>

<?php include "../template/footer.php"; ?>