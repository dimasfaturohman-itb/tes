<?php
session_start();
include "../config/koneksi.php";

if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

// ==============================================================================
// KONDISI 2: JIKA USER MENGKLIK TOMBOL "BAYAR SEKARANG" (PROSES INSERT DATABASE)
// ==============================================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['proses_checkout_final'])) {
    
    $alamat_final = mysqli_real_escape_string($conn, $_POST['alamat_final']);
    $metode_pembayaran = mysqli_real_escape_string($conn, $_POST['metode_pembayaran']);
    $bank_tujuan = mysqli_real_escape_string($conn, $_POST['nama_bank']);
    $total_bayar = intval($_POST['total_akhir']);
    $diskon = intval($_POST['diskon']);
    $id_cart_array = $_POST['checkout']; // Array ID Cart

    // Tentukan Batas Pembayaran & Invoice untuk Non-COD
    $batas_pembayaran = "NULL"; 
    $nomor_pembayaran = "";

    if ($metode_pembayaran == 'BANK' || $metode_pembayaran == 'QRIS') {
        $prefix = ($metode_pembayaran == 'BANK') ? 'TRF' : 'QRS';
        $nomor_pembayaran = $prefix . '-' . date('Ymd') . '-' . rand(100000, 999999);
        $batas_pembayaran = "'" . date("Y-m-d H:i:s", strtotime("+1 day")) . "'"; 
    }

    // ==============================================================================
    // PERBAIKAN DI SINI: Mengubah 'total_bayar' menjadi 'total' & 'tanggal_pesanan' menjadi 'tanggal_pesan'
    // ==============================================================================
    $query_pesanan = "INSERT INTO pesanan 
        (id_user, nomor_pembayaran, metode_pembayaran, bank_tujuan, total, diskon, alamat_pengiriman, status, status_pembayaran, batas_pembayaran, tanggal_pesan) 
        VALUES 
        ('$id_user', '$nomor_pembayaran', '$metode_pembayaran', '$bank_tujuan', '$total_bayar', '$diskon', '$alamat_final', 'diproses', 'pending', $batas_pembayaran, NOW())";

    $insert_pesanan = mysqli_query($conn, $query_pesanan);

    if (!$insert_pesanan) {
        die("Gagal membuat pesanan utama: " . mysqli_error($conn));
    }

    $id_pesanan_baru = mysqli_insert_id($conn);

    // Pindahkan Barang dari Cart ke Detail Pesanan
    foreach ($id_cart_array as $id_cart) {
        $id_cart = intval($id_cart);
        
        $cart_item_q = mysqli_query($conn, "SELECT * FROM cart WHERE id_cart='$id_cart' AND id_user='$id_user'");
        $cart_item = mysqli_fetch_assoc($cart_item_q);
        
        if ($cart_item) {
            $id_produk = $cart_item['id_produk'];
            $qty = $cart_item['qty'];
            $ukuran = mysqli_real_escape_string($conn, $cart_item['ukuran'] ?? 'Standard');
            
            $produk_q = mysqli_query($conn, "SELECT harga FROM produk WHERE id_produk='$id_produk'");
            $prod = mysqli_fetch_assoc($produk_q);
            $harga = $prod['harga'];
            
            mysqli_query($conn, "INSERT INTO detail_pesanan (id_pesanan, id_produk, qty, harga, ukuran) VALUES ('$id_pesanan_baru', '$id_produk', '$qty', '$harga', '$ukuran')");
            mysqli_query($conn, "DELETE FROM cart WHERE id_cart='$id_cart' AND id_user='$id_user'");
        }
    }

    echo "<script>
        alert('Pesanan kamu berhasil dibuat! ✨ Silakan cek detailnya.');
        window.location.href = 'detail_pesanan.php?id=$id_pesanan_baru';
    </script>";
    exit;
}

// ==============================================================================
// KONDISI 1: JIKA USER BARU DATANG DARI CART.PHP (TAMPILKAN HALAMAN FORM)
// ==============================================================================
if (!isset($_POST['checkout']) || empty($_POST['checkout'])) {
    echo "<script>alert('Pilih produk dulu di keranjang!'); window.location='cart.php';</script>";
    exit;
}

$id_cart = $_POST['checkout'];
$ids = implode(",", array_map('intval', $id_cart));

$user_query = mysqli_query($conn, "SELECT * FROM users WHERE id_user='$id_user'");
$u = mysqli_fetch_assoc($user_query);

$daftar_alamat = [
    $u['alamat'],
    "Jl. Kwangya No. 127, Perumahan Neo City",
    "Kantor Pusat Mochimare, Sudirman Kav. 22"
];

