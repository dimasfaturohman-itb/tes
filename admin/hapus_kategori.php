<?php

include 'koneksi.php';

$id = $_GET['id'];

$hapus = mysqli_query(
    $conn,
    "DELETE FROM kategori WHERE id_kategori='$id'"
);

if($hapus){

    echo "

    <script>

        alert('Kategori berhasil dihapus');

        window.location='kategori.php';

    </script>

    ";

}else{

    echo "

    <script>

        alert('Kategori gagal dihapus');

        window.location='kategori.php';

    </script>

    ";

}

?>