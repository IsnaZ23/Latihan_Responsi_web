<?php
    include "koneksi.php";
    $pesan = "";

    if (isset($_POST["register"])) {
        $username = trim($_POST["username"]);
        $password_asli = $_POST["password"];

        if (!empty($username) && !empty($password_asli)) {
            $stmt_cek = $koneksi->prepare("SELECT id FROM users WHERE username = ?");
            $stmt_cek->bind_param("s", $username);
            $stmt_cek->execute();
            $id_cek = 0;
            $stmt_cek->bind_result($id_cek);
            $stmt_cek->fetch();
            $stmt_cek->close();

            if ($id_cek) {
                $pesan = "Username sudah terdaftar!";
            } else {
                $password_hash = password_hash($password_asli, PASSWORD_DEFAULT);
                $stmt = $koneksi->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
                $stmt->bind_param("ss", $username, $password_hash);

                if ($stmt->execute()) {
                    header("Location: login.php?status=registered");
                    exit();
                } else {
                    $pesan = "Registrasi gagal: " . $koneksi->error;
                }
                $stmt->close();
            }
        } else {
            $pesan = "Username dan Password wajib diisi!";
        }
    }
?>

<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Toko Kue</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center min-vh-100">
    <div class="card p-4 shadow-sm" style="width: 350px;">
        <h3 class="text-center mb-3">Register</h3>

        <?php if (!empty($pesan)): ?>
            <div class="alert alert-danger py-2"><?= $pesan; ?></div>
        <?php endif; ?>

        <form action="register.php" method="POST">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Masukkan username" required autocomplete="off">
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
            </div>
            <button type="submit" name="register" class="btn btn-success w-100 mb-3">Register</button>
            <div class="text-center">
                <small>Sudah punya akun? <a href="login.php">Login di sini</a></small>
            </div>
        </form>
    </div>
</body>
</html>
