<style>
    /* Footer Style */
    .footer-custom {
        background: #ffffff;
        padding: 40px 0;
        margin-top: 50px;
        border-top: 1px solid #eee;
        color: #777;
    }
    .footer-brand {
        font-size: 24px;
        font-weight: 800;
        background: linear-gradient(135deg, #ff4d6d, #ff758f);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 10px;
        display: block;
    }
    .footer-links a {
        color: #666;
        text-decoration: none;
        transition: 0.3s;
        display: block;
        margin-bottom: 8px;
    }
    .footer-links a:hover {
        color: #ff4d6d;
        padding-left: 5px;
    }
    .social-icons a {
        color: #ff4d6d;
        font-size: 20px;
        margin-right: 15px;
        transition: 0.3s;
    }
    .social-icons a:hover {
        transform: translateY(-3px);
    }
</style>

<footer class="footer-custom">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <a href="#" class="footer-brand">Mochimo Admin</a>
                <p class="small">Sistem manajemen toko yang dirancang untuk kemudahan operasional dan efisiensi bisnis Anda.</p>
            </div>

            <div class="col-md-4 mb-4">
                <h6 class="fw-bold text-dark mb-3">Navigasi Cepat</h6>
                <div class="footer-links">
                    <a href="dashboard.php">Dashboard Utama</a>
                    <a href="produk.php">Kelola Produk</a>
                    <a href="pesanan.php">Laporan Pesanan</a>
                    <a href="customer.php">Data Customer</a>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <h6 class="fw-bold text-dark mb-3">Kontak Admin</h6>
                <p class="small mb-2"><i class="bi bi-envelope-fill me-2"></i> support@mochimo.com</p>
                <div class="social-icons">
                    <a href="#"><i class="bi bi-instagram"></i></a>
                    <a href="#"><i class="bi bi-whatsapp"></i></a>
                    <a href="#"><i class="bi bi-github"></i></a>
                </div>
            </div>
        </div>
        
        <div class="text-center pt-4 mt-4 border-top">
            <p class="small mb-0">&copy; <?= date('Y'); ?> Mochimo Store. All Rights Reserved.</p>
        </div>
    </div>
</footer>