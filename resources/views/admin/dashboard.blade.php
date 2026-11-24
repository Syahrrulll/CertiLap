@extends('layouts.app')

@section('title', 'Dashboard Admin — CertiLap')

@section('content')
<div class="page-header d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
    <div>
        <h3 class="font-heading text-dark mb-1 fs-3">
            <i class="bi bi-speedometer2 text-accent-red me-2"></i>Ringkasan Sistem & Statistik Pakar
        </h3>
        <p class="text-muted small mb-0 font-body">Pantau basis pengetahuan, data gejala, dan seluruh riwayat konsultasi pengguna.</p>
    </div>
    <div class="p-2 px-3 rounded d-flex align-items-center gap-2 small" style="background: #FFFFFF; border: 1px solid var(--border-light);">
        <span class="status-dot" style="background-color: var(--accent-green);"></span>
        <span class="text-dark fw-semibold">{{ date('d F Y') }}</span>
        <span class="text-dim">|</span>
        <span class="text-accent-green">Sistem Aktif</span>
    </div>
</div>

<!-- Statistik Kartu Formal White & Soft Red -->
<div class="row g-3 mb-4">
    @php
        $cards = [
            ['icon'=>'bi-exclamation-triangle-fill', 'c_text'=>'var(--accent-red)',   'bg'=>'var(--accent-red-subtle)', 'border'=>'var(--accent-red-border)', 'label'=>'Total Kerusakan',  'val'=>$stats['kerusakan'], 'desc'=>'10 Kategori Standar'],
            ['icon'=>'bi-clipboard-pulse',          'c_text'=>'var(--accent-amber)', 'bg'=>'#FFFBEB',                 'border'=>'#FDE68A',                 'label'=>'Total Gejala',     'val'=>$stats['gejala'],    'desc'=>'40 Gejala Terdaftar'],
            ['icon'=>'bi-diagram-3-fill',           'c_text'=>'var(--accent-red)',   'bg'=>'var(--accent-red-subtle)', 'border'=>'var(--accent-red-border)', 'label'=>'Basis Aturan CF',  'val'=>$stats['rules'],     'desc'=>'Kaidah MB & MD Pakar'],
            ['icon'=>'bi-search-heart-fill',        'c_text'=>'var(--accent-cyan)',  'bg'=>'#F0F9FF',                 'border'=>'#BAE6FD',                 'label'=>'Total Konsultasi', 'val'=>$stats['diagnosa'],  'desc'=>'Konsultasi Selesai'],
            ['icon'=>'bi-people-fill',              'c_text'=>'var(--accent-green)', 'bg'=>'#ECFDF5',                 'border'=>'#A7F3D0',                 'label'=>'Pengguna Aktif',   'val'=>$stats['users'],     'desc'=>'Akun Non-Admin'],
        ];
    @endphp
    @foreach ($cards as $c)
    <div class="col-sm-6 col-xl-4">
        <div class="card-stat">
            <div class="stat-icon" style="background: {{ $c['bg'] }}; color: {{ $c['c_text'] }}; border: 1px solid {{ $c['border'] }};">
                <i class="bi {{ $c['icon'] }}"></i>
            </div>
            <div>
                <div class="text-muted small fw-semibold" style="font-size: 11px;">{{ $c['label'] }}</div>
                <div class="font-heading fs-3 text-dark">{{ $c['val'] }}</div>
                <small class="text-muted" style="font-size:11px;">{{ $c['desc'] }}</small>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Riwayat Diagnosa Terbaru -->
<div class="gaming-card">
    <div class="p-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2" style="border-color: var(--border-light) !important; background: var(--surface-light);">
        <div class="d-flex align-items-center gap-2">
            <span class="p-2 rounded text-accent-red" style="background: var(--accent-red-subtle);">
                <i class="bi bi-clock-history fs-5"></i>
            </span>
            <span class="fw-bold text-dark fs-6">Riwayat Diagnosa Terkini</span>
        </div>
        <a href="{{ route('admin.riwayat.index') }}" class="btn btn-gaming-secondary btn-sm">
            Lihat Semua Riwayat <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead>
                <tr>
                    <th class="ps-4">No</th>
                    <th>Nama Pengguna</th>
                    <th>Kerusakan Terdeteksi</th>
                    <th>Nilai CF</th>
                    <th>Keyakinan (%)</th>
                    <th>Waktu Diagnosa</th>
                    <th class="text-center pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($riwayatTerbaru as $i => $row)
                    @php
                        $pct = (float) $row->persentase;
                        $cls = $pct >= 75 ? 'danger' : ($pct >= 40 ? 'warning' : 'secondary');
                    @endphp
                    <tr>
                        <td class="ps-4 font-mono text-muted">{{ $i + 1 }}</td>
                        <td>
                            <div class="fw-bold text-dark">{{ $row->user->name ?? 'Guest/Deleted' }}</div>
                            <small class="text-muted">@ {{ $row->user->username ?? '-' }}</small>
                        </td>
                        <td>
                            @if ($row->kerusakanUtama)
                                <span class="badge me-1" style="background: var(--accent-red-subtle); color: var(--accent-red); border: 1px solid var(--accent-red-border);">{{ $row->kerusakanUtama->kode_kerusakan }}</span>
                                <span class="text-dark fw-medium">{{ $row->kerusakanUtama->nama_kerusakan }}</span>
                            @else
                                <span class="text-muted">Tidak Terdeteksi</span>
                            @endif
                        </td>
                        <td class="font-mono text-muted">{{ $row->nilai_cf_akhir }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1" style="height:6px; min-width:60px; background: #E2E8F0;">
                                    <div class="progress-bar bg-{{ $cls }}" style="width:{{ $pct }}%"></div>
                                </div>
                                <span class="text-dark small fw-semibold">{{ $pct }}%</span>
                            </div>
                        </td>
                        <td class="text-muted small">{{ $row->tanggal_diagnosa->format('d/m/Y H:i') }}</td>
                        <td class="text-center pe-4">
                            <a href="{{ route('admin.riwayat.detail', $row->id_riwayat) }}" class="btn btn-gaming-secondary btn-sm py-1 px-2" title="Lihat Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            Belum ada riwayat konsultasi tercatat.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
