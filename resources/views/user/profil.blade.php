@extends('layouts.app')

@section('title', 'Profil Saya — CertiLap')

@section('content')
<div class="page-header">
    <h4><i class="bi bi-person-circle me-2 text-accent-red"></i>Profil Saya</h4>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold border-bottom py-3">
                <i class="bi bi-person-fill me-2"></i>Data Profil
            </div>
            <div class="card-body">
                <form action="{{ route('user.profil.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nama Lengkap</label>
                        <input type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Username <span class="text-muted">(tidak bisa diubah)</span></label>
                        <input class="form-control bg-light" value="{{ $user->username }}" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Email</label>
                        <input type="email" class="form-control" name="email" value="{{ old('email', $user->email) }}">
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold border-bottom py-3">
                <i class="bi bi-lock-fill me-2"></i>Ubah Password
            </div>
            <div class="card-body">
                <form action="{{ route('user.profil.password') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Password Lama</label>
                        <input type="password" class="form-control" name="old_password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Password Baru</label>
                        <input type="password" class="form-control" name="new_password" placeholder="Min. 6 karakter" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" name="new_password_confirmation" required>
                    </div>
                    <button type="submit" class="btn btn-warning fw-semibold"><i class="bi bi-shield-lock me-1"></i>Ganti Password</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

