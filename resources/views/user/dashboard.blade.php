@extends('layouts.app')

@section('title', 'Dashboard — CertiLap')

@section('content')

{{-- ═══ HERO BANNER ═══ --}}
<div class="dash-hero mb-4">
    <div class="dash-hero-left">
        <div class="dash-hero-pill">
            <i class="bi bi-person-check-fill"></i>
            <span>{{ auth()->user()->username }}</span>
            <span class="dash-hero-dot"></span>
            <span class="text-accent-red fw-semibold">Aktif</span>
        </div>
        <h1 class="dash-hero-title">Halo, {{ $user->name }}! 👋</h1>
        <p class="dash-hero-sub">Diagnosa hardware laptop Anda dengan <strong>Certainty Factor</strong> — cepat, terstruktur, akurat.</p>
        <div class="d-flex flex-wrap gap-3 mt-4">
            <a href="{{ route('user.diagnosa.index') }}" class="btn btn-gaming-primary btn-lg px-5">
                <i class="bi bi-lightning-charge-fill me-2"></i>Mulai Diagnosa
            </a>
            <a href="{{ route('user.riwayat.index') }}" class="btn btn-gaming-outline btn-lg px-4">
                <i class="bi bi-clock-history me-2"></i>Riwayat
            </a>
        </div>
    </div>
    <div class="dash-hero-visual d-none d-lg-flex">
        <div class="dash-hero-ring">
            <i class="bi bi-laptop"></i>
        </div>
        <div class="dash-hero-badge badge-top">
            <i class="bi bi-cpu-fill me-1"></i> Hardware AI
        </div>
        <div class="dash-hero-badge badge-bot">
            <i class="bi bi-shield-check me-1"></i> CF Engine
        </div>
    </div>
</div>

