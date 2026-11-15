<?php
    include 'koneksi.php';
    $pesan = "";

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $user = trim($_POST['username']);
        $pass = trim($_POST['password']);

        if(!empty($user) && !empty($pass)){
            $stmt_chek = $koneksi->prepare("SELECT id FROM users WHERE username = ?");
            $stmt_chek->bind_param("s", $user);
            $stmt_chek->execute();
            $stmt_chek->store_result();

            if($stmt_chek->num_rows > 0){
                $pesan = "Username Sudah terdaftar!";
            }else{
                $hashed_PW = password_hash($pass, PASSWORD_DEFAULT);
                $stmt_insert = $koneksi->prepare("INSERT INTO users (username, password) VALUES(?,?)");
                $stmt_insert->bind_param("ss", $user, $hashed_PW);
                if($stmt_insert->execute()){
                    header("Location: login.php?status=registered");
                    exit();
                }else{
                    $pesan = "Gagal Mendaftar!";
                }
                $stmt_insert->close();
            }

            $stmt_chek->close();

        }else{
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