@extends('layouts.app')

@section('title', 'Manajemen Pengguna — CertiLap')

@section('content')
<div class="page-header">
    <h4><i class="bi bi-people me-2 text-info"></i>Manajemen Pengguna</h4>
    <span class="badge bg-secondary">{{ count($dataList) }} Total</span>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Diagnosa</th>
                        <th>Terdaftar</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($dataList as $i => $row)
                    <tr>
                        <td class="text-muted small">{{ $i + 1 }}</td>
                        <td class="fw-semibold">{{ $row->name }}</td>
                        <td>{{ $row->username }}</td>
                        <td class="text-muted small">{{ $row->email ?? '-' }}</td>
                        <td>
                            <span class="badge bg-{{ $row->role === 'admin' ? 'warning text-dark' : 'primary' }}">
                                {{ ucfirst($row->role) }}
                            </span>
                        </td>
                        <td class="text-center"><span class="badge bg-light text-dark border">{{ $row->riwayat_count }}</span></td>
                        <td class="text-muted small">{{ $row->created_at ? $row->created_at->format('d M Y') : '-' }}</td>
                        <td class="text-center">
                            @if ($row->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $row->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger btn-hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            @else
                                <span class="badge bg-light text-muted">Akun Anda</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

