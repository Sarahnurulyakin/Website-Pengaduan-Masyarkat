<?php
session_start();
$title = 'Buat Laporan';

require '../../public/app.php';
require '../layouts/header.php';
require '../layouts/navUser.php';

if (isset($_POST["submit"])) {
  $hasil = tambahAduan($_POST);
  if ($hasil > 0) {
    $sukses = true;
  } elseif ($hasil == -1) {
    $error = '❌ File tidak dikirim atau terjadi error saat upload.';
  } elseif ($hasil == -2) {
    $error = '⚠️ Ekstensi file harus JPG, JPEG, atau PNG.';
  } elseif ($hasil == -3) {
    $error = '⚠️ Ukuran file maksimal 2MB.';
  } elseif ($hasil == -4) {
    $error = '❌ Gagal menyimpan file ke server.';
  } else {
    $error = '❌ Maaf, laporan gagal dikirim. (Kode: ' . $hasil . ')';
  }
}
?>

<h3 class="text-gray-900" data-aos="fade-left">Buat laporan keluh kesah anda disini</h3>
<hr>
<div class="card border-bottom-primary shadow" data-aos="fade-up">
  <div class="card-body">
    <div class="container">
      <?php if (isset($sukses)) : ?>
        <div class="alert alert-success">✅ Laporan berhasil dikirim, terima kasih!</div>
      <?php elseif (isset($error)) : ?>
        <div class="alert alert-danger"><?= $error ?></div>
      <?php endif; ?>

      <div class="row">
        <div class="col-4">
          <img src="../../assets/img/img-buat-laporan.svg" width="300" alt="">
        </div>
        <div class="col-8 mt-2">
          <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
              <label for="tanggal">Tanggal</label>
              <input type="date" class="form-control" name="tgl_pengaduan" required>

              <label for="nik">NIK</label>
              <input type="number" class="form-control" name="nik" required>

              <label for="foto">Foto (JPG/PNG, max 2MB)</label>
              <input type="file" class="form-control-file" name="foto" id="foto" accept="image/png, image/jpeg" required>
              <img id="preview" src="#" style="max-width: 200px; display: none; margin-top: 10px;">
            </div>
            <div class="form-group">
              <label for="isi">Isi Laporan</label>
              <textarea class="form-control" name="isi_laporan" rows="5" required></textarea>
              <input type="hidden" name="status" value="proses">
              <button type="submit" name="submit" class="btn btn-primary mt-3">Kirim</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.getElementById("foto").onchange = function (event) {
  const [file] = event.target.files;
  if (file) {
    const img = document.getElementById("preview");
    img.src = URL.createObjectURL(file);
    img.style.display = "block";
  }
};
</script>

<?php require '../layouts/footer.php'; ?>
