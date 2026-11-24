# CertiLap — Sistem Pakar Diagnosa Kerusakan Hardware Laptop

<p align="center">
  <strong>Aplikasi Sistem Pakar Cerdas Diagnosa Kerusakan Komponen Laptop & PC Berbasis Metode Certainty Factor (CF)</strong>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12%2F13-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/Database-SQLite-003B57?style=for-the-badge&logo=sqlite&logoColor=white" alt="SQLite">
  <img src="https://img.shields.io/badge/Theme-Clean_White_%26_Soft_Red-E11D48?style=for-the-badge" alt="Theme">
  <img src="https://img.shields.io/badge/Tests-8%20Passed-10B981?style=for-the-badge&logo=phpunit&logoColor=white" alt="Tests">
</p>

---

## 📌 Tentang Proyek

**CertiLap** adalah aplikasi sistem pakar yang dirancang untuk membantu pengguna mengidentifikasi kerusakan pada perangkat keras laptop dan komputer jinjing secara akurat, cepat, dan mandiri. Sistem ini mengadopsi metode inferensi **Certainty Factor (CF)** untuk mengolah ketidakpastian fakta dari pengguna dan bobot keahlian teknisi pakar.

### 📚 Landasan Teori & Referensi Ilmiah
Basis pengetahuan, daftar gejala, kaidah aturan produksi, serta bobot *Measure of Belief* (MB) dan *Measure of Disbelief* (MD) pada sistem ini bersumber langsung dari penelitian jurnal ilmiah:
- **Jurnal:** *Jurnal TIKomSiN (Teknologi Informasi Komunikasi dan Sistem Informasi Terintegrasi)*, Vol. 13 No. 1, April 2025.
- **Narasumber Pakar:** Teknisi Spesialis Hardware **CV. Angkasa Komputer Solo**.
- **Hasil Pengujian Validasi:** Telah diuji terhadap 15 kasus kerusakan nyata dengan tingkat akurasi mencapai **87%**.

---

## ⚙️ Logika Algoritma Certainty Factor (CF)

Sistem mengimplementasikan kalkulasi Certainty Factor standar secara matematis presisi:

1. **Perhitungan CF Gejala Tunggal:**
   $$\text{CF}[H, E] = \text{CF}_{\text{pakar}} \times \text{CF}_{\text{user}}$$
   *di mana $\text{CF}_{\text{pakar}} = \text{MB} - \text{MD}$ dan $\text{CF}_{\text{user}} \in [0.2, 1.0]$.*

2. **Kombinasi Gejala Paralel ($\text{CF}_{\text{combine}}$):**
   - **Keduanya Positif ($\text{CF}_1 \ge 0, \text{CF}_2 \ge 0$):**
     $$\text{CF}_{\text{combine}} = \text{CF}_1 + \text{CF}_2 \times (1 - \text{CF}_1)$$
   - **Keduanya Negatif ($\text{CF}_1 < 0, \text{CF}_2 < 0$):**
     $$\text{CF}_{\text{combine}} = \text{CF}_1 + \text{CF}_2 \times (1 + \text{CF}_1)$$
   - **Tanda Berbeda (Salah Satu Negatif):**
     $$\text{CF}_{\text{combine}} = \frac{\text{CF}_1 + \text{CF}_2}{1 - \min(|\text{CF}_1|, |\text{CF}_2|)}$$

3. **Tingkat Keyakinan Diagnosa:**
   - $\ge 75\%$ : **Tinggi (Sangat Mungkin Terjadi)**
   - $40\% - 74.9\%$ : **Cukup (Kemungkinan Sedang)**
   - $< 40\%$ : **Rendah (Kemungkinan Kecil)**

---

## 🗂️ Cakupan 10 Kerusakan & 40 Gejala

### 10 Kategori Kerusakan (K01 — K10)
| Kode | Nama Kerusakan Laptop | Modul Hardware Terkait |
| :---: | :--- | :--- |
| **K01** | Kerusakan pada RAM | Modul Memori Utama |
| **K02** | Kerusakan pada Layar | Panel LCD/LED & Kabel Fleksibel |
| **K03** | Kerusakan pada Harddisk | Storage Media (HDD / SSD) |
| **K04** | Kerusakan pada Keyboard | Matriks Tombol & Fleksibel |
| **K05** | Kerusakan pada Webcam | Modul Kamera & USB Hub |
| **K06** | Kerusakan pada Baterai | Sel Baterai & Sirkuit BMS |
| **K07** | Kerusakan pada Sistem Operasi | File Sistem OS & Registry |
| **K08** | Kerusakan pada VGA | GPU & VRAM Visual Graphics |
| **K09** | Kerusakan pada Chip CPU | Prosesor & Pendingin Heatsink |
| **K10** | Kerusakan pada Komponen Motherboard | Sirkuit Daya & IC Regulator |

