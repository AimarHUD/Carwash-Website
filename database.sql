 DROP DATABASE IF EXISTS db_carwash;
CREATE DATABASE db_carwash CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_carwash;

CREATE TABLE tb_admin (
  id_admin INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  nama_lengkap VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  level ENUM('super','admin') NOT NULL DEFAULT 'admin',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE tb_pelanggan (
  id_pelanggan INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  no_hp VARCHAR(25) NOT NULL,
  email VARCHAR(100),
  alamat TEXT,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  deleted_at TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE tb_kendaraan (
  id_kendaraan INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_pelanggan INT UNSIGNED NOT NULL,
  jenis_kendaraan ENUM('mobil','motor') NOT NULL,
  plat_nomor VARCHAR(25) NOT NULL,
  merk VARCHAR(50) NOT NULL,
  warna VARCHAR(30) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  deleted_at TIMESTAMP NULL DEFAULT NULL,
  CONSTRAINT fk_kendaraan_pelanggan FOREIGN KEY (id_pelanggan) REFERENCES tb_pelanggan(id_pelanggan) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE tb_layanan (
  id_layanan INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nama_layanan VARCHAR(100) NOT NULL,
  deskripsi TEXT,
  harga DECIMAL(12,2) NOT NULL DEFAULT 0,
  estimasi_waktu VARCHAR(50) NOT NULL,
  kategori ENUM('mobil','motor') NOT NULL DEFAULT 'mobil',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  deleted_at TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE tb_karyawan (
  id_karyawan INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  no_hp VARCHAR(25) NOT NULL,
  jabatan VARCHAR(50) NOT NULL,
  status_aktif TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  deleted_at TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE tb_transaksi (
  id_transaksi INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_pelanggan INT UNSIGNED NOT NULL,
  id_kendaraan INT UNSIGNED NOT NULL,
  id_karyawan INT UNSIGNED NOT NULL,
  tanggal_transaksi DATE NOT NULL,
  status ENUM('antri','proses','selesai','batal') NOT NULL DEFAULT 'antri',
  total_bayar DECIMAL(12,2) NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  deleted_at TIMESTAMP NULL DEFAULT NULL,
  CONSTRAINT fk_transaksi_pelanggan FOREIGN KEY (id_pelanggan) REFERENCES tb_pelanggan(id_pelanggan) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT fk_transaksi_kendaraan FOREIGN KEY (id_kendaraan) REFERENCES tb_kendaraan(id_kendaraan) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT fk_transaksi_karyawan FOREIGN KEY (id_karyawan) REFERENCES tb_karyawan(id_karyawan) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE tb_detail_transaksi (
  id_detail INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_transaksi INT UNSIGNED NOT NULL,
  id_layanan INT UNSIGNED NOT NULL,
  harga DECIMAL(12,2) NOT NULL,
  qty INT UNSIGNED NOT NULL DEFAULT 1,
  subtotal DECIMAL(12,2) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_detail_transaksi FOREIGN KEY (id_transaksi) REFERENCES tb_transaksi(id_transaksi) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_detail_layanan FOREIGN KEY (id_layanan) REFERENCES tb_layanan(id_layanan) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE tb_pembayaran (
  id_pembayaran INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_transaksi INT UNSIGNED NOT NULL,
  metode_bayar VARCHAR(50) NOT NULL,
  jumlah_bayar DECIMAL(12,2) NOT NULL DEFAULT 0,
  status_bayar ENUM('pending','lunas','gagal') NOT NULL DEFAULT 'pending',
  tanggal_bayar DATETIME NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_pembayaran_transaksi FOREIGN KEY (id_transaksi) REFERENCES tb_transaksi(id_transaksi) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE tb_company_profile (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nama_perusahaan VARCHAR(150) NOT NULL,
  logo VARCHAR(255) DEFAULT NULL,
  alamat TEXT NOT NULL,
  no_telp VARCHAR(50) NOT NULL,
  email VARCHAR(100) NOT NULL,
  jam_operasional VARCHAR(100) NOT NULL,
  deskripsi TEXT,
  visi TEXT,
  misi TEXT,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE tb_kontak (
  id_kontak INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  no_telp VARCHAR(25) NOT NULL,
  pesan TEXT NOT NULL,
  status ENUM('baru','dibaca') NOT NULL DEFAULT 'baru',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  deleted_at TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE tb_galeri (
  id_galeri INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  judul VARCHAR(150) NOT NULL,
  gambar VARCHAR(255) NOT NULL,
  keterangan TEXT,
  tanggal_upload DATE NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  deleted_at TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE tb_artikel (
  id_artikel INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  judul VARCHAR(255) NOT NULL,
  isi TEXT NOT NULL,
  image VARCHAR(255) DEFAULT NULL,
  penulis VARCHAR(100) NOT NULL,
  tanggal DATE NOT NULL,
  status ENUM('Draft','Publish') NOT NULL DEFAULT 'Draft',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO tb_admin (username, password, nama_lengkap, email, level) VALUES
('admin', '$2y$10$u8eLQH1TYqlBSA/quYVnJ.zDRCUWEvSMtNaxShKHpOYyHIeVQ2hnG', 'Administrator', 'admin@carwash.com', 'super');

INSERT INTO tb_company_profile (nama_perusahaan, logo, alamat, no_telp, email, jam_operasional, deskripsi, visi, misi) VALUES
('WashWoosh', NULL, 'Jl. Raya Dapa No.12, Jakarta', '081234567890', 'info@washwoosh.com', 'Senin - Minggu 08:00 - 19:00', 'Layanan cuci mobil dan motor cepat, bersih, dan ramah lingkungan.', 'Menjadi washwoosh terbaik di wilayah Dapa.', 'Memberikan layanan berkualitas dengan harga terjangkau.');

INSERT INTO tb_pelanggan (nama, no_hp, email, alamat) VALUES
('Budi Santoso', '081111222333', 'budi@example.com', 'Jl. Melati 5'),
('Siti Aminah', '082222333444', 'siti@example.com', 'Jl. Mawar 12');

INSERT INTO tb_kendaraan (id_pelanggan, jenis_kendaraan, plat_nomor, merk, warna) VALUES
(1, 'mobil', 'B 1234 CD', 'Toyota Avanza', 'Putih'),
(2, 'motor', 'B 5678 EF', 'Honda Vario', 'Hitam');

INSERT INTO tb_layanan (nama_layanan, deskripsi, harga, estimasi_waktu, kategori) VALUES
('Cuci Eksterior Mobil', 'Pencucian luar kendaraan dengan shampo khusus.', 50000.00, '30 menit', 'mobil'),
('Cuci Interior Mobil', 'Membersihkan bagian dalam kabin dan dashboard.', 70000.00, '40 menit', 'mobil'),
('Cuci Exterior Motor', 'Cuci luar motor dengan detail nozzle.', 30000.00, '20 menit', 'motor');

INSERT INTO tb_karyawan (nama, no_hp, jabatan, status_aktif) VALUES
('Andi Wijaya', '081333444555', 'Staff Lap', 1),
('Rina Febri', '081444555666', 'Kasir', 1);

INSERT INTO tb_transaksi (id_pelanggan, id_kendaraan, id_karyawan, tanggal_transaksi, status, total_bayar) VALUES
(1, 1, 1, CURDATE(), 'proses', 120000.00),
(2, 2, 1, CURDATE(), 'antri', 30000.00);

INSERT INTO tb_detail_transaksi (id_transaksi, id_layanan, harga, qty, subtotal) VALUES
(1, 1, 50000.00, 1, 50000.00),
(1, 2, 70000.00, 1, 70000.00),
(2, 3, 30000.00, 1, 30000.00);

INSERT INTO tb_pembayaran (id_transaksi, metode_bayar, jumlah_bayar, status_bayar, tanggal_bayar) VALUES
(1, 'Tunai', 120000.00, 'pending', NOW()),
(2, 'Transfer', 30000.00, 'pending', NOW());

INSERT INTO tb_kontak (nama, email, no_telp, pesan, status) VALUES
('Hari Purnomo', 'hari.purnomo@email.com', '089876543210', 'Halo, saya ingin menanyakan tentang paket cuci premium untuk mobil saya. Berapa harganya dan berapa lama prosesnya?', 'baru'),
('Dewi Lestari', 'dewi.lestari@email.com', '081234567890', 'Saya ingin booking layanan cuci motor untuk hari Minggu pukul 10:00. Apakah tersedia?', 'dibaca'),
('Roni Wijaya', 'roni.wijaya@email.com', '082987654321', 'Apakah ada diskon untuk member setia atau paket tahunan?', 'baru');

INSERT INTO tb_galeri (judul, gambar, keterangan, tanggal_upload) VALUES
('Cuci Mobil Premium', 'uploads/carwash-1.jpg', 'Mobil bersih kinclong setelah treatment.', CURDATE()),
('Cuci Motor Cepat', 'uploads/carwash-2.jpg', 'Motor dibersihkan sampai detail.', CURDATE());
