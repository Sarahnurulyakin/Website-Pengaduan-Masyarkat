<?php

ini_set('max_execution_time', 10); // waktu maksimum 10 detik

// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "laporan");

// Cek koneksi berhasil atau gagal
if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

// Mulai session jika belum
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function tambahAduan($data) {
    global $conn;

    $tgl    = htmlspecialchars($data["tgl_pengaduan"]);
    $nik    = htmlspecialchars($data["nik"]);
    $isi    = htmlspecialchars($data["isi_laporan"]);
    $status = htmlspecialchars($data["status"]);

    // Buat folder upload jika belum ada
    $uploadDir = '../../assets/uploads/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Cek apakah file foto ada
    if (!isset($_FILES['foto']) || $_FILES['foto']['error'] !== 0) {
        return -1; // Tidak ada file / error upload
    }

    $namaFile   = $_FILES['foto']['name'];
    $tmpName    = $_FILES['foto']['tmp_name'];
    $ukuranFile = $_FILES['foto']['size'];

    $extValid = ['jpg', 'jpeg', 'png'];
    $extFile  = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));

    // Validasi ekstensi
    if (!in_array($extFile, $extValid)) {
        return -2; // ekstensi salah
    }

    // Validasi ukuran
    if ($ukuranFile > 2 * 1024 * 1024) {
        return -3; // file terlalu besar
    }

    // Nama file unik
    $namaBaru = uniqid() . '.' . $extFile;
    $pathSimpan = $uploadDir . $namaBaru;

    // Simpan file
    if (!move_uploaded_file($tmpName, $pathSimpan)) {
        return -4; // gagal upload
    }

    // Simpan ke DB
    $query = "INSERT INTO pengaduan VALUES (NULL, '$tgl', '$nik', '$isi', '$namaBaru', '$status')";
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

// Verifikasi aduan oleh petugas
function verify($data) {
    global $conn;

    $id     = htmlspecialchars($data["id_pengaduan"]);
    $tgl    = htmlspecialchars($data["tgl_pengaduan"]);
    $nik    = htmlspecialchars($data["nik"]);
    $isi    = htmlspecialchars($data["isi_laporan"]);
    $foto   = htmlspecialchars($data["foto"]);
    $status = htmlspecialchars($data["status"]);

    $query = "UPDATE pengaduan SET
                tgl_pengaduan = '$tgl',
                nik = '$nik',
                isi_laporan = '$isi',
                foto = '$foto',
                status = '$status'
              WHERE id_pengaduan = '$id'";
    
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

// Tambah tanggapan dari petugasfunction tanggapan($data) {
    function tanggapan($data)
    {
        global $conn;
    
        $id_pengaduan   = htmlspecialchars($data['id_pengaduan']);
        $tgl_tanggapan  = htmlspecialchars($data['tgl_tanggapan']);
        $isi_tanggapan  = htmlspecialchars($data['tanggapan']);
        $id_petugas     = htmlspecialchars($data['id_petugas']);
    
        // Cek apakah sudah ada tanggapan
        $cek = mysqli_query($conn, "SELECT * FROM tanggapan WHERE id_pengaduan = '$id_pengaduan'");
        if (mysqli_num_rows($cek) > 0) {
            return 0; // Sudah ditanggapi
        }
    
        // Pastikan id_petugas valid
        $cekPetugas = mysqli_query($conn, "SELECT * FROM petugas WHERE id_petugas = '$id_petugas'");
        if (mysqli_num_rows($cekPetugas) == 0) {
            echo "<script>alert('ID Petugas tidak ditemukan!');</script>";
            return 0;
        }
    
        // Insert tanggapan
        $query = "INSERT INTO tanggapan (id_pengaduan, tgl_tanggapan, tanggapan, id_petugas)
                  VALUES ('$id_pengaduan', '$tgl_tanggapan', '$isi_tanggapan', '$id_petugas')";
        $insert = mysqli_query($conn, $query);
    
        if (!$insert) {
            echo "<pre>Gagal Insert: " . mysqli_error($conn) . "</pre>";
            return 0;
        }
    
        // Update status pengaduan
        mysqli_query($conn, "UPDATE pengaduan SET status = 'selesai' WHERE id_pengaduan = '$id_pengaduan'");
        return mysqli_affected_rows($conn);
    }
    

  


// Registrasi masyarakat
function regisUser($data) {
    global $conn;

    $nik      = htmlspecialchars($data["nik"]);
    $nama     = htmlspecialchars($data["nama"]);
    $username = htmlspecialchars($data["username"]);
    $password = htmlspecialchars($data["password"]);
    $telp     = htmlspecialchars($data["telp"]);

    $query = "INSERT INTO masyarakat VALUES ('$nik', '$nama', '$username', '$password', '$telp')";
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

// Tambah petugas
function addPetugas($data) {
    global $conn;

    $nama     = htmlspecialchars($data["nama_petugas"]);
    $username = htmlspecialchars($data["username"]);
    $password = htmlspecialchars($data["password"]);
    $telp     = htmlspecialchars($data["telp"]);
    $level    = htmlspecialchars($data["level"]);

    $query = "INSERT INTO petugas VALUES (NULL, '$nama', '$username', '$password', '$telp', '$level')";
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

// Edit data petugas
function editPetugas($data) {
    global $conn;

    $id       = htmlspecialchars($data["id_petugas"]);
    $nama     = htmlspecialchars($data["nama_petugas"]);
    $username = htmlspecialchars($data["username"]);
    $password = htmlspecialchars($data["password"]);
    $telp     = htmlspecialchars($data["telp"]);
    $level    = htmlspecialchars($data["level"]);

    $query = "UPDATE petugas SET
                nama_petugas = '$nama',
                username = '$username',
                password = '$password',
                telp = '$telp',
                level = '$level'
              WHERE id_petugas = '$id'";
    
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

// Hapus petugas
function deletePetugas($id) {
    global $conn;

    mysqli_query($conn, "DELETE FROM petugas WHERE id_petugas = $id");

    return mysqli_affected_rows($conn);
}
?>
