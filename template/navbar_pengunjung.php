<nav class="navbar navbar-expand-lg bg-white shadow-sm py-3 sticky-top">

    <div class="container">

        <!-- LOGO -->
        <a class="navbar-brand fw-bold fs-3 text-danger"
            href="index.php">

            Mochimo

        </a>

        <!-- TOGGLE -->
        <button class="navbar-toggler border-0 shadow-none"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarMochimo">

            <i class="bi bi-list fs-2"></i>

        </button>

        <div class="collapse navbar-collapse"
            id="navbarMochimo">

            <!-- SEARCH -->
            <form action="../index.php"
                method="GET"
                class="d-flex mx-auto navbar-search">

                <input type="search"
                    name="keyword"
                    class="form-control rounded-pill border-0 shadow-none me-2"
                    placeholder="Cari produk lucu...">

                <button class="btn btn-danger rounded-pill px-4">

                    Cari

                </button>

            </form>

            <!-- MENU RIGHT -->
            <div class="d-flex align-items-center gap-4 ms-lg-4 mt-3 mt-lg-0">

                <!-- ABOUT -->
                <a class="nav-link fw-semibold"
                    href="template/about.php">

                    About

                </a>

                <!-- CART -->
                <a href="pengunjung/cart.php"
                    class="text-dark position-relative nav-icon">

                    <i class="bi bi-cart3 fs-4"></i>

                    <?php
                    $jumlah_cart = isset($_SESSION['cart'])
                        ? count($_SESSION['cart'])
                        : 0;
                    ?>

                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">

                        <?= $jumlah_cart; ?>

                    </span>

                </a>

                <!-- LOGIN -->
                <a href="auth/login.php"
                    class="btn btn-outline-danger rounded-pill px-4">

                    Login

                </a>

                <!-- REGISTER -->
                <a href="auth/register.php"
                    class="btn btn-danger rounded-pill px-4">

                    Register

                </a>

            </div>

        </div>

    </div>

</nav>

<style>

.navbar{
    border-bottom:1px solid #f5f5f5;
}

.navbar-brand{
    letter-spacing:1px;
}

.nav-link{
    color:#444 !important;
    transition:.3s;
}

.nav-link:hover{
    color:#ff4d6d !important;
}

.navbar-search{
    width:420px;
}

.navbar-search input{
    background:#f8f8f8;
    padding:10px 18px;
}

.nav-icon{
    transition:.3s;
}

.nav-icon:hover{
    color:#ff4d6d !important;
}

@media(max-width:991px){

    .navbar-search{
        width:100%;
        margin:15px 0;
    }

}

</style>