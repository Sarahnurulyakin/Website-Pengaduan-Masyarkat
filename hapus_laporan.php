<?php
// tanggapan.php (Halaman daftar tanggapan)

session_start();
require '../../public/app.php'; // Pastikan koneksi dan session tersedia

// Ambil data tanggapan
$tanggapan = mysqli_query($conn, "SELECT t.*, p.isi_laporan FROM tanggapan t JOIN pengaduan p ON t.id_pengaduan = p.id_pengaduan");

require '../layouts/header.php';
?>

<div class="container mt-5">
    <h3>Daftar Tanggapan</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Isi Tanggapan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; while($row = mysqli_fetch_assoc($tanggapan)) : ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($row['tanggapan']); ?></td>
                    <td>
                        <button class="btn btn-danger btn-sm hapus-tanggapan"
                                data-id="<?= $row['id_tanggapan']; ?>"
                                data-isi="<?= htmlspecialchars($row['tanggapan']); ?>">
                            Hapus
                        </button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php require '../layouts/footer.php'; ?>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const tombolHapus = document.querySelectorAll(".hapus-tanggapan");

    tombolHapus.forEach(btn => {
        btn.addEventListener("click", function () {
            const idTanggapan = this.dataset.id;
            const isiTanggapan = this.dataset.isi;

            const konfirmasi = confirm(
                `Apakah Anda yakin ingin menghapus tanggapan berikut?\n\n"${isiTanggapan}"\n\nTindakan ini tidak dapat dibatalkan dan akan mengubah status pengaduan menjadi belum ditanggapi.`
            );

            if (konfirmasi) {
                fetch("../petugas/hapus_tanggapan.php", { // pastikan path ini sesuai lokasi file PHP
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ id_tanggapan: idTanggapan })
                })
                .then(response => response.json())
                .then(data => {
                    console.log("Response dari server:", data);
                    if (data.success) {
                        alert("\u2705 " + data.message);
                        location.reload();
                    } else {
                        alert("\u274C Gagal: " + data.message);
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("Terjadi kesalahan teknis saat menghapus tanggapan.");
                });
            }
        });
    });
});
</script>