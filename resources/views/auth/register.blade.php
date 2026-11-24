<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Baru — CertiLap</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Sleek Gaming Hardware CSS -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
</head>
<body>
<div class="auth-wrapper">
    <div class="auth-card" style="max-width:480px;">
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center p-3 mb-2 rounded"
                 style="background: var(--accent-red-subtle); border: 1px solid var(--accent-red); box-shadow: 0 0 16px var(--accent-red-glow);">
                <i class="bi bi-person-plus text-accent-red fs-3"></i>
            </div>
            <h4 class="font-heading fs-3 text-white mb-1">Daftar Akun Baru</h4>
            <p class="text-muted small mb-0">CertiLap — Sistem Pakar Diagnosa Kerusakan Laptop</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger alert-auto-dismiss py-2 border-0 bg-danger bg-opacity-10 text-danger small">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.post') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label text-white small fw-semibold">Nama Lengkap <span class="text-accent-red">*</span></label>
                <input type="text" class="form-control" name="name" placeholder="Nama lengkap Anda"
                       value="{{ old('name') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label text-white small fw-semibold">Username <span class="text-accent-red">*</span></label>
                <input type="text" class="form-control" name="username" placeholder="Username unik (tanpa spasi)"
                       value="{{ old('username') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label text-white small fw-semibold">Email <span class="text-muted">(opsional)</span></label>
                <input type="email" class="form-control" name="email" placeholder="email@contoh.com"
                       value="{{ old('email') }}">
            </div>
            <div class="mb-3">
                <label class="form-label text-white small fw-semibold">Password <span class="text-accent-red">*</span></label>
                <input type="password" class="form-control" name="password" placeholder="Minimal 6 karakter" required>
            </div>
            <div class="mb-4">
                <label class="form-label text-white small fw-semibold">Konfirmasi Password <span class="text-accent-red">*</span></label>
                <input type="password" class="form-control" name="password_confirmation" placeholder="Ulangi password" required>
            </div>
            <button type="submit" class="btn btn-gaming-primary w-100 py-2 fs-6">
                <i class="bi bi-person-check-fill me-2"></i>Daftar Sekarang
            </button>
        </form>

        <hr class="my-4" style="border-color: var(--slate-border);">
        <p class="text-center small text-muted mb-0">
            Sudah memiliki akun?
            <a href="{{ route('login') }}" class="text-accent-red fw-bold text-decoration-none ms-1">Masuk di sini</a>
        </p>
        <p class="text-center mt-2 mb-0">
            <a href="{{ url('/') }}" class="text-dim small text-decoration-none">&larr; Kembali ke Beranda</a>
        </p>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('assets/js/script.js') }}"></script>
</body>
</html>
