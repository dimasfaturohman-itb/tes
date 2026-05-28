<?php
include 'template/header.php';
include 'template/navbar_pengunjung.php';
include 'config/koneksi.php';

/* =========================
   SEARCH PRODUK
========================= */

$keyword = '';

if (isset($_GET['cari'])) {
    $keyword = trim($_GET['cari']);
}

if (isset($_GET['keyword'])) {
    $keyword = trim($_GET['keyword']);
}

if (!empty($keyword)) {

    $keyword = mysqli_real_escape_string($conn, $keyword);

    $produk = mysqli_query($conn, "
        SELECT * FROM produk
        WHERE nama_produk LIKE '%$keyword%'
        OR deskripsi LIKE '%$keyword%'
        ORDER BY id_produk DESC
    ");

} else {

    $produk = mysqli_query($conn, "
        SELECT * FROM produk
        ORDER BY id_produk DESC
    ");
}
?>

<style>

/* HERO */
.hero-banner{
    background: linear-gradient(135deg,#ff4d6d,#ff758f);
    color:white;
    padding:60px 30px;
    border-radius:24px;
}

.hero-banner h1{
    font-weight:800;
}

/* CATEGORY */
.category-card{
    display:block;
    background:white;
    padding:20px;
    border-radius:16px;
    text-align:center;
    box-shadow:0 2px 10px rgba(0,0,0,0.05);
    transition:0.2s;
    color:black;
}

.category-card:hover{
    transform:translateY(-4px);
    background:#fff0f3;
}

/* PRODUCT */
.product-card{
    border-radius:16px;
    overflow:hidden;
    box-shadow:0 2px 10px rgba(0,0,0,0.05);
    display:flex;
    flex-direction:column;
    height:100%;
    background:white;
}

.price{
    font-weight:700;
    color:#ff4d6d;
}

/* EMPTY */
.empty-box{
    text-align:center;
    padding:50px;
    color:#888;
}

/* RESET BUTTON */
.reset-btn{
    display:inline-block;
    margin-top:10px;
    padding:6px 14px;
    border:1px solid #ff4d6d;
    color:#ff4d6d;
    border-radius:20px;
    text-decoration:none;
    font-size:14px;
}

.reset-btn:hover{
    background:#ff4d6d;
    color:white;
}

</style>

<div class="container py-4">

    <!-- HERO -->
    <div class="hero-banner mb-5 text-center">

        <h1>Belanja Barang Lucu & Estetik ✨</h1>

        <p>Temukan produk favoritmu sekarang</p>

        <a href="#produk" class="btn btn-light rounded-pill px-4">
            Lihat Produk
        </a>

    </div>

    <!-- SEARCH INFO -->
    <?php if(!empty($keyword)) : ?>
        <div class="mb-3">

            <h5>
                Hasil pencarian: <b><?= htmlspecialchars($keyword); ?></b>
            </h5>

            <!-- RESET SEARCH -->
            <a href="index.php"
            class="btn btn-light rounded-pill px-4">

                Lihat Semua Produk

            </a>
        </div>
    <?php endif; ?>

    <!-- PRODUK -->
    <div class="row" id="produk">

        <?php if(mysqli_num_rows($produk) > 0) : ?>

            <?php while($p = mysqli_fetch_assoc($produk)) { ?>

            <div class="col-6 col-md-3 mb-4 d-flex">

                <div class="product-card w-100">

                    <img src="admin/upload/<?= $p['gambar']; ?>"
                         class="img-fluid w-100"
                         style="height:220px; object-fit:cover;">

                    <div class="p-3 d-flex flex-column h-100">

                        <h6><?= $p['nama_produk']; ?></h6>

                        <div class="price mb-2">
                            Rp <?= number_format($p['harga']); ?>
                        </div>

                        <a href="auth/register.php?id=<?= $p['id_produk']; ?>"
                           class="btn btn-outline-danger rounded-pill mt-auto w-100">

                            Detail Produk

                        </a>

                    </div>

                </div>

            </div>

            <?php } ?>

        <?php else : ?>

            <div class="empty-box">
                <h5>Tidak ada produk ditemukan</h5>
                <p>Coba kata kunci lain</p>

                <a href="index.php" class="reset-btn">
                    Kembali ke semua produk
                </a>
            </div>

        <?php endif; ?>

    </div>

</div>

<?php include 'template/footer.php'; ?>