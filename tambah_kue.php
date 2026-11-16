<?php
    include 'koneksi.php';
    include 'cek_session.php';

    if (isset($_POST["submit"])) {
        $nama  = trim($_POST["nama"]);
        $harga = (int) $_POST["harga"];
        $stok  = (int) $_POST["stok"];

        $namaBerkas      = $_FILES["foto"]["name"];
        $lokasiSementara = $_FILES["foto"]["tmp_name"];
        $extensi         = pathinfo($namaBerkas, PATHINFO_EXTENSION);

        $namaFotoBaru = time() . "_" . uniqid() . "." . $extensi;
        $lokasiTujuan = "uploads/" . $namaFotoBaru;

        if (move_uploaded_file($lokasiSementara, $lokasiTujuan)) {
            $stmt = $koneksi->prepare("INSERT INTO kue (nama, harga, stok, foto) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("siis", $nama, $harga, $stok, $namaFotoBaru);

            if ($stmt->execute()) {
                header("Location: dashboard.php");
            } else {
                echo "Gagal menyimpan data: " . $koneksi->error;
            }
            $stmt->close();
        } else {
            echo "Unggah berkas gagal.";
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

        <form action="tambah_kue.php" method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm">
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
                <input type="submit" name="submit" value="Tambah" class="btn btn-primary">
                <a href="dashboard.php" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</body>
</html>
