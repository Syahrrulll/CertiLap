@extends('layouts.app')

@section('title', 'Manajemen Kerusakan — CertiLap')

@section('content')
<div class="page-header">
    <h4><i class="bi bi-exclamation-triangle me-2 text-danger"></i>Manajemen Kerusakan</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-plus-lg me-1"></i>Tambah Kerusakan
    </button>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width:60px">#</th>
                        <th>Kode</th>
                        <th>Nama Kerusakan</th>
                        <th>Solusi</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($dataList as $i => $row)
                    <tr>
                        <td class="text-muted small">{{ $i + 1 }}</td>
                        <td><span class="badge bg-danger">{{ $row->kode_kerusakan }}</span></td>
                        <td class="fw-semibold">{{ $row->nama_kerusakan }}</td>
                        <td class="text-muted small" style="max-width:320px;">{{ Str::limit($row->solusi, 90) }}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-primary me-1"
                                    data-bs-toggle="modal" data-bs-target="#modalEdit{{ $row->id_kerusakan }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="{{ route('admin.kerusakan.destroy', $row->id_kerusakan) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger btn-hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                    <!-- Modal Edit -->
                    <div class="modal fade" id="modalEdit{{ $row->id_kerusakan }}" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title fw-bold">Edit Kerusakan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.kerusakan.update', $row->id_kerusakan) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold small">Kode Kerusakan</label>
                                                <input type="text" class="form-control" name="kode_kerusakan" value="{{ $row->kode_kerusakan }}" required>
                                            </div>
                                            <div class="col-md-8">
                                                <label class="form-label fw-semibold small">Nama Kerusakan</label>
                                                <input type="text" class="form-control" name="nama_kerusakan" value="{{ $row->nama_kerusakan }}" required>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label fw-semibold small">Deskripsi</label>
                                                <textarea class="form-control" name="deskripsi" rows="2">{{ $row->deskripsi }}</textarea>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label fw-semibold small">Solusi</label>
                                                <textarea class="form-control" name="solusi" rows="3" required>{{ $row->solusi }}</textarea>
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
                <h5 class="modal-title fw-bold">Tambah Data Kerusakan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.kerusakan.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Kode Kerusakan</label>
                            <input type="text" class="form-control" name="kode_kerusakan" placeholder="K01" required maxlength="10">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-semibold small">Nama Kerusakan</label>
                            <input type="text" class="form-control" name="nama_kerusakan" placeholder="Nama kategori kerusakan" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" rows="2" placeholder="Penjelasan singkat kerusakan"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Solusi Penanganan <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="solusi" rows="3" placeholder="Langkah penanganan dan perbaikan" required></textarea>
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

