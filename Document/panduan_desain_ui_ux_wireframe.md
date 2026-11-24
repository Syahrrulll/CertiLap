# Panduan Desain Antarmuka (UI/UX) — Sistem Pakar Diagnosa Kerusakan Laptop

Dokumen ini menjadi pedoman visual agar tampilan aplikasi web modern, bersih, mudah dipahami oleh pengguna awam, serta konsisten di halaman User maupun Admin.

---

## 1. Identitas Visual & Palet Warna
Mengusung tema **Clean Tech / Modern Dashboard** yang rapi dan profesional:
* **Primary Color (Biru Utama):** `#2563EB` (Tombol aksi utama, highlight menu aktif, header).
* **Secondary Color (Biru Gelap / Slate):** `#1E293B` (Sidebar navigasi, teks judul).
* **Background Utama:** `#F8FAFC` (Abu-abu sangat terang agar nyaman di mata).
* **Surface / Card:** `#FFFFFF` dengan batas halus `#E2E8F0` dan bayangan tipis (*soft shadow*).
* **Warna Status / Indikator CF:**
  * **Tinggi ($\ge 75\%$):** `#16A34A` (Hijau — Keyakinan Tinggi / Valid).
  * **Sedang ($40\% - 74\%$):** `#D97706` (Kuning/Oranye — Kemungkinan Sedang).
  * **Rendah ($< 40\%$):** `#DC2626` (Merah — Keyakinan Rendah / Peringatan).
* **Tipografi:** `Inter` atau `Plus Jakarta Sans` (dari Google Fonts) — sangat tajam untuk membaca daftar gejala dan tabel angka.

---

## 2. Tata Letak Halaman Utama (Wireframes)

### A. Halaman Login & Registrasi (`auth/login.php`)
* Tata letak terpusat (*centered card*) dengan lebar maksimal `420px`.
* Bagian atas: Ikon laptop/sistem pakar + Judul aplikasi.
* Form input: `Username`, `Password`, dan tombol `Masuk`.
* Tautan di bawah tombol: *"Belum punya akun? Daftar di sini"* (untuk User baru).

### B. Halaman Diagnosa Kerusakan (`user/diagnosa.php`) — *Halaman Inti*
Dirancang agar pengguna awam tidak bingung melihat 30–40 daftar gejala sekaligus:
* **Header:** Judul *"Diagnosa Kerusakan Laptop"* + instruksi singkat: *"Pilih gejala yang dialami laptop Anda beserta tingkat keyakinannya. Abaikan gejala yang tidak dialami."*
* **Fitur Pencarian Cepat (JavaScript):** Kotak input *"Cari gejala (misal: blue screen, baterai, beep)..."* untuk memfilter daftar secara *real-time*.
* **Daftar Gejala (Tabel / Grid 2 Kolom):**
  * Kiri: Kode (`G01`) & Nama Gejala.
  * Kanan: Dropdown pilihan tingkat keyakinan (*Default*: `Tidak Dialami / Pilih...`, lalu opsi: `Tidak Yakin (0.2)`, `Kurang Yakin (0.4)`, `Cukup Yakin (0.6)`, `Yakin (0.8)`, `Sangat Yakin (1.0)`).
* **Floating / Sticky Action Bar di Bawah:** Menampilkan jumlah gejala yang sudah dipilih + tombol `Reset` dan tombol utama `Proses Diagnosa`.

### C. Halaman Hasil Diagnosa (`user/hasil.php`)
Menampilkan informasi secara hierarkis dari kesimpulan utama hingga transparansi perhitungan:
1. **Kartu Kesimpulan Utama (Hero Result Card):**
   * Nama Kerusakan Tertinggi (misal: **Kerusakan pada RAM**).
   * **Progress Bar / Badge Persentase Besar** (misal: **92.50%** — Kategori: *Sangat Yakin*).
   * Kotak **Rekomendasi Solusi Penanganan** yang jelas dan berpoin.
   * Disclaimer sesuai Batasan Masalah: *"Hasil diagnosa ini merupakan identifikasi awal berbasis sistem pakar dan bukan pengganti pemeriksaan fisik langsung oleh teknisi."*
2. **Bagian Kemungkinan Kerusakan Lain (Secondary Possibilities):**
   * Menampilkan peringkat ke-2 dan ke-3 (jika ada gejala yang beririsan) beserta persentasenya.
3. **Tabel Transparansi Perhitungan (Accordion / Toggle):**
   * Menampilkan daftar gejala yang dipilih user, $CF_{user}$, $CF_{pakar}$, dan $CF_{gejala}$ agar dosen penguji maupun teknisi bisa memverifikasi perhitungan sistem secara langsung.
4. **Tombol Aksi:** `Cetak Hasil (Print)` (menggunakan `@media print` CSS agar rapi tanpa sidebar) dan `Diagnosa Ulang`.

### D. Halaman Dashboard & CRUD Admin (`admin/dashboard.php`)
* **Sidebar Kiri:** Logo Aplikasi, Menu Dashboard, Data Kerusakan, Data Gejala, Basis Pengetahuan (Rules), Riwayat Diagnosa, Data User, Profil, Logout.
* **Konten Utama Dashboard:** 4 Kartu Statistik (Total Kerusakan, Total Gejala, Total Aturan CF, Total Diagnosa) + Tabel 5 Riwayat Diagnosa Terbaru.
* **Halaman Basis Pengetahuan (`admin/rules.php`):**
  * Form tambah aturan: Dropdown `Pilih Kerusakan`, Dropdown `Pilih Gejala`, Input angka `Nilai MB (0 - 1)`, Input angka `Nilai MD (0 - 1)`, dan *preview* otomatis `CF Pakar = MB - MD` sebelum disimpan.

---

## 3. Responsivitas
* Menggunakan sistem grid responsif (Bootstrap 5 / Tailwind CSS) agar tampilan tetap rapi saat dibuka melalui layar laptop (1366px / 1920px) maupun perangkat *mobile* (sidebar otomatis berubah menjadi tombol menu *hamburger*).