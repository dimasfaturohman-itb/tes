<?php

session_start();

include 'koneksi.php';

$email = $_POST['email'];
$password = md5($_POST['password']);

$query = mysqli_query(
    $conn,
    "SELECT * FROM users 
     WHERE email='$email' 
     AND password='$password'
     AND role='admin'"
);

$cek = mysqli_num_rows($query);

if($cek > 0){

    $data = mysqli_fetch_assoc($query);

    $_SESSION['admin'] = $data['nama'];
    $_SESSION['id_user'] = $data['id_user'];

    header("Location: dashboard.php");

}else{

    header("Location: login.php?error=gagal");

}

?>