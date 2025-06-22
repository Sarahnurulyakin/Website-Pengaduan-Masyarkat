<?php
$title = 'Dashboard';

require '../layouts/header.php';
require '../layouts/navUser.php';
require '../../koneksi.php';

// Pastikan koneksi berhasil
if (!$conn) {
    die("Koneksi tidak tersedia.");
}

// Query hitung data
$jumlah_pengaduan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM pengaduan"))['total'];
$jumlah_tanggapan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM tanggapan"))['total'];
$jumlah_masyarakat = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM masyarakat"))['total'];
?>

<div class="container stats-cards">
    <div class="row">
        <!-- Card Pengaduan -->
        <div class="col-md-4 mb-4">
            <div class="card border-left-info border-bottom-info shadow-lg h-100 py-3">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="h5 mb-0 fw-bold text-info">
                                <?= $jumlah_pengaduan; ?> Pengaduan
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comment fa-2x text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Tanggapan -->
        <div class="col-md-4 mb-4">
            <div class="card border-left-success border-bottom-success shadow-lg h-100 py-3">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="h5 mb-0 fw-bold text-success">
                                <?= $jumlah_tanggapan; ?> Tanggapan
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Akun Masyarakat -->
        <div class="col-md-4 mb-4">
            <div class="card border-left-warning border-bottom-warning shadow-lg h-100 py-3">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="h5 mb-0 fw-bold text-warning">
                                <?= $jumlah_masyarakat; ?> Akun Masyarakat
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
