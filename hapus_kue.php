<?php
    include 'koneksi.php';
    include 'cek_session.php';

    $id = (int) $_GET["id"];

    $stmt = $koneksi->prepare("SELECT foto FROM kue WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $hasil = $stmt->get_result();
    $data = $hasil->fetch_assoc();
    $stmt->close();

    if ($data) {
        if (!empty($data["foto"]) && file_exists("uploads/" . $data["foto"])) {
            unlink("uploads/" . $data["foto"]);
        }

        $hapus = $koneksi->prepare("DELETE FROM kue WHERE id = ?");
        $hapus->bind_param("i", $id);

        if ($hapus->execute()) {
            header("Location: dashboard.php");
        } else {
            echo "Gagal menghapus data: " . $koneksi->error;
        }
        $hapus->close();
    } else {
        header("Location: dashboard.php");
        exit();
    }
?>
