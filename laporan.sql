DROP DATABASE IF EXISTS laporan;
CREATE DATABASE laporan;
USE laporan;

-- Tabel masyarakat
CREATE TABLE masyarakat (
  nik VARCHAR(16) NOT NULL PRIMARY KEY,
  nama VARCHAR(36) NOT NULL,
  username VARCHAR(25) NOT NULL UNIQUE,
  password VARCHAR(32) NOT NULL,
  telp VARCHAR(13) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO masyarakat (nik, nama, username, password, telp) VALUES
('8781921', 'jonoeverybody', 'jonsky', '123', '08767121234'),
('9979132', 'alip', 'alipudin', '123', '908913210'),
('81388813', 'Muhammad Reza Aditya', 'rejakartans', '123', '085772867820'),
('97788123', 'johanes', 'johan', '123', '081382829789'),
('98712382', 'fahri', 'ipqy', '1234', '0878771321');

-- Tabel petugas
CREATE TABLE petugas (
  id_petugas INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  nama_petugas VARCHAR(35) NOT NULL,
  username VARCHAR(25) NOT NULL UNIQUE,
  password VARCHAR(32) NOT NULL,
  telp VARCHAR(13) NOT NULL,
  level ENUM('admin','petugas') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO petugas (id_petugas, nama_petugas, username, password, telp, level) VALUES
(1, 'Yildi Andriana', 'yildi', '123', '0879871231', 'petugas'),
(2, 'admin', 'admin', 'admin', '9870971312', 'admin');

-- Tabel pengaduan
CREATE TABLE pengaduan (
  id_pengaduan INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  tgl_pengaduan DATE NOT NULL,
  nik VARCHAR(16) NOT NULL,
  isi_laporan TEXT NOT NULL,
  foto VARCHAR(255) NOT NULL,
  status ENUM('0','proses','selesai') NOT NULL DEFAULT '0',
  FOREIGN KEY (nik) REFERENCES masyarakat(nik) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO pengaduan (id_pengaduan, tgl_pengaduan, nik, isi_laporan, foto, status) VALUES
(3, '2025-05-12', '8781921', 'nyobaa ngelaporr', 'hc.jpg', 'selesai'),
(4, '2025-05-21', '9979132', 'nyoba ngelapor lagi boosss', 'suntuk.jpg', 'proses'),
(5, '2025-05-21', '81388813', 'laporan baruuuuu', 'login.svg', 'selesai'),
(6, '2025-05-21', '97788123', 'ngelaporr lagii nih boss', 'img-buat-laporan.svg', 'selesai'),
(7, '2025-05-22', '98712382', 'hallo saya mao curhat dongg tapii... boongg', 'img-dashboard-user.svg', 'selesai'),
(8, '2025-05-21', '8781921', 'laporr 86!', 'tanggapan.svg', 'selesai'),
(9, '2025-05-21', '9979132', 'laporr lagii nihh bosss', 'error-404-monochrome.svg', 'selesai'),
(10, '2025-05-23', '81388813', 'percobaan laporan terakhir', 'santay.svg', 'selesai');

-- Tabel tanggapan
CREATE TABLE tanggapan (
  id_tanggapan INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  id_pengaduan INT(11) NOT NULL,
  tgl_tanggapan DATE NOT NULL,
  tanggapan TEXT NOT NULL,
  id_petugas INT(11) NOT NULL,
  FOREIGN KEY (id_pengaduan) REFERENCES pengaduan(id_pengaduan) ON DELETE CASCADE,
  FOREIGN KEY (id_petugas) REFERENCES petugas(id_petugas) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO tanggapan (id_tanggapan, id_pengaduan, tgl_tanggapan, tanggapan, id_petugas) VALUES
(1, 3, '2025-06-01', 'laporan sudah selesai', 1),
(2, 4, '2025-06-02', 'laporan sedang diproses', 1),
(3, 5, '2025-06-02', 'laporan sudah selesai', 2),
(4, 6, '2025-06-02', 'laporan sudah selesai', 2),
(5, 7, '2025-06-03', 'laporan sudah selesai', 2),
(6, 8, '2025-06-03', 'laporan sudah selesai', 2),
(7, 9, '2025-06-03', 'laporan sudah selesai', 2),
(8, 10, '2025-06-04', 'laporan sudah selesai', 2);