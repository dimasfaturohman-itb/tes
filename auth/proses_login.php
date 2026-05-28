<?php
session_start();
include '../config/koneksi.php';

$email = $_POST['email'];
$password = $_POST['password'];

$query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
$data = mysqli_fetch_assoc($query);

if ($data && password_verify($password, $data['password'])) {

    $_SESSION['id_user'] = $data['id_user'];
    $_SESSION['nama'] = $data['nama'];
    $_SESSION['role'] = $data['role'];

    header("Location: ../customer/dashboard.php");
    exit;

} else {
    echo "LOGIN GAGAL";
}