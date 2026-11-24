# Desain Basis Data (MySQL) — Sistem Pakar Diagnosa Kerusakan Laptop

Basis data dirancang menggunakan arsitektur relasional (RDBMS MySQL) untuk menyimpan seluruh komponen Basis Pengetahuan (*Knowledge Base*), data pengguna, serta log riwayat konsultasi diagnosa secara terstruktur.

---

## 1. Entity Relationship Diagram (ERD) — Relasi Tabel

```text
[users] (1) ──────────< (M) [riwayat_diagnosa] (1) ──────────< (M) [detail_riwayat]
                                   │                                      │
                                   │ (M:1)                                │ (M:1)
                                   ▼                                      ▼
                             [kerusakan] (1) ───< (M) [basis_pengetahuan] (M) >─── (1) [gejala]
```

**Penjelasan Relasi:**
1. Satu `users` dapat melakukan banyak konsultasi di `riwayat_diagnosa` (1-to-Many).
2. Satu `riwayat_diagnosa` memiliki satu kesimpulan kerusakan utama (relasi ke `kerusakan`) dan banyak gejala terpilih di `detail_riwayat` (1-to-Many).
3. Tabel `basis_pengetahuan` menjadi tabel penghubung (*junction table*) antara `kerusakan` dan `gejala` (Many-to-Many) yang menyimpan bobot kepastian pakar ($MB$, $MD$, dan $CF_{pakar}$).

---

## 2. Struktur Tabel (Data Dictionary)

### A. Tabel `users`
Menyimpan akun Administrator dan Pengguna Akhir.
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id_user` | `INT AUTO_INCREMENT` | Primary Key |
| `nama_lengkap` | `VARCHAR(100)` | Nama lengkap pengguna/admin |
| `username` | `VARCHAR(50)` | Unique, untuk login |
| `email` | `VARCHAR(100)` | Opsional / kontak |
| `password` | `VARCHAR(255)` | Disimpan dalam bentuk hash (`BCRYPT`) |
| `role` | `ENUM('admin', 'user')` | Hak akses sistem (default: `'user'`) |
| `created_at` | `TIMESTAMP` | Waktu pendaftaran |

### B. Tabel `kerusakan`
Menyimpan 10 kategori kerusakan laptop sesuai Batasan Masalah.
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id_kerusakan` | `INT AUTO_INCREMENT` | Primary Key |
| `kode_kerusakan` | `VARCHAR(10)` | Unique (contoh: `K01` s/d `K10`) |
| `nama_kerusakan` | `VARCHAR(100)` | Nama kategori kerusakan |
| `deskripsi` | `TEXT` | Penjelasan singkat kerusakan |
| `solusi` | `TEXT` | Langkah penanganan awal / rekomendasi perbaikan |

### C. Tabel `gejala`
Menyimpan daftar gejala kerusakan laptop.
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id_gejala` | `INT AUTO_INCREMENT` | Primary Key |
| `kode_gejala` | `VARCHAR(10)` | Unique (contoh: `G01`, `G02`, dst.) |
| `nama_gejala` | `VARCHAR(255)` | Deskripsi gejala yang mudah dipahami user |

### D. Tabel `basis_pengetahuan` (Rules)
Menyimpan kaidah produksi (*IF Gejala THEN Kerusakan*) beserta bobot pakar.
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id_rule` | `INT AUTO_INCREMENT` | Primary Key |
| `id_kerusakan` | `INT` | Foreign Key $\rightarrow$ `kerusakan(id_kerusakan)` |
| `id_gejala` | `INT` | Foreign Key $\rightarrow$ `gejala(id_gejala)` |
| `mb` | `DECIMAL(3,2)` | Measure of Belief ($0.00$ s/d $1.00$) |
| `md` | `DECIMAL(3,2)` | Measure of Disbelief ($0.00$ s/d $1.00$) |
| `cf_pakar` | `DECIMAL(4,2)` | Nilai $MB - MD$ (rentang $-1.00$ s/d $1.00$) |

