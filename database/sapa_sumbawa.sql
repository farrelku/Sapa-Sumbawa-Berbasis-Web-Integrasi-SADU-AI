-- ==============================================================
-- DATABASE SCHEMA & DUMP: SAPA SUMBAWA
-- Sistem Pengaduan Warga & Disposisi OPD Kabupaten Sumbawa
-- ==============================================================

-- 1. TABEL: opds (Organisasi Perangkat Daerah Kabupaten Sumbawa)
CREATE TABLE IF NOT EXISTS `opds` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `code` VARCHAR(50) NOT NULL UNIQUE,
    `name` VARCHAR(255) NOT NULL,
    `short_name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(255) NULL,
    `phone` VARCHAR(50) NULL,
    `address` VARCHAR(255) NULL,
    `description` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. TABEL: users (Warga, Admin Pemkab, & Pegawai OPD)
CREATE TABLE IF NOT EXISTS `users` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `email_verified_at` TIMESTAMP NULL,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('warga', 'opd', 'admin') NOT NULL DEFAULT 'warga',
    `opd_id` BIGINT UNSIGNED NULL,
    `nik` VARCHAR(20) NULL,
    `nip` VARCHAR(30) NULL,
    `phone` VARCHAR(20) NULL,
    `address` TEXT NULL,
    `position` VARCHAR(100) NULL,
    `remember_token` VARCHAR(100) NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    FOREIGN KEY (`opd_id`) REFERENCES `opds` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. TABEL: reports (Laporan / Pengaduan Warga)
CREATE TABLE IF NOT EXISTS `reports` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `ticket_number` VARCHAR(50) NOT NULL UNIQUE,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `opd_id` BIGINT UNSIGNED NULL,
    `category` VARCHAR(100) NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT NOT NULL,
    `location_detail` VARCHAR(255) NOT NULL,
    `subdistrict` VARCHAR(100) NOT NULL,
    `photo_path` VARCHAR(255) NULL,
    `urgency` ENUM('rendah', 'sedang', 'mendesak') NOT NULL DEFAULT 'sedang',
    `status` ENUM('menunggu_verifikasi', 'didisposisikan', 'diproses', 'selesai', 'ditolak') NOT NULL DEFAULT 'menunggu_verifikasi',
    `admin_notes` TEXT NULL,
    `opd_response` TEXT NULL,
    `opd_action_photo` VARCHAR(255) NULL,
    `dispatched_at` TIMESTAMP NULL,
    `processed_at` TIMESTAMP NULL,
    `resolved_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`opd_id`) REFERENCES `opds` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. TABEL: report_timelines (Riwayat Log Tindakan & Audit Trail)
CREATE TABLE IF NOT EXISTS `report_timelines` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `report_id` BIGINT UNSIGNED NOT NULL,
    `user_id` BIGINT UNSIGNED NULL,
    `status` VARCHAR(50) NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `notes` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==============================================================
-- DATA AWAL / SEEDER (INSERT INTO)
-- ==============================================================

