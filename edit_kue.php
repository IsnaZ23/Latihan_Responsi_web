<?php
    include 'koneksi.php';
    include 'cek_session.php';

    $id = (int) $_GET["id"];

    $stmt = $koneksi->prepare("SELECT * FROM kue WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $hasil = $stmt->get_result();
    $data = $hasil->fetch_assoc();
    $stmt->close();

    if (!$data) {
        header("Location: dashboard.php");
        exit();
    }

    if (isset($_POST["submit"])) {
        $nama  = trim($_POST["nama"]);
        $harga = (int) $_POST["harga"];
        $stok  = (int) $_POST["stok"];
        $foto  = $data["foto"];

        if (!empty($_FILES["foto"]["name"])) {
            $namaBerkas      = $_FILES["foto"]["name"];
            $lokasiSementara = $_FILES["foto"]["tmp_name"];
            $extensi         = pathinfo($namaBerkas, PATHINFO_EXTENSION);

            $namaFotoBaru = time() . "_" . uniqid() . "." . $extensi;
            $lokasiTujuan = "uploads/" . $namaFotoBaru;

            if (move_uploaded_file($lokasiSementara, $lokasiTujuan)) {
                if (file_exists("uploads/" . $data["foto"])) {
                    unlink("uploads/" . $data["foto"]);
                }
                $foto = $namaFotoBaru;
            }
        }

        $update = $koneksi->prepare("UPDATE kue SET nama = ?, harga = ?, stok = ?, foto = ? WHERE id = ?");
        $update->bind_param("siisi", $nama, $harga, $stok, $foto, $id);

        if ($update->execute()) {
            header("Location: dashboard.php");
        } else {
            echo "Gagal memperbarui data: " . $koneksi->error;
        }
        $update->close();
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

        <form action="edit_kue.php?id=<?php echo $data['id']; ?>" method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm">
            <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
            <div class="mb-3">
                <label class="form-label d-block">Foto Lama</label>
                <img src="uploads/<?php echo $data['foto']; ?>" width="100" class="mb-2 img-thumbnail" alt="Foto Lama">
            </div>
            <div class="mb-3">
                <label class="form-label">Ganti Foto (Opsional)</label>
                <input type="file" name="foto" class="form-control" accept="image/*">
            </div>
            <div class="mb-3">
                <label class="form-label">Nama Kue</label>
                <input type="text" name="nama" class="form-control" value="<?php echo $data['nama']; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Harga</label>
                <input type="number" name="harga" class="form-control" value="<?php echo $data['harga']; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Stok</label>
                <input type="number" name="stok" class="form-control" value="<?php echo $data['stok']; ?>" required>
            </div>
            <div>
                <input type="submit" name="submit" value="Simpan Perubahan" class="btn btn-primary">
                <a href="dashboard.php" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</body>
</html>
