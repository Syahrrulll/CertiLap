@extends('layouts.app')

@section('title', 'Manajemen Gejala — CertiLap')

@section('content')
<div class="page-header">
    <h4><i class="bi bi-clipboard-pulse me-2 text-warning"></i>Manajemen Gejala</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-plus-lg me-1"></i>Tambah Gejala
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
                        <th>Nama Gejala</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($dataList as $i => $row)
                    <tr>
                        <td class="text-muted small">{{ $i + 1 }}</td>
                        <td><span class="badge bg-warning text-dark">{{ $row->kode_gejala }}</span></td>
                        <td>{{ $row->nama_gejala }}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-primary me-1"
                                    data-bs-toggle="modal" data-bs-target="#modalEdit{{ $row->id_gejala }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="{{ route('admin.gejala.destroy', $row->id_gejala) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger btn-hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                    <!-- Modal Edit -->
                    <div class="modal fade" id="modalEdit{{ $row->id_gejala }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title fw-bold">Edit Gejala</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.gejala.update', $row->id_gejala) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold small">Kode Gejala</label>
                                            <input type="text" class="form-control" name="kode_gejala" value="{{ $row->kode_gejala }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold small">Nama Gejala</label>
                                            <input type="text" class="form-control" name="nama_gejala" value="{{ $row->nama_gejala }}" required>
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
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Tambah Data Gejala</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.gejala.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Kode Gejala</label>
                        <input type="text" class="form-control" name="kode_gejala" placeholder="G01" required maxlength="10">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nama Gejala</label>
                        <input type="text" class="form-control" name="nama_gejala" placeholder="Deskripsi gejala yang dialami user" required>
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