### 7 Kategori Alur Gejala (G01 — G40)
1. **RAM & Boot (4 Gejala):** G01 (Bluescreen dump memory), G02 (Beep code), G03 (Blank hitam), G04 (System freeze).
2. **Layar & Display (4 Gejala):** G05 (Garis vertikal/horizontal), G06 (Layar redup), G07 (Layar berkedip), G14 (Flicker/bergoyang).
3. **Harddisk & Storage (5 Gejala):** G08 (Harddisk tidak terdeteksi), G09 (Data hilang/korup), G10 (Kinerja lambat), G11 (Bunyi aneh klik), G12 (Boot lambat).
4. **Keyboard & Webcam (8 Gejala):** G13 (Tombol tidak fungsi), G15 (Mengetik sendiri), G16 (Tombol mengacak), G17 (Power keyboard mati), G18 (Webcam tidak buka), G19 (Kamera tidak terdeteksi), G20 (Gambar buram), G21 (Audio kamera tidak bersuara).
5. **Baterai & Daya (5 Gejala):** G22 (Baterai cepat habis), G23 (Tidak penuh 100%), G24 (Baterai menggelembung), G25 (Tidak menampung daya), G26 (Baterai tidak terdeteksi).
6. **Sistem Operasi (4 Gejala):** G27 (Sering macet), G28 (Aplikasi tidak terbuka), G29 (Force close), G30 (Bluescreen OS).
7. **VGA, CPU & Mesin (10 Gejala):** G31 (Visual patah-patah), G32 (Artifak tekstur grafis), G33 (No display), G34 (Mati mendadak), G35 (Restart berulang), G36 (Mati total), G37 (Overheat ekstrem), G38 (Komponen fisik terbakar), G39 (Sulit hidup), G40 (Lampu charging nyala tapi laptop tidak mau hidup).

---

## ✨ Fitur Unggulan Sistem

1. **Konsultasi Bertahap (*Step-by-Step Category Walkthrough*):**
   - Pengguna tidak langsung dihadapkan dengan 40 pertanyaan sekaligus, melainkan dipandu bertahap per 7 kategori komponen hardware (4 hingga 8 gejala per langkah).
   - Pengguna dapat melompat antar kategori menggunakan *filter pill tabs* interaktif dengan indikator centang jumlah gejala terpilih.
2. **Simulasi Interaktif Certainty Factor di Landing Page (*CF Playground*):**
   - Pengunjung dapat menguji interaksi rumus Certainty Factor secara langsung sebelum login melalui slider keyakinan interaktif.
3. **Navigasi Terpadu (*Unified Top Navigation Bar*):**
   - Transisi mulus antara halaman depan (Landing Page) dan halaman konsultasi (Dashboard) menggunakan satu kesatuan topbar modern yang konsisten.
4. **Cetak & Ekspor Laporan PDF:**
   - Hasil diagnosa dilengkapi tombol cetak laporan rapi yang memuat persentase kepastian, matriks probabilitas peringkat, serta rekomendasi tindakan penanganan teknis mandiri.
5. **Manajemen Basis Pengetahuan Admin:**
   - CRUD data kerusakan, data gejala, basis aturan CF (perhitungan preview otomatis $CF = MB - MD$), manajemen akun pengguna, serta log riwayat diagnosa.
6. **Desain Human-Computer Interaction (HCI) Bersih:**
   - Nuansa **Clean White** berpadu dengan aksen **Soft Crimson Ruby**, dilengkapi tipografi tajam (*Rajdhani*, *Plus Jakarta Sans*, *JetBrains Mono*).

---

## 🔑 Kredensial Akun Bawaan (Default Seeder)

| Peran (Role) | Username | Password | Akses Fitur |
| :--- | :---: | :---: | :--- |
| **Administrator / Pakar** | `admin` | `admin123` | Kelola Kerusakan, Gejala, Aturan CF, Pengguna, dan Riwayat Global |
| **Pengguna / User** | `user` | `user123` | Diagnosa Bertahap, Riwayat Konsultasi Pribadi, Cetak PDF, Profil |

---

## 🚀 Panduan Instalasi & Menjalankan Aplikasi

Pastikan di komputer Anda telah terinstal **PHP 8.2+** dan **Composer**.

### 1. Clone atau Buka Folder Repositori
```bash
git clone https://github.com/username/CertiLap.git
cd CertiLap
```

### 2. Instal Dependensi Composer
```bash
composer install
```

### 3. Konfigurasi Lingkungan (`.env`)
Salin file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
Secara default, aplikasi menggunakan database **SQLite** (`database/database.sqlite`), sehingga Anda **tidak memerlukan instalasi/menjalankan MySQL**:
```env
DB_CONNECTION=sqlite
```

### 4. Generate App Key
```bash
php artisan key:generate
```

### 5. Jalankan Migrasi & Seeder Database
```bash
php artisan migrate --seed
```

### 6. Jalankan Server Development
```bash
php artisan serve
```
Buka peramban (browser) dan akses: **[http://localhost:8000](http://localhost:8000)**

---

## 🧪 Menjalankan Pengujian Otomatis

Aplikasi dilengkapi pengujian fitur otomatis (Feature & Unit Tests) yang memvalidasi otentikasi login, halaman publik, perhitungan formula Certainty Factor, dan alur diagnosa:

```bash
php artisan test
```
*Hasil: 8 Tests Passed, 29 Assertions (100% Green).*

---

## 📄 Lisensi & Hak Cipta
Aplikasi ini dikembangkan untuk keperluan akademik tugas akhir / proyek Sistem Pakar Semester 7.  
Data pakar bersumber dari **Jurnal TIKomSiN (2025)** dan **CV. Angkasa Komputer Solo**.
