@extends('layouts.app')

@section('title', 'Diagnosa Kerusakan Laptop — CertiLap')

@section('content')
<div class="page-header mb-4">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-2 rounded-pill small"
                 style="background: var(--accent-red-subtle); color: var(--accent-red); border: 1px solid var(--accent-red-border);">
                <i class="bi bi-cpu-fill"></i>
                <span class="fw-semibold">Kuisioner Diagnosa Interaktif</span>
            </div>
            <h3 class="font-heading text-dark mb-1 fs-3">Diagnosa Kerusakan Laptop</h3>
            <p class="text-muted small mb-0 font-body">
                Jawab pertanyaan kondisi laptop Anda di bawah ini secara bertahap atau langsung analisa kapan saja.
            </p>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#questMapModal">
                <i class="bi bi-grid-3x3-gap-fill me-1"></i>Peta Soal (1-40)
            </button>
            <button type="button" class="btn btn-sm btn-gaming-outline" id="btnToggleViewMode">
                <i class="bi bi-list-task me-1"></i><span id="viewModeText">Mode Daftar</span>
            </button>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('user.diagnosa.proses') }}" id="formDiagnosa">
    @csrf

    @php
        $gejalaMeta = [
            1  => ['cat' => 'ram', 'icon' => 'bi-memory', 'cat_title' => 'RAM & Boot', 'clue' => 'Layar biru mendadak (BSOD) dengan tulisan dumping memory saat laptop digunakan.'],
            2  => ['cat' => 'ram', 'icon' => 'bi-arrow-clockwise', 'cat_title' => 'RAM & Boot', 'clue' => 'Laptop mendadak restart sendiri saat mulai membuka software atau game.'],
            3  => ['cat' => 'ram', 'icon' => 'bi-soundwave', 'cat_title' => 'RAM & Boot', 'clue' => 'Terdengar bunyi beep berulang-ulang dari motherboard saat dinyalakan.'],
            4  => ['cat' => 'ram', 'icon' => 'bi-hourglass-split', 'cat_title' => 'RAM & Boot', 'clue' => 'Kinerja laptop terasa sangat lambat (lagging) meski hanya beban ringan.'],
            5  => ['cat' => 'layar', 'icon' => 'bi-display', 'cat_title' => 'Layar & Display', 'clue' => 'Muncul garis vertikal atau horizontal permanen pada panel layar.'],
            6  => ['cat' => 'layar', 'icon' => 'bi-brightness-high', 'cat_title' => 'Layar & Display', 'clue' => 'Tampilan layar berkedip-kedip (flickering) saat dipakai atau engsel digerakkan.'],
            7  => ['cat' => 'layar', 'icon' => 'bi-tv', 'cat_title' => 'Layar & Display', 'clue' => 'Layar tetap gelap gulita (blank hitam) padahal lampu power menyala.'],
            8  => ['cat' => 'storage', 'icon' => 'bi-hdd-fill', 'cat_title' => 'Harddisk & Storage', 'clue' => 'Terdengar bunyi ketukan klik (clicking) atau gesekan dari dalam laptop.'],
            9  => ['cat' => 'storage', 'icon' => 'bi-file-earmark-x', 'cat_title' => 'Harddisk & Storage', 'clue' => 'File atau data mendadak hilang, rusak (corrupt), atau partisi tidak terbaca.'],
            10 => ['cat' => 'storage', 'icon' => 'bi-clock-history', 'cat_title' => 'Harddisk & Storage', 'clue' => 'Waktu booting menuju Windows memakan waktu sangat lama (lebih dari 3 menit).'],
            11 => ['cat' => 'storage', 'icon' => 'bi-exclamation-octagon', 'cat_title' => 'Harddisk & Storage', 'clue' => 'Sering muncul pesan error I/O read/write saat membuka file.'],
            12 => ['cat' => 'storage', 'icon' => 'bi-pause-circle', 'cat_title' => 'Harddisk & Storage', 'clue' => 'Kursor mouse macet total (freeze) selama beberapa saat ketika membuka folder.'],
            13 => ['cat' => 'input', 'icon' => 'bi-keyboard', 'cat_title' => 'Keyboard & Webcam', 'clue' => 'Beberapa tombol keyboard tidak merespon sama sekali saat ditekan.'],
            14 => ['cat' => 'layar', 'icon' => 'bi-sun', 'cat_title' => 'Layar & Display', 'clue' => 'Layar terlihat sangat redup dan tombol pengatur kecerahan tidak berpengaruh.'],
            15 => ['cat' => 'input', 'icon' => 'bi-keyboard-fill', 'cat_title' => 'Keyboard & Webcam', 'clue' => 'Karakter tertentu mengetik sendiri secara terus-menerus tanpa disentuh.'],
            16 => ['cat' => 'input', 'icon' => 'bi-power', 'cat_title' => 'Keyboard & Webcam', 'clue' => 'Tombol power pada keyboard tidak merespon saat hendak dinyalakan.'],
            17 => ['cat' => 'input', 'icon' => 'bi-camera-video-off', 'cat_title' => 'Keyboard & Webcam', 'clue' => 'Lampu LED indikator di samping kamera webcam mati.'],
            18 => ['cat' => 'input', 'icon' => 'bi-camera-video', 'cat_title' => 'Keyboard & Webcam', 'clue' => 'Kamera webcam internal tidak terdeteksi oleh Windows Device Manager.'],
            19 => ['cat' => 'input', 'icon' => 'bi-image-alt', 'cat_title' => 'Keyboard & Webcam', 'clue' => 'Hasil kamera webcam buram, pecah, bergaris, atau patah-patah.'],
            20 => ['cat' => 'input', 'icon' => 'bi-cursor-fill', 'cat_title' => 'Keyboard & Webcam', 'clue' => 'Tombol klik kiri/kanan touchpad macet, keras, atau tidak mau membal.'],
            21 => ['cat' => 'input', 'icon' => 'bi-cursor', 'cat_title' => 'Keyboard & Webcam', 'clue' => 'Kursor mouse melompat-lompat sendiri tanpa dikendalikan.'],
            22 => ['cat' => 'baterai', 'icon' => 'bi-battery-half', 'cat_title' => 'Baterai & Daya', 'clue' => 'Baterai cepat habis dan drop dalam waktu kurang dari 30 menit.'],
            23 => ['cat' => 'baterai', 'icon' => 'bi-battery-charging', 'cat_title' => 'Baterai & Daya', 'clue' => 'Status plugged in tapi baterai tidak mau mengisi daya.'],
            24 => ['cat' => 'baterai', 'icon' => 'bi-plug-fill', 'cat_title' => 'Baterai & Daya', 'clue' => 'Laptop langsung mati seketika saat kabel charger adapter dicabut.'],
            25 => ['cat' => 'baterai', 'icon' => 'bi-shield-exclamation', 'cat_title' => 'Baterai & Daya', 'clue' => 'Casing bawah atau area touchpad terlihat terangkat karena baterai menggelembung.'],
            26 => ['cat' => 'baterai', 'icon' => 'bi-slash-circle', 'cat_title' => 'Baterai & Daya', 'clue' => 'Status mengisi tapi angka persen baterai mandek tidak bertambah.'],
            27 => ['cat' => 'os', 'icon' => 'bi-window-x', 'cat_title' => 'Sistem Operasi (OS)', 'clue' => 'Muncul pesan hitam Operating System Not Found saat booting.'],
            28 => ['cat' => 'os', 'icon' => 'bi-window-dash', 'cat_title' => 'Sistem Operasi (OS)', 'clue' => 'Sering mengalami Bluescreen (BSOD) secara acak saat laptop sedang dipakai.'],
            29 => ['cat' => 'os', 'icon' => 'bi-hourglass', 'cat_title' => 'Sistem Operasi (OS)', 'clue' => 'Membuka menu setting Windows memakan waktu sangat lambat.'],
            30 => ['cat' => 'os', 'icon' => 'bi-wrench-adjustable', 'cat_title' => 'Sistem Operasi (OS)', 'clue' => 'Laptop selalu masuk ke Automatic Repair atau Startup Repair saat booting.'],
            31 => ['cat' => 'mesin', 'icon' => 'bi-grid-1x2', 'cat_title' => 'VGA & Mesin', 'clue' => 'Muncul kotak-kotak artifak aneh atau warna layar terdistorsi.'],
            32 => ['cat' => 'mesin', 'icon' => 'bi-box-arrow-right', 'cat_title' => 'VGA & Mesin', 'clue' => 'Game atau software 3D mendadak keluar sendiri (force close).'],
            33 => ['cat' => 'mesin', 'icon' => 'bi-thermometer-high', 'cat_title' => 'VGA & Mesin', 'clue' => 'Bodi bawah laptop terasa sangat panas menyengat (overheating).'],
            34 => ['cat' => 'mesin', 'icon' => 'bi-fan', 'cat_title' => 'VGA & Mesin', 'clue' => 'Kipas laptop berdengung kencang terus-menerus tanpa henti.'],
            35 => ['cat' => 'mesin', 'icon' => 'bi-power', 'cat_title' => 'VGA & Mesin', 'clue' => 'Laptop mati mendadak setelah beberapa menit dipakai karena suhu panas.'],
            36 => ['cat' => 'mesin', 'icon' => 'bi-x-circle-fill', 'cat_title' => 'VGA & Mesin', 'clue' => 'Laptop mati total (matot), kipas dan lampu tidak bereaksi sama sekali.'],
            37 => ['cat' => 'mesin', 'icon' => 'bi-lightbulb-off', 'cat_title' => 'VGA & Mesin', 'clue' => 'Lampu indikator power hanya berkedip 1-2 detik lalu langsung padam.'],
            38 => ['cat' => 'mesin', 'icon' => 'bi-fire', 'cat_title' => 'VGA & Mesin', 'clue' => 'Tercium aroma gosong atau bau hangus komponen terbakar dari ventilasi.'],
            39 => ['cat' => 'mesin', 'icon' => 'bi-usb-symbol', 'cat_title' => 'VGA & Mesin', 'clue' => 'Semua port USB tidak merespon saat dicolokkan flashdisk atau mouse.'],
            40 => ['cat' => 'mesin', 'icon' => 'bi-plug', 'cat_title' => 'VGA & Mesin', 'clue' => 'Colokan lubang charger terasa longgar dan harus digoyang agar mengisi.'],
        ];

        $quizItems = [];
        foreach ($allGejala as $idx => $g) {
            $idG = (int)$g->id_gejala;
            $meta = $gejalaMeta[$idG] ?? [
                'cat' => 'mesin',
                'icon' => 'bi-cpu',
                'cat_title' => 'Hardware Umum',
                'clue' => 'Periksa kondisi fisik dan kinerja komponen perangkat keras laptop Anda.'
            ];

            $quizItems[] = [
                'index' => $idx + 1,
                'id' => $idG,
                'kode' => $g->kode_gejala,
                'nama' => $g->nama_gejala,
                'cat' => $meta['cat'],
                'cat_title' => $meta['cat_title'],
                'icon' => $meta['icon'],
                'clue' => $meta['clue'],
            ];
        }
    @endphp

    <!-- Data Gejala Hidden Inputs -->
    @foreach ($allGejala as $g)
        <input type="hidden" name="gejala[{{ $g->id_gejala }}]" id="hidden_gejala_{{ $g->id_gejala }}" value="0">
    @endforeach

    <!-- =========================================================================
         1. GAMIFIED QUIZ STAGE (ORGANIC, SOFT & SPACIOUS)
         ========================================================================= -->
    <div id="quizGameView" class="quiz-wrapper">
        <div class="quiz-stage-card" id="quizCardBox">

            <!-- Category & Step Header -->
            <div class="quiz-top-bar">
                <span class="quiz-cat-pill" id="hudCatBadge">
                    <i class="bi bi-memory" id="hudCatIcon"></i>
                    <span id="hudCatTitle">RAM & Boot Sequence</span>
                </span>
                <div class="d-flex align-items-center gap-3">
                    <span class="text-muted small fw-medium">
                        Pertanyaan <strong class="text-dark fs-6" id="hudQuestionNumber">1</strong> dari {{ count($allGejala) }}
                    </span>
                    <span class="badge rounded-pill px-3 py-1" style="background: #FFF1F2; color: #E11D48; font-weight: 700;" id="hudProgressPercent">
                        3% Selesai
                    </span>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="quiz-progress-track">
                <div class="quiz-progress-bar-fill" id="hudProgressFill" style="width: 2.5%;"></div>
            </div>

            <!-- Question Box -->
            <div class="quiz-question-box">
                <span class="quiz-q-badge" id="hudQuestionCode">Gejala G01</span>
                <h3 class="quiz-main-question" id="quizQuestionText">
                    Apakah laptop sering mengalami bluescreen dengan pesan dumping physical memory?
                </h3>
                <div class="quiz-clue-callout" id="quizQuestionClue">
                    <i class="bi bi-info-circle-fill text-accent-red mt-1"></i>
                    <span id="quizClueContent">Layar biru mendadak (BSOD) dengan tulisan dumping memory saat laptop digunakan.</span>
                </div>
            </div>

            <!-- Human-Centered Choice Options -->
            <div class="quiz-options-list">
                <!-- Option 0: Normal -->
                <div class="quiz-option-item active" data-cf="0">
                    <div class="quiz-option-left">
                        <div class="quiz-option-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <div>
                            <div class="quiz-option-title">Tidak Dialami (Kondisi Normal)</div>
                            <div class="quiz-option-desc">Laptop berfungsi normal, tidak pernah mengalami kendala ini</div>
                        </div>
                    </div>
                    <div class="quiz-radio-indicator">
                        <i class="bi bi-check quiz-radio-check"></i>
                    </div>
                </div>

                <!-- Option 0.2: Ragu-ragu -->
                <div class="quiz-option-item" data-cf="0.2">
                    <div class="quiz-option-left">
                        <div class="quiz-option-icon">
                            <i class="bi bi-question-circle"></i>
                        </div>
                        <div>
                            <div class="quiz-option-title">Ragu-Ragu / Pernah Sekilas (0.2)</div>
                            <div class="quiz-option-desc">Pernah muncul 1-2 kali atau sangat jarang, tidak begitu yakin</div>
                        </div>
                    </div>
                    <div class="quiz-radio-indicator">
                        <i class="bi bi-check quiz-radio-check"></i>
                    </div>
                </div>

                <!-- Option 0.4: Kurang Yakin -->
                <div class="quiz-option-item" data-cf="0.4">
                    <div class="quiz-option-left">
                        <div class="quiz-option-icon">
                            <i class="bi bi-exclamation-circle"></i>
                        </div>
                        <div>
                            <div class="quiz-option-title">Kurang Yakin / Kadang Muncul (0.4)</div>
                            <div class="quiz-option-desc">Kadang-kadang muncul saat laptop dipakai santai</div>
                        </div>
                    </div>
                    <div class="quiz-radio-indicator">
                        <i class="bi bi-check quiz-radio-check"></i>
                    </div>
                </div>

                <!-- Option 0.6: Cukup Yakin -->
                <div class="quiz-option-item" data-cf="0.6">
                    <div class="quiz-option-left">
                        <div class="quiz-option-icon">
                            <i class="bi bi-check2-circle"></i>
                        </div>
                        <div>
                            <div class="quiz-option-title">Cukup Yakin & Sering Terjadi (0.6)</div>
                            <div class="quiz-option-desc">Sering terjadi dan mulai mengganggu aktivitas sehari-hari</div>
                        </div>
                    </div>
                    <div class="quiz-radio-indicator">
                        <i class="bi bi-check quiz-radio-check"></i>
                    </div>
                </div>

                <!-- Option 1.0: Sangat Yakin -->
                <div class="quiz-option-item" data-cf="1.0">
                    <div class="quiz-option-left">
                        <div class="quiz-option-icon">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                        </div>
                        <div>
                            <div class="quiz-option-title">Sangat Yakin & Parah Sekali (1.0)</div>
                            <div class="quiz-option-desc">Pasti dialami terus-menerus dan kondisi kerusakan parah</div>
                        </div>
                    </div>
                    <div class="quiz-radio-indicator">
                        <i class="bi bi-check quiz-radio-check"></i>
                    </div>
                </div>
            </div>

            <!-- Integrated Card Footer Navigation -->
            <div class="quiz-card-footer">
                <button type="button" class="btn btn-gaming-secondary px-3" id="btnQuizPrev" disabled>
                    <i class="bi bi-arrow-left me-1"></i>Sebelumnya
                </button>

                <div class="d-flex align-items-center gap-2">
                    <button type="button" id="btnReset" class="btn btn-sm btn-link text-muted text-decoration-none" title="Reset semua pilihan">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                    </button>
                    <span class="badge rounded-pill px-3 py-2" id="gejalaTerpilih" style="background-color: var(--accent-red) !important; color:#FFFFFF; font-size: 0.85rem;">
                        0 Gejala Terpilih
                    </span>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-light border text-muted px-3" id="btnQuizSkip">
                        Lewati Soal <i class="bi bi-chevron-right ms-1"></i>
                    </button>
                    <button type="submit" class="btn btn-gaming-primary px-4" id="btnDiagnosa" disabled>
                        <i class="bi bi-cpu-fill me-1"></i>Proses Diagnosa
                    </button>
                </div>
            </div>
        </div>
    </div>


    <!-- =========================================================================
         2. ALTERNATIVE: LIST VIEW MODE
         ========================================================================= -->
    <div id="listViewMode" class="d-none">
        <div class="gaming-card p-3 mb-4">
            <div class="position-relative">
                <i class="bi bi-search position-absolute text-muted" style="left:16px; top:50%; transform:translateY(-50%); font-size:1.05rem;"></i>
                <input type="text" id="searchGejala" class="form-control ps-5 py-2"
                       placeholder="Cari gejala cepat (misal: bluescreen, overheat, baterai drop, layar bergaris)..." autocomplete="off">
            </div>
        </div>

        <div class="d-flex align-items-center gap-2 overflow-auto pb-2 mb-4 symptom-category-tabs">
            <button type="button" class="category-tab-btn active" data-category="all">
                <i class="bi bi-grid-fill me-1"></i>Semua Gejala (40)
            </button>
            <button type="button" class="category-tab-btn" data-category="ram">
                <i class="bi bi-memory me-1"></i>RAM & Boot
            </button>
            <button type="button" class="category-tab-btn" data-category="layar">
                <i class="bi bi-display me-1"></i>Layar
            </button>
            <button type="button" class="category-tab-btn" data-category="storage">
                <i class="bi bi-hdd-fill me-1"></i>Storage
            </button>
            <button type="button" class="category-tab-btn" data-category="input">
                <i class="bi bi-keyboard me-1"></i>Keyboard/Webcam
            </button>
            <button type="button" class="category-tab-btn" data-category="baterai">
                <i class="bi bi-battery-charging me-1"></i>Baterai
            </button>
            <button type="button" class="category-tab-btn" data-category="os">
                <i class="bi bi-window-stack me-1"></i>OS
            </button>
            <button type="button" class="category-tab-btn" data-category="mesin">
                <i class="bi bi-cpu me-1"></i>VGA & Mesin
            </button>
        </div>

        <div class="gaming-card p-3 p-md-4 mb-4">
            @foreach ($quizItems as $item)
                <div class="gejala-row p-3 rounded mb-3 d-flex align-items-center justify-content-between gap-3 flex-wrap flex-md-nowrap"
                     data-category="{{ $item['cat'] }}" data-id="{{ $item['id'] }}">
                    <div class="d-flex align-items-start gap-3 flex-grow-1">
                        <span class="badge px-2 py-1 mt-1" style="background: var(--accent-red-subtle); color: var(--accent-red); border: 1px solid var(--accent-red-border); min-width:48px; text-align:center;">
                            {{ $item['kode'] }}
                        </span>
                        <div>
                            <div class="text-dark fw-semibold" style="font-size: 0.95rem; line-height: 1.5;">
                                {{ $item['nama'] }}
                            </div>
                            <small class="text-muted d-block mt-1">
                                <i class="bi bi-tag me-1"></i>{{ $item['cat_title'] }} • {{ $item['clue'] }}
                            </small>
                        </div>
                    </div>
                    <div style="min-width: 220px; width: 100%; max-width: 260px;">
                        <select class="form-select form-select-sm gejala-list-select" data-id="{{ $item['id'] }}">
                            <option value="0">Tidak Dialami (0)</option>
                            <option value="0.2">Ragu-Ragu (0.2)</option>
                            <option value="0.4">Kurang Yakin (0.4)</option>
                            <option value="0.6">Cukup Yakin (0.6)</option>
                            <option value="1.0">Sangat Yakin (1.0)</option>
                        </select>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Sticky Action Dock Khusus Mode Daftar Ringkas -->
        <div class="sticky-action-bar">
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted small fw-medium">Total Gejala Dipilih:</span>
                    <span class="badge bg-danger fs-6 px-3 py-1 listGejalaTerpilih" style="background-color: var(--accent-red) !important;">0</span>
                </div>
                <span class="text-muted d-none d-md-inline small">
                    • Anda bisa langsung memproses diagnosa kapan saja setelah memilih minimal 1 gejala
                </span>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-gaming-secondary btnResetList">
                    <i class="bi bi-arrow-counterclockwise me-1"></i>
                    <span class="d-none d-sm-inline">Reset</span>
                </button>
                <button type="submit" class="btn btn-gaming-primary px-4 btn-submit-diagnosa" id="btnDiagnosaList" disabled>
                    <i class="bi bi-cpu-fill me-1"></i>
                    <span>Proses Diagnosa Sekarang</span>
                </button>
            </div>
        </div>
    </div>
