@extends('layouts.app')

@section('title', 'Hasil Diagnosa — CertiLap')

@section('content')
<div class="page-header d-print-none mb-3">
    <div>
        <h3 class="font-heading text-dark mb-1 fs-3">
            <i class="bi bi-clipboard-data text-accent-red me-2"></i>Hasil Analisis Diagnosa Kerusakan
        </h3>
        <p class="text-muted small mb-0 font-body">Rincian inferensi Certainty Factor berdasarkan bobot pakar teknisi dan keyakinan Anda.</p>
    </div>
    <div class="d-flex gap-2">
        <button id="btnCetak" class="btn btn-gaming-secondary btn-sm">
            <i class="bi bi-printer me-1"></i>Cetak Laporan PDF
        </button>
        <a href="{{ route('user.diagnosa.index') }}" class="btn btn-gaming-primary btn-sm">
            <i class="bi bi-arrow-repeat me-1"></i>Diagnosa Ulang
        </a>
    </div>
</div>

<div class="text-muted small mb-4">
    <i class="bi bi-calendar-event text-accent-red me-1"></i>Waktu Konsultasi: <strong>{{ $riwayat->tanggal_diagnosa->format('d F Y, H:i') }} WIB</strong>
</div>

<!-- Hero Result Card (HCI Visual Hierarchy Level 1) -->
@if ($riwayat->id_kerusakan_utama && $riwayat->kerusakanUtama)
<div class="gaming-card mb-4 p-4 p-md-5" style="background: #FFFFFF; border: 1px solid var(--accent-red-border); box-shadow: 0 8px 24px rgba(225, 29, 72, 0.08);">
    <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge px-3 py-1" style="background: var(--accent-red-subtle); color: var(--accent-red); border: 1px solid var(--accent-red-border); font-size:11px;">
                    DIAGNOSA UTAMA (PERINGKAT #1)
                </span>
                <span class="badge bg-light text-dark border">{{ $riwayat->kerusakanUtama->kode_kerusakan }}</span>
            </div>
            <h2 class="font-heading text-dark mb-3 fs-2">{{ $riwayat->kerusakanUtama->nama_kerusakan }}</h2>
            
            <div class="d-flex align-items-center gap-3 flex-wrap mb-3">
                <div class="d-flex align-items-center gap-2 fs-2 fw-bold text-accent-red font-heading">
                    <i class="bi bi-patch-check-fill text-accent-amber"></i>
                    <span>{{ $riwayat->persentase }}%</span>
                </div>
                <span class="badge px-3 py-2 rounded" style="background: var(--accent-red-subtle); color: var(--accent-red); border: 1px solid var(--accent-red-border);">
                    {{ $kategori }}
                </span>
            </div>

            <div class="progress mb-2" style="width:340px; max-width:100%; height:10px; background: #E2E8F0; border-radius: 6px;">
                <div class="progress-bar" style="width: {{ $riwayat->persentase }}%; background: linear-gradient(90deg, var(--accent-red) 0%, #FB7185 100%);"></div>
            </div>
            <div class="text-muted small">
                Nilai Akumulasi CF: <code class="text-dark px-2 py-1 rounded" style="background: var(--surface-light); border: 1px solid var(--border-light);">{{ $riwayat->nilai_cf_akhir }}</code>
            </div>
        </div>
        <div class="d-none d-md-block text-center pe-3">
            <div class="p-3 rounded-circle" style="background: var(--accent-red-subtle); border: 1px solid var(--accent-red-border);">
                <i class="bi bi-laptop text-accent-red" style="font-size: 4.8rem; filter: drop-shadow(0 0 14px var(--accent-red-glow));"></i>
            </div>
        </div>
    </div>
</div>

<!-- Rekomendasi Solusi Penanganan -->
<div class="gaming-card mb-4" style="border-left: 4px solid var(--accent-green) !important;">
    <div class="p-3 border-bottom d-flex align-items-center justify-content-between" style="border-color: var(--border-light) !important; background: var(--surface-light);">
        <div class="fw-bold text-dark fs-6">
            <i class="bi bi-tools text-accent-green me-2"></i>Rekomendasi Tindakan & Solusi Penanganan
        </div>
        <span class="badge" style="background: rgba(5, 150, 105, 0.12); color: var(--accent-green);">Panduan Mandiri</span>
    </div>
    <div class="p-4">
        <div class="p-3 rounded mb-3" style="background: var(--surface-light); border: 1px solid var(--border-light);">
            <div class="fw-semibold text-accent-cyan mb-1"><i class="bi bi-info-circle me-1"></i>Deskripsi Kerusakan:</div>
            <p class="text-muted small mb-0">{{ $riwayat->kerusakanUtama->deskripsi ?: 'Tidak ada keterangan tambahan.' }}</p>
        </div>
        <div class="fw-bold text-dark mb-2">Panduan Tindakan yang Disarankan:</div>
        <p class="text-dark mb-0 font-body" style="line-height:1.8; font-size:0.95rem;">
            {!! nl2br(e($riwayat->kerusakanUtama->solusi)) !!}
        </p>
    </div>
</div>
@else
<div class="alert alert-warning mb-4 shadow-sm border-0 bg-warning bg-opacity-10 text-warning">
    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
    <strong>Gejala belum spesifik:</strong> Tidak ada kerusakan yang terdeteksi dengan tingkat signifikansi yang cukup. Coba pilih lebih banyak gejala yang dialami laptop Anda.
</div>
@endif

<!-- Matriks Peringkat Kerusakan Lainnya -->
@if (!empty($hasilLain) && count($hasilLain) > 1)
<div class="gaming-card mb-4">
    <div class="p-3 border-bottom d-flex align-items-center justify-content-between" style="border-color: var(--border-light) !important; background: var(--surface-light);">
        <span class="fw-bold text-dark fs-6">
            <i class="bi bi-bar-chart-line text-accent-red me-2"></i>Peringkat Kerusakan Lainnya
        </span>
        <small class="text-muted">Urutan probabilitas berdasarkan kombinasi Certainty Factor</small>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th class="ps-4">Rank</th>
                    <th>Kode</th>
                    <th>Nama Kerusakan</th>
                    <th>Nilai CF</th>
                    <th style="min-width: 180px;">Persentase</th>
                    <th class="pe-4">Tingkat</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($hasilLain as $idx => $res)
                    @php
                        $isTop = $idx === 0;
                        $pctVal = (float)$res['persentase'];
                        $badgeCls = $pctVal >= 75 ? 'danger' : ($pctVal >= 40 ? 'warning' : 'secondary');
                    @endphp
                    <tr>
                        <td class="ps-4 font-mono fw-bold text-dark">#{{ $idx + 1 }}</td>
                        <td><span class="badge" style="background: var(--accent-red-subtle); color: var(--accent-red);">{{ $res['kode_kerusakan'] }}</span></td>
                        <td class="fw-semibold text-dark">
                            {{ $res['nama_kerusakan'] }}
                            @if ($isTop)
                                <span class="badge bg-danger ms-2 small">UTAMA</span>
                            @endif
                        </td>
                        <td class="font-mono text-muted">{{ number_format($res['cf'], 4) }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1" style="height: 6px; background: #E2E8F0;">
                                    <div class="progress-bar bg-{{ $badgeCls }}" style="width: {{ $pctVal }}%;"></div>
                                </div>
                                <span class="text-dark small fw-semibold" style="min-width: 45px;">{{ $pctVal }}%</span>
                            </div>
                        </td>
                        <td class="pe-4">
                            <span class="badge bg-{{ $badgeCls }} bg-opacity-25 text-{{ $badgeCls }}">
                                {{ $res['kategori'] }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<!-- Rincian Bukti & Log Gejala -->
<div class="gaming-card mb-4">
    <div class="p-3 border-bottom d-flex align-items-center justify-content-between" style="border-color: var(--border-light) !important; background: var(--surface-light);">
        <span class="fw-bold text-dark fs-6">
            <i class="bi bi-journal-code text-accent-cyan me-2"></i>Rincian Gejala yang Dipilih
        </span>
        <span class="badge" style="background: var(--accent-red-subtle); color: var(--accent-red);">{{ count($detailsWithCf ?? []) }} Gejala</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th class="ps-4">No</th>
                    <th>Kode</th>
                    <th>Gejala Kerusakan</th>
                    <th>Tingkat Keyakinan</th>
                    <th>CF User</th>
                    <th>CF Pakar</th>
                    <th class="pe-4">CF Gejala</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($detailsWithCf ?? [] as $idx => $det)
                    <tr>
                        <td class="ps-4 font-mono text-muted">{{ $idx + 1 }}</td>
                        <td>
                            <span class="badge" style="background: rgba(2, 132, 199, 0.12); color: var(--accent-cyan);">
                                {{ $det['kode_gejala'] ?: '-' }}
                            </span>
                        </td>
                        <td class="text-dark">{{ $det['nama_gejala'] ?: '-' }}</td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                {{ $det['label_keyakinan'] }}
                            </span>
                        </td>
                        <td class="font-mono text-muted">
                            {{ number_format((float)$det['cf_user'], 2) }}
                        </td>
                        <td class="font-mono text-muted">
                            {{ $det['cf_pakar'] !== null ? number_format((float)$det['cf_pakar'], 2) : '-' }}
                        </td>
                        <td class="pe-4 font-mono text-accent-red fw-bold">
                            {{ $det['cf_gejala'] !== null ? number_format((float)$det['cf_gejala'], 4) : '-' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
