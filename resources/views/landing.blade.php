<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CertiLap — Sistem Pakar Diagnosa Kerusakan Laptop (Certainty Factor)</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Custom Clean White & Soft Red CSS -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
</head>
<body>

<!-- Main Navbar -->
<nav class="navbar navbar-expand-lg navbar-app sticky-top py-3">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-3 text-decoration-none" href="{{ url('/') }}">
            <div class="d-flex align-items-center justify-content-center p-2 rounded"
                 style="background: var(--accent-red-subtle); border: 1px solid var(--accent-red-border); box-shadow: 0 0 10px var(--accent-red-glow);">
                <i class="bi bi-laptop fs-4 text-accent-red"></i>
            </div>
            <div>
                <div class="font-heading fs-4 text-dark lh-1">CERTI<span class="text-accent-red">LAP</span></div>
                <small class="text-muted" style="font-size: 11px; letter-spacing: 0.5px;">SISTEM PAKAR KERUSAKAN LAPTOP</small>
            </div>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navApp">
            <i class="bi bi-list fs-2 text-accent-red"></i>
        </button>
        <div class="collapse navbar-collapse" id="navApp">
            <ul class="navbar-nav ms-auto align-items-center gap-2 pt-3 pt-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="#alur">Alur Konsultasi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#simulasi">Simulasi CF</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#kategori">10 Kerusakan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#penelitian">Data Penelitian</a>
                </li>
                @auth
                    <li class="nav-item ms-lg-2">
                        <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('user.dashboard') }}" class="btn btn-gaming-primary">
                            <i class="bi bi-speedometer2 me-1"></i>Buka Dashboard
                        </a>
                    </li>
                @else
                    <li class="nav-item ms-lg-2">
                        <a href="{{ route('login') }}" class="btn btn-gaming-outline">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Masuk
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('register') }}" class="btn btn-gaming-primary">
                            <i class="bi bi-person-plus me-1"></i>Daftar Akun
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section: Clean White with Soft Red Accents -->
<section class="py-5 position-relative" style="background: #FFFFFF;">
    <div class="container py-lg-4">
        <div class="row align-items-center gy-5">
            <div class="col-lg-7">
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-3 rounded"
                     style="background: var(--accent-red-subtle); border: 1px solid var(--accent-red-border); font-size: 0.85rem; color: var(--accent-red);">
                    <i class="bi bi-shield-check"></i>
                    <span>Sistem Pakar Hardware Laptop & PC</span>
                </div>
                <h1 class="display-5 font-heading text-dark mb-3" style="line-height: 1.2;">
                    DIAGNOSA KERUSAKAN <span class="text-accent-red">HARDWARE LAPTOP</span> BERBASIS CERTAINTY FACTOR
                </h1>
                <p class="lead text-muted mb-4 font-body" style="font-size: 1.05rem; line-height: 1.75;">
                    Identifikasi masalah perangkat keras laptop Anda secara akurat dan terstruktur. Menggunakan kalkulasi probabilitas Certainty Factor berdasarkan bobot pakar teknisi ahli untuk memberikan estimasi kerusakan serta solusi perbaikan yang tepat sasaran.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ auth()->check() ? route('user.diagnosa.index') : route('login') }}" class="btn btn-gaming-primary px-4 py-3 fs-6">
                        <i class="bi bi-lightning-charge-fill me-2"></i>Mulai Diagnosa Sekarang
                    </a>
                    <a href="#simulasi" class="btn btn-gaming-outline px-4 py-3 fs-6">
                        <i class="bi bi-sliders me-2"></i>Coba Simulasi CF
                    </a>
                </div>
                <div class="mt-4 pt-3 d-flex align-items-center gap-4 text-muted small flex-wrap">
                    <div><i class="bi bi-cpu text-accent-red me-1"></i>10 Komponen Inti</div>
                    <div><i class="bi bi-clipboard-pulse text-accent-red me-1"></i>40 Gejala Terdaftar</div>
                    <div><i class="bi bi-award text-accent-red me-1"></i>Akurasi Teruji 87%</div>
                </div>
            </div>

            <!-- Sleek Hardware Inspector Card (Light Edition) -->
            <div class="col-lg-5">
                <div class="gaming-card p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2" style="border-color: var(--border-light) !important;">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-laptop text-accent-red fs-5"></i>
                            <span class="font-heading fw-bold text-dark fs-6">HARDWARE DIAGNOSTIC MONITOR</span>
                        </div>
                        <span class="badge" style="background: rgba(5, 150, 105, 0.12); color: var(--accent-green); border: 1px solid rgba(5, 150, 105, 0.25);">READY</span>
                    </div>

                    <!-- Mini Hardware Status Meters -->
                    <div class="d-flex flex-column gap-3 mb-4">
                        <div>
                            <div class="d-flex justify-content-between small mb-1">
                                <span class="text-muted">RAM & Boot Sequence</span>
                                <span class="text-dark fw-semibold">Terkalibrasi</span>
                            </div>
                            <div class="progress" style="height: 6px; background: #F1F5F9;">
                                <div class="progress-bar" style="width: 85%; background-color: var(--accent-red) !important;"></div>
                            </div>
                        </div>
                        <div>
                            <div class="d-flex justify-content-between small mb-1">
                                <span class="text-muted">Layar & GPU Rendering</span>
                                <span class="text-dark fw-semibold">Terkalibrasi</span>
                            </div>
                            <div class="progress" style="height: 6px; background: #F1F5F9;">
                                <div class="progress-bar" style="width: 78%; background-color: var(--accent-red) !important;"></div>
                            </div>
                        </div>
                        <div>
                            <div class="d-flex justify-content-between small mb-1">
                                <span class="text-muted">Storage & File System</span>
                                <span class="text-dark fw-semibold">Terkalibrasi</span>
                            </div>
                            <div class="progress" style="height: 6px; background: #F1F5F9;">
                                <div class="progress-bar" style="width: 92%; background-color: var(--accent-red) !important;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="p-3 rounded mb-3" style="background: var(--surface-light); border: 1px solid var(--border-light);">
                        <div class="small text-muted mb-1"><i class="bi bi-info-circle text-accent-red me-1"></i>Algoritma Inferensi:</div>
                        <div class="font-mono text-dark small" style="font-size: 11px;">
                            CF_combine = CF1 + CF2 &times; (1 - CF1)
                        </div>
                    </div>

                    <div class="text-center">
                        <small class="text-muted" style="font-size: 11px;">
                            Berdasarkan Kaidah Teknisi Ahli CV. Angkasa Komputer Solo
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 4 Langkah Mudah Konsultasi (Clean White Cards on Subtle Slate) -->
<section id="alur" class="py-5" style="background: var(--surface-light); border-top: 1px solid var(--border-light); border-bottom: 1px solid var(--border-light);">
    <div class="container py-4">
        <div class="text-center mb-5">
            <span class="badge mb-2 px-3 py-1" style="background: var(--accent-red-subtle); color: var(--accent-red); border: 1px solid var(--accent-red-border);">Alur Konsultasi</span>
            <h2 class="font-heading text-dark fs-2 mb-2">4 LANGKAH MUDAH MELAKUKAN DIAGNOSA</h2>
            <p class="text-muted mb-0" style="max-width: 600px; margin: 0 auto;">
                Proses pemeriksaan kerusakan laptop yang dirancang cepat, terstruktur, dan ramah pengguna:
            </p>
        </div>

        <div class="row g-3">
            <div class="col-md-6 col-lg-3">
                <div class="step-feature-card">
                    <div class="step-number">01</div>
                    <h5 class="text-dark fw-bold mb-2 fs-6">Masuk / Daftar Akun</h5>
                    <p class="text-muted small mb-0 font-body">
                        Login atau daftarkan akun agar seluruh riwayat pengujian dan rekomendasi solusi tersimpan rapi.
                    </p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="step-feature-card">
                    <div class="step-number">02</div>
                    <h5 class="text-dark fw-bold mb-2 fs-6">Pilih Gejala Kerusakan</h5>
                    <p class="text-muted small mb-0 font-body">
                        Tandai tanda-tanda anomali yang muncul pada laptop Anda dari daftar 40 gejala perangkat keras.
                    </p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="step-feature-card">
                    <div class="step-number">03</div>
                    <h5 class="text-dark fw-bold mb-2 fs-6">Pilih Skala Keyakinan</h5>
                    <p class="text-muted small mb-0 font-body">
                        Tentukan seberapa yakin Anda dengan gejala tersebut (skala 0.2 Tidak Yakin hingga 1.0 Sangat Yakin).
                    </p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="step-feature-card">
                    <div class="step-number">04</div>
                    <h5 class="text-dark fw-bold mb-2 fs-6">Dapatkan Solusi & Bukti</h5>
                    <p class="text-muted small mb-0 font-body">
                        Lihat persentase kerusakan tertinggi, bukti perhitungan Certainty Factor, dan rekomendasi perbaikan.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Simulasi Interaktif Certainty Factor (Clean Light Panel) -->
