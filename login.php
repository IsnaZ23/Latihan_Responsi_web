<?php
session_start();

include 'koneksi.php';

$pesan = "";
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $user = trim($_POST['username']);
        $pass = trim($_POST['password']);

        if(!empty($user) && !empty($pass)){
            $stmt = $koneksi->prepare("SELECT id, username, password FROM users WHERE username = ?");
            $stmt->bind_param("s", $user);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();

                if (password_verify($pass, $row['password'])) {
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['username'] = $row['username'];

                    header("Location: dashboard.php");
                    exit();
                }else{
                    $pesan = "password salah!";
                }
            }else{
                $pesan = "Username tidak ditemukan! ";
            }
            $stmt->close();
        }else{
            $pesan = "Username dan Password wajib diisi!";
        }
    }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Toko Kue</title>
</head>
<body>

    <h2>Login</h2>

    <?php if (!empty($pesan)): ?>
        <p style="color: red;"><?= $pesan; ?></p>
    <?php endif; ?>

    <?php if (isset($_GET['status']) && $_GET['status'] == 'registered'): ?>
        <p style="color: green;">Registrasi berhasil! Silakan login.</p>
    <?php endif; ?>

    <form action="login.php" method="POST">
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
        <button type="submit">Login</button>
    </form>

    <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>

</body>
</html>