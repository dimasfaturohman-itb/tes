<?php
session_start();
include "../config/koneksi.php";

if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

// 1. Query Cart disesuaikan dengan Varian Produk (Mendukung Produk Single maupun Produk Varian)
$cart = mysqli_query($conn, "
    SELECT 
        c.*, 
        p.nama_produk, 
        p.gambar AS gambar_produk_utama,
        p.harga AS harga_produk_utama,
        v.id_varian,
        v.warna,
        v.ukuran,
        v.harga_varian,
        v.gambar_varian,
        v.stok AS stok_varian,
        p.stok AS stok_produk_utama
    FROM cart c
    JOIN produk p ON c.id_produk = p.id_produk
    LEFT JOIN varian_produk v ON c.id_varian = v.id_varian
    WHERE c.id_user='$id_user'
");

include "../template/header.php";
include "../template/navbar_customer.php";
?>

<style>
body { background:#f5f5f5; font-family:'Segoe UI', sans-serif; }

/* CART STYLING */
.cart-box { background:#fff; border-radius:18px; padding:20px; box-shadow:0 2px 10px rgba(0,0,0,0.05); }

.cart-header {
    display:grid;
    grid-template-columns: 50px 2fr 1fr 1fr 1fr 100px;
    padding:15px 0;
    border-bottom:1px solid #eee;
    font-weight:700;
    color:#666;
    font-size: 14px;
}

.cart-row {
    display:grid;
    grid-template-columns: 50px 2fr 1fr 1fr 1fr 100px;
    align-items:center;
    padding:18px 0;
    border-bottom:1px solid #f1f1f1;
}

.product { display:flex; align-items:center; gap:15px; }
.product img { width:75px; height:75px; object-fit:cover; border-radius:12px; border:1px solid #eee; }
.name { font-size:15px; font-weight:600; margin-bottom: 2px; }
.variant-desc { font-size: 12px; color: #888; background: #f8f9fa; padding: 2px 8px; border-radius: 4px; display: inline-block; }
.price { color:#ff4d6d; font-weight:700; }

/* QTY */
.qty-box { display:flex; align-items:center; gap:8px; }
.qty-btn { width:30px; height:30px; border:none; border-radius:8px; background:#f1f1f1; font-weight:bold; cursor:pointer; transition: 0.2s; }
.qty-btn:hover { background: #e2e2e2; }
.qty-input { width:40px; text-align:center; border:none; background:transparent; font-weight:600; }

.delete-link { color:#ff4d6d; text-decoration:none; font-weight:600; }

/* SUMMARY */
.summary {
    margin-top:25px;
    background:#fff;
    padding:20px 30px;
    border-radius:18px;
    box-shadow:0 2px 10px rgba(0,0,0,0.05);
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.total-price { font-size:24px; font-weight:700; color:#ff4d6d; }

.checkout-btn {
    background:#ff4d6d;
    border:none;
    border-radius:12px;
    padding:12px 40px;
    color:white;
    font-weight:600;
    cursor:pointer;
    transition: 0.2s;
}
.checkout-btn:hover { background: #ff355d; }

.back-home-btn{
    position: fixed; bottom: 25px; right: 25px; width: 48px; height: 48px;
    background: #ff4d6d; color: white; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 6px 15px rgba(0,0,0,0.15); text-decoration: none; z-index: 999; transition: 0.2s ease;
}
.back-home-btn:hover{ transform: scale(1.08); background: #ff355d; }

@media(max-width:768px){
    .cart-header { display:none; }
    .cart-row { grid-template-columns:1fr; gap:10px; }
    .summary { flex-direction:column; gap:15px; text-align:center; }
}
</style>

<div class="container py-4">

    <a href="dashboard.php" class="back-home-btn">
        <i class="bi bi-house-door-fill"></i>
    </a>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0">🛒 Keranjang Saya</h3>
        <span class="badge bg-white text-dark border px-3 py-2 rounded-pill shadow-sm">
            <?= mysqli_num_rows($cart); ?> Item
        </span>
    </div>

    <?php if(mysqli_num_rows($cart) > 0) : ?>
    <form action="checkout.php" method="POST" id="formCart">

        <div class="cart-box">
            <div class="cart-header">
                <div><input type="checkbox" id="checkAll"></div>
                <div>Produk</div>
                <div>Harga</div>
                <div>Kuantitas</div>
                <div>Subtotal</div>
                <div>Aksi</div>
            </div>

            <?php while($c = mysqli_fetch_assoc($cart)) : 
                // Tentukan harga: Pakai harga varian jika ada, jika tidak pakai harga produk utama
                $hargaFix = (!empty($c['id_varian']) && (int)$c['harga_varian'] > 0) ? (int)$c['harga_varian'] : (int)$c['harga_produk_utama'];
                
                // Tentukan gambar: Pakai gambar varian jika ada, jika tidak pakai gambar produk utama
                $gambarFix = (!empty($c['gambar_varian'])) ? $c['gambar_varian'] : $c['gambar_produk_utama'];
                
                // Tentukan batas stok maksimal untuk interaksi tombol +
                $stokMaksimal = (!empty($c['id_varian'])) ? (int)$c['stok_varian'] : (int)$c['stok_produk_utama'];
                
                $subtotal = $hargaFix * (int)$c['qty'];
            ?>
            <div class="cart-row" data-id="<?= $c['id_cart']; ?>">
                <div>
                    <input type="checkbox" class="check-item" name="checkout[]" value="<?= $c['id_cart']; ?>" data-price="<?= $hargaFix; ?>">
                </div>

                <div class="product">
                    <img src="../admin/upload/<?= htmlspecialchars($gambarFix); ?>" onerror="this.src='../assets/img/no-image.png';">
                    <div>
                        <div class="name"><?= htmlspecialchars($c['nama_produk']); ?></div>
                        
                        <?php if(!empty($c['id_varian'])): 
                            $vWarna = trim($c['warna'] ?? '');
                            $vUkuran = trim($c['ukuran'] ?? '');
                            $textVarian = ($vWarna != '' && $vUkuran != '') ? "$vWarna, $vUkuran" : ($vWarna != '' ? $vWarna : $vUkuran);
                        ?>
                            <div class="variant-desc"><i class="bi bi-info-circle"></i> Varian: <?= htmlspecialchars($textVarian); ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="price">Rp <?= number_format($hargaFix, 0, ',', '.'); ?></div>

                <div class="qty-box">
                    <button type="button" class="qty-btn" onclick="ubahQty('<?= $c['id_cart']; ?>', -1)">-</button>
                    <input type="text" class="qty-input" id="qty_<?= $c['id_cart']; ?>" value="<?= $c['qty']; ?>" data-price="<?= $hargaFix; ?>" data-max-stok="<?= $stokMaksimal; ?>" readonly>
                    <button type="button" class="qty-btn" onclick="ubahQty('<?= $c['id_cart']; ?>', 1)">+</button>
                </div>

                <div class="price subtotal" id="subtotal_<?= $c['id_cart']; ?>">Rp <?= number_format($subtotal, 0, ',', '.'); ?></div>

                <div>
                    <a href="hapus_cart.php?id=<?= $c['id_cart']; ?>" class="delete-link" onclick="return confirm('Hapus item ini dari keranjang?')">Hapus</a>
                </div>
            </div>
            <?php endwhile; ?>
        </div>

        <div class="summary">
            <div>
                <div class="text-muted small fw-semibold">Total Terpilih</div>
                <div class="total-price" id="grandTotal">Rp 0</div>
            </div>
            <button type="submit" class="checkout-btn">Lanjut Checkout</button>
        </div>

    </form>
    <?php else : ?>
        <div class="text-center py-5 bg-white rounded-4 shadow-sm mt-3">
            <i class="bi bi-cart-x" style="font-size:4rem; color: #ff4d6d;"></i>
            <h5 class="mt-3 fw-bold">Keranjang kosong</h5>
            <a href="dashboard.php" class="btn btn-danger px-4 rounded-pill" style="background:#ff4d6d; border:none;">Belanja</a>
        </div>
    <?php endif; ?>

</div>

<script type="text/javascript">
const checkAll = document.getElementById('checkAll');
const checkItems = document.querySelectorAll('.check-item');
const grandTotalText = document.getElementById('grandTotal');
const formCart = document.getElementById('formCart');

// 1. Fungsi Utama Mengubah Kuantitas (+ atau -)
function ubahQty(idCart, perubahan) {
    let inputQty = document.getElementById('qty_' + idCart);
    let qtySekarang = parseInt(inputQty.value);
    let hargaItem = parseInt(inputQty.getAttribute('data-price'));
    let maxStok = parseInt(inputQty.getAttribute('data-max-stok'));
    
    let qtyBaru = qtySekarang + perubahan;
    
    // Validasi batas minimum kuantitas
    if (qtyBaru < 1) {
        alert("Kuantitas minimal adalah 1!");
        return;
    }
    
    // Validasi batas stok maksimal dari database
    if (qtyBaru > maxStok) {
        alert("Stok tidak mencukupi! Batas maksimal pembelian adalah " + maxStok + " pcs.");
        return;
    }
    
    // Set nilai baru ke input text browser
    inputQty.value = qtyBaru;
    
    // Update visual Subtotal baris produk tersebut
    let subtotalBaru = hargaItem * qtyBaru;
    document.getElementById('subtotal_' + idCart).innerText = 'Rp ' + subtotalBaru.toLocaleString('id-ID');
    
    // Hitung ulang Grand Total belanjaan terpilih
    hitungGrandTotal();
    
    // Jalankan AJAX di background untuk mengupdate kuantitas di database MySQL secara real-time
    updateQtyDatabase(idCart, qtyBaru);
}

// 2. Fungsi Kirim Data Update Qty ke MySQL via Async/Fetch API
function updateQtyDatabase(idCart, qtyBaru) {
    fetch('update_qty_cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id_cart=' + idCart + '&qty=' + qtyBaru
    })
    .then(response => response.json())
    .then(data => {
        if(!data.success) {
            alert(data.message || "Gagal memperbarui kuantitas di database.");
        }
    })
    .catch(error => console.error('Error Sync Database:', error));
}

// 3. Fungsi Menghitung Ulang Total Harga Barang yang sedang di-Checklist
function hitungGrandTotal() {
    let total = 0;
    checkItems.forEach(item => {
        if (item.checked) {
            let idCart = item.value;
            let qty = parseInt(document.getElementById('qty_' + idCart).value);
            let harga = parseInt(document.getElementById('qty_' + idCart).getAttribute('data-price'));
            total += (harga * qty);
        }
    });
    grandTotalText.innerText = 'Rp ' + total.toLocaleString('id-ID');
}

// 4. Sistem Checklist All (Pilih Semua)
if(checkAll) {
    checkAll.addEventListener('change', function() {
        checkItems.forEach(item => {
            item.checked = checkAll.checked;
        });
        hitungGrandTotal();
    });
}

// 5. Listener jika checkbox satuan diklik satu per satu
checkItems.forEach(item => {
    item.addEventListener('change', function() {
        hitungGrandTotal();
        // sinkronisasi checkbox master "pilih semua"
        let allChecked = Array.from(checkItems).every(i => i.checked);
        if(checkAll) checkAll.checked = allChecked;
    });
});

// 6. Validasi Form saat tombol Checkout diklik
if(formCart) {
    formCart.addEventListener('submit', function(e) {
        let adaYangDiCheck = Array.from(checkItems).some(item => item.checked);
        if (!adaYangDiCheck) {
            e.preventDefault();
            alert("Silakan pilih minimal satu produk untuk melanjutkan checkout!");
        }
    });
}
</script>

<?php include "../template/footer.php"; ?>