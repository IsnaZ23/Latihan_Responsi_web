<?php
require 'koneksi.php';
require 'cek_session.php';

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}
$id = intval($_GET['id']);
$result = mysqli_query($koneksi, "SELECT * FROM kue WHERE id = $id");
$kue = mysqli_fetch_assoc($result);

if (!$kue) {
    header("Location: dashboard.php");
    exit();
}

if (isset($_POST['update'])) {
    $nama  = trim($_POST['nama']);
    $harga = intval($_POST['harga']);
    $stok  = intval($_POST['stok']);

    $foto_lama = $kue['foto'];
    $nama_foto_akhir = $foto_lama;

    if (!empty($_FILES['foto']['name'])) {
        $foto_name = $_FILES['foto']['name'];
        $foto_tmp  = $_FILES['foto']['tmp_name'];
        $ext       = pathinfo($foto_name, PATHINFO_EXTENSION);
        $nama_baru = time() . '_' . uniqid() . '.' . $ext;

        if (move_uploaded_file($foto_tmp, 'uploads/' . $nama_baru)) {
            $nama_foto_akhir = $nama_baru;
            if (file_exists('uploads/' . $foto_lama)) {
                unlink('uploads/' . $foto_lama);
            }
        }
    }

    $stmt = $koneksi->prepare("UPDATE kue SET nama=?, harga=?, stok=?, foto=? WHERE id=?");
    $stmt->bind_param("siisi", $nama, $harga, $stok, $nama_foto_akhir, $id);
    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kue</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">
    <div class="container" style="max-width: 500px;">
        <h3 class="mb-4">Edit Kue</h3>
        <form method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm">
            <div class="mb-3">
                <label class="form-label d-block">Foto Lama</label>
                <img src="uploads/<?= htmlspecialchars($kue['foto']); ?>" width="100" class="mb-2 img-thumbnail" alt="Foto Lama">
            </div>
            <div class="mb-3">
                <label class="form-label">Ganti Foto (Opsional)</label>
                <input type="file" name="foto" class="form-control" accept="image/*">
            </div>
            <div class="mb-3">
                <label class="form-label">Nama Kue</label>
                <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($kue['nama']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Harga</label>
                <input type="number" name="harga" class="form-control" value="<?= $kue['harga']; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Stok</label>
                <input type="number" name="stok" class="form-control" value="<?= $kue['stok']; ?>" required>
            </div>
            <div>
                <button type="submit" name="update" class="btn btn-primary">Simpan Perubahan</button>
                <a href="dashboard.php" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</body>
</html>