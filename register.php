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
</head>
<body>

    <h2>Register</h2>

    <?php if (!empty($pesan)): ?>
        <p style="color: red;"><?= $pesan; ?></p>
    <?php endif; ?>

    <!-- Form HTML Dasar (Tampilan nanti disesuaikan Bootstrap) -->
    <form action="register.php" method="POST">
        <div>
            <label for="username">Username:</label><br>
            <input type="text" name="username" id="username" required autocomplete="off">
        </div>

        <br>
        
        <div>
            <label for="password">Password:</label><br>
            <input type="password" name="password" id="password" required>
        </div>
        <br>
        <button type="submit">Register</button>
    </form>

    <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>

</body>
</html>