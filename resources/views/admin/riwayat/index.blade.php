@extends('layouts.app')

@section('title', 'Riwayat Diagnosa — CertiLap')

@section('content')
<div class="page-header">
    <h4><i class="bi bi-clock-history me-2 text-primary"></i>Semua Riwayat Diagnosa</h4>
    <span class="badge bg-secondary">{{ count($dataList) }} Total</span>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Pengguna</th>
                        <th>Kerusakan Terdeteksi</th>
                        <th>CF Akhir</th>
                        <th>Keyakinan (%)</th>
                        <th>Tanggal</th>
                        <th class="text-center">Detail</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($dataList as $i => $row)
                    @php
                        $pct = (float) $row->persentase;
                        $cls = $pct >= 75 ? 'success' : ($pct >= 40 ? 'warning' : 'danger');
                    @endphp
                    <tr>
                        <td class="text-muted small">{{ $i + 1 }}</td>
                        <td class="fw-semibold">{{ $row->user->name ?? 'User Terhapus' }}</td>
                        <td>
                            @if ($row->kerusakanUtama)
                                <span class="badge bg-danger me-1">{{ $row->kerusakanUtama->kode_kerusakan }}</span>
                                {{ $row->kerusakanUtama->nama_kerusakan }}
                            @else
                                <span class="text-muted">Tidak ada kecocokan</span>
                            @endif
                        </td>
                        <td class="font-monospace">{{ $row->nilai_cf_akhir }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1" style="height:6px; min-width:60px;">
                                    <div class="progress-bar bg-{{ $cls }}" style="width:{{ $pct }}%"></div>
                                </div>
                                <span class="badge bg-{{ $cls }}">{{ $pct }}%</span>
                            </div>
                        </td>
                        <td class="text-muted small">{{ $row->tanggal_diagnosa->format('d M Y H:i') }}</td>
                        <td class="text-center">
                            <a href="{{ route('user.diagnosa.hasil', $row->id_riwayat) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Belum ada riwayat diagnosa.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

