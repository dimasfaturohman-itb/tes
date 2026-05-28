<?php
session_start();
include "../config/koneksi.php";

if (isset($_POST['id_cart']) && isset($_POST['qty'])) {
    $id_cart = mysqli_real_escape_string($conn, $_POST['id_cart']);
    $qty = mysqli_real_escape_string($conn, $_POST['qty']);
    
    // Update Qty di database
    mysqli_query($conn, "UPDATE cart SET qty = '$qty' WHERE id_cart = '$id_cart'");
}
?>