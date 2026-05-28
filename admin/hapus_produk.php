<?php

include 'koneksi.php';

/*
|--------------------------------------------------------------------------
| AMBIL ID PRODUK
|--------------------------------------------------------------------------
*/

$id = $_GET['id'];

/*
|--------------------------------------------------------------------------
| AMBIL DATA PRODUK
|--------------------------------------------------------------------------
*/

$query = mysqli_query(
    $conn,
    "SELECT * FROM produk WHERE id_produk='$id'"
);

$data = mysqli_fetch_assoc($query);

/*
|--------------------------------------------------------------------------
| HAPUS GAMBAR
|--------------------------------------------------------------------------
*/

$gambar = $data['gambar'];

if(file_exists("upload/".$gambar)){

    unlink("upload/".$gambar);

}

/*
|--------------------------------------------------------------------------
| HAPUS DATA PRODUK
|--------------------------------------------------------------------------
*/

$hapus = mysqli_query(
    $conn,
    "DELETE FROM produk WHERE id_produk='$id'"
);

/*
|--------------------------------------------------------------------------
| CEK BERHASIL
|--------------------------------------------------------------------------
*/

if($hapus){

    echo "

    <script>

        alert('Produk berhasil dihapus');

        window.location='produk.php';

    </script>

    ";

}else{

    echo "

    <script>

        alert('Produk gagal dihapus');

        window.location='produk.php';

    </script>

    ";

}

?>