$cart_res = mysqli_query($conn, "
    SELECT c.*, p.nama_produk, p.harga, p.gambar 
    FROM cart c 
    JOIN produk p ON c.id_produk = p.id_produk 
    WHERE c.id_cart IN ($ids) 
    AND c.id_user='$id_user'
");

$total = 0;
$items = [];

while($c = mysqli_fetch_assoc($cart_res)){
    $total += ($c['harga'] * $c['qty']);
    $items[] = $c;
}

$query_voucher = mysqli_query($conn, "
    SELECT * FROM voucher
    WHERE status='aktif'
    AND expired >= CURDATE()
    ORDER BY id_voucher DESC
");

include "../template/header.php";
include "../template/navbar_customer.php";
?>

<style>
    body { background:#f4f7f6; color: #444; font-family: 'Inter', sans-serif; }
    .checkout-card { background:white; border-radius:12px; padding:20px; box-shadow:0 2px 10px rgba(0,0,0,0.03); margin-bottom: 15px; border: 1px solid #eee; }
    .section-title { font-size:1rem; font-weight:700; margin-bottom:15px; display:flex; align-items:center; gap:8px; border-bottom: 1px solid #f8f8f8; padding-bottom: 10px; }
    .section-title i { color: #ff4d6d; }
    .product-item { display: flex; align-items: center; gap: 12px; padding: 10px 0; border-bottom: 1px solid #f9f9f9; }
    .product-img { width: 50px; height: 50px; object-fit: cover; border-radius: 8px; border: 1px solid #f0f0f0; }
    .product-info h6 { font-size: 0.9rem; margin-bottom: 2px; }
    .option-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
    .bank-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 8px; margin-top: 12px; }
    .selectable-box { border: 1.5px solid #eee; padding: 10px 5px; border-radius: 8px; text-align: center; cursor: pointer; transition: all 0.2s ease; background: #fff; font-size: 0.8rem; display: flex; flex-direction: column; align-items: center; justify-content: center; height: 65px; font-weight: 600; }
    .selectable-box i { font-size: 1.2rem; margin-bottom: 4px; }
    .selectable-box:hover { border-color: #ff4d6d; background: #fffafa; }
    .selectable-box.active, .selectable-box.selected { border-color: #ff4d6d; background: #fff5f7; color: #ff4d6d; }
    .total-price { font-size: 22px; font-weight: 800; color: #ff4d6d; }
    .btn-checkout { background: #ff4d6d; color: white; border: none; width: 100%; padding: 12px; border-radius: 10px; font-weight: 700; font-size: 1rem; }
    .form-select, .form-control { font-size: 0.85rem; border-radius: 8px; }
</style>

<div class="container py-4">
    <form action="" method="POST" id="formCheckout">
        <input type="hidden" name="proses_checkout_final" value="1">

        <div class="row g-3">
            <div class="col-lg-8">
                <div class="checkout-card">
                    <div class="section-title"><i class="bi bi-geo-alt-fill"></i> Alamat Pengiriman</div>
                    <select name="alamat_final" class="form-select border-1">
                        <?php foreach($daftar_alamat as $almt) : ?>
                            <option value="<?= htmlspecialchars($almt) ?>"><?= htmlspecialchars($almt) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="checkout-card">
                    <div class="section-title"><i class="bi bi-bag-heart-fill"></i> Rincian Produk</div>
                    <?php foreach($items as $item) : ?>
                    <div class="product-item">
                        <img src="../admin/upload/<?= $item['gambar'] ?>" class="product-img">
                        <div class="flex-grow-1 product-info">
                            <h6 class="mb-0 fw-bold"><?= htmlspecialchars($item['nama_produk']) ?></h6>
                            <small class="text-muted"><?= $item['qty'] ?> barang</small>
                        </div>
                        <span class="fw-bold small">Rp <?= number_format($item['harga'] * $item['qty']) ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="checkout-card">
                    <div class="section-title"><i class="bi bi-credit-card-fill"></i> Metode Pembayaran</div>
                    <div class="option-grid">
                        <div class="selectable-box active" onclick="selectPay('cod', this)"><i class="bi bi-truck"></i><span>COD</span></div>
                        <div class="selectable-box" onclick="selectPay('bank', this)"><i class="bi bi-bank"></i><span>Transfer</span></div>
                        <div class="selectable-box" onclick="selectPay('qris', this)"><i class="bi bi-qr-code-scan"></i><span>QRIS</span></div>
                    </div>
                    
                    <div id="info-cod" class="mt-3 p-3 rounded" style="background-color: #fff0f2; color: #ff4d6d; border: 1px solid #ffccd5; font-size: 0.85rem;">
                        <i class="bi bi-heart-fill"></i> Siapkan uang sebesar <b>Rp <span id="total-cod"><?= number_format($total) ?></span></b> untuk diserahkan ke kurir saat paket tiba ya! 💕
                    </div>

                    <div id="panel-bank" style="display:none;">
                        <hr class="my-3 opacity-0">
                        <div class="bank-grid">
                            <div class="selectable-box" style="height:45px;" onclick="selectBank('BCA', this)">BCA</div>
                            <div class="selectable-box" style="height:45px;" onclick="selectBank('MANDIRI', this)">MDR</div>
                            <div class="selectable-box" style="height:45px;" onclick="selectBank('BNI', this)">BNI</div>
                            <div class="selectable-box" style="height:45px;" onclick="selectBank('BRI', this)">BRI</div>
                            <div class="selectable-box" style="height:45px;" onclick="selectBank('BSI', this)">BSI</div>
                        </div>
                        <input type="hidden" name="nama_bank" id="inputBank" value="">
                    </div>
                    <input type="hidden" name="metode_pembayaran" id="inputMetode" value="COD">
                </div>
            </div>

            <div class="col-lg-4">
                <div class="checkout-card sticky-top" style="top:20px;">
                    <div class="section-title">Voucher & Ringkasan</div>
                    <div class="voucher-box mb-3 p-2 border rounded">
                        <select class="form-select form-select-sm mb-2" id="vSelect">
                            <option value="">Gunakan Voucher</option>
                            <?php while($v = mysqli_fetch_assoc($query_voucher)) : ?>
                                <option value="<?= $v['diskon'] ?>" data-min="<?= $v['minimal_belanja'] ?>">
                                    Diskon <?= $v['diskon'] ?>% - Min. Belanja Rp <?= number_format($v['minimal_belanja']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Total Harga (Subtotal)</span>
                        <span>Rp <?= number_format($total) ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 text-danger small" id="rowDisc" style="display: none !important;">
                        <span class="text-muted">Diskon Voucher</span>
                        <span>- Rp <span id="valDisc">0</span></span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-bold">Total Bayar</span>
                        <div class="total-price" id="totalDisplay">Rp <?= number_format($total) ?></div>
                    </div>
                    <input type="hidden" name="total_akhir" id="inputTotal" value="<?= $total ?>">
                    <input type="hidden" name="diskon" id="inputDiskon" value="0">
                    
                    <?php foreach($id_cart as $id) : ?>
                        <input type="hidden" name="checkout[]" value="<?= $id ?>">
                    <?php endforeach; ?>
                    
                    <button type="submit" class="btn-checkout">Bayar Sekarang</button>
                    <p class="text-center text-muted mt-2 mb-0" style="font-size:0.7rem;">Dengan membayar, Anda menyetujui S&K Mochimare.</p>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
const subtotal = <?= $total ?>;

function selectPay(type, el) {
    document.querySelectorAll('.option-grid .selectable-box').forEach(i => i.classList.remove('active'));
    el.classList.add('active');
    const pBank = document.getElementById('panel-bank');
    const infoCod = document.getElementById('info-cod');
    pBank.style.display = (type === 'bank') ? 'block' : 'none';
    infoCod.style.display = (type === 'cod') ? 'block' : 'none';
    const totalAkhir = document.getElementById('inputTotal').value;
    document.getElementById('total-cod').innerText = parseInt(totalAkhir).toLocaleString('id-ID');
    if(type !== 'bank'){
        document.getElementById('inputBank').value = "";
        document.querySelectorAll('.bank-grid .selectable-box').forEach(b => b.classList.remove('selected'));
    }
    document.getElementById('inputMetode').value = type.toUpperCase();
}

function selectBank(name, el){
    document.querySelectorAll('.bank-grid .selectable-box').forEach(b => b.classList.remove('selected'));
    el.classList.add('selected');
    document.getElementById('inputBank').value = name;
}

document.getElementById('vSelect').addEventListener('change', function(){
    const selected = this.options[this.selectedIndex];
    let persen = parseInt(selected.value) || 0;
    let minimal = parseInt(selected.dataset.min) || 0;
    let diskon = 0;
    if(persen > 0){
        if(subtotal < minimal){
            alert("Minimal belanja Rp " + minimal.toLocaleString('id-ID'));
            this.value = "";
            document.getElementById('rowDisc').style.setProperty('display', 'none', 'important');
            updateUI(subtotal, 0);
            return;
        }
        diskon = subtotal * persen / 100;
    }
    if(diskon > 0){
        document.getElementById('rowDisc').style.setProperty('display', 'flex', 'important');
        document.getElementById('valDisc').innerText = Math.round(diskon).toLocaleString('id-ID');
        updateUI(subtotal - Math.round(diskon), Math.round(diskon));
    } else {
        document.getElementById('rowDisc').style.setProperty('display', 'none', 'important');
        updateUI(subtotal, 0);
    }
});

function updateUI(totalAkhir, diskon) {
    document.getElementById('inputDiskon').value = diskon;
    document.getElementById('inputTotal').value = totalAkhir;
    document.getElementById('totalDisplay').innerText = "Rp " + totalAkhir.toLocaleString('id-ID');
    document.getElementById('total-cod').innerText = totalAkhir.toLocaleString('id-ID');
}

document.getElementById('formCheckout').onsubmit = function(){
    const metode = document.getElementById('inputMetode').value;
    const bank = document.getElementById('inputBank').value;
    if(metode === 'BANK' && bank === ""){ alert("Silakan pilih bank tujuan transfer!"); return false; }
    return true;
};
</script>

<?php include "../template/footer.php"; ?>