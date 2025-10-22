<?php

include "config/koneksi.php";

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

if (isset($_GET['hal'], $_GET['id_arsip']) && $_GET['hal'] === "edit") {
    $id_arsip = (int) $_GET['id_arsip'];
    $stmt = $koneksi->prepare("SELECT * FROM tbl_arsip WHERE id_arsip = ?");
    $stmt->bind_param("i", $id_arsip);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($data = $result->fetch_assoc()) {
        $vno_surat = $data['no_surat'];
        $vtanggal_surat = date('Y-m-d', strtotime($data['tanggal_surat']));
        $vtanggal_diterima = date('Y-m-d', strtotime($data['tanggal_diterima']));
        $vperihal = $data['perihal'];
        $vfile_surat = $data['file_surat'];
        $vid_departemen = $data['id_departemen'];
        $vid_pengirim = $data['id_pengirim'];
    }
    $stmt->close();
}

if (isset($_POST['bsimpan'])) {
    // ... (Logika simpan data PHP)
    $fileName = $vfile_surat;
    if (!empty($_FILES['file_surat']['name'])) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        if ($vfile_surat && file_exists($targetDir . $vfile_surat)) {
            unlink($targetDir . $vfile_surat);
        }
        $fileNameRaw = time() . "_" . basename($_FILES["file_surat"]["name"]);
        $fileName = preg_replace("/[^a-zA-Z0-9_\.\-]/", "", $fileNameRaw);
        $targetFilePath = $targetDir . $fileName;
        $allowedTypes = ['application/pdf', 'image/jpeg', 'image/png'];
        $fileType = mime_content_type($_FILES["file_surat"]["tmp_name"]);
        if (!in_array($fileType, $allowedTypes)) {
            echo "<script>alert('Format file tidak didukung. Hanya PDF, JPG, PNG yang diizinkan.');</script>";
        } else {
            move_uploaded_file($_FILES["file_surat"]["tmp_name"], $targetFilePath);
        }
    }
    $no_surat = trim($_POST['no_surat']);
    $perihal = trim($_POST['perihal']);
    $id_departemen = trim($_POST['id_departemen']);
    $id_pengirim = trim($_POST['id_pengirim']);
    $tanggal_surat = trim($_POST['tanggal_surat']);
    $tanggal_diterima = trim($_POST['tanggal_diterima']);
    if (!$tanggal_surat || !$tanggal_diterima) {
        echo "<script>alert('Format tanggal salah, harap isi tanggal dengan benar.');</script>";
    } else {
        if (isset($_GET['hal'], $_GET['id_arsip']) && $_GET['hal'] == "edit") {
            $id_arsip = (int) $_GET['id_arsip'];
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
}

if (isset($_GET['hal'], $_GET['id_arsip']) && $_GET['hal'] == "hapus") {
    $id_arsip = (int)$_GET['id_arsip'];
    $stmt = $koneksi->prepare("SELECT file_surat FROM tbl_arsip WHERE id_arsip=?");
    $stmt->bind_param("i", $id_arsip);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($data = $result->fetch_assoc()) {
        if ($data['file_surat'] && file_exists('uploads/' . $data['file_surat'])) {
            unlink('uploads/' . $data['file_surat']);
        }
    }
    $stmt->close();

    $delete_stmt = $koneksi->prepare("DELETE FROM tbl_arsip WHERE id_arsip=?");
    $delete_stmt->bind_param("i", $id_arsip);
    if ($delete_stmt->execute()) {
        echo "<script>alert('Data berhasil dihapus.'); window.location='$halaman_utama';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data.'); window.location='$halaman_utama';</script>";
    }
    $delete_stmt->close();
    exit;
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
    .btn .fa-solid {
        margin-right: 5px;
    }
    .icon-link {
        display: inline-block;
    }
    .icon-link i {
        font-size: 1.75rem;
        transition: transform 0.2s ease-in-out;
    }
    .icon-link:hover i {
        transform: scale(1.15);
    }
    .fa-file-pdf { color: #dc3545; }
    .fa-image { color: #007bff; }
    .fa-file-lines { color: #6c757d; }
</style>

<div class="container my-4">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><?= (isset($_GET['hal'], $_GET['id_arsip']) && $_GET['hal'] === 'edit') ? 'Edit Arsip Surat' : 'Form Arsip Surat' ?></h5>
        </div>
        <div class="card-body">
            <form method="post" enctype="multipart/form-data" autocomplete="off" novalidate>
                <div class="mb-3">
                    <label for="no_surat" class="form-label">No Surat</label>
                    <input type="text" class="form-control" id="no_surat" name="no_surat" value="<?= htmlspecialchars($vno_surat) ?>" readonly>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="tanggal_surat" class="form-label">Tanggal Surat</label>
                        <input type="date" class="form-control" id="tanggal_surat" name="tanggal_surat" value="<?= htmlspecialchars($vtanggal_surat) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="tanggal_diterima" class="form-label">Tanggal Diterima</label>
                        <input type="date" class="form-control" id="tanggal_diterima" name="tanggal_diterima" value="<?= htmlspecialchars($vtanggal_diterima) ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="perihal" class="form-label">Perihal</label>
                    <input type="text" class="form-control" id="perihal" name="perihal" placeholder="Masukkan perihal" value="<?= htmlspecialchars($vperihal) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="file_surat" class="form-label">File Surat (PDF/JPG/PNG)</label>
                    <input class="form-control" type="file" id="file_surat" name="file_surat" accept=".pdf,.jpg,.jpeg,.png">
                    <?php if ($vfile_surat) : ?>
                        <small>File saat ini: <a href="uploads/<?= htmlspecialchars($vfile_surat) ?>" target="_blank" rel="noopener noreferrer"><?= htmlspecialchars($vfile_surat) ?></a></small>
                    <?php endif; ?>
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="id_departemen" class="form-label">Departemen</label>
                        <select class="form-select" id="id_departemen" name="id_departemen" required>
                            <option value="">-- Pilih Departemen --</option>
                            <?php mysqli_data_seek($departemen, 0); while ($d = mysqli_fetch_assoc($departemen)) : ?>
                                <option value="<?= $d['id_departemen'] ?>" <?= ($vid_departemen == $d['id_departemen']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($d['nama_departemen']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="id_pengirim" class="form-label">Pengirim Surat</label>
                        <select class="form-select" id="id_pengirim" name="id_pengirim" required>
                            <option value="">-- Pilih Pengirim Surat --</option>
                            <?php mysqli_data_seek($pengirim, 0); while ($p = mysqli_fetch_assoc($pengirim)) : ?>
                                <option value="<?= $p['id_pengirim'] ?>" <?= ($vid_pengirim == $p['id_pengirim']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($p['nama_pengirim']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <div>
                    <button type="submit" name="bsimpan" class="btn btn-success"><i class="fa-solid fa-floppy-disk"></i>Simpan</button>
                    <a href="<?= $halaman_utama ?>" class="btn btn-secondary"><i class="fa-solid fa-rotate-left"></i>Batal</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0 mt-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Data Arsip Surat</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 50px;">No.</th>
                            <th>No Surat</th>
                            <th>Tanggal Surat</th>
                            <th>Tanggal Diterima</th>
                            <th>Perihal</th>
                            <th>Departemen</th>
                            <th>Pengirim</th>
                            <th class="text-center">File</th>
                            <th class="text-center" style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT a.*, d.nama_departemen, p.nama_pengirim
                                  FROM tbl_arsip a
                                  LEFT JOIN tbl_departemen d ON a.id_departemen = d.id_departemen
                                  LEFT JOIN tbl_pengirim_surat p ON a.id_pengirim = p.id_pengirim
                                  ORDER BY a.id_arsip DESC";

                        $tampil = mysqli_query($koneksi, $query);
                        $no = 1;
                        if(mysqli_num_rows($tampil) > 0):
                            while ($data = mysqli_fetch_assoc($tampil)):
                        ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td><?= htmlspecialchars($data['no_surat']) ?></td>
                            <td><?= htmlspecialchars(date('d-m-Y', strtotime($data['tanggal_surat']))) ?></td>
                            <td><?= htmlspecialchars(date('d-m-Y', strtotime($data['tanggal_diterima']))) ?></td>
                            <td><?= htmlspecialchars($data['perihal']) ?></td>
                            <td><?= htmlspecialchars($data['nama_departemen']) ?></td>
                            <td><?= htmlspecialchars($data['nama_pengirim']) ?></td>
                            <td class="text-center">
                                <?php if ($data['file_surat']):
                                    $file_extension = strtolower(pathinfo($data['file_surat'], PATHINFO_EXTENSION));
                                    $icon_class = 'fa-solid fa-file-lines'; // Default
                                    if ($file_extension == 'pdf') {
                                        $icon_class = 'fa-solid fa-file-pdf';
                                    } elseif (in_array($file_extension, ['jpg', 'jpeg', 'png'])) {
                                        $icon_class = 'fa-solid fa-image';
                                    }
                                ?>
                                    <a href="uploads/<?= htmlspecialchars($data['file_surat']) ?>" target="_blank" class="icon-link" title="Lihat: <?= htmlspecialchars($data['file_surat']) ?>">
                                        <i class="<?= $icon_class ?>"></i>
                                    </a>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="<?= $halaman_utama ?>&hal=edit&id_arsip=<?= $data['id_arsip'] ?>" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                    </a>
                                    <a href="<?= $halaman_utama ?>&hal=hapus&id_arsip=<?= $data['id_arsip'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')" title="Hapus">
                                        <i class="fa-solid fa-trash-can"></i> Hapus
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php
                            endwhile;
                        else:
                        ?>
                        <tr>
                            <td colspan="9" class="text-center text-muted">Data arsip surat tidak ditemukan.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Tooltip Bootstrap
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.forEach(tooltipTriggerEl => {
        new bootstrap.Tooltip(tooltipTriggerEl)
    });
</script>