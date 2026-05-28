<?php
session_start();
include "../config/koneksi.php";

if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

$user = mysqli_query($conn,"
SELECT * FROM users
WHERE id_user='$id_user'
");

$data = mysqli_fetch_assoc($user);

include "../template/header.php";
include "../template/navbar_customer.php";
?>

<a href="../customer/dashboard.php"
   class="back-btn">

    <i class="bi bi-arrow-left"></i>

</a>

<style>

body{
    background:#f5f5f5;
    font-family:'Segoe UI', sans-serif;
}

/* =========================
   FLOAT BACK BUTTON
========================= */

.back-btn{
    position:fixed;
    top:90px;
    left:20px;
    width:48px;
    height:48px;
    background:white;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    box-shadow:0 4px 12px rgba(0,0,0,0.12);
    color:#ff4d6d;
    text-decoration:none;
    font-size:22px;
    z-index:9999;
    transition:0.2s;
}

/* Tombol Back Home */
    .back-home-btn {
        position: fixed; bottom: 25px; right: 25px; width: 48px; height: 48px;
        background: #ff4d6d; color: white; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 6px 15px rgba(0,0,0,0.15); text-decoration: none;
        z-index: 999; transition: 0.2s ease;
    }
    .back-home-btn:hover { transform: scale(1.08); background: #ff355d; color: white; }

.back-btn:hover{
    background:#ff4d6d;
    color:white;
    transform:scale(1.05);
}

/* =========================
   PROFILE WRAPPER
========================= */

.profile-wrapper{
    background:white;
    border-radius:20px;
    padding:30px;
    box-shadow:0 2px 10px rgba(0,0,0,0.05);
}

/* =========================
   SIDEBAR
========================= */

.sidebar{
    border-right:1px solid #eee;
    min-height:650px;
}

.profile-mini{
    display:flex;
    align-items:center;
    gap:15px;
    padding-bottom:20px;
    border-bottom:1px solid #eee;
}

.profile-mini img{
    width:60px;
    height:60px;
    border-radius:50%;
    border:2px solid #eee;
    object-fit:cover;
}

.profile-mini-name{
    font-weight:700;
}

.edit-profile{
    color:#888;
    text-decoration:none;
    font-size:14px;
}

/* MENU */

.menu-section{
    margin-top:30px;
}

.menu-title{
    font-weight:700;
    margin-bottom:15px;
    font-size:18px;
}

.menu-link{
    display:block;
    text-decoration:none;
    color:#555;
    margin-bottom:18px;
    padding-left:10px;
    transition:0.2s;
}

.menu-link:hover{
    color:#ff4d6d;
}

.menu-active{
    color:#ff4d6d;
    font-weight:600;
}

/* =========================
   CONTENT
========================= */

.content-title{
    font-size:32px;
    font-weight:700;
}

.content-subtitle{
    color:#777;
    margin-bottom:25px;
}

.form-label{
    font-weight:600;
    margin-bottom:8px;
}

.form-control{
    border-radius:12px;
    padding:12px;
    border:1px solid #ddd;
}

.form-control:focus{
    border-color:#ff4d6d;
    box-shadow:none;
}

/* GENDER */

.gender-box{
    display:flex;
    gap:20px;
    margin-top:10px;
}

/* BUTTON */

.save-btn{
    background:#ff4d6d;
    border:none;
    border-radius:12px;
    padding:12px 35px;
    color:white;
    font-weight:600;
}

.save-btn:hover{
    background:#ff3366;
}

/* RIGHT PHOTO */

.photo-box{
    border-left:1px solid #eee;
    text-align:center;
    padding-left:30px;
}

.photo-box img{
    width:130px;
    height:130px;
    border-radius:50%;
    object-fit:cover;
    border:3px solid #eee;
}

.upload-btn{
    margin-top:20px;
    border-radius:10px;
}

.photo-text{
    color:#888;
    font-size:14px;
    margin-top:15px;
}

/* MOBILE */

@media(max-width:768px){

    .sidebar{
        border-right:none;
        min-height:auto;
        margin-bottom:30px;
    }

    .photo-box{
        border-left:none;
        margin-top:30px;
        padding-left:0;
    }

}

</style>

<div class="container py-4">

<div class="profile-wrapper">

<div class="row">

    <!-- SIDEBAR -->
    <div class="col-md-3 sidebar">

        <div class="profile-mini">

            <?php if($data['foto'] != ''){ ?>

                <img src="../assets/profile/<?= $data['foto']; ?>">

            <?php } else { ?>

                <img src="../assets/img/user.png">

            <?php } ?>

            <div>

                <div class="profile-mini-name">
                    <?= $data['nama']; ?>
                </div>

                <a href="#"
                   class="edit-profile">

                    <i class="bi bi-pencil"></i>
                    Ubah Profil

                </a>

            </div>

        </div>

        <!-- MENU -->
        <div class="menu-section">

            <div class="menu-title">
                👤 Akun Saya
            </div>

            <a href="#"
               class="menu-link menu-active">
                Profil
            </a>

            <a href="#"
               class="menu-link">
                Bank & Kartu
            </a>

            <a href="#"
               class="menu-link">
                Alamat
            </a>

            <a href="#"
               class="menu-link">
                Ubah Password
            </a>

            <a href="#"
               class="menu-link">
                Pengaturan Notifikasi
            </a>

            <a href="#"
               class="menu-link">
                Pengaturan Privasi
            </a>

        </div>

        <div class="menu-section">

            <a href="../customer/pesanan.php"
               class="menu-link">

                📦 Pesanan Saya

            </a>

            <a href="#"
               class="menu-link">

                🔔 Notifikasi

            </a>

            <a href="#"
               class="menu-link">

                🎟 Voucher Saya

            </a>

        </div>

    </div>

    <!-- CONTENT -->
    <div class="col-md-9">

        <div class="content-title">
            Profil Saya
        </div>

        <div class="content-subtitle">
            Kelola informasi profil Anda untuk mengontrol,
            melindungi dan mengamankan akun
        </div>

        <hr>

        <form action="update_profile.php"
              method="POST"
              enctype="multipart/form-data">

        <div class="row">

            <!-- FORM -->
            <div class="col-md-8">

                <input type="hidden"
                       name="id_user"
                       value="<?= $data['id_user']; ?>">

                <!-- USERNAME -->
                <div class="mb-4">

                    <label class="form-label">
                        Username
                    </label>

                    <input type="text"
                           name="username"
                           class="form-control"
                           value="<?= $data['nama']; ?>">

                </div>

                <!-- NAMA -->
                <div class="mb-4">

                    <label class="form-label">
                        Nama
                    </label>

                    <input type="text"
                           name="nama"
                           class="form-control"
                           value="<?= $data['nama']; ?>">

                </div>

                <!-- EMAIL -->
                <div class="mb-4">

                    <label class="form-label">
                        Email
                    </label>

                    <input type="email"
                           name="email"
                           class="form-control"
                           value="<?= $data['email']; ?>">

                </div>

                <!-- NO HP -->
                <div class="mb-4">

                    <label class="form-label">
                        Nomor Telepon
                    </label>

                    <input type="text"
                           name="no_hp"
                           class="form-control"
                           value="<?= $data['no_hp']; ?>">

                </div>

                <!-- GENDER -->
                <div class="mb-4">

                    <label class="form-label">
                        Jenis Kelamin
                    </label>

                    <div class="gender-box">

                        <div class="form-check">

                            <input class="form-check-input"
                                   type="radio"
                                   name="gender"
                                   value="Laki-laki"
                                   <?= ($data['gender'] == 'Laki-laki') ? 'checked' : ''; ?>>

                            <label class="form-check-label">
                                Laki-laki
                            </label>

                        </div>

                        <div class="form-check">

                            <input class="form-check-input"
                                   type="radio"
                                   name="gender"
                                   value="Perempuan"
                                   <?= ($data['gender'] == 'Perempuan') ? 'checked' : ''; ?>>

                            <label class="form-check-label">
                                Perempuan
                            </label>

                        </div>

                    </div>

                </div>

                <!-- ALAMAT -->
                <div class="mb-4">

                    <label class="form-label">
                        Alamat
                    </label>

                    <textarea name="alamat"
                              rows="4"
                              class="form-control"><?= $data['alamat']; ?></textarea>

                </div>

            </div>

            <!-- FOTO -->
            <div class="col-md-4 photo-box">

                <?php if($data['foto'] != ''){ ?>

                    <img src="../assets/profile/<?= $data['foto']; ?>"
                         id="previewImg">

                <?php } else { ?>

                    <img src="../assets/img/user.png"
                         id="previewImg">

                <?php } ?>

                <br>

                <!-- INPUT FILE -->
                <input type="file"
                       name="foto"
                       id="fotoInput"
                       hidden
                       accept="image/*">

                <!-- BUTTON -->
                <button type="button"
                        class="btn btn-outline-secondary upload-btn"
                        onclick="document.getElementById('fotoInput').click()">

                    Pilih Gambar

                </button>

                <div class="photo-text">

                    Ukuran gambar: maks. 1 MB <br>
                    Format gambar: .JPEG, .PNG

                </div>

                <!-- BUTTON SIMPAN -->
                <button type="submit"
                        class="save-btn mt-4">

                    Simpan

                </button>

            </div>

        </div>

        </form>

    </div>

</div>

</div>

</div>

<script>

const fotoInput = document.getElementById('fotoInput');
const previewImg = document.getElementById('previewImg');

fotoInput.addEventListener('change', function(){

    const file = this.files[0];

    if(file){

        const reader = new FileReader();

        reader.onload = function(e){

            previewImg.src = e.target.result;

        }

        reader.readAsDataURL(file);

    }

});

</script>

<a href="dashboard.php" class="back-home-btn">
    <i class="bi bi-house-door-fill"></i>
</a>

<?php include "../template/footer.php"; ?>