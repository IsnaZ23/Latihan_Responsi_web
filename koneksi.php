<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "toko_kue";

$koneksi = mysqli_connect($host, $user, $pass, $db);
if($koneksi->connect_error){
 die("Koneksi database gagal ! " . $koneksi->connect_error);
}
?>