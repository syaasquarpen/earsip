<?php
include "/config/koneksi.php";

$halaman_utama = "?halaman=arsip_surat";

$departemen = mysqli_query($koneksi, "SELECT * FROM tbl_departemen ORDER BY nama_departemen ASC");
$pengirim = mysqli_query($koneksi, "SELECT * FROM tbl_pengirim_surat ORDER BY nama_pengirim ASC");

function generateNoSurat($koneksi) {
    do {
        $no_surat = 'SURAT-' . date('Ymd') . '-' . rand(10000, 99999);
        $cek = mysqli_query($koneksi, "SELECT 1 FROM tbl_arsip WHERE no_surat='$no_surat'");
    } while (mysqli_num_rows($cek) > 0);
    return $no_surat;
}

$vno_surat = generateNoSurat($koneksi);
$vtanggal_surat = $vtanggal_diterima = $vperihal = $vfile_surat = $vid_departemen = $vid_pengirim = "";

if (isset($_GET['hal']) && $_GET['hal'] == "edit" && isset($_GET['id_arsip'])) {
    $id_arsip = (int) $_GET['id_arsip'];
    $stmt = $koneksi->prepare("SELECT * FROM tbl_arsip WHERE id_arsip = ?");
    $stmt->bind_param("i", $id_arsip);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($data = $result->fetch_assoc()) {
        $vno_surat = $data['no_surat'];
        $vtanggal_surat = $data['tanggal_surat'];
        $vtanggal_diterima = $data['tanggal_diterima'];
        $vperihal = $data['perihal'];
        $vfile_surat = $data['file_surat'];
        $vid_departemen = $data['id_departemen'];
        $vid_pengirim = $data['id_pengirim'];
    }
    $stmt->close();
}

if (isset($_POST['bsimpan'])) {
    $fileName = $vfile_surat;

    if (!empty($_FILES['file_surat']['name'])) {
        $targetDir = "../../uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        if ($vfile_surat && file_exists($targetDir . $vfile_surat)) {
            unlink($targetDir . $vfile_surat);
        }

        $fileNameRaw = time() . "_" . basename($_FILES["file_surat"]["name"]);
        $fileName = preg_replace("/[^a-zA-Z0-9_\.\-]/", "", $fileNameRaw);
        $targetFilePath = $targetDir . $fileName;
        move_uploaded_file($_FILES["file_surat"]["tmp_name"], $targetFilePath);
    }

    $no_surat = trim($_POST['no_surat']);
    $tanggal_surat = trim($_POST['tanggal_surat']);
    $tanggal_diterima = trim($_POST['tanggal_diterima']);
    $perihal = trim($_POST['perihal']);
    $id_departemen = trim($_POST['id_departemen']);
    $id_pengirim = trim($_POST['id_pengirim']);

    if (isset($_GET['hal']) && $_GET['hal'] == "edit" && isset($_GET['id_arsip'])) {
        $id_arsip = (int)$_GET['id_arsip'];
        $stmt = $koneksi->prepare("UPDATE tbl_arsip SET no_surat=?, tanggal_surat=?, tanggal_diterima=?, perihal=?, file_surat=?, id_departemen=?, id_pengirim=? WHERE id_arsip=?");
        $stmt->bind_param("sssssssi", $no_surat, $tanggal_surat, $tanggal_diterima, $perihal, $fileName, $id_departemen, $id_pengirim, $id_arsip);
        if ($stmt->execute()) {
            echo "<script>alert('Data berhasil diperbarui!');window.location='$halaman_utama';</script>";
            exit;
        } else {
            echo "<script>alert('Gagal memperbarui data!');</script>";
        }
        $stmt->close();
    } else {
        $stmt = $koneksi->prepare("INSERT INTO tbl_arsip (no_surat, tanggal_surat, tanggal_diterima, perihal, file_surat, id_departemen, id_pengirim) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $no_surat, $tanggal_surat, $tanggal_diterima, $perihal, $fileName, $id_departemen, $id_pengirim);
        if ($stmt->execute()) {
            echo "<script>alert('Data berhasil disimpan!');window.location='$halaman_utama';</script>";
            exit;
        } else {
            echo "<script>alert('Gagal menyimpan data!');</script>";
        }
        $stmt->close();
    }
}
?>

<!-- Form HTML -->
<form method="post" enctype="multipart/form-data">
    <input type="text" name="no_surat" value="<?= htmlspecialchars($vno_surat) ?>" readonly>
    <button type="submit" name="bsimpan">Simpan</button>
</form>
