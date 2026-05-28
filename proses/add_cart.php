<?php
session_start();

$id_produk = $_GET['id'];

if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

$_SESSION['cart'][] = $id_produk;

header("Location: ../guest/cart.php");