-- Data Master OPD Kabupaten Sumbawa
INSERT INTO `opds` (`id`, `code`, `name`, `short_name`, `email`, `phone`, `address`, `description`, `created_at`, `updated_at`) VALUES
(1, 'PUPR', 'Dinas Pekerjaan Umum dan Penataan Ruang', 'Dinas PUPR', 'pupr@sumbawaterkini.go.id', '0371-21011', 'Jl. Garuda No. 10, Sumbawa Besar', 'Menangani infrastruktur jalan, jembatan, drainase, irigasi, dan tata ruang wilayah Kabupaten Sumbawa.', NOW(), NOW()),
(2, 'DISKOMINFO', 'Dinas Komunikasi, Informatika, Statistik dan Persandian', 'Diskominfo SP', 'diskominfo@sumbawaterkini.go.id', '0371-21012', 'Jl. Hasanuddin No. 12, Sumbawa Besar', 'Menangani jaringan internet publik, sistem informasi pemerintahan, aduan pelayanan digital dan telekomunikasi.', NOW(), NOW()),
(3, 'DISHUB', 'Dinas Perhubungan', 'Dinas Perhubungan', 'dishub@sumbawaterkini.go.id', '0371-21013', 'Jl. Cendrawasih No. 5, Sumbawa Besar', 'Menangani rambu lalu lintas, penerangan jalan umum (PJU), terminal, dermaga, dan rekayasa lalu lintas.', NOW(), NOW()),
(4, 'DLH', 'Dinas Lingkungan Hidup', 'Dinas LH', 'dlh@sumbawaterkini.go.id', '0371-21014', 'Jl. Dr. Sutomo No. 8, Sumbawa Besar', 'Menangani pengelolaan sampah liar, kebersihan ruang publik kota, taman, pencemaran air, dan limbah lingkungan.', NOW(), NOW()),
(5, 'SATPOLPP', 'Satuan Polisi Pamong Praja', 'Satpol PP', 'satpolpp@sumbawaterkini.go.id', '0371-21015', 'Kompleks Kantor Bupati Sumbawa', 'Penegakan Perda, penertiban ketertiban umum dan ketenteraman masyarakat di wilayah Sumbawa.', NOW(), NOW()),
(6, 'DINKES', 'Dinas Kesehatan', 'Dinas Kesehatan', 'dinkes@sumbawaterkini.go.id', '0371-21016', 'Jl. Kartini No. 4, Sumbawa Besar', 'Menangani keluhan pelayanan puskesmas, fasilitas kesehatan, wabah penyakit, dan gizi buruk.', NOW(), NOW()),
(7, 'DUKCAPIL', 'Dinas Kependudukan dan Pencatatan Sipil', 'Disdukcapil', 'dukcapil@sumbawaterkini.go.id', '0371-21017', 'Jl. Diponegoro No. 3, Sumbawa Besar', 'Pelayanan KTP-el, Kartu Keluarga, Akta Kelahiran, dan dokumen administrasi kependudukan lainnya.', NOW(), NOW()),
(8, 'BPBD', 'Badan Penanggulangan Bencana Daerah', 'BPBD Sumbawa', 'bpbd@sumbawaterkini.go.id', '0371-21018', 'Jl. Samota No. 1, Sumbawa Besar', 'Penyaluran bantuan dan penanganan cepat darurat bencana banjir, longsor, kekeringan, dan pohon tumbang.', NOW(), NOW()),
(9, 'DISPERKIM', 'Dinas Perumahan Rakyat dan Kawasan Permukiman', 'Disperkim', 'perkim@sumbawaterkini.go.id', '0371-21019', 'Jl. Melati No. 2, Sumbawa Besar', 'Menangani sanitasi permukiman, drainase lingkungan perumahan, air minum pedesaan, dan rumah tidak layak huni.', NOW(), NOW());

-- Data Akun Pengguna Default (Password default: 'password')
-- Hash bcrypt '$2y$12$D23E4Nl4BqBq12H7kO038.Ww3vYp6KqR2lJ9iZ8fJq6xJ.Gj1H5yC' setara dengan string 'password'
INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `opd_id`, `nik`, `nip`, `phone`, `address`, `position`, `created_at`, `updated_at`) VALUES
(1, 'Admin Pemkab Sumbawa', 'admin@sumbawakab.go.id', '$2y$12$1uTqjF6dGb96nFpvy81U.Oslr9a2kglgW2Z3qfDq6kZp05m6VnZ1q', 'admin', NULL, NULL, '198501012010011001', '081234567890', 'Sekretariat Daerah Kantor Bupati Sumbawa', 'Administrator Pusat', NOW(), NOW()),
(2, 'Petugas Dinas PUPR', 'pupr@sumbawakab.go.id', '$2y$12$1uTqjF6dGb96nFpvy81U.Oslr9a2kglgW2Z3qfDq6kZp05m6VnZ1q', 'opd', 1, NULL, '198804152012021003', '082198765432', 'Jl. Garuda No. 10, Sumbawa Besar', 'Koordinator Bidang Bina Marga', NOW(), NOW()),
(3, 'Petugas Dishub Sumbawa', 'dishub@sumbawakab.go.id', '$2y$12$1uTqjF6dGb96nFpvy81U.Oslr9a2kglgW2Z3qfDq6kZp05m6VnZ1q', 'opd', 3, NULL, '199002202014031002', '085233445566', 'Jl. Cendrawasih No. 5', 'Kasi Sarana & Prasarana Jalan', NOW(), NOW()),
(4, 'Petugas DLH Sumbawa', 'dlh@sumbawakab.go.id', '$2y$12$1uTqjF6dGb96nFpvy81U.Oslr9a2kglgW2Z3qfDq6kZp05m6VnZ1q', 'opd', 4, NULL, '199208102016042001', '087811223344', 'Jl. Dr. Sutomo No. 8', 'Kasi Pengelolaan Persampahan', NOW(), NOW()),
(5, 'Lalu Ahmad Sumbawa', 'warga@sumbawakab.go.id', '$2y$12$1uTqjF6dGb96nFpvy81U.Oslr9a2kglgW2Z3qfDq6kZp05m6VnZ1q', 'warga', NULL, '5204011203950001', NULL, '081987654321', 'Kel. Brang Biji, Kec. Sumbawa', NULL, NOW(), NOW());
