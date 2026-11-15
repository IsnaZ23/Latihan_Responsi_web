<?php
    include 'koneksi.php';
    include 'cek_session.php';

    $query = "SELECT * FROM kue ORDER BY id DESC";
    $result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Katalog Kue</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Katalog Kue</h2>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>

        <div class="row">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img src="uploads/<?= $row['foto']; ?>" class="card-img-top" style="height: 180px; object-fit: cover;" alt="<?= $row['nama']; ?>">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= htmlspecialchars($row['nama']); ?></h5>
                            <p class="card-text mb-1">Harga: Rp <?= number_format($row['harga'], 0, ',', '.'); ?></p>
                            <p class="card-text mb-3">Stok: <?= $row['stok']; ?></p>
                            <div class="mt-auto">
                                <a href="edit_kue.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm me-1">Edit</a>
                                <a href="hapus_kue.php?id=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus kue ini?')">Hapus</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <div class="mt-3">
            <a href="tambah_kue.php" class="btn btn-primary">Tambah Kue</a>
        </div>
    </div>
</body>
</html>