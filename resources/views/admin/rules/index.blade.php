@extends('layouts.app')

@section('title', 'Basis Pengetahuan — CertiLap')

@section('content')
<div class="page-header">
    <h4><i class="bi bi-diagram-3 me-2 text-primary"></i>Basis Pengetahuan (CF Pakar)</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-plus-lg me-1"></i>Tambah Rule
    </button>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" style="font-size:.85rem;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kerusakan</th>
                        <th>Gejala</th>
                        <th class="text-center">MB</th>
                        <th class="text-center">MD</th>
                        <th class="text-center">CF Pakar</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($dataList as $i => $row)
                    @php
                        $cf = (float) $row->cf_pakar;
                        $cfCls = $cf >= 0.6 ? 'success' : ($cf >= 0.4 ? 'warning' : 'secondary');
                    @endphp
                    <tr>
                        <td class="text-muted">{{ $i + 1 }}</td>
                        <td>
                            <span class="badge bg-danger me-1">{{ $row->kerusakan->kode_kerusakan ?? '-' }}</span>
                            {{ $row->kerusakan->nama_kerusakan ?? 'Kerusakan Terhapus' }}
                        </td>
                        <td>
                            <span class="badge bg-warning text-dark me-1">{{ $row->gejala->kode_gejala ?? '-' }}</span>
                            {{ Str::limit($row->gejala->nama_gejala ?? 'Gejala Terhapus', 55) }}
                        </td>
                        <td class="text-center">{{ $row->mb }}</td>
                        <td class="text-center">{{ $row->md }}</td>
                        <td class="text-center"><span class="badge bg-{{ $cfCls }}">{{ $cf }}</span></td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-primary me-1"
                                    data-bs-toggle="modal" data-bs-target="#modalEdit{{ $row->id_rule }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="{{ route('admin.rules.destroy', $row->id_rule) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger btn-hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                    <!-- Modal Edit -->
                    <div class="modal fade" id="modalEdit{{ $row->id_rule }}" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title fw-bold">Edit Rule CF</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.rules.update', $row->id_rule) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold small">Kerusakan</label>
                                                <select class="form-select" name="id_kerusakan" required>
                                                    @foreach ($allKerusakan as $k)
                                                        <option value="{{ $k->id_kerusakan }}" {{ $k->id_kerusakan == $row->id_kerusakan ? 'selected' : '' }}>
                                                            [{{ $k->kode_kerusakan }}] {{ $k->nama_kerusakan }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold small">Gejala</label>
                                                <select class="form-select" name="id_gejala" required>
                                                    @foreach ($allGejala as $g)
                                                        <option value="{{ $g->id_gejala }}" {{ $g->id_gejala == $row->id_gejala ? 'selected' : '' }}>
                                                            [{{ $g->kode_gejala }}] {{ Str::limit($g->nama_gejala, 50) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold small">MB</label>
                                                <input type="number" class="form-control mb-edit" name="mb" step="0.1" min="0" max="1" value="{{ $row->mb }}" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold small">MD</label>
                                                <input type="number" class="form-control md-edit" name="md" step="0.1" min="0" max="1" value="{{ $row->md }}" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold small">CF Pakar</label>
                                                <div class="form-control bg-light fw-bold text-center cf-preview-edit">{{ $row->cf_pakar }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Perbarui</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Tambah Rule CF Pakar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.rules.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Kerusakan (Hipotesis)</label>
                            <select class="form-select" name="id_kerusakan" required>
                                <option value="">-- Pilih Kerusakan --</option>
                                @foreach ($allKerusakan as $k)
                                    <option value="{{ $k->id_kerusakan }}">[{{ $k->kode_kerusakan }}] {{ $k->nama_kerusakan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Gejala (Evidence)</label>
                            <select class="form-select" name="id_gejala" required>
                                <option value="">-- Pilih Gejala --</option>
                                @foreach ($allGejala as $g)
                                    <option value="{{ $g->id_gejala }}">[{{ $g->kode_gejala }}] {{ Str::limit($g->nama_gejala, 60) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">MB (Measure of Belief) <span class="text-muted">0.0–1.0</span></label>
                            <input type="number" class="form-control" id="mb" name="mb" step="0.1" min="0" max="1" value="0.0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">MD (Measure of Disbelief) <span class="text-muted">0.0–1.0</span></label>
                            <input type="number" class="form-control" id="md" name="md" step="0.1" min="0" max="1" value="0.0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">CF Pakar (MB − MD)</label>
                            <div class="form-control bg-light fw-bold text-center" id="cfPakarPreview" style="color:#16A34A;">0.00</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