</form>

<!-- =========================================================================
     4. MODAL PETA SOAL (QUEST MAP 1-40)
     ========================================================================= -->
<div class="modal fade" id="questMapModal" tabindex="-1" aria-labelledby="questMapModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 24px; border: 1px solid rgba(226, 232, 240, 0.8); box-shadow: 0 20px 40px rgba(0,0,0,0.08);">
            <div class="modal-header border-bottom p-4" style="background: #F8FAFC; border-radius: 24px 24px 0 0;">
                <div>
                    <h5 class="modal-title font-heading fw-bold text-dark" id="questMapModalLabel">
                        <i class="bi bi-compass text-accent-red me-2"></i>Peta Pertanyaan Gejala (1-40)
                    </h5>
                    <p class="text-muted small mb-0">Klik pada nomor soal untuk langsung melompat ke pertanyaan yang ingin diperiksa.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="d-flex align-items-center gap-3 mb-3 pb-2 border-bottom flex-wrap">
                    <span class="small text-muted"><span class="badge bg-secondary me-1">Abu-abu</span> Belum Diisi / Normal</span>
                    <span class="small text-muted"><span class="badge bg-danger me-1">Merah</span> Gejala Dipilih</span>
                    <span class="small text-muted"><span class="badge border border-danger text-danger bg-white me-1">Border Merah</span> Soal Aktif</span>
                </div>
                <div class="quest-map-grid" id="questMapGrid">
                    @foreach ($quizItems as $item)
                        <button type="button" class="quest-node-btn" data-index="{{ $item['index'] - 1 }}" data-id="{{ $item['id'] }}" id="node-btn-{{ $item['id'] }}">
                            <span class="fw-bold fs-6">{{ $item['index'] }}</span>
                            <span class="badge badge-cf px-1" style="font-size: 10px; background: #E2E8F0; color: #475569;">{{ $item['kode'] }}</span>
                        </button>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer border-top p-3 justify-content-between">
                <span class="text-muted small" id="questMapSummary">0 dari 40 gejala terindikasi rusak</span>
                <button type="button" class="btn btn-gaming-primary btn-sm px-3" data-bs-dismiss="modal">Tutup Peta</button>
            </div>
        </div>
    </div>
</div>

<script>
    window.CERTILAP_QUIZ_DATA = @json($quizItems);
</script>
@endsection