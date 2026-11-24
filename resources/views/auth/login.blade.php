<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — CertiLap</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Sleek Gaming Hardware CSS -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
</head>
<body>
<div class="auth-wrapper">
    <div class="auth-card">
        <!-- Logo Header -->
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center p-3 mb-2 rounded"
                 style="background: var(--accent-red-subtle); border: 1px solid var(--accent-red); box-shadow: 0 0 16px var(--accent-red-glow);">
                <i class="bi bi-laptop text-accent-red fs-3"></i>
            </div>
            <h4 class="font-heading fs-3 text-white mb-1">CERTI<span class="text-accent-red">LAP</span></h4>
            <p class="text-muted small mb-0">Sistem Pakar Diagnosa Kerusakan Laptop</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-auto-dismiss d-flex align-items-center gap-2 py-2 border-0 bg-success bg-opacity-10 text-success small">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-auto-dismiss d-flex align-items-center gap-2 py-2 border-0 bg-danger bg-opacity-10 text-danger small">
                <i class="bi bi-exclamation-triangle-fill"></i> {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="mb-3">
                <label for="username" class="form-label text-white small fw-semibold">Username atau Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                    <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username"
                           placeholder="Masukkan username / email" value="{{ old('username') }}" required autofocus>
                </div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label text-white small fw-semibold">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password"
                           placeholder="Masukkan password" required>
                </div>
            </div>
            <div class="mb-4 form-check">
                <input type="checkbox" class="form-check-input bg-dark border-secondary" id="remember" name="remember">
                <label class="form-check-label small text-muted" for="remember">Ingat Saya</label>
            </div>
            <button type="submit" class="btn btn-gaming-primary w-100 py-2 fs-6">
                <i class="bi bi-box-arrow-in-right me-2"></i>Masuk ke Akun
            </button>
        </form>

        <div class="text-center mt-3 p-2 rounded small" style="background: var(--slate-850); border: 1px solid var(--slate-border); font-size: 11px;">
            <div class="text-dim mb-1">Akun Percobaan (Demo):</div>
            <span class="text-white">admin</span> / <span class="text-white">admin123</span> &bull; 
            <span class="text-white">user</span> / <span class="text-white">user123</span>
        </div>

        <hr class="my-4" style="border-color: var(--slate-border);">
        <p class="text-center small text-muted mb-0">
            Belum memiliki akun?
            <a href="{{ route('register') }}" class="text-accent-red fw-bold text-decoration-none ms-1">Daftar di sini</a>
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
