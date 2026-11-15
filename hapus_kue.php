<?php
require 'koneksi.php';
require 'cek_session.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = mysqli_query($koneksi, "SELECT foto FROM kue WHERE id = $id");
    
    if ($kue = mysqli_fetch_assoc($result)) {
        $foto = $kue['foto'];
        if (!empty($foto) && file_exists('uploads/' . $foto)) {
            unlink('uploads/' . $foto);
        }
        mysqli_query($koneksi, "DELETE FROM kue WHERE id = $id");
    }
}
header("Location: dashboard.php");
exit();
?>