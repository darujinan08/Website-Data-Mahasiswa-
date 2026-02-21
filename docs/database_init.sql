-- ============================================
-- DATABASE INITIALIZATION SCRIPT
-- Aplikasi Web Manajemen Data Mahasiswa
-- ============================================

-- 1. CREATE DATABASE
CREATE DATABASE IF NOT EXISTS db_mahasiswa;
USE db_mahasiswa;

-- 2. CREATE TABLE MAHASISWA
CREATE TABLE IF NOT EXISTS mahasiswa (
    NIM VARCHAR(20) PRIMARY KEY COMMENT 'Nomor Induk Mahasiswa',
    NAMA VARCHAR(100) NOT NULL COMMENT 'Nama Mahasiswa',
    ALAMAT TEXT NOT NULL COMMENT 'Alamat Mahasiswa',
    TANGGAL_LAHIR DATE NOT NULL COMMENT 'Tanggal Lahir Mahasiswa',
    GENDER ENUM('Laki-laki', 'Perempuan') NOT NULL COMMENT 'Jenis Kelamin Mahasiswa',
    CREATED_AT TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu Pembuatan Data',
    UPDATED_AT TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Waktu Update Data'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel Data Mahasiswa';

-- 3. CREATE INDEXES
ALTER TABLE mahasiswa ADD INDEX idx_nama (NAMA);
ALTER TABLE mahasiswa ADD INDEX idx_gender (GENDER);

-- 4. INSERT SAMPLE DATA (Optional - untuk testing)
INSERT INTO mahasiswa (NIM, NAMA, ALAMAT, TANGGAL_LAHIR, GENDER) VALUES
('19001', 'Budi Santoso', 'Jl. Merdeka No. 10, Jakarta', '2003-05-15', 'Laki-laki'),
('19002', 'Siti Nurhaliza', 'Jl. Ahmad Yani No. 25, Bandung', '2002-08-22', 'Perempuan'),
('19003', 'Ahmad Wijaya', 'Jl. Sudirman No. 45, Surabaya', '2004-01-10', 'Laki-laki'),
('19004', 'Linda Permatasari', 'Jl. Gatot Subroto No. 12, Medan', '2003-03-18', 'Perempuan'),
('19005', 'Rudi Hermawan', 'Jl. Diponegoro No. 30, Yogyakarta', '2002-11-05', 'Laki-laki');

-- 5. VERIFY TABLE
SHOW TABLES;
DESCRIBE mahasiswa;

-- ============================================
-- End of Database Initialization Script
-- ============================================
