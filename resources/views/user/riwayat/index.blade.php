@extends('layouts.app')

@section('title', 'Riwayat Diagnosa Saya — CertiLap')

@section('content')
<div class="page-header d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
    <div>
        <h3 class="font-heading text-dark mb-1 fs-3">
            <i class="bi bi-clock-history text-accent-red me-2"></i>Riwayat Diagnosa Saya
        </h3>
        <p class="text-muted small mb-0 font-body">Daftar arsip seluruh pengujian Certainty Factor yang pernah Anda lakukan.</p>
    </div>
    <a href="{{ route('user.diagnosa.index') }}" class="btn btn-gaming-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Diagnosa Baru
    </a>
</div>

@if ($dataList->isEmpty())
<div class="gaming-card text-center py-5">
    <div class="p-3 d-inline-flex rounded-circle mb-3" style="background: var(--accent-red-subtle); border: 1px solid var(--accent-red-border);">
        <i class="bi bi-clipboard-x text-accent-red" style="font-size:2.8rem;"></i>
    </div>
    <h5 class="text-dark mb-2">Belum Ada Riwayat Diagnosa</h5>
    <p class="text-muted small mb-3">Mulai konsultasi pertama untuk memeriksa kondisi laptop Anda!</p>
    <a href="{{ route('user.diagnosa.index') }}" class="btn btn-gaming-primary">
        <i class="bi bi-lightning-charge-fill me-1"></i>Mulai Diagnosa Sekarang
    </a>
</div>
@else
<div class="gaming-card">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead>
                <tr>
                    <th class="ps-4">No</th>
                    <th>Kerusakan Terdeteksi</th>
                    <th>CF Akhir</th>
                    <th>Tingkat Keyakinan (%)</th>
                    <th>Waktu Konsultasi</th>
                    <th class="text-center pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($dataList as $i => $row)
                @php
                    $pct = (float) $row->persentase;
                    $cls = $pct >= 75 ? 'danger' : ($pct >= 40 ? 'warning' : 'secondary');
                @endphp
                <tr>
                    <td class="ps-4 font-mono text-muted">{{ $i + 1 }}</td>
                    <td>
                        @if ($row->kerusakanUtama)
                            <span class="badge me-1" style="background: var(--accent-red-subtle); color: var(--accent-red); border: 1px solid var(--accent-red-border);">{{ $row->kerusakanUtama->kode_kerusakan }}</span>
                            <span class="fw-semibold text-dark">{{ $row->kerusakanUtama->nama_kerusakan }}</span>
                        @else
                            <span class="text-muted">Tidak ada kecocokan</span>
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
                        <div class="d-flex justify-content-center gap-1">
                            <a href="{{ route('user.diagnosa.hasil', $row->id_riwayat) }}" class="btn btn-gaming-secondary btn-sm py-1 px-2" title="Lihat Hasil">
                                <i class="bi bi-file-earmark-medical"></i>
                            </a>
                            <form method="POST" action="{{ route('user.riwayat.hapus', $row->id_riwayat) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-gaming-outline btn-sm py-1 px-2 btn-hapus" title="Hapus Riwayat">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
