<?php
// --- SIMPAN DATA BARU / UPDATE DATA ---
$vnama_pengirim = "";
$valamat = "";
$vnomor_hp = "";
$vemail = "";
$error_email = "";

// Koneksi ke database 
include "config/koneksi.php";

if (isset($_POST['bsimpan'])) {
    // Ambil data POST dulu agar bisa dipakai kembali jika validasi gagal
    $vnama_pengirim = $_POST['nama_pengirim'];
    $valamat = $_POST['alamat'];
    $vnomor_hp = $_POST['nomor_hp'];
    $vemail = $_POST['email'];

    // Validasi email mengandung '@'
    if (strpos($vemail, '@') === false) {
        $error_email = "Email harus mengandung karakter '@'";
        // Tidak simpan data, tetap tampilkan form dengan error
    } else {
        if (isset($_GET['hal']) && $_GET['hal'] == "edit") {
            // UPDATE DATA
            $update = mysqli_query($koneksi, "UPDATE tbl_pengirim_surat SET 
                nama_pengirim = '".mysqli_real_escape_string($koneksi, $vnama_pengirim)."',
                alamat = '".mysqli_real_escape_string($koneksi, $valamat)."',
                nomor_hp = '".mysqli_real_escape_string($koneksi, $vnomor_hp)."',
                email = '".mysqli_real_escape_string($koneksi, $vemail)."'
                WHERE id_pengirim = '".(int)$_GET['id_pengirim']."'
            ");
            if ($update) {
                echo "<script>alert('Update Data Sukses');document.location='?halaman=pengirim_surat';</script>";
            } else {
                echo "<script>alert('Update Data Gagal');document.location='?halaman=pengirim_surat';</script>";
            }
        } else {
            // SIMPAN DATA BARU
            $simpan = mysqli_query($koneksi, "INSERT INTO tbl_pengirim_surat (nama_pengirim, alamat, nomor_hp, email) 
                VALUES (
                    '".mysqli_real_escape_string($koneksi, $vnama_pengirim)."', 
                    '".mysqli_real_escape_string($koneksi, $valamat)."', 
                    '".mysqli_real_escape_string($koneksi, $vnomor_hp)."', 
                    '".mysqli_real_escape_string($koneksi, $vemail)."'
                )");
            if ($simpan) {
                echo "<script>alert('Simpan Data Sukses');document.location='?halaman=pengirim_surat';</script>";
            } else {
                echo "<script>alert('Simpan Data Gagal');document.location='?halaman=pengirim_surat';</script>";
            }
        }
    }
}

// --- TAMPILKAN DATA UNTUK EDIT ---
if (isset($_GET['hal']) && $_GET['hal'] == "edit" && isset($_GET['id_pengirim'])) {
    $id = (int)$_GET['id_pengirim'];
    $tampil = mysqli_query($koneksi, "SELECT * FROM tbl_pengirim_surat WHERE id_pengirim = '$id'");
    $data = mysqli_fetch_array($tampil);
    if ($data) {
        // Jika belum submit form bsimpan, maka isi variabel dari database
        if (!isset($_POST['bsimpan'])) {
            $vnama_pengirim = $data['nama_pengirim'];
            $valamat = $data['alamat'];
            $vnomor_hp = $data['nomor_hp'];
            $vemail = $data['email'];
        }
    }
}

// --- HAPUS DATA ---
if (isset($_GET['hal']) && $_GET['hal'] == "hapus" && isset($_GET['id_pengirim'])) {
    $id = (int)$_GET['id_pengirim'];
    $hapus = mysqli_query($koneksi, "DELETE FROM tbl_pengirim_surat WHERE id_pengirim = '$id'");
    if ($hapus) {
        echo "<script>alert('Hapus Data Sukses');document.location='?halaman=pengirim_surat';</script>";
    }
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
    /* Style untuk merapikan ikon dan teks pada tombol */
    .btn .fa-solid {
        margin-right: 5px;
    }
</style>

<div class="container my-4">

    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><?= isset($_GET['hal']) && $_GET['hal'] == "edit" ? "Edit Data Pengirim Surat" : "Form Data Pengirim Surat" ?></h5>
        </div>
        <div class="card-body">
            <form method="post" action="" autocomplete="off" novalidate>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="nama_pengirim" class="form-label">Nama Pengirim</label>
                        <input type="text" class="form-control" id="nama_pengirim" name="nama_pengirim" value="<?= htmlspecialchars($vnama_pengirim) ?>" placeholder="Masukkan nama pengirim" required>
                    </div>
                    <div class="col-md-6">
                        <label for="nomor_hp" class="form-label">Nomor HP</label>
                        <input type="tel" class="form-control" id="nomor_hp" name="nomor_hp" value="<?= htmlspecialchars($vnomor_hp) ?>" placeholder="Masukkan nomor HP" required>
                    </div>
                    <div class="col-12">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat" required><?= htmlspecialchars($valamat) ?></textarea>
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control <?= $error_email ? 'is-invalid' : '' ?>" id="email" name="email" value="<?= htmlspecialchars($vemail) ?>" placeholder="Masukkan email" required>
                        <?php if ($error_email) : ?>
                            <div class="invalid-feedback">
                                <?= $error_email ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <button type="submit" name="bsimpan" class="btn btn-success me-2">
                            <i class="fa-solid fa-floppy-disk"></i> Simpan
                        </button>
                        <a href="?halaman=pengirim_surat" class="btn btn-secondary">
                            <i class="fa-solid fa-rotate-left"></i> Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0 mt-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Data Pengirim Surat</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 50px;">No</th>
                            <th>Nama Pengirim</th>
                            <th>Alamat</th>
                            <th>Nomor HP</th>
                            <th>Email</th>
                            <th class="text-center" style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $tampil = mysqli_query($koneksi, "SELECT * FROM tbl_pengirim_surat ORDER BY id_pengirim DESC");
                        $no = 1;
                        if (mysqli_num_rows($tampil) > 0) :
                            while ($data = mysqli_fetch_array($tampil)) :
                        ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td><?= htmlspecialchars($data['nama_pengirim']) ?></td>
                                <td><?= htmlspecialchars($data['alamat']) ?></td>
                                <td><?= htmlspecialchars($data['nomor_hp']) ?></td>
                                <td><?= htmlspecialchars($data['email']) ?></td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="?halaman=pengirim_surat&hal=edit&id_pengirim=<?= $data['id_pengirim'] ?>" class="btn btn-sm btn-warning" title="Edit">
                                            <i class="fa-solid fa-pen-to-square"></i> Edit
                                        </a>
                                        <a href="?halaman=pengirim_surat&hal=hapus&id_pengirim=<?= $data['id_pengirim'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')" title="Hapus">
                                            <i class="fa-solid fa-trash-can"></i> Hapus
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php
                            endwhile;
                        else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted">Data pengirim surat tidak ditemukan.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>