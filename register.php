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
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register - Toko Kue</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex align-items-center justify-content-center min-vh-100">
    <div class="card p-4 shadow-sm" style="width: 350px;">
        <h3 class="text-center mb-3">Register</h3>


        <?php if (!empty($pesan)): ?>
            <div class="alert alert-danger py-2" role="alert">
                <?= $pesan; ?>
            </div>
        <?php endif; ?>

        <form action="register.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username:</label>
                <input type="text" name="username" id="username" class="form-control" required autocomplete="off">
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>


            <button type="submit" class="btn btn-primary w-100 mb-3">Register</button>
        </form>

        <div class="text-center">
            <small>Sudah punya akun? <a href="login.php">Login di sini</a></small>
        </div>

    </div>

</body>

</body>
</html>