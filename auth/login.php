<?php
include '../template/header.php';
?>

<div class="container">

    <div class="row min-vh-100 align-items-center justify-content-center">

        <!-- KIRI (ILUSTRASI TANPA GAMBAR FILE) -->
        <div class="col-lg-6 d-none d-lg-block">
            <div class="p-5 text-center">

                <h1 class="display-4 fw-bold text-danger mb-4">
                    Welcome Back ✨
                </h1>

                <p class="text-secondary fs-5">
                    Belanja produk aesthetic, lucu,
                    dan kebutuhan harian jadi lebih mudah.
                </p>

                <!-- ILUSTRASI LANGSUNG -->
                <div class="mt-5 p-4 rounded-4 shadow-sm"
                     style="background:linear-gradient(135deg,#ff4d6d,#ff758f); color:white;">

                    <i class="bi bi-bag-heart-fill"
                       style="font-size:80px;"></i>

                    <h4 class="mt-3 fw-bold">
                        Mochimo Store
                    </h4>

                    <p class="mb-0">
                        Belanja lebih cepat & mudah 🚀
                    </p>

                </div>

            </div>
        </div>

        <!-- KANAN (FORM LOGIN) -->
        <div class="col-lg-5 col-md-8">

            <div class="card border-0 shadow-lg rounded-5">

                <div class="card-body p-5">

                    <h2 class="fw-bold text-center mb-4">
                        Login Account
                    </h2>

                    <form action="proses_login.php" method="POST">

                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-danger w-100">
                            Login
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

<?php include '../template/footer.php'; ?>