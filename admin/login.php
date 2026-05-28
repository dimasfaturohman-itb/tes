<?php
session_start();

if (isset($_SESSION['admin'])) {
    header("Location: dashboard.php");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login Admin</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>

        body{
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #ff4d6d, #ff758f);
            font-family: Arial, sans-serif;
        }

        .login-box{
            width: 100%;
            max-width: 420px;
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .login-icon{
            width: 90px;
            height: 90px;
            background: #ff4d6d;
            border-radius: 50%;
            margin: auto;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-size: 40px;
            margin-bottom: 20px;
        }

        .form-control{
            padding: 12px;
            border-radius: 10px;
        }

        .btn-login{
            background: #ff4d6d;
            border: none;
            padding: 12px;
            border-radius: 10px;
            font-weight: bold;
        }

        .btn-login:hover{
            background: #e63e5c;
        }

    </style>

</head>
<body>

<div class="login-box">

    <div class="login-icon">
        <i class="bi bi-shield-lock-fill"></i>
    </div>

    <h3 class="text-center fw-bold">Admin Login</h3>

    <p class="text-center text-muted mb-4">
        Silakan login ke dashboard admin
    </p>

    <!-- Alert Error -->
    <?php if(isset($_GET['error'])) : ?>

        <div class="alert alert-danger">
            Email atau password salah!
        </div>

    <?php endif; ?>

    <form action="proses_login.php" method="POST">

        <!-- Email -->
        <div class="mb-3">

            <label class="form-label">
                Email
            </label>

            <div class="input-group">

                <span class="input-group-text">
                    <i class="bi bi-envelope-fill"></i>
                </span>

                <input 
                    type="email"
                    name="email"
                    class="form-control"
                    placeholder="Masukkan email"
                    required
                >

            </div>

        </div>

        <!-- Password -->
        <div class="mb-4">

            <label class="form-label">
                Password
            </label>

            <div class="input-group">

                <span class="input-group-text">
                    <i class="bi bi-lock-fill"></i>
                </span>

                <input 
                    type="password"
                    name="password"
                    class="form-control"
                    placeholder="Masukkan password"
                    required
                >

            </div>

        </div>

        <!-- Button -->
        <button type="submit" class="btn btn-danger btn-login w-100">

            <i class="bi bi-box-arrow-in-right"></i>

            Login

        </button>

    </form>

</div>

</body>
</html>