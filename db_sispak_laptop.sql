-- ============================================================
-- DATABASE: db_sispak_laptop
-- Sistem Pakar Diagnosa Kerusakan Laptop - Metode Certainty Factor
-- Sumber Data Pakar: Jurnal TIKomSiN Vol.13 No.1, April 2025
--   Widianto, Widada, Sandradewi, Remawati
--   "Implementasi Metode Certainty Factor dalam Sistem Pakar
--    untuk Diagnosa Kerusakan Laptop"
-- ============================================================

CREATE DATABASE IF NOT EXISTS db_sispak_laptop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_sispak_laptop;

-- ============================================================
-- 1. TABEL USERS
-- ============================================================
CREATE TABLE IF NOT EXISTS users (
    id_user      INT AUTO_INCREMENT PRIMARY KEY,
    nama_lengkap VARCHAR(100) NOT NULL,
    username     VARCHAR(50)  NOT NULL UNIQUE,
    email        VARCHAR(100) DEFAULT NULL,
    password     VARCHAR(255) NOT NULL,
    role         ENUM('admin','user') NOT NULL DEFAULT 'user',
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
-- 2. TABEL KERUSAKAN (10 kategori sesuai jurnal)
-- ============================================================
CREATE TABLE IF NOT EXISTS kerusakan (
    id_kerusakan  INT AUTO_INCREMENT PRIMARY KEY,
    kode_kerusakan VARCHAR(10)  NOT NULL UNIQUE,
    nama_kerusakan VARCHAR(100) NOT NULL,
    deskripsi      TEXT,
    solusi         TEXT NOT NULL
) ENGINE=InnoDB;

-- ============================================================
-- 3. TABEL GEJALA (40 gejala sesuai jurnal)
-- ============================================================
CREATE TABLE IF NOT EXISTS gejala (
    id_gejala  INT AUTO_INCREMENT PRIMARY KEY,
    kode_gejala VARCHAR(10)  NOT NULL UNIQUE,
    nama_gejala VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

-- ============================================================
-- 4. TABEL BASIS PENGETAHUAN / RULES
-- ============================================================
CREATE TABLE IF NOT EXISTS basis_pengetahuan (
    id_rule      INT AUTO_INCREMENT PRIMARY KEY,
    id_kerusakan INT NOT NULL,
    id_gejala    INT NOT NULL,
    mb           DECIMAL(3,2) NOT NULL DEFAULT 0.00,
    md           DECIMAL(3,2) NOT NULL DEFAULT 0.00,
    cf_pakar     DECIMAL(4,2) NOT NULL DEFAULT 0.00,
    FOREIGN KEY (id_kerusakan) REFERENCES kerusakan(id_kerusakan) ON DELETE CASCADE,
    FOREIGN KEY (id_gejala)    REFERENCES gejala(id_gejala) ON DELETE CASCADE,
    UNIQUE KEY unique_rule (id_kerusakan, id_gejala)
) ENGINE=InnoDB;

-- ============================================================
-- 5. TABEL RIWAYAT DIAGNOSA
-- ============================================================
CREATE TABLE IF NOT EXISTS riwayat_diagnosa (
    id_riwayat         INT AUTO_INCREMENT PRIMARY KEY,
    id_user            INT NOT NULL,
    id_kerusakan_utama INT DEFAULT NULL,
    nilai_cf_akhir     DECIMAL(5,4) NOT NULL DEFAULT 0.0000,
    persentase         DECIMAL(5,2) NOT NULL DEFAULT 0.00,
    hasil_lain_json    TEXT,
    tanggal_diagnosa   DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user)            REFERENCES users(id_user) ON DELETE CASCADE,
    FOREIGN KEY (id_kerusakan_utama) REFERENCES kerusakan(id_kerusakan) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ============================================================
-- 6. TABEL DETAIL RIWAYAT
-- ============================================================
CREATE TABLE IF NOT EXISTS detail_riwayat (
    id_detail       INT AUTO_INCREMENT PRIMARY KEY,
    id_riwayat      INT NOT NULL,
    id_gejala       INT NOT NULL,
    cf_user         DECIMAL(3,2) NOT NULL,
    label_keyakinan VARCHAR(50) NOT NULL,
    FOREIGN KEY (id_riwayat) REFERENCES riwayat_diagnosa(id_riwayat) ON DELETE CASCADE,
    FOREIGN KEY (id_gejala)  REFERENCES gejala(id_gejala) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- SEEDING: 10 KERUSAKAN LAPTOP
-- ============================================================
INSERT INTO kerusakan (kode_kerusakan, nama_kerusakan, deskripsi, solusi) VALUES
('K01', 'Kerusakan pada RAM',              'Gangguan pada modul memori utama (Random Access Memory).', 'Bersihkan pin kuningan RAM dengan penghapus karet lembut, coba pindahkan ke slot RAM lain, atau ganti modul RAM baru jika tetap bermasalah.'),
('K02', 'Kerusakan pada Layar',            'Kerusakan pada panel LCD/LED, kabel fleksibel layar, atau inverter backlight.', 'Hubungkan laptop ke monitor eksternal untuk memastikan kerusakan panel, periksa kabel fleksibel LCD, atau lakukan penggantian panel layar.'),
('K03', 'Kerusakan pada Harddisk',         'Terjadi bad sector atau kegagalan mekanis pada media penyimpanan (HDD/SSD).', 'Segera lakukan backup data penting, jalankan pemeriksaan disk (CHKDSK), atau ganti media penyimpanan ke SSD baru.'),
('K04', 'Kerusakan pada Keyboard',         'Kerusakan pada jalur matriks tombol atau fleksibel keyboard.', 'Bersihkan sela tombol dari debu, uji menggunakan keyboard eksternal USB, dan ganti modul keyboard internal jika kerusakan permanen.'),
('K05', 'Kerusakan pada Webcam',           'Gangguan pada modul kamera internal atau kabel fleksibel kamera.', 'Periksa pengaturan privasi kamera dan perbarui driver webcam. Jika tidak terdeteksi secara fisik, periksa konektor atau ganti modul webcam.'),
('K06', 'Kerusakan pada Baterai',          'Penurunan kapasitas sel baterai (wear level tinggi) atau baterai menggelembung.', 'Lakukan kalibrasi baterai. Jika baterai menggelembung atau daya tidak tertampung, segera lepas dan ganti dengan baterai original baru.'),
('K07', 'Kerusakan pada Sistem Operasi',   'Korupsi file sistem, infeksi malware, atau konflik driver pada OS.', 'Lakukan pemindaian antivirus, jalankan System File Checker (sfc /scannow), gunakan fitur System Restore, atau lakukan instalasi ulang OS.'),
('K08', 'Kerusakan pada VGA',              'Gangguan pada unit pengolah grafis (GPU) atau VRAM.', 'Perbarui atau instal ulang driver grafis secara bersih (clean install). Jika muncul artifacting parah, lakukan pengecekan chip VGA oleh teknisi.'),
('K09', 'Kerusakan pada Chip Prosesor',    'Overheating ekstrem atau kegagalan fungsi pada CPU.', 'Bersihkan kipas pendingin (fan) dan heatsink dari debu, ganti thermal paste prosesor, serta pastikan sirkulasi udara laptop lancar.'),
('K10', 'Kerusakan pada Komponen Motherboard', 'Korsleting (short) atau kerusakan komponen IC/kapasitor pada papan induk.', 'Lepaskan baterai dan adaptor segera untuk mencegah kerusakan meluas, lalu bawa ke teknisi ahli untuk pengecekan jalur tegangan motherboard.');

-- ============================================================
-- SEEDING: 40 GEJALA LAPTOP (dari Jurnal TIKomSiN 2025)
-- ============================================================
INSERT INTO gejala (kode_gejala, nama_gejala) VALUES
('G01', 'Layar blue screen dengan laporan "dumping physical memory to disk"'),
('G02', 'Muncul bunyi beep panjang atau pendek ketika laptop dihidupkan'),
('G03', 'Layar blank hitam ketika laptop dihidupkan'),
('G04', 'Layar blue screen dengan laporan "dumping physical memory to disk" (crash RAM)'),
('G05', 'Muncul bunyi beep panjang atau pendek ketika laptop dihidupkan (RAM)'),
('G06', 'Layar blank hitam ketika laptop dihidupkan (RAM)'),
('G07', 'Layar berkedip-kedip'),
('G08', 'Harddisk tidak terdeteksi oleh sistem'),
('G09', 'Data mengalami kerusakan atau hilang'),
('G10', 'Kinerja laptop lambat'),
('G11', 'Timbul suara aneh pada harddisk'),
('G12', 'Proses booting menjadi lambat'),
('G13', 'Sebagian atau semua tombol keyboard tidak berfungsi'),
('G14', 'Layar berkedip-kedip (keyboard)'),
('G15', 'Keyboard mengetik sendiri'),
('G16', 'Ketika tombol tertentu ditekan hasilnya tidak sesuai atau mengacak'),
('G17', 'Laptop tidak bisa dinyalakan jika tombol power berada di keyboard'),
('G18', 'Aplikasi webcam tidak dapat dibuka'),
('G19', 'Kamera tidak terdeteksi'),
('G20', 'Hasil gambar kamera buram atau jelek'),
('G21', 'Hasil video tidak mengeluarkan suara'),
('G22', 'Baterai cepat terkuras habis'),
('G23', 'Baterai tidak dapat terisi penuh 100%'),
('G24', 'Perubahan fisik pada baterai (menggelembung)'),
('G25', 'Baterai tidak dapat menampung daya'),
('G26', 'Baterai tidak terdeteksi'),
('G27', 'Sering macet saat digunakan'),
('G28', 'Tidak bisa membuka aplikasi'),
('G29', 'Proses booting menjadi lambat (OS)'),
('G30', 'Terjadi Bluescreen'),
('G31', 'Tampilan visual menjadi patah-patah'),
('G32', 'Muncul gambar atau garis aneh bertekstur (artifacting)'),
('G33', 'Tampilan no display'),
('G34', 'Laptop tiba-tiba mati saat digunakan'),
('G35', 'Sistem hanya menyala sementara atau sering restart berulang'),
('G36', 'Laptop tidak bisa hidup sama sekali'),
('G37', 'Laptop sering mengalami overheating'),
('G38', 'Ada komponen fisik yang rusak atau terbakar pada motherboard'),
('G39', 'Laptop sulit dihidupkan'),
('G40', 'Indikator charging nyala tapi ketika dihidupkan tidak bisa');

-- ============================================================
-- SEEDING: BASIS PENGETAHUAN / RULES (Kaidah Produksi)
-- Sumber: Tabel 5 Jurnal TIKomSiN Vol.13 No.1, 2025
-- MB diasumsikan = CF_pakar, MD = 0 (pakar hanya memberi nilai positif)
-- ============================================================

-- K01 - Kerusakan pada RAM
INSERT INTO basis_pengetahuan (id_kerusakan, id_gejala, mb, md, cf_pakar) VALUES
(1, 1, 1.0, 0.0, 1.00),  -- G01: Blue screen dump -> RAM (CF=1.0)
(1, 2, 0.4, 0.0, 0.40),  -- G02: Bunyi beep -> RAM (CF=0.4)
(1, 3, 0.4, 0.0, 0.40),  -- G03: Layar blank hitam -> RAM (CF=0.4)
(1, 4, 0.2, 0.0, 0.20);  -- G04: Crash/freeze -> RAM (CF=0.2)

-- K02 - Kerusakan pada Layar
INSERT INTO basis_pengetahuan (id_kerusakan, id_gejala, mb, md, cf_pakar) VALUES
(2, 7,  0.4, 0.0, 0.40),  -- G07: Layar berkedip -> Layar (CF=0.4)
(2, 33, 0.4, 0.0, 0.40);  -- G33: No display -> Layar (CF=0.4)

-- K03 - Kerusakan pada Harddisk
INSERT INTO basis_pengetahuan (id_kerusakan, id_gejala, mb, md, cf_pakar) VALUES
(3, 8,  0.6, 0.0, 0.60),  -- G08: HDD tidak terdeteksi (CF=0.6)
(3, 9,  0.4, 0.0, 0.40),  -- G09: Data rusak/hilang (CF=0.4)
(3, 10, 0.4, 0.0, 0.40),  -- G10: Laptop lambat (CF=0.4)
(3, 11, 0.8, 0.0, 0.80),  -- G11: Suara aneh HDD (CF=0.8)
(3, 12, 0.2, 0.0, 0.20);  -- G12: Booting lambat (CF=0.2)

-- K04 - Kerusakan pada Keyboard
INSERT INTO basis_pengetahuan (id_kerusakan, id_gejala, mb, md, cf_pakar) VALUES
(4, 13, 0.8, 0.0, 0.80),  -- G13: Tombol tidak berfungsi (CF=0.8)
(4, 15, 0.8, 0.0, 0.80),  -- G15: Keyboard mengetik sendiri (CF=0.8)
(4, 16, 0.6, 0.0, 0.60),  -- G16: Tombol salah output (CF=0.6)
(4, 17, 0.2, 0.0, 0.20);  -- G17: Laptop tidak bisa nyala dari keyboard (CF=0.2)

-- K05 - Kerusakan pada Webcam
INSERT INTO basis_pengetahuan (id_kerusakan, id_gejala, mb, md, cf_pakar) VALUES
(5, 18, 0.6, 0.0, 0.60),  -- G18: Aplikasi webcam tidak bisa dibuka (CF=0.6)
(5, 19, 0.6, 0.0, 0.60),  -- G19: Kamera tidak terdeteksi (CF=0.6)
(5, 20, 0.8, 0.0, 0.80),  -- G20: Gambar kamera buram (CF=0.8)
(5, 21, 0.2, 0.0, 0.20);  -- G21: Video tidak ada suara (CF=0.2)

-- K06 - Kerusakan pada Baterai
INSERT INTO basis_pengetahuan (id_kerusakan, id_gejala, mb, md, cf_pakar) VALUES
(6, 22, 0.8, 0.0, 0.80),  -- G22: Baterai cepat habis (CF=0.8)
(6, 23, 0.8, 0.0, 0.80),  -- G23: Tidak bisa penuh 100% (CF=0.8)
(6, 24, 0.8, 0.0, 0.80),  -- G24: Perubahan fisik baterai (CF=0.8)
(6, 25, 0.4, 0.0, 0.40),  -- G25: Tidak bisa tampung daya (CF=0.4)
(6, 26, 0.2, 0.0, 0.20);  -- G26: Baterai tidak terdeteksi (CF=0.2)

-- K07 - Kerusakan pada Sistem Operasi
INSERT INTO basis_pengetahuan (id_kerusakan, id_gejala, mb, md, cf_pakar) VALUES
(7, 10, 0.2, 0.0, 0.20),  -- G10: Kinerja lambat -> OS (CF=0.2)
(7, 27, 0.4, 0.0, 0.40),  -- G27: Sering macet (CF=0.4)
(7, 28, 0.6, 0.0, 0.60),  -- G28: Tidak bisa buka aplikasi (CF=0.6)
(7, 29, 0.2, 0.0, 0.20),  -- G29: Booting lambat OS (CF=0.2)
(7, 30, 0.6, 0.0, 0.60);  -- G30: Bluescreen OS (CF=0.6)

-- K08 - Kerusakan pada VGA
INSERT INTO basis_pengetahuan (id_kerusakan, id_gejala, mb, md, cf_pakar) VALUES
(8, 7,  0.2, 0.0, 0.20),  -- G07: Layar berkedip -> VGA (CF=0.2)
(8, 31, 0.6, 0.0, 0.60),  -- G31: Tampilan patah-patah (CF=0.6)
(8, 32, 0.8, 0.0, 0.80),  -- G32: Artifacting (CF=0.8)
(8, 33, 0.4, 0.0, 0.40);  -- G33: No display -> VGA (CF=0.4)

-- K09 - Kerusakan pada Chip Prosesor
INSERT INTO basis_pengetahuan (id_kerusakan, id_gejala, mb, md, cf_pakar) VALUES
(9, 34, 0.6, 0.0, 0.60),  -- G34: Laptop tiba-tiba mati (CF=0.6)
(9, 35, 0.4, 0.0, 0.40),  -- G35: Sering restart (CF=0.4)
(9, 36, 0.2, 0.0, 0.20),  -- G36: Tidak bisa hidup sama sekali (CF=0.2)
(9, 37, 0.4, 0.0, 0.40);  -- G37: Overheating (CF=0.4)

-- K10 - Kerusakan pada Komponen Motherboard
INSERT INTO basis_pengetahuan (id_kerusakan, id_gejala, mb, md, cf_pakar) VALUES
(10, 36, 0.2, 0.0, 0.20),  -- G36: Tidak bisa hidup -> Motherboard (CF=0.2)
(10, 38, 0.8, 0.0, 0.80),  -- G38: Komponen terbakar/rusak fisik (CF=0.8)
(10, 39, 0.4, 0.0, 0.40),  -- G39: Sulit dihidupkan (CF=0.4)
(10, 40, 0.4, 0.0, 0.40);  -- G40: Charging nyala tapi tidak bisa hidup (CF=0.4)

-- ============================================================
-- SEEDING: AKUN ADMIN DEFAULT
-- Password: admin123 (bcrypt)
-- ============================================================
INSERT INTO users (nama_lengkap, username, email, password, role) VALUES
('Administrator', 'admin', 'admin@sispak.id', '$2y$12$zz80Rlj.8G9SoxRw4RoPq.axlgyJeE1SdpZScgJNGyogjaO9B60jm', 'admin');
-- Password default admin: admin123
