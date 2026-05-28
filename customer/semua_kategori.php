<?php
session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/koneksi.php';
include '../template/header.php';
include '../template/navbar_customer.php';

$kategori_list = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
?>

<div class="container py-4">

    <h4 class="mb-4">📂 Semua Kategori</h4>

    <div class="row text-center">

        <?php while($k = mysqli_fetch_assoc($kategori_list)) { ?>

        <div class="col-6 col-md-3 mb-3">
            <a href="dashboard.php?kategori=<?= urlencode($k['nama_kategori']); ?>" style="text-decoration:none;">

                <div class="p-3 bg-white rounded-4 shadow-sm" style="transition: 0.3s;">
                    <img src="../admin/upload/<?= $k['ikon']; ?>" 
                         alt="<?= $k['nama_kategori']; ?>" 
                         style="width: 60px; height: 60px; object-fit: cover; border-radius: 10px;">
                    
                    <div class="mt-2 fw-semibold text-dark">
                        <?= $k['nama_kategori']; ?>
                    </div>
                </div>

            </a>
        </div>

        <?php } ?>

    </div>

</div>

<?php include '../template/footer.php'; ?>