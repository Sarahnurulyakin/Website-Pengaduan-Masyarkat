<?php
session_start();
$title = 'Tanggapan';

require '../../public/app.php';
require '../layouts/header.php';
require '../layouts/navPetugas.php';

// Cek login petugas
if (!isset($_SESSION['id_petugas'])) {
    header('Location: ../auth/login.php');
    exit;
}

// Ambil ID pengaduan dari URL
$id = isset($_GET["id_pengaduan"]) ? intval($_GET["id_pengaduan"]) : null;

if (!$id) {
    echo "<div class='alert alert-danger'>ID pengaduan tidak valid.</div>";
    require '../layouts/footer.php';
    exit;
}

// Ambil data pengaduan
$result = mysqli_query($conn, "SELECT * FROM pengaduan WHERE id_pengaduan = $id");
$row = mysqli_fetch_assoc($result);

if (!$row) {
    echo "<div class='alert alert-danger'>Laporan tidak ditemukan.</div>";
    require '../layouts/footer.php';
    exit;
}

// Cek apakah sudah ditanggapi
$cek = mysqli_query($conn, "SELECT * FROM tanggapan WHERE id_pengaduan = $id");
$sudahDitanggapi = mysqli_num_rows($cek) > 0;

// Handle form submit
if (isset($_POST["submit"])) {
    $id_petugas = $_SESSION['id_petugas'];

    if (!$sudahDitanggapi) {
        $_POST['id_pengaduan'] = $id;
        $_POST['id_petugas'] = $id_petugas;

        $hasil = tanggapan($_POST);

        if ($hasil > 0) {
            $sukses = true;
        } else {
            $error = "Gagal menyimpan tanggapan. Silakan coba lagi.";
        }
    } else {
        $error = "Laporan sudah pernah ditanggapi.";
    }
}
?>

<div class="d-flex justify-content-center mt-4">
    <div class="card w-75 shadow-sm">
        <div class="card-body">
            <?php if (isset($sukses)) : ?>
                <div class="alert alert-success">✅ Tanggapan berhasil disimpan.</div>
            <?php elseif (isset($error)) : ?>
                <div class="alert alert-danger">❌ <?= $error; ?></div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-6 d-flex align-items-center justify-content-center">
                    <img src="../../assets/img/tanggapan.svg" width="300" alt="Ilustrasi Tanggapan">
                </div>
                <div class="col-md-6">
                    <form action="" method="POST">
                        <input type="hidden" name="id_pengaduan" value="<?= htmlspecialchars($row['id_pengaduan']); ?>">

                        <div class="form-group">
                            <label><strong>NIK Pengadu</strong></label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($row['nik']); ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label><strong>Tanggal Tanggapan</strong></label>
                            <input type="date" name="tgl_tanggapan" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label><strong>Isi Laporan</strong></label>
                            <textarea class="form-control" rows="3" readonly><?= htmlspecialchars($row['isi_laporan']); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label><strong>Tanggapan</strong></label>
                            <textarea class="form-control" name="tanggapan" rows="4" required></textarea>
                        </div>

                        <div class="form-group text-right">
                            <?php if (!$sudahDitanggapi) : ?>
                                <button type="submit" name="submit" class="btn btn-primary">Kirim Tanggapan</button>
                            <?php else : ?>
                                <button class="btn btn-secondary" disabled>Laporan sudah ditanggapi</button>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require '../layouts/footer.php'; ?>
