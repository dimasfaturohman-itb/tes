<!-- auth/register.php -->

<?php
include '../template/header.php';
?>

<div class="container">

    <div class="row min-vh-100 align-items-center justify-content-center">

        <!-- LEFT -->
        <div class="col-lg-6 d-none d-lg-block">

            <div class="p-5">

                <h1 class="display-4 fw-bold text-danger mb-4">
                    Join With Us 💖
                </h1>

                <p class="text-secondary fs-5">
                    Daftar sekarang dan nikmati pengalaman
                    belanja yang lebih mudah.
                </p>

                <img src="../assets/img/mochimo.png"
                    class="img-fluid mt-4">

            </div>

        </div>

        <!-- FORM -->
        <div class="col-lg-5 col-md-8">

            <div class="card border-0 shadow-lg rounded-5">

                <div class="card-body p-5">

                    <div class="text-center mb-4">

                        <h2 class="fw-bold">
                            Create Account
                        </h2>

                        <p class="text-muted">
                            Daftar akun customer baru
                        </p>

                    </div>

                    <form action="proses_register.php"
                        method="POST">

                        <!-- NAMA -->
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Nama Lengkap
                            </label>

                            <input type="text"
                                name="nama"
                                class="form-control py-3"
                                placeholder="Masukkan nama">

                        </div>

                        <!-- EMAIL -->
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Email
                            </label>

                            <input type="email"
                                name="email"
                                class="form-control py-3"
                                placeholder="Masukkan email">

                        </div>

                        <!-- NO HP -->
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                No HP
                            </label>

                            <input type="text"
                                name="no_hp"
                                class="form-control py-3"
                                placeholder="Masukkan no hp">

                        </div>

                        <!-- PASSWORD -->
                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Password
                            </label>

                            <input type="password"
                                name="password"
                                class="form-control py-3"
                                placeholder="Masukkan password">

                        </div>

                        <!-- BUTTON -->
                        <button class="btn btn-danger w-100 py-3 rounded-pill fw-semibold">

                            Register

                        </button>

                    </form>

                    <!-- LOGIN -->
                    <div class="text-center mt-4">

                        <p class="text-muted">

                            Sudah punya akun?

                            <a href="login.php"
                                class="text-danger fw-semibold text-decoration-none">

                                Login

                            </a>

                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<?php
include '../template/footer.php';
?>