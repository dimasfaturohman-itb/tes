<?php
include 'header.php';
include 'navbar_pengunjung.php';
?>

<!-- ABOUT SECTION -->

<section class="about-section py-5">

    <div class="container">

        <div class="row align-items-center g-5">

            <!-- IMAGE -->
            <div class="col-lg-6">

                <img src="../assets/img/mochimo.png"
                    class="img-fluid rounded-4 shadow"
                    alt="About Mochimo">

            </div>

            <!-- CONTENT -->
            <div class="col-lg-6">

                <span class="badge bg-danger-subtle text-danger mb-3 px-3 py-2">

                    Tentang Mochimo

                </span>

                <h2 class="fw-bold mb-4">

                    Belanja Produk Lucu, Aesthetic,
                    dan Kebutuhan Harian Jadi Lebih Mudah ✨

                </h2>

                <p class="text-secondary lh-lg">

                    Mochimo merupakan website ecommerce berbasis web
                    yang menyediakan berbagai produk lifestyle dan
                    kebutuhan harian dengan konsep modern seperti
                    Miniso dan Naiso.

                </p>

                <p class="text-secondary lh-lg">

                    Website ini dirancang untuk memberikan pengalaman
                    belanja online yang nyaman, cepat, dan mudah digunakan
                    oleh semua kalangan pengguna melalui tampilan yang
                    responsive, clean, dan user friendly.

                </p>

                <!-- FEATURES -->
                <div class="row mt-4 g-3">

                    <div class="col-md-6">

                        <div class="about-feature">

                            <i class="bi bi-grid-fill"></i>

                            <div>

                                <h6 class="fw-bold mb-1">
                                    Banyak Kategori
                                </h6>

                                <small class="text-muted">
                                    Fashion, beauty, alat tulis,
                                    aksesoris, dan lainnya.
                                </small>

                            </div>

                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="about-feature">

                            <i class="bi bi-truck"></i>

                            <div>

                                <h6 class="fw-bold mb-1">
                                    Tracking Pesanan
                                </h6>

                                <small class="text-muted">
                                    Pantau status pengiriman
                                    produk secara real-time.
                                </small>

                            </div>

                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="about-feature">

                            <i class="bi bi-shield-check"></i>

                            <div>

                                <h6 class="fw-bold mb-1">
                                    Pembayaran Aman
                                </h6>

                                <small class="text-muted">
                                    Mendukung COD, Transfer Bank,
                                    dan QRIS.
                                </small>

                            </div>

                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="about-feature">

                            <i class="bi bi-phone"></i>

                            <div>

                                <h6 class="fw-bold mb-1">
                                    Responsive Design
                                </h6>

                                <small class="text-muted">
                                    Nyaman digunakan di desktop
                                    maupun smartphone.
                                </small>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<style>

.about-section{
    background: #fff;
}

.about-feature{
    display: flex;
    gap: 15px;
    background: #fff5f5;
    padding: 18px;
    border-radius: 18px;
    align-items: start;
    height: 100%;
}

.about-feature i{
    font-size: 24px;
    color: #dc3545;
}

</style>

<?php
include 'footer.php';
?>