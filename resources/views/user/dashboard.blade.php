@extends('layouts.app')

@section('title', 'Dashboard Pengguna — CertiLap')

@section('content')
<!-- Welcome Banner with Clean White & Soft Red Accents -->
<div class="gaming-card mb-4 p-4 p-md-5" style="background: #FFFFFF; border: 1px solid var(--border-light);">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-4 position-relative z-1">
        <div style="max-width: 620px;">
            <div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-3 rounded small"
                 style="background: var(--accent-red-subtle); border: 1px solid var(--accent-red-border); color: var(--accent-red);">
                <i class="bi bi-person-check-fill"></i>
                <span>Sesi Pengguna Aktif &bull; {{ auth()->user()->username }}</span>
            </div>
            <h2 class="font-heading text-dark mb-2 fs-2">Selamat Datang, {{ $user->name }}! 👋</h2>
            <p class="text-muted mb-4 font-body" style="line-height:1.75;">
                Temukan estimasi awal kerusakan laptop Anda secara terstruktur menggunakan sistem pakar berbasis <strong>Certainty Factor</strong>. Silakan mulai konsultasi baru atau tinjau riwayat diagnosa Anda.
            </p>
            <div class="d-flex flex-wrap gap-3">
                <a href="{{ route('user.diagnosa.index') }}" class="btn btn-gaming-primary px-4 py-2">
                    <i class="bi bi-lightning-charge-fill me-2"></i>Mulai Diagnosa Laptop
                </a>
                <a href="{{ route('user.riwayat.index') }}" class="btn btn-gaming-outline px-4 py-2">
                    <i class="bi bi-clock-history me-2"></i>Riwayat Diagnosa
                </a>
            </div>
        </div>
        <div class="d-none d-lg-block text-center p-3">
            <div class="p-4 rounded" style="background: var(--accent-red-subtle); border: 1px solid var(--accent-red-border);">
                <i class="bi bi-laptop text-accent-red" style="font-size: 4.8rem; filter: drop-shadow(0 0 14px var(--accent-red-glow));"></i>
            </div>
        </div>
    </div>
</div>

<!-- Stats Row -->
<div class="row g-3 mb-4">
    <div class="col-sm-6">
        <div class="card-stat">
            <div class="stat-icon" style="background: rgba(2, 132, 199, 0.1); color: var(--accent-cyan); border: 1px solid rgba(2, 132, 199, 0.2);">
                <i class="bi bi-clipboard2-check-fill"></i>
            </div>
            <div>
                <div class="text-muted small fw-semibold">Total Konsultasi Saya</div>
                <div class="font-heading fs-3 text-dark">{{ $totalDiagnosa }} <span class="fs-6 font-body text-muted">Kali</span></div>
            </div>
        </div>
    </div>
    @if ($lastDiagnosa)
        @php
            $pct = (float) $lastDiagnosa->persentase;
            $badgeColor = $pct >= 75 ? 'danger' : ($pct >= 40 ? 'warning' : 'info');
        @endphp
        <div class="col-sm-6">
            <div class="card-stat">
                <div class="stat-icon" style="background: var(--accent-red-subtle); color: var(--accent-red); border: 1px solid var(--accent-red-border);">
                    <i class="bi bi-patch-exclamation-fill"></i>
                </div>
                <div class="overflow-hidden">
                    <div class="text-muted small fw-semibold">Diagnosa Terakhir</div>
                    <div class="fw-bold text-dark text-truncate">{{ $lastDiagnosa->kerusakanUtama->nama_kerusakan ?? 'Tidak terdeteksi' }}</div>
                    <span class="badge bg-{{ $badgeColor }} mt-1" style="font-size: 11px;">{{ $pct }}% Kepastian</span>
                </div>
            </div>
        </div>
    @else
        <div class="col-sm-6">
            <div class="card-stat">
                <div class="stat-icon" style="background: rgba(5, 150, 105, 0.1); color: var(--accent-green); border: 1px solid rgba(5, 150, 105, 0.2);">
                    <i class="bi bi-check2-circle"></i>
                </div>
                <div>
                    <div class="text-muted small fw-semibold">Status Diagnosa</div>
                    <div class="fw-bold text-dark">Siap Memulai</div>
                    <small class="text-muted">Belum ada riwayat tercatat</small>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Quick Guidance Panel -->
<div class="gaming-card p-4">
    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2" style="border-color: var(--border-light) !important;">
        <span class="fw-bold text-dark"><i class="bi bi-info-circle-fill text-accent-red me-2"></i>Panduan Pemeriksaan Laptop</span>
        <span class="badge" style="background: var(--surface-light); color: var(--text-muted); border: 1px solid var(--border-light);">Tips Praktis</span>
    </div>
    <div class="row g-3">
        <div class="col-md-4">
            <div class="p-3 rounded h-100" style="background: var(--surface-light); border: 1px solid var(--border-light);">
                <div class="text-accent-red fw-bold small mb-1">1. Amati Gejala Fisik</div>
                <p class="text-muted small mb-0 font-body">
                    Perhatikan apakah laptop mengeluarkan bunyi klik pada harddisk, layar bergaris, panas berlebih, atau mati mendadak.
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3 rounded h-100" style="background: var(--surface-light); border: 1px solid var(--border-light);">
                <div class="text-accent-amber fw-bold small mb-1">2. Pilih Skala Keyakinan</div>
                <p class="text-muted small mb-0 font-body">
                    Tentukan seberapa yakin Anda dengan kemunculan gejala tersebut (0.2 hingga 1.0) untuk meningkatkan ketepatan inferensi.
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3 rounded h-100" style="background: var(--surface-light); border: 1px solid var(--border-light);">
                <div class="text-accent-green fw-bold small mb-1">3. Unduh Bukti Rekomendasi</div>
                <p class="text-muted small mb-0 font-body">
                    Simpan atau cetak hasil diagnosa PDF untuk referensi estimasi biaya perbaikan dan penggantian suku cadang laptop.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
