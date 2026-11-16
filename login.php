<?php
    session_start();
    include "koneksi.php";

    if (isset($_POST["login"])) {
        $username = trim($_POST["username"]);
        $password_asli = $_POST["password"];

        $stmt = $koneksi->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $id = 0;
        $user_db = "";
        $password_hash = "";
        $stmt->bind_result($id, $user_db, $password_hash);
        $stmt->fetch();
        $stmt->close();

        if ($password_hash !== "" && password_verify($password_asli, $password_hash)) {
            $_SESSION["status_login"] = true;
            $_SESSION["user_id"] = $id;
            $_SESSION["username"] = $user_db;
            setcookie("user", $user_db, time() + 86400);
            header("Location: dashboard.php");
            exit();
        } else {
            header("Location: login.php?pesan=salah");
            exit();
        }
    }
?>

<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Toko Kue</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center min-vh-100">
    <div class="card p-4 shadow-sm" style="width: 350px;">
        <h3 class="text-center mb-3">Login</h3>

        <?php if (isset($_GET["pesan"]) && $_GET["pesan"] == "salah"): ?>
            <div class="alert alert-danger py-2">Username atau password salah.</div>
        <?php endif; ?>

        <?php if (isset($_GET["status"]) && $_GET["status"] == "registered"): ?>
            <div class="alert alert-success py-2">Registrasi berhasil! Silakan login.</div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Masukkan username" required autocomplete="off">
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary w-100 mb-3">Login</button>
            <div class="text-center">
                <small>Belum punya akun? <a href="register.php">Register</a></small>
            </div>
        </form>
    </div>
</body>
</html>
