<?php
// pastikan koneksi tersedia
if (!isset($koneksi)) {
    include "config/koneksi.php";
}

$vnama_departemen = "";

// SIMPAN / UPDATE
if (isset($_POST['bsimpan'])) {
    $nama = trim($_POST['nama_departemen']);

    if ($nama === "") {
        echo "<script>alert('Nama departemen tidak boleh kosong.'); window.history.back();</script>";
        exit;
    }

    if (isset($_GET['hal']) && $_GET['hal'] === "edit" && isset($_GET['id_departemen'])) {
        $id = (int) $_GET['id_departemen'];
        $stmt = $koneksi->prepare("UPDATE tbl_departemen SET nama_departemen = ? WHERE id_departemen = ?");
        $stmt->bind_param("si", $nama, $id);
        $ok = $stmt->execute();
        $err = $stmt->error;
        $stmt->close();

        if ($ok) {
            echo "<script>alert('Update Data Sukses'); window.location='?halaman=departemen';</script>";
            exit;
        } else {
            echo "<script>alert('Update Data Gagal: ' + " . json_encode($err) . "); window.history.back();</script>";
            exit;
        }
    } else {
        $stmt = $koneksi->prepare("INSERT INTO tbl_departemen (nama_departemen) VALUES (?)");
        $stmt->bind_param("s", $nama);
        $ok = $stmt->execute();
        $err = $stmt->error;
        $stmt->close();

        if ($ok) {
            echo "<script>alert('Simpan Data Sukses'); window.location='?halaman=departemen';</script>";
            exit;
        } else {
            echo "<script>alert('Simpan Data Gagal: ' + " . json_encode($err) . "); window.history.back();</script>";
            exit;
        }
    }
}

// TAMPILKAN DATA UNTUK EDIT
if (isset($_GET['hal']) && $_GET['hal'] === "edit" && isset($_GET['id_departemen'])) {
    $id = (int) $_GET['id_departemen'];
    $stmt = $koneksi->prepare("SELECT nama_departemen FROM tbl_departemen WHERE id_departemen = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($vnama_departemen);
    $stmt->fetch();
    $stmt->close();
}

// HAPUS DATA
if (isset($_GET['hal']) && $_GET['hal'] === "hapus" && isset($_GET['id_departemen'])) {
    $id = (int) $_GET['id_departemen'];
    $stmt = $koneksi->prepare("DELETE FROM tbl_departemen WHERE id_departemen = ?");
    $stmt->bind_param("i", $id);
    $ok = $stmt->execute();
    $err = $stmt->error;
    $stmt->close();

    if ($ok) {
        echo "<script>alert('Hapus Data Sukses'); window.location='?halaman=departemen';</script>";
        exit;
    } else {
        echo "<script>alert('Hapus Data Gagal: ' + " . json_encode($err) . "); window.history.back();</script>";
        exit;
    }
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

<style>
/* Style untuk merapikan ikon dan teks */
.btn .fa-solid {
    margin-right: 5px;
}

/* Transisi halus untuk semua icon di tombol */
.btn i {
    transition: transform 0.28s ease, color 0.2s ease;
}

/* Efek hover tiap tombol */
.btn-success:hover i { transform: translateY(-3px) rotate(-8deg) scale(1.12); color: #eaffea; }
.btn-secondary:hover i { transform: translateY(-3px) rotate(8deg) scale(1.06); color: #f0f0f0; }
.btn-warning:hover i { transform: translateY(-4px) rotate(-6deg) scale(1.08); color: #fff7e6; }
.btn-danger:hover i { animation: shake 0.42s linear; color: #ffeaea; }

/* Animasi shake khusus tombol hapus */
@keyframes shake {
    0% { transform: translateX(0) rotate(0); }
    20% { transform: translateX(-4px) rotate(-4deg); }
    40% { transform: translateX(4px) rotate(4deg); }
    60% { transform: translateX(-3px) rotate(-2deg); }
    80% { transform: translateX(3px) rotate(2deg); }
    100% { transform: translateX(0) rotate(0); }
}
</style>

<div class="card shadow-sm border-0 mt-3">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><?= (isset($_GET['hal']) && $_GET['hal'] === "edit") ? "Edit Departemen" : "Tambah Departemen" ?></h5>
    </div>
    <div class="card-body">
        <form method="post" action="">
            <div class="mb-3">
                <label for="nama_departemen" class="form-label">Nama Departemen</label>
                <input type="text" class="form-control" id="nama_departemen" name="nama_departemen"
                       value="<?= htmlspecialchars($vnama_departemen) ?>" placeholder="Masukkan nama departemen" required>
            </div>
            <div>
                <button type="submit" name="bsimpan" class="btn btn-success">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan
                </button>
                <a href="?halaman=departemen" class="btn btn-secondary">
                    <i class="fa-solid fa-rotate-left"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0 mt-4">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0">Data Departemen</h5>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th class="text-center" width="50">No</th>
                    <th>Nama Departemen</th>
                    <th class="text-center" width="180">Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $res = $koneksi->query("SELECT id_departemen, nama_departemen FROM tbl_departemen ORDER BY id_departemen DESC");
            $no = 1;
            while ($row = $res->fetch_assoc()) :
            ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['nama_departemen']) ?></td>
                    <td class="text-center">
                        <a href="?halaman=departemen&hal=edit&id_departemen=<?= (int)$row['id_departemen'] ?>"
                           class="btn btn-sm btn-warning">
                            <i class="fa-solid fa-pen-to-square"></i> Edit
                        </a>
                        <a href="?halaman=departemen&hal=hapus&id_departemen=<?= (int)$row['id_departemen'] ?>"
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Yakin ingin menghapus data ini?')">
                            <i class="fa-solid fa-trash-can"></i> Hapus
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    <?php if (isset($_GET['hal']) && $_GET['hal'] === "edit") : ?>
        const inputDepartemen = document.getElementById("nama_departemen");
        if (inputDepartemen) {
            inputDepartemen.focus();
            inputDepartemen.select();
            inputDepartemen.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    <?php endif; ?>
});
</script>