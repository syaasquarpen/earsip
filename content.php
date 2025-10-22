<?php 
// Routing halaman berdasarkan parameter 'halaman' dan 'hal'
$halaman = isset($_GET['halaman']) ? $_GET['halaman'] : '';

if ($halaman == "departemen") {
    include "modul/departemen/departemen.php";
} 
elseif ($halaman == "pengirim_surat") {
    include "modul/pengirim_surat/pengirim_surat.php";
} 
elseif ($halaman == "arsip_surat") {
    
    include "modul/arsip/data.php";
} 
else {
    include "modul/home.php";
}
?>
