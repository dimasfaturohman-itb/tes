<?php
session_start();

include "../config/koneksi.php";
include "../template/header.php";
include "../template/navbar_customer.php";

if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Ambil ID Pesanan dari URL untuk divalidasi dan dilempar ke file proses
$id_pesanan = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_pesanan <= 0) {
    echo "<script>alert('ID Pesanan tidak valid!'); window.location='pesanan_saya.php';</script>";
    exit;
}
?>

<style>
body{
    background:#f5f5f5;
}

/* =========================
   CARD
========================= */
.upload-card{
    background:white;
    border:none;
    border-radius:24px;
    box-shadow:0 5px 18px rgba(0,0,0,0.05);
}

/* =========================
   TITLE
========================= */
.page-title{
    font-weight:700;
    color:#222;
}

.page-subtitle{
    color:#888;
    font-size:14px;
}

/* =========================
   INPUT FILE
========================= */
.custom-file{
    border:2px dashed #ffb3c1;
    border-radius:18px;
    padding:35px 20px;
    text-align:center;
    background:#fff8fa;
    transition:0.2s ease;
    cursor: pointer;
}

.custom-file:hover{
    border-color:#ff4d6d;
    background:#fff0f3;
}

.custom-file input{
    display:none;
}

.upload-icon{
    font-size:55px;
    color:#ff4d6d;
}

.file-text{
    margin-top:10px;
    font-size:14px;
    color:#777;
}

/* =========================
   BUTTON
========================= */
.btn-upload{
    background:linear-gradient(135deg,#ff4d6d,#ff758f);
    border:none;
    color:white;
    font-weight:600;
    padding:12px;
    border-radius:14px;
    transition:0.2s ease;
}

.btn-upload:hover{
    transform:translateY(-2px);
    color:white;
}

/* =========================
   PREVIEW
========================= */
.preview-box{
    display:none;
    margin-top:20px;
    text-align:center;
}

.preview-box img{
    width:220px;
    border-radius:18px;
    border:1px solid #eee;
    object-fit:cover;
}
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
            <div class="card upload-card p-4">

                <div class="text-center mb-4">
                    <h3 class="page-title">
                        Upload Bukti Pembayaran
                    </h3>
                    <p class="page-subtitle mb-0">
                        Upload screenshot atau foto bukti transfer pembayaran
                    </p>
                </div>

                <form action="proses_upload_bukti.php" method="POST" enctype="multipart/form-data">
                    
                    <input type="hidden" name="id_pesanan" value="<?= $id_pesanan; ?>">

                    <div class="mb-4">
                        <label class="custom-file w-100">
                            <div>
                                <i class="bi bi-cloud-arrow-up-fill upload-icon"></i>
                                <div class="file-text" id="label-text">
                                    Klik untuk memilih gambar
                                </div>
                                <small class="text-muted">
                                    JPG, PNG, JPEG
                                </small>
                            </div>
                            <input
                                type="file"
                                name="bukti"
                                id="inputFile"
                                accept="image/*"
                                required>
                        </label>
                    </div>

                    <div class="preview-box" id="previewBox">
                        <img id="previewImg">
                    </div>

                    <button type="submit" class="btn btn-upload w-100 mt-3">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        Upload Sekarang
                    </button>

                </form>

            </div>
        </div>
    </div>
</div>

<script>
const inputFile = document.getElementById("inputFile");
const previewBox = document.getElementById("previewBox");
const previewImg = document.getElementById("previewImg");
const labelText = document.getElementById("label-text");

inputFile.addEventListener("change", function(){
    const file = this.files[0];
    if(file){
        const reader = new FileReader();
        labelText.innerText = file.name; // Mengubah teks nama file yang dipilih
        
        reader.onload = function(e){
            previewImg.src = e.target.result;
            previewBox.style.display = "block";
        }
        reader.readAsDataURL(file);
    }
});
</script>

<?php include "../template/footer.php"; ?>