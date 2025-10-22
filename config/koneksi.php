<?php
// config/koneksi.php
$server   = "localhost";
$user     = "root";
$pass = "";
$database = "dbarsip";

$koneksi = mysqli_connect($server, $user, $pass, $database);
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>