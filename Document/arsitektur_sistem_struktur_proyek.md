# Arsitektur Sistem — Sistem Pakar Diagnosa Kerusakan Laptop (Certainty Factor)

Dokumen ini merupakan panduan arsitektur teknis untuk pengembangan aplikasi web **Sistem Pakar Diagnosa Kerusakan Laptop** menggunakan metode *Certainty Factor* (CF) sesuai dengan proposal penelitian.

---

## 1. Tech Stack & Lingkungan Pengembangan
Sesuai dengan spesifikasi pada Bab III (3.3.2 Instrumen Pengembangan Sistem):
* **Web Server Lokal:** Laragon (Apache)
* **Bahasa Pemrograman (Back-end):** PHP 8.x (Native terstruktur / Modular)
* **Basis Data (RDBMS):** MySQL / MariaDB (dikelola via phpMyAdmin / HeidiSQL)
* **Antarmuka (Front-end):** HTML5, CSS3 (Tailwind CSS atau Bootstrap 5), JavaScript (Vanilla JS)
* **Keamanan Dasar:** `PDO` / `MySQLi Prepared Statements` (mencegah SQL Injection), `password_hash()` (enkripsi kata sandi), dan `PHP Session` untuk autentikasi hak akses.

---

## 2. Pembagian Peran Aktor (Role-Based Access Control)

| Aktor | Hak Akses & Modul Utama |
| :--- | :--- |
| **Administrator (Admin / Pakar)** | 1. Autentikasi (Login & Logout).<br>2. **Dashboard:** Ringkasan jumlah kerusakan, gejala, basis aturan, pengguna, dan total diagnosa.<br>3. **Manajemen Kerusakan (CRUD):** Kelola 10 kategori kerusakan laptop & solusi penanganannya.<br>4. **Manajemen Gejala (CRUD):** Kelola daftar gejala kerusakan.<br>5. **Manajemen Basis Pengetahuan / Rules (CRUD):** Menghubungkan gejala dengan kerusakan serta menentukan nilai $MB$, $MD$, dan $CF_{pakar}$.<br>6. **Manajemen Pengguna & Riwayat:** Melihat daftar pengguna serta memantau seluruh riwayat diagnosa.<br>7. **Profil Admin:** Mengubah data akun & kata sandi. |
| **Pengguna (User)** | 1. Autentikasi (Registrasi akun baru, Login, & Logout).<br>2. **Dashboard / Beranda:** Informasi singkat cara penggunaan sistem.<br>3. **Modul Diagnosa:** Memilih gejala yang dialami laptop beserta tingkat keyakinan ($CF_{user}$).<br>4. **Hasil Diagnosa:** Melihat kerusakan dengan persentase keyakinan tertinggi, rincian perhitungan CF, kemungkinan kerusakan lain, solusi perbaikan, serta fitur cetak (Print/PDF).<br>5. **Riwayat Diagnosa:** Melihat log dan detail konsultasi yang pernah dilakukan sebelumnya.<br>6. **Profil User:** Mengedit data profil pribadi. |

---

## 3. Struktur Folder Proyek (Laragon)
Letakkan folder proyek di dalam direktori `C:\laragon\www\sispak-laptop\`. Struktur folder dirancang modular agar mudah dikerjakan secara tim (3 orang):

```text
sispak-laptop/
├── config/
│   ├── database.php          # Koneksi PDO/MySQLi ke database MySQL
│   └── auth.php              # Middleware pengecekan session (isLoggedIn, isAdmin)
├── core/
│   └── CertaintyFactor.php   # Class/fungsi inti perhitungan algoritma CF
├── assets/
│   ├── css/
│   │   └── style.css         # Custom styling tambahan
│   ├── js/
│   │   └── script.js         # Interaktivitas UI (validasi form, pencarian gejala)
│   └── img/                  # Logo & aset gambar
├── includes/
│   ├── header.php            # Template header & navbar
│   ├── sidebar_admin.php     # Navigasi sidebar khusus Admin
│   ├── sidebar_user.php      # Navigasi sidebar khusus User
│   └── footer.php            # Template footer & load script JS
├── auth/
│   ├── login.php             # Halaman & proses login (Admin & User)
│   ├── register.php          # Halaman & proses pendaftaran User baru
│   └── logout.php            # Proses hapus session
├── admin/
│   ├── dashboard.php         # Ringkasan statistik sistem
│   ├── kerusakan.php         # CRUD Data Kerusakan
│   ├── gejala.php            # CRUD Data Gejala
│   ├── rules.php             # CRUD Basis Pengetahuan (CF Pakar / MB & MD)
│   ├── users.php             # Kelola daftar user
│   ├── riwayat.php           # Daftar seluruh riwayat diagnosa
│   └── profil.php            # Edit profil admin
├── user/
│   ├── dashboard.php         # Beranda user
│   ├── diagnosa.php          # Form pemilihan gejala & tingkat keyakinan
│   ├── proses_diagnosa.php   # Controller pemanggil CertaintyFactor.php & simpan DB
│   ├── hasil.php             # Tampilan hasil diagnosa & tombol cetak
│   ├── riwayat.php           # Riwayat diagnosa milik user yang sedang login
│   └── profil.php            # Edit profil user
└── index.php                 # Landing page / redirect ke login
```

---

## 4. Pembagian Tugas Tim (Rekomendasi)
Karena proyek ini dikerjakan oleh 3 mahasiswa, alur pengerjaan dapat dibagi secara paralel:
* **Anggota 1 (Database & Core Algorithm):** Membangun database MySQL (`database.md`), menyiapkan data kepakaran, dan mengimplementasikan `core/CertaintyFactor.php`.
* **Anggota 2 (Modul Admin & Autentikasi):** Mengerjakan sistem login/register, Dashboard Admin, serta CRUD Kerusakan, Gejala, dan Basis Pengetahuan.
* **Anggota 3 (Modul User & UI/UX):** Mengerjakan tampilan antarmuka (`design.md`), Form Diagnosa, Halaman Hasil Diagnosa (beserta fitur cetak), dan Riwayat Diagnosa.