### E. Tabel `riwayat_diagnosa`
Menyimpan ringkasan hasil setiap sesi konsultasi.
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id_riwayat` | `INT AUTO_INCREMENT` | Primary Key |
| `id_user` | `INT` | Foreign Key $\rightarrow$ `users(id_user)` |
| `id_kerusakan_utama` | `INT` | Foreign Key $\rightarrow$ `kerusakan(id_kerusakan)` (CF tertinggi) |
| `nilai_cf_akhir` | `DECIMAL(5,4)` | Nilai CF gabungan tertinggi (misal: `0.9250`) |
| `persentase` | `DECIMAL(5,2)` | Persentase keyakinan (misal: `92.50`) |
| `hasil_lain_json` | `TEXT` | JSON berisi daftar kemungkinan kerusakan lain (peringkat 2, 3, dst.) |
| `tanggal_diagnosa` | `DATETIME` | Waktu pelaksanaan diagnosa |

### F. Tabel `detail_riwayat`
Menyimpan rincian gejala dan tingkat keyakinan yang dipilih user pada suatu sesi diagnosa.
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id_detail` | `INT AUTO_INCREMENT` | Primary Key |
| `id_riwayat` | `INT` | Foreign Key $\rightarrow$ `riwayat_diagnosa(id_riwayat)` |
| `id_gejala` | `INT` | Foreign Key $\rightarrow$ `gejala(id_gejala)` |
| `cf_user` | `DECIMAL(3,2)` | Nilai bobot keyakinan yang dipilih user (misal: `0.60`, `0.80`) |
| `label_keyakinan` | `VARCHAR(50)` | Teks pilihan user (misal: `'Cukup Yakin'`, `'Sangat Yakin'`) |

---

## 3. Script SQL Siap Pakai (`db_sispak_laptop.sql`)

Salin dan jalankan *query* berikut pada phpMyAdmin / HeidiSQL di Laragon:

```sql
CREATE DATABASE IF NOT EXISTS db_sispak_laptop;
USE db_sispak_laptop;

-- 1. Tabel Users
CREATE TABLE users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    nama_lengkap VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) DEFAULT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Tabel Kerusakan
CREATE TABLE kerusakan (
    id_kerusakan INT AUTO_INCREMENT PRIMARY KEY,
    kode_kerusakan VARCHAR(10) NOT NULL UNIQUE,
    nama_kerusakan VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    solusi TEXT NOT NULL
);

-- 3. Tabel Gejala
CREATE TABLE gejala (
    id_gejala INT AUTO_INCREMENT PRIMARY KEY,
    kode_gejala VARCHAR(10) NOT NULL UNIQUE,
    nama_gejala VARCHAR(255) NOT NULL
);

-- 4. Tabel Basis Pengetahuan (Rules)
CREATE TABLE basis_pengetahuan (
    id_rule INT AUTO_INCREMENT PRIMARY KEY,
    id_kerusakan INT NOT NULL,
    id_gejala INT NOT NULL,
    mb DECIMAL(3,2) NOT NULL DEFAULT 0.00,
    md DECIMAL(3,2) NOT NULL DEFAULT 0.00,
    cf_pakar DECIMAL(4,2) NOT NULL DEFAULT 0.00,
    FOREIGN KEY (id_kerusakan) REFERENCES kerusakan(id_kerusakan) ON DELETE CASCADE,
    FOREIGN KEY (id_gejala) REFERENCES gejala(id_gejala) ON DELETE CASCADE
);

-- 5. Tabel Riwayat Diagnosa
CREATE TABLE riwayat_diagnosa (
    id_riwayat INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_kerusakan_utama INT DEFAULT NULL,
    nilai_cf_akhir DECIMAL(5,4) NOT NULL DEFAULT 0.0000,
    persentase DECIMAL(5,2) NOT NULL DEFAULT 0.00,
    hasil_lain_json TEXT,
    tanggal_diagnosa DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE,
    FOREIGN KEY (id_kerusakan_utama) REFERENCES kerusakan(id_kerusakan) ON DELETE SET NULL
);

-- 6. Tabel Detail Riwayat
CREATE TABLE detail_riwayat (
    id_detail INT AUTO_INCREMENT PRIMARY KEY,
    id_riwayat INT NOT NULL,
    id_gejala INT NOT NULL,
    cf_user DECIMAL(3,2) NOT NULL,
    label_keyakinan VARCHAR(50) NOT NULL,
    FOREIGN KEY (id_riwayat) REFERENCES riwayat_diagnosa(id_riwayat) ON DELETE CASCADE,
    FOREIGN KEY (id_gejala) REFERENCES gejala(id_gejala) ON DELETE CASCADE
);

-- ==========================================
-- DATA AWAL (SEEDING) 10 KATEGORI KERUSAKAN
-- Sesuai Batasan Masalah Bab 1.4 Proposal
-- ==========================================
INSERT INTO kerusakan (kode_kerusakan, nama_kerusakan, deskripsi, solusi) VALUES
('K01', 'Kerusakan pada RAM', 'Gangguan pada modul memori utama (Random Access Memory).', 'Bersihkan pin kuningan RAM dengan penghapus karet lembut, coba pindahkan ke slot RAM lain, atau ganti modul RAM baru jika tetap bermasalah.'),
('K02', 'Kerusakan pada Layar', 'Kerusakan pada panel LCD/LED atau kabel fleksibel layar.', 'Hubungkan laptop ke monitor eksternal untuk memastikan kerusakan panel, periksa kabel fleksibel LCD, atau lakukan penggantian panel layar.'),
('K03', 'Kerusakan pada Harddisk', 'Terjadi bad sector atau kegagalan mekanis pada media penyimpanan (HDD/SSD).', 'Segera lakukan backup data penting, jalankan pemeriksaan disk (CHKDSK), atau ganti media penyimpanan ke SSD baru.'),
('K04', 'Kerusakan pada Keyboard', 'Kerusakan pada jalur matriks tombol atau fleksibel keyboard.', 'Bersihkan sela tombol dari debu, uji menggunakan keyboard eksternal USB, dan ganti modul keyboard internal jika kerusakan permanen.'),
('K05', 'Kerusakan pada Webcam', 'Gangguan pada modul kamera internal atau kabel fleksibel kamera.', 'Periksa pengaturan privasi kamera dan perbarui driver webcam. Jika tidak terdeteksi secara fisik, periksa konektor atau ganti modul webcam.'),
('K06', 'Kerusakan pada Baterai', 'Penurunan kapasitas sel baterai (wear level tinggi) atau baterai menggelembung.', 'Lakukan kalibrasi baterai. Jika baterai menggelembung atau daya tidak tertampung, segera lepas dan ganti dengan baterai original baru.'),
('K07', 'Kerusakan pada Sistem Operasi', 'Korupsi file sistem, infeksi malware, atau konflik driver pada OS.', 'Lakukan pemindaian antivirus, jalankan System File Checker (sfc /scannow), gunakan fitur System Restore, atau lakukan instalasi ulang OS.'),
('K08', 'Kerusakan pada VGA', 'Gangguan pada unit pengolah grafis (GPU) atau VRAM.', 'Perbarui atau instal ulang driver grafis secara bersih (clean install). Jika muncul artifacting parah, lakukan pengecekan chip VGA oleh teknisi.'),
('K09', 'Kerusakan pada Chip Prosesor', 'Overheating ekstrem atau kegagalan fungsi pada CPU.', 'Bersihkan kipas pendingin (fan) dan heatsink dari debu, ganti thermal paste prosesor, serta pastikan sirkulasi udara laptop lancar.'),
('K10', 'Kerusakan pada Komponen Motherboard', 'Korsleting (short) atau kerusakan komponen IC/kapasitor pada papan induk.', 'Lepaskan baterai dan adaptor segera untuk mencegah kerusakan meluas, lalu bawa ke teknisi ahli untuk pengecekan jalur tegangan motherboard.');
```