<section id="simulasi" class="py-5" style="background: #FFFFFF;">
    <div class="container py-4">
        <div class="text-center mb-5">
            <span class="badge mb-2 px-3 py-1" style="background: var(--accent-red-subtle); color: var(--accent-red); border: 1px solid var(--accent-red-border);">Simulasi Interaktif</span>
            <h2 class="font-heading text-dark fs-2 mb-2">SIMULASI PERHITUNGAN CERTAINTY FACTOR</h2>
            <p class="text-muted mb-0" style="max-width: 650px; margin: 0 auto;">
                Uji coba interaktif cara kerja penggabungan bobot keyakinan Certainty Factor. Pilih kasus kerusakan dan geser tingkat keyakinan Anda:
            </p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="simulator-card">
                    <div class="row g-4 align-items-center">
                        <div class="col-md-5">
                            <label class="small text-muted fw-semibold mb-2">Langkah 1: Pilih Kasus Kerusakan</label>
                            <div class="d-flex flex-column gap-2">
                                <button type="button" class="sim-case-btn active" data-case="ram">
                                    <div class="fw-bold text-dark">Kasus A: Kerusakan RAM</div>
                                    <small class="text-muted">Bluescreen (G01) + Sering Freeze (G03)</small>
                                </button>
                                <button type="button" class="sim-case-btn" data-case="gpu">
                                    <div class="fw-bold text-dark">Kasus B: Kerusakan VGA</div>
                                    <small class="text-muted">Layar Bergaris (G30) + Overheat (G32)</small>
                                </button>
                                <button type="button" class="sim-case-btn" data-case="battery">
                                    <div class="fw-bold text-dark">Kasus C: Kerusakan Baterai</div>
                                    <small class="text-muted">Drop Cepat (G21) + Tidak Mengisi (G22)</small>
                                </button>
                            </div>

                            <div class="mt-4">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="small text-muted fw-semibold">Langkah 2: Tingkat Keyakinan Pengguna</label>
                                    <span class="text-accent-red fw-bold font-mono" id="simConfidenceValue">0.8</span>
                                </div>
                                <input type="range" class="form-range" id="simConfidenceSlider" min="0.2" max="1.0" step="0.2" value="0.8">
                                <div class="d-flex justify-content-between text-muted" style="font-size: 11px;">
                                    <span>0.2 (Kurang Yakin)</span>
                                    <span>0.6 (Cukup Yakin)</span>
                                    <span>1.0 (Sangat Yakin)</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-7 border-start-md" style="border-color: var(--border-light) !important;">
                            <div class="p-4 rounded mb-3" style="background: var(--surface-light); border: 1px solid var(--border-light);">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="small text-muted">Hasil Estimasi Diagnosa:</span>
                                    <span class="badge" style="background: var(--accent-red-subtle); color: var(--accent-red); border: 1px solid var(--accent-red-border);">Simulasi Berjalan</span>
                                </div>
                                <h5 class="fw-bold text-dark mb-2" id="simTargetTitle">
                                    K01 — Kerusakan pada RAM
                                </h5>
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="small text-muted">Persentase Kepastian:</span>
                                    <span class="font-heading fs-3 text-accent-red fw-bold" id="simPctDisplay">78.4%</span>
                                </div>
                                <div class="cf-gauge-bar mb-3">
                                    <div class="cf-gauge-fill" id="simGaugeFill" style="width: 78.4%;"></div>
                                </div>
                                <div class="p-3 rounded font-mono small" style="background: #FFFFFF; border: 1px solid var(--border-light); font-size: 12px; color: var(--text-body);">
                                    <div class="text-accent-cyan fw-bold mb-1">// Rincian Formula Perhitungan:</div>
                                    <div id="simFormulaDisplay">
                                        CF1 = 0.70 &times; 0.8 = <span class="text-dark fw-bold">0.560</span><br>
                                        CF2 = 0.50 &times; 0.8 = <span class="text-dark fw-bold">0.400</span><br>
                                        <span class="text-accent-cyan">CF_gabungan</span> = 0.560 + 0.400 &times; (1 - 0.560) = <strong class="text-accent-red">0.7360</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end">
                                <a href="{{ auth()->check() ? route('user.diagnosa.index') : route('login') }}" class="btn btn-gaming-primary w-100 py-2">
                                    <i class="bi bi-box-arrow-in-right me-1"></i>Lakukan Diagnosa Lengkap
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 10 Kategori Kerusakan Hardware (Clean White Cards) -->
<section id="kategori" class="py-5" style="background: var(--surface-light); border-top: 1px solid var(--border-light); border-bottom: 1px solid var(--border-light);">
    <div class="container py-4">
        <div class="text-center mb-5">
            <span class="badge mb-2 px-3 py-1" style="background: var(--accent-red-subtle); color: var(--accent-red); border: 1px solid var(--accent-red-border);">Katalog Hardware</span>
            <h2 class="font-heading text-dark fs-2 mb-2">10 KATEGORI KERUSAKAN LAPTOP</h2>
            <p class="text-muted mb-0">Komponen perangkat keras yang dapat dianalisis oleh sistem pakar Certainty Factor:</p>
        </div>
        <div class="row g-3">
            @php
                $subsystems = [
                    ['kode'=>'K01', 'title'=>'Kerusakan pada RAM', 'icon'=>'bi-memory', 'desc'=>'Bluescreen memory dump, sistem freeze tiba-tiba, gagal POST boot.'],
                    ['kode'=>'K02', 'title'=>'Kerusakan Layar', 'icon'=>'bi-display', 'desc'=>'Layar bergaris, berkedip (flicker), panel redup atau blank.'],
                    ['kode'=>'K03', 'title'=>'Kerusakan Harddisk', 'icon'=>'bi-hdd-fill', 'desc'=>'Bad sector, disk not detected, muncul bunyi klik, loading lambat.'],
                    ['kode'=>'K04', 'title'=>'Kerusakan Keyboard', 'icon'=>'bi-keyboard', 'desc'=>'Tombol mengetik sendiri (ghosting), macet, tombol power tidak merespons.'],
                    ['kode'=>'K05', 'title'=>'Kerusakan Webcam', 'icon'=>'bi-camera-video', 'desc'=>'Modul webcam tidak terdeteksi, gambar error, driver crash berulang.'],
                    ['kode'=>'K06', 'title'=>'Kerusakan Baterai', 'icon'=>'bi-battery-charging', 'desc'=>'Baterai cepat habis/drop, tidak mau mengisi daya, baterai mengembung.'],
                    ['kode'=>'K07', 'title'=>'Kerusakan Sistem OS', 'icon'=>'bi-window-stack', 'desc'=>'Sistem operasi korup, file crash berulang, restart otomatis.'],
                    ['kode'=>'K08', 'title'=>'Kerusakan VGA', 'icon'=>'bi-gpu-card', 'desc'=>'Artifak garis visual saat grafis berat, layar hitam saat load driver.'],
                    ['kode'=>'K09', 'title'=>'Kerusakan Chip CPU', 'icon'=>'bi-cpu', 'desc'=>'Overheat ekstrem, thermal throttling tinggi, laptop mati tiba-tiba.'],
                    ['kode'=>'K10', 'title'=>'Kerusakan Motherboard', 'icon'=>'bi-motherboard', 'desc'=>'Korsleting sirkuit daya, IC regulator rusak, laptop mati total (no power).'],
                ];
            @endphp
            @foreach ($subsystems as $s)
            <div class="col-sm-6 col-lg-4 col-xl-3">
                <div class="hardware-card">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="badge" style="background: var(--accent-red-subtle); color: var(--accent-red); font-size: 11px;">
                            {{ $s['kode'] }}
                        </span>
                        <div class="hardware-icon-box">
                            <i class="bi {{ $s['icon'] }}"></i>
                        </div>
                    </div>
                    <h6 class="fw-bold text-dark mb-2 fs-6">{{ $s['title'] }}</h6>
                    <p class="text-muted small mb-0 font-body" style="line-height:1.6;">{{ $s['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Data Penelitian & Validasi Ilmiah -->
<section id="penelitian" class="py-5" style="background: #FFFFFF;">
    <div class="container py-4">
        <div class="row align-items-center gy-4">
            <div class="col-lg-7">
                <span class="badge mb-2 px-3 py-1" style="background: var(--accent-red-subtle); color: var(--accent-red); border: 1px solid var(--accent-red-border);">Validasi Ilmiah</span>
                <h2 class="font-heading text-dark fs-2 mb-3">SUMBER DATA & VALIDASI PENELITIAN</h2>
                <p class="text-muted font-body mb-3" style="line-height: 1.8;">
                    Data basis pengetahuan sistem pakar ini diadopsi dari penelitian jurnal ilmiah:
                    <br><strong class="text-dark">Jurnal TIKomSiN (Teknologi Informasi Komunikasi dan Sistem Informasi Terintegrasi), Vol. 13 No. 1, April 2025</strong>.
                </p>
                <p class="text-muted font-body mb-3 small" style="line-height: 1.7;">
                    Data kaidah Measure of Belief (MB) dan Measure of Disbelief (MD) diperoleh langsung melalui wawancara mendalam bersama teknisi spesialis <strong>CV. Angkasa Komputer Solo</strong>.
                </p>
                <div class="d-flex align-items-center gap-4 text-muted small flex-wrap pt-2">
                    <div><i class="bi bi-check2-circle text-accent-green me-1"></i>15 Kasus Nyata Teruji</div>
                    <div><i class="bi bi-award text-accent-red me-1"></i>Tingkat Akurasi 87%</div>
                    <div><i class="bi bi-journal-text text-accent-cyan me-1"></i>ISSN: 2338-4018</div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="gaming-card p-4">
                    <h5 class="fw-bold text-dark mb-2 fs-6">Siap Memeriksa Laptop Anda?</h5>
                    <p class="text-muted small mb-4">
                        Daftarkan akun atau masuk ke sistem untuk memulai diagnosa dan menyimpan laporan hasil konsultasi Anda.
                    </p>
                    <a href="{{ auth()->check() ? route('user.diagnosa.index') : route('login') }}" class="btn btn-gaming-primary w-100 py-3">
                        <i class="bi bi-lightning-charge-fill me-2"></i>Mulai Diagnosa Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="py-4 border-top text-center text-muted small" style="border-color: var(--border-light) !important; background: #FFFFFF;">
    <div class="container">
        <div class="font-heading text-dark fs-5 mb-1">
            CERTI<span class="text-accent-red">LAP</span>
        </div>
        <div>Sistem Pakar Diagnosa Kerusakan Hardware Laptop &bull; Metode Certainty Factor</div>
        <div class="mt-1 text-muted">&copy; {{ date('Y') }} Tugas Akhir / Projek Sistem Pakar. Data referensi Jurnal TIKomSiN 2025.</div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('assets/js/script.js') }}"></script>
</body>
</html>