{{-- ═══ STAT CARDS ═══ --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="dash-stat-card">
            <div class="dash-stat-icon"><i class="bi bi-clipboard2-pulse-fill"></i></div>
            <div class="dash-stat-num" data-count="{{ $totalDiagnosa }}">0</div>
            <div class="dash-stat-label">Total Diagnosa</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="dash-stat-card">
            <div class="dash-stat-icon"><i class="bi bi-layers-fill"></i></div>
            <div class="dash-stat-num" data-count="40">0</div>
            <div class="dash-stat-label">Basis Gejala</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="dash-stat-card">
            <div class="dash-stat-icon"><i class="bi bi-bug-fill"></i></div>
            <div class="dash-stat-num" data-count="10">0</div>
            <div class="dash-stat-label">Jenis Kerusakan</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        @if ($lastDiagnosa)
            @php $pct = (float) $lastDiagnosa->persentase; @endphp
            <div class="dash-stat-card dash-stat-card--highlight">
                <div class="dash-stat-icon"><i class="bi bi-graph-up-arrow"></i></div>
                <div class="dash-stat-num" data-count="{{ (int)$pct }}" data-suffix="%">0%</div>
                <div class="dash-stat-label">CF Terakhir</div>
            </div>
        @else
            <a href="{{ route('user.diagnosa.index') }}" class="dash-stat-card dash-stat-card--cta text-decoration-none">
                <div class="dash-stat-icon"><i class="bi bi-plus-circle-fill"></i></div>
                <div class="dash-stat-num" style="font-size:1.1rem;">Mulai<br>Sekarang</div>
                <div class="dash-stat-label">Belum ada data</div>
            </a>
        @endif
    </div>
</div>

{{-- ═══ ALUR DIAGNOSA INTERAKTIF ═══ --}}
<div class="gaming-card p-4 mb-4">
    <div class="d-flex align-items-center gap-2 mb-4">
        <i class="bi bi-map-fill text-accent-red fs-5"></i>
        <span class="fw-bold text-dark fs-6">Alur Konsultasi</span>
        <span class="ms-auto badge" style="background:var(--accent-red-subtle);color:var(--accent-red);border:1px solid var(--accent-red-border);">3 Langkah</span>
    </div>
    <div class="dash-steps">
        <a href="{{ route('user.diagnosa.index') }}" class="dash-step-card">
            <div class="dash-step-num">01</div>
            <div class="dash-step-icon"><i class="bi bi-search-heart-fill"></i></div>
            <div class="dash-step-body">
                <div class="dash-step-title">Pilih Gejala</div>
                <div class="dash-step-desc">Jawab 40 pertanyaan kondisi hardware satu per satu</div>
            </div>
            <i class="bi bi-arrow-right-circle-fill dash-step-arrow"></i>
        </a>
        <div class="dash-step-connector"><i class="bi bi-chevron-right"></i></div>
        <div class="dash-step-card">
            <div class="dash-step-num">02</div>
            <div class="dash-step-icon"><i class="bi bi-sliders2-vertical"></i></div>
            <div class="dash-step-body">
                <div class="dash-step-title">Atur Keyakinan</div>
                <div class="dash-step-desc">Tentukan skala 0.2 – 1.0 pada setiap gejala</div>
            </div>
            <i class="bi bi-arrow-right-circle-fill dash-step-arrow"></i>
        </div>
        <div class="dash-step-connector"><i class="bi bi-chevron-right"></i></div>
        <div class="dash-step-card">
            <div class="dash-step-num">03</div>
            <div class="dash-step-icon"><i class="bi bi-file-earmark-bar-graph-fill"></i></div>
            <div class="dash-step-body">
                <div class="dash-step-title">Lihat Hasil</div>
                <div class="dash-step-desc">Dapatkan rekomendasi kerusakan & solusi perbaikan</div>
            </div>
            <i class="bi bi-arrow-right-circle-fill dash-step-arrow"></i>
        </div>
    </div>
</div>

{{-- ═══ QUICK ACTIONS ═══ --}}
<div class="row g-3">
    <div class="col-md-6">
        <a href="{{ route('user.diagnosa.index') }}" class="dash-action-card text-decoration-none">
            <div class="dash-action-icon"><i class="bi bi-lightning-charge-fill"></i></div>
            <div>
                <div class="dash-action-title">Diagnosa Baru</div>
                <div class="dash-action-sub">Mulai sesi konsultasi hardware</div>
            </div>
            <i class="bi bi-chevron-right ms-auto text-accent-red fs-5"></i>
        </a>
    </div>
    <div class="col-md-6">
        <a href="{{ route('user.riwayat.index') }}" class="dash-action-card text-decoration-none">
            <div class="dash-action-icon"><i class="bi bi-clock-history"></i></div>
            <div>
                <div class="dash-action-title">Riwayat Diagnosa</div>
                <div class="dash-action-sub">{{ $totalDiagnosa }} sesi tersimpan</div>
            </div>
            <i class="bi bi-chevron-right ms-auto text-accent-red fs-5"></i>
        </a>
    </div>
    <div class="col-md-6">
        <a href="{{ route('user.profil') }}" class="dash-action-card text-decoration-none">
            <div class="dash-action-icon"><i class="bi bi-person-fill"></i></div>
            <div>
                <div class="dash-action-title">Profil Saya</div>
                <div class="dash-action-sub">Kelola akun & keamanan</div>
            </div>
            <i class="bi bi-chevron-right ms-auto text-accent-red fs-5"></i>
        </a>
    </div>
    <div class="col-md-6">
        <div class="dash-action-card dash-action-card--info">
            <div class="dash-action-icon"><i class="bi bi-cpu"></i></div>
            <div>
                <div class="dash-action-title">Metode CF</div>
                <div class="dash-action-sub">Certainty Factor — inferensi berbasis keyakinan</div>
            </div>
            <span class="badge ms-auto" style="background:var(--accent-red-subtle);color:var(--accent-red);font-size:10px;">v2.5</span>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Animated counter
document.querySelectorAll('.dash-stat-num[data-count]').forEach(el => {
    const target = parseInt(el.dataset.count, 10);
    const suffix = el.dataset.suffix || '';
    if (target === 0) { el.textContent = '0' + suffix; return; }
    let start = 0;
    const duration = 900;
    const step = Math.ceil(target / (duration / 16));
    const timer = setInterval(() => {
        start = Math.min(start + step, target);
        el.textContent = start + suffix;
        if (start >= target) clearInterval(timer);
    }, 16);
});
</script>
@endpush
