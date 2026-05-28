<?php
session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/login.php");
    exit;
}

include "../config/koneksi.php";

/*
|--------------------------------------------------------------------------
| AMBIL DATA VOUCHER DARI DATABASE
|--------------------------------------------------------------------------
| Hanya voucher aktif dan belum expired
*/

$queryVoucher = mysqli_query($conn, "
SELECT * FROM voucher
WHERE status='aktif'
AND expired >= CURDATE()
ORDER BY id_voucher DESC
");

include "../template/header.php";
include "../template/navbar_customer.php";
?>

<style>

body{
    background:#f5f5f5;
    font-family:'Segoe UI', sans-serif;
}

/* =========================
   PAGE TITLE
========================= */

.page-title{
    font-size:28px;
    font-weight:700;
}

/* =========================
   VOUCHER CARD
========================= */

.voucher-card{
    background:white;
    border-radius:22px;
    overflow:hidden;
    box-shadow:0 3px 12px rgba(0,0,0,0.06);
    transition:0.2s;
}

.voucher-card:hover{
    transform:translateY(-4px);
}

.voucher-left{
    background:linear-gradient(135deg,#ff4d6d,#ff758f);
    color:white;
    padding:25px;
    height:100%;
}

.voucher-left h3{
    font-weight:700;
}

.voucher-right{
    padding:25px;
}

/* BUTTON */
.claim-btn{
    border-radius:30px;
    padding:10px 24px;
    font-weight:600;
}

/* FLOAT BACK */
.back-btn{
    position:fixed;
    top:90px;
    left:20px;
    width:48px;
    height:48px;
    background:white;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    box-shadow:0 4px 12px rgba(0,0,0,0.12);
    color:#ff4d6d;
    text-decoration:none;
    font-size:22px;
    z-index:999;
    transition:0.2s;
}

.back-btn:hover{
    background:#ff4d6d;
    color:white;
}

/* EMPTY */
.empty-box{
    background:white;
    border-radius:22px;
    padding:60px 30px;
    text-align:center;
    box-shadow:0 3px 12px rgba(0,0,0,0.06);
}

/* MOBILE */
@media(max-width:768px){

    .voucher-left{
        border-radius:0;
    }

}

</style>

<!-- BACK BUTTON -->
<a href="../customer/dashboard.php"
   class="back-btn">

    <i class="bi bi-arrow-left"></i>

</a>

<div class="container py-4">

    <!-- TITLE -->
    <div class="mb-4">

        <div class="page-title">
            🎟 Voucher Saya
        </div>

        <small class="text-muted">
            Klaim voucher dan gunakan saat checkout
        </small>

    </div>

    <?php if(mysqli_num_rows($queryVoucher) > 0) : ?>

        <?php while($v = mysqli_fetch_assoc($queryVoucher)) : ?>

        <div class="voucher-card mb-4">

            <div class="row g-0 align-items-center">

                <!-- LEFT -->
                <div class="col-md-3">

                    <div class="voucher-left text-center">

                        <h3>
                            <?= $v['diskon']; ?>%
                        </h3>

                        <div>
                            OFF
                        </div>

                    </div>

                </div>

                <!-- RIGHT -->
                <div class="col-md-9">

                    <div class="voucher-right">

                        <h5 class="fw-bold text-danger">

                            Voucher <?= $v['kode_voucher']; ?>

                        </h5>

                        <p class="mb-2 text-muted">

                            Diskon <?= $v['diskon']; ?>%
                            minimal belanja
                            Rp <?= number_format($v['minimal_belanja']); ?>

                        </p>

                        <small class="text-muted">

                            Berlaku sampai:
                            <?= date('d M Y', strtotime($v['expired'])); ?>

                        </small>

                        <div class="mt-3">

                            <button 
                                class="btn btn-danger claim-btn"
                                onclick="copyVoucher('<?= $v['kode_voucher']; ?>')"
                            >

                                Klaim Voucher

                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <?php endwhile; ?>

    <?php else : ?>

        <!-- EMPTY -->

        <div class="empty-box">

            <img src="../assets/img/empty.png"
                 width="120"
                 class="mb-3">

            <h5 class="fw-bold">
                Belum Ada Voucher
            </h5>

            <p class="text-muted">

                Saat ini belum ada voucher aktif
                yang tersedia ✨

            </p>

        </div>

    <?php endif; ?>

</div>

<script>

function copyVoucher(kode){

    navigator.clipboard.writeText(kode);

    alert("Kode voucher berhasil disalin: " + kode);

}

</script>

<?php include "../template/footer.php"; ?>