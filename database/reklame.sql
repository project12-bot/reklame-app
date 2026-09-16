-- =========================================================
-- Database: db_reklame
-- Sistem Informasi Pemesanan & Produksi Reklame
-- =========================================================

CREATE DATABASE IF NOT EXISTS `db_reklame` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `db_reklame`;

-- ---------------------------------------------------------
-- Tabel users (semua pengguna sistem, dibedakan oleh role)
-- ---------------------------------------------------------
CREATE TABLE `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `nama_lengkap` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) DEFAULT NULL,
  `no_hp` VARCHAR(20) DEFAULT NULL,
  `role` ENUM('admin','customer','desainer','direktur','accounting','kepala_produksi') NOT NULL,
  `status` ENUM('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `foto` VARCHAR(255) DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Tabel customers (detail tambahan untuk user ber-role customer)
-- ---------------------------------------------------------
CREATE TABLE `customers` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `nama_perusahaan` VARCHAR(150) DEFAULT NULL,
  `alamat` TEXT DEFAULT NULL,
  `no_hp` VARCHAR(20) DEFAULT NULL,
  `email` VARCHAR(100) DEFAULT NULL,
  `kontak_person` VARCHAR(100) DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_customers_user` (`user_id`),
  CONSTRAINT `fk_customers_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Tabel jenis_reklame (master jenis reklame / produk)
-- ---------------------------------------------------------
CREATE TABLE `jenis_reklame` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_jenis` VARCHAR(100) NOT NULL,
  `deskripsi` TEXT DEFAULT NULL,
  `satuan` VARCHAR(30) DEFAULT 'unit',
  `harga_dasar` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `status` ENUM('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Tabel pemesanan (order utama, menjadi pusat alur kerja)
-- ---------------------------------------------------------
CREATE TABLE `pemesanan` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `kode_pemesanan` VARCHAR(30) NOT NULL,
  `customer_id` INT UNSIGNED NOT NULL,
  `jenis_reklame_id` INT UNSIGNED NOT NULL,
  `ide_kebutuhan` TEXT DEFAULT NULL COMMENT 'ide/kebutuhan desain dari customer',
  `ukuran` VARCHAR(100) DEFAULT NULL,
  `lokasi_pemasangan` VARCHAR(255) DEFAULT NULL,
  `catatan_customer` TEXT DEFAULT NULL,
  `harga_ditawarkan` DECIMAL(15,2) DEFAULT NULL,
  `harga_disetujui` DECIMAL(15,2) DEFAULT NULL,
  `status` ENUM(
    'baru',
    'proses_desain',
    'menunggu_approval_customer',
    'revisi_desain_customer',
    'disetujui_customer',
    'menunggu_approval_direktur',
    'revisi_desain_direktur',
    'disetujui_direktur',
    'menunggu_approval_harga',
    'harga_disetujui',
    'spk_dibuat',
    'proses_produksi',
    'selesai_produksi',
    'selesai',
    'dibatalkan'
  ) NOT NULL DEFAULT 'baru',
  `tanggal_pesan` DATE NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_pemesanan` (`kode_pemesanan`),
  KEY `fk_pemesanan_customer` (`customer_id`),
  KEY `fk_pemesanan_jenis` (`jenis_reklame_id`),
  CONSTRAINT `fk_pemesanan_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_pemesanan_jenis` FOREIGN KEY (`jenis_reklame_id`) REFERENCES `jenis_reklame` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Tabel desain (versi file desain per pemesanan)
-- ---------------------------------------------------------
CREATE TABLE `desain` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `pemesanan_id` INT UNSIGNED NOT NULL,
  `versi` INT NOT NULL DEFAULT 1,
  `file_desain` VARCHAR(255) NOT NULL,
  `keterangan` TEXT DEFAULT NULL,
  `dibuat_oleh` INT UNSIGNED NOT NULL COMMENT 'user_id desainer',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_desain_pemesanan` (`pemesanan_id`),
  KEY `fk_desain_user` (`dibuat_oleh`),
  CONSTRAINT `fk_desain_pemesanan` FOREIGN KEY (`pemesanan_id`) REFERENCES `pemesanan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_desain_user` FOREIGN KEY (`dibuat_oleh`) REFERENCES `users` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Tabel revisi_desain (permintaan revisi dari customer/direktur)
-- ---------------------------------------------------------
CREATE TABLE `revisi_desain` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `desain_id` INT UNSIGNED NOT NULL,
  `pemesanan_id` INT UNSIGNED NOT NULL,
  `diminta_oleh` ENUM('customer','direktur') NOT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  `catatan_revisi` TEXT NOT NULL,
  `status` ENUM('menunggu','selesai') NOT NULL DEFAULT 'menunggu',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_revisi_desain` (`desain_id`),
  KEY `fk_revisi_pemesanan` (`pemesanan_id`),
  CONSTRAINT `fk_revisi_desain` FOREIGN KEY (`desain_id`) REFERENCES `desain` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_revisi_pemesanan` FOREIGN KEY (`pemesanan_id`) REFERENCES `pemesanan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Tabel persetujuan (log approval desain & harga oleh customer/direktur)
-- ---------------------------------------------------------
CREATE TABLE `persetujuan` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `pemesanan_id` INT UNSIGNED NOT NULL,
  `desain_id` INT UNSIGNED DEFAULT NULL,
  `jenis` ENUM('desain_customer','desain_direktur','harga_customer') NOT NULL,
  `status` ENUM('setuju','revisi') NOT NULL,
  `catatan` TEXT DEFAULT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_persetujuan_pemesanan` (`pemesanan_id`),
  KEY `fk_persetujuan_user` (`user_id`),
  CONSTRAINT `fk_persetujuan_pemesanan` FOREIGN KEY (`pemesanan_id`) REFERENCES `pemesanan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_persetujuan_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Tabel spk (Surat Perintah Kerja)
-- ---------------------------------------------------------
CREATE TABLE `spk` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `pemesanan_id` INT UNSIGNED NOT NULL,
  `no_spk` VARCHAR(30) NOT NULL,
  `isi_spk` TEXT DEFAULT NULL,
  `file_spk` VARCHAR(255) DEFAULT NULL,
  `dibuat_oleh` INT UNSIGNED NOT NULL COMMENT 'user_id accounting',
  `tanggal_spk` DATE NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `no_spk` (`no_spk`),
  KEY `fk_spk_pemesanan` (`pemesanan_id`),
  CONSTRAINT `fk_spk_pemesanan` FOREIGN KEY (`pemesanan_id`) REFERENCES `pemesanan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Tabel produksi (proses produksi oleh Kepala Produksi)
-- ---------------------------------------------------------
CREATE TABLE `produksi` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `pemesanan_id` INT UNSIGNED NOT NULL,
  `spk_id` INT UNSIGNED NOT NULL,
  `catatan_produksi` TEXT DEFAULT NULL,
  `tanggal_mulai` DATE DEFAULT NULL,
  `tanggal_selesai` DATE DEFAULT NULL,
  `status` ENUM('belum_mulai','proses','selesai') NOT NULL DEFAULT 'belum_mulai',
  `updated_by` INT UNSIGNED DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_produksi_pemesanan` (`pemesanan_id`),
  KEY `fk_produksi_spk` (`spk_id`),
  CONSTRAINT `fk_produksi_pemesanan` FOREIGN KEY (`pemesanan_id`) REFERENCES `pemesanan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_produksi_spk` FOREIGN KEY (`spk_id`) REFERENCES `spk` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Tabel status_log (riwayat perubahan status untuk laporan & tracking)
-- ---------------------------------------------------------
CREATE TABLE `status_log` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `pemesanan_id` INT UNSIGNED NOT NULL,
  `status` VARCHAR(50) NOT NULL,
  `keterangan` VARCHAR(255) DEFAULT NULL,
  `created_by` INT UNSIGNED DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_statuslog_pemesanan` (`pemesanan_id`),
  CONSTRAINT `fk_statuslog_pemesanan` FOREIGN KEY (`pemesanan_id`) REFERENCES `pemesanan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =========================================================
-- DATA AWAL (SEED)
-- =========================================================

-- Password default untuk SEMUA akun contoh di bawah ini: password123
-- (hash dibuat dengan password_hash('password123', PASSWORD_BCRYPT))
-- Segera ganti password setelah login pertama kali untuk keamanan produksi.
INSERT INTO `users` (`username`,`password`,`nama_lengkap`,`email`,`role`,`status`) VALUES
('admin','$2y$10$QTqiVO4RDGCD/dHnC9qYm.lu3yzX27hvSX5VCt4xLRsayql7zrl42','Administrator','admin@reklame.local','admin','aktif'),
('desainer1','$2y$10$QTqiVO4RDGCD/dHnC9qYm.lu3yzX27hvSX5VCt4xLRsayql7zrl42','Dedi Desainer','desainer@reklame.local','desainer','aktif'),
('direktur1','$2y$10$QTqiVO4RDGCD/dHnC9qYm.lu3yzX27hvSX5VCt4xLRsayql7zrl42','Bapak Direktur','direktur@reklame.local','direktur','aktif'),
('accounting1','$2y$10$QTqiVO4RDGCD/dHnC9qYm.lu3yzX27hvSX5VCt4xLRsayql7zrl42','Ani Accounting','accounting@reklame.local','accounting','aktif'),
('kaprod1','$2y$10$QTqiVO4RDGCD/dHnC9qYm.lu3yzX27hvSX5VCt4xLRsayql7zrl42','Kepala Produksi','kaprod@reklame.local','kepala_produksi','aktif'),
('customer1','$2y$10$QTqiVO4RDGCD/dHnC9qYm.lu3yzX27hvSX5VCt4xLRsayql7zrl42','PT Maju Jaya','customer1@reklame.local','customer','aktif');

-- Catatan: Anda juga bisa membuat user baru langsung lewat menu Kelola Pengguna di aplikasi.

INSERT INTO `customers` (`user_id`,`nama_perusahaan`,`alamat`,`no_hp`,`email`,`kontak_person`) VALUES
(6,'PT Maju Jaya','Jl. Sudirman No. 10, Jakarta','081234567890','customer1@reklame.local','Budi Santoso');

INSERT INTO `jenis_reklame` (`nama_jenis`,`deskripsi`,`satuan`,`harga_dasar`,`status`) VALUES
('Billboard','Reklame ukuran besar di pinggir jalan raya','m2',350000,'aktif'),
('Neon Box','Reklame dengan pencahayaan lampu neon/LED','m2',450000,'aktif'),
('Spanduk','Reklame kain/flexi banner ukuran sedang','m2',35000,'aktif'),
('Banner','Reklame berdiri ukuran kecil-menengah','pcs',150000,'aktif'),
('Videotron','Reklame layar digital LED','m2',5000000,'aktif');
