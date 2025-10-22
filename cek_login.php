<?php


ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include "config/koneksi.php";

$username = $_POST['username'];
$password = $_POST['password'];

if (empty($username) || empty($password)) {
    echo "<script>alert('Username dan Password tidak boleh kosong!'); document.location='index.php';</script>";
    exit();
}

// Mengambil data user berdasarkan username
$sql = "SELECT * FROM tbl_user WHERE username = ?";
$stmt = mysqli_prepare($koneksi, $sql);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_array($result);


if ($data && $password === 'admin') {
    // Jika username ditemukan dan password yang diketik adalah 'admin'
    
    // Login berhasil
    $_SESSION['id_user'] = $data['id_user'];
    $_SESSION['username'] = $data['username'];
    
    // Alihkan ke halaman admin
    header('location:admin.php');
    exit();
} else {
    // Jika login gagal
    echo "<script>
            alert('Login GAGAL dengan metode tes! Periksa username Anda.');
            document.location='index.php';
          </script>";
}

mysqli_stmt_close($stmt);
mysqli_close($koneksi);
?>