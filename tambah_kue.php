<?php
include 'koneksi.php';
include 'cek_session.php';

if (isset($_POST['submit'])) {
    $nama  = trim($_POST['nama']);
    $harga = intval($_POST['harga']);
    $stok  = intval($_POST['stok']);

    $foto_nama = $_FILES['foto']['name'];
    $foto_tmp  = $_FILES['foto']['tmp_name'];
    $ext       = pathinfo($foto_nama, PATHINFO_EXTENSION);

    $nama_foto_baru = time() . '_' . uniqid() . '.' . $ext;
    $target_dir     = 'uploads/' . $nama_foto_baru;

    if (move_uploaded_file($foto_tmp, $target_dir)) {
        $query = "INSERT INTO kue (nama, harga, stok, foto) VALUES ('$nama', '$harga', '$stok', '$nama_foto_baru')";
        if (mysqli_query($koneksi, $query)) {
            header("Location: dashboard.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kue</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light p-4">
    <div class="container" style="max-width: 500px;">
        <h3 class="mb-4">Tambah Kue</h3>
        
        <form method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm">
            <div class="mb-3">
                <label class="form-label">Foto Kue</label>
                <input type="file" name="foto" class="form-control" accept="image/*" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Nama Kue</label>
                <input type="text" name="nama" class="form-control" required autocomplete="off">
            </div>
            <div class="mb-3">
                <label class="form-label">Harga</label>
                <input type="number" name="harga" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Stok</label>
                <input type="number" name="stok" class="form-control" required>
            </div>
            <div>
                <button type="submit" name="submit" class="btn btn-primary">Tambah</button>
                <a href="dashboard.php" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</body>
</html>