<?php

include 'koneksi.php';

/* =========================
   SIMPAN KATEGORI
========================= */

if(isset($_POST['simpan'])){

    $nama_kategori = mysqli_real_escape_string($conn, $_POST['nama_kategori']);

    if(empty($_FILES['icon']['name'])){
        echo "<script>alert('Icon kategori wajib diupload');window.history.back();</script>";
        exit;
    }

    $namaFile = preg_replace("/[^a-zA-Z0-9.-]/", "_", $_FILES['icon']['name']);
    $nama_ikon = time().'_'.$namaFile; // Gunakan nama_ikon agar konsisten

    move_uploaded_file(
        $_FILES['icon']['tmp_name'],
        "upload/".$nama_ikon // Sesuaikan dengan variabel di atas
    );

    // PASTIKAN nama kolom di bawah ini SAMA dengan yang ada di database Anda
    // Jika di database kolomnya bernama 'ikon', maka tulis 'ikon'
    $simpan = mysqli_query($conn,"
        INSERT INTO kategori(nama_kategori, ikon) 
        VALUES('$nama_kategori','$nama_ikon')
    ");

    if($simpan){
        echo "<script>alert('Kategori berhasil ditambahkan');window.location='kategori.php';</script>";
    }else{
        // Debugging: jika gagal, tampilkan error MySQL-nya
        echo "<script>alert('Gagal: ".mysqli_error($conn)."');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Tambah Kategori</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>

/* =========================
   BASE
========================= */

body{
    background:#f5f5f5;
    font-family:'Segoe UI',sans-serif;
}

/* =========================
   NAVBAR PINK (DASHBOARD STYLE)
========================= */

.navbar{
    background:linear-gradient(135deg,#ff4d6d,#ff758f);
    padding:15px 25px;
}

.navbar-brand{
    font-weight:800;
    font-size:28px;
    color:white !important;
}

/* =========================
   BOX
========================= */

.form-box{
    background:white;
    padding:35px;
    border-radius:20px;
    margin-top:50px;
    box-shadow:0 5px 15px rgba(0,0,0,0.08);
}

/* =========================
   INPUT
========================= */

.form-control{
    height:50px;
    border-radius:12px;
}

/* =========================
   ICON UPLOAD
========================= */

.upload-box{
    border:2px dashed #ddd;
    border-radius:16px;
    padding:25px;
    text-align:center;
    cursor:pointer;
    transition:0.2s;
}

.upload-box:hover{
    border-color:#ff4d6d;
}

.preview-icon{
    width:90px;
    height:90px;
    object-fit:cover;
    border-radius:14px;
    margin-top:10px;
    border:1px solid #eee;
}

/* =========================
   BUTTON
========================= */

.btn-pink{
    background:#ff4d6d;
    border:none;
    color:white;
    font-weight:600;
    border-radius:12px;
}

.btn-pink:hover{
    background:#ff2e63;
    color:white;
}

</style>

</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-dark">
    <div class="container">
        <a href="kategori.php" class="navbar-brand">
            Mochimo
        </a>
    </div>
</nav>

<div class="container">

<div class="row justify-content-center">

<div class="col-md-6">

<div class="form-box">

<h3 class="fw-bold text-center">Tambah Kategori</h3>
<p class="text-muted text-center">Tambahkan kategori + icon</p>

<form method="POST" enctype="multipart/form-data">

<!-- NAMA -->
<div class="mb-3">

<label class="form-label">Nama Kategori</label>

<input type="text"
       name="nama_kategori"
       class="form-control"
       placeholder="Contoh: Hoodie"
       required>

</div>

<!-- ICON UPLOAD -->
<div class="mb-3">

<label class="form-label">Icon Kategori</label>

<label class="upload-box w-100">

<i class="bi bi-image fs-1 text-muted"></i>

<div class="mt-2">Upload Icon / Gambar</div>

<input type="file"
       name="icon"
       accept="image/*"
       hidden
       onchange="previewIcon(event)">

</label>

<div class="text-center">

<img id="preview"
     class="preview-icon"
     style="display:none;">

</div>

</div>

<!-- BUTTON -->
<div class="d-flex gap-2">

<button type="submit"
        name="simpan"
        class="btn btn-pink w-100">

<i class="bi bi-save"></i>
Simpan

</button>

<a href="kategori.php"
   class="btn btn-secondary w-100">

Kembali

</a>

</div>

</form>

</div>

</div>

</div>

</div>

<script>

/* PREVIEW ICON */
function previewIcon(event){

    let reader = new FileReader();

    reader.onload = function(){

        let img = document.getElementById('preview');
        img.src = reader.result;
        img.style.display = 'block';
    }

    reader.readAsDataURL(event.target.files[0]);
}

</script>

</body>
</html>


<?php include 'footer.php'; ?>