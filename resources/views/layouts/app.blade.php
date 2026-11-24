<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CertiLap — Sistem Pakar Diagnosa Kerusakan Laptop')</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Custom Clean White & Soft Red CSS -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>

<!-- Main Top Navbar (Satu Kesatuan Desain dengan Beranda) -->
<nav class="navbar navbar-expand-lg navbar-app sticky-top py-3">
    <div class="container">
        <!-- Logo Brand -->
        <a class="navbar-brand d-flex align-items-center gap-3 text-decoration-none" href="{{ url('/') }}">
            <div class="d-flex align-items-center justify-content-center p-2 rounded"
                 style="background: var(--accent-red-subtle); border: 1px solid var(--accent-red-border); box-shadow: 0 0 10px var(--accent-red-glow);">
                <i class="bi bi-laptop fs-4 text-accent-red"></i>
            </div>
            <div>
                <div class="font-heading fs-4 text-dark lh-1">CERTI<span class="text-accent-red">LAP</span></div>
                <small class="text-muted" style="font-size: 11px; letter-spacing: 0.5px;">SISTEM PAKAR KERUSAKAN LAPTOP</small>
            </div>
        </a>

        <!-- Mobile Toggler -->
        <button class="navbar-toggler border-0 text-accent-red" type="button" data-bs-toggle="collapse" data-bs-target="#navApp">
            <i class="bi bi-list fs-2"></i>
        </button>

        <!-- Navbar Links & Profile -->
        <div class="collapse navbar-collapse" id="navApp">
            @auth
                <!-- Menu Navigasi Pengguna & Admin -->
                <ul class="navbar-nav me-auto ms-lg-4 align-items-lg-center gap-1 pt-3 pt-lg-0">
                    @if(auth()->user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-speedometer2 me-1 text-accent-cyan"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.kerusakan.*') ? 'active' : '' }}" href="{{ route('admin.kerusakan.index') }}">
                                <i class="bi bi-exclamation-triangle me-1 text-accent-red"></i>Kerusakan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.gejala.*') ? 'active' : '' }}" href="{{ route('admin.gejala.index') }}">
                                <i class="bi bi-clipboard-pulse me-1 text-accent-amber"></i>Gejala
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.rules.*') ? 'active' : '' }}" href="{{ route('admin.rules.index') }}">
                                <i class="bi bi-diagram-3 me-1 text-accent-red"></i>Basis CF
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                                <i class="bi bi-people me-1 text-accent-green"></i>Pengguna
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.riwayat.*') ? 'active' : '' }}" href="{{ route('admin.riwayat.index') }}">
                                <i class="bi bi-clock-history me-1 text-accent-cyan"></i>Riwayat
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}" href="{{ route('user.dashboard') }}">
                                <i class="bi bi-house me-1 text-accent-cyan"></i>Beranda
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('user.diagnosa.*') ? 'active' : '' }}" href="{{ route('user.diagnosa.index') }}">
                                <i class="bi bi-search-heart me-1 text-accent-red"></i>Diagnosa Laptop
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('user.riwayat.*') ? 'active' : '' }}" href="{{ route('user.riwayat.index') }}">
                                <i class="bi bi-clock-history me-1 text-accent-amber"></i>Riwayat Diagnosa
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('user.profil') ? 'active' : '' }}" href="{{ route('user.profil') }}">
                                <i class="bi bi-person-circle me-1 text-accent-green"></i>Profil Saya
                            </a>
                        </li>
                    @endif
                </ul>

                <!-- Profile Dropdown & Quick Actions -->
                <div class="d-flex align-items-center gap-3 pt-3 pt-lg-0">
                    @if(!auth()->user()->isAdmin())
                        <a href="{{ route('user.diagnosa.index') }}" class="btn btn-gaming-primary btn-sm d-none d-xl-inline-flex">
                            <i class="bi bi-plus-lg me-1"></i>Diagnosa Baru
                        </a>
                    @endif

                    <!-- User Account Dropdown -->
                    <div class="dropdown">
                        <button class="btn btn-outline-light border d-flex align-items-center gap-2 p-1 pe-3 rounded-pill bg-white shadow-sm"
                                type="button" data-bs-toggle="dropdown" aria-expanded="false" style="border-color: var(--border-light) !important;">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                                 style="width: 32px; height: 32px; background: linear-gradient(135deg, var(--accent-red), #BE123C); font-size: 13px;">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <div class="text-start d-none d-sm-block">
                                <div class="fw-bold text-dark small lh-1">{{ Str::limit(auth()->user()->name, 14) }}</div>
                                <span class="badge p-0 text-muted" style="font-size: 10px;">
                                    {{ auth()->user()->isAdmin() ? 'Administrator' : 'Pengguna Aktif' }}
                                </span>
                            </div>
                            <i class="bi bi-chevron-down text-muted small ms-1"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border p-2 mt-2" style="border-color: var(--border-light) !important; min-width: 200px;">
                            <li class="px-2 py-1 mb-1 border-bottom">
                                <div class="fw-bold text-dark small">{{ auth()->user()->name }}</div>
                                <small class="text-muted">{{ auth()->user()->username }}</small>
                            </li>
                            <li>
                                <a class="dropdown-item rounded small py-2" href="{{ auth()->user()->isAdmin() ? route('admin.profil') : route('user.profil') }}">
                                    <i class="bi bi-person me-2 text-muted"></i>Profil Saya
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item rounded small py-2" href="{{ url('/') }}">
                                    <i class="bi bi-house me-2 text-muted"></i>Halaman Depan
                                </a>
                            </li>
                            <li><hr class="dropdown-divider my-1"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item rounded small py-2 text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>Keluar Akun
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            @else
                <!-- Guest View Links -->
                <ul class="navbar-nav ms-auto align-items-center gap-2 pt-3 pt-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}#alur">Alur Konsultasi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}#simulasi">Simulasi CF</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}#kategori">10 Kerusakan</a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a href="{{ route('login') }}" class="btn btn-gaming-outline btn-sm">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Masuk
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('register') }}" class="btn btn-gaming-primary btn-sm">
                            <i class="bi bi-person-plus me-1"></i>Daftar
                        </a>
                    </li>
                </ul>
            @endauth
        </div>
    </div>
</nav>

<!-- Main Page Content Container -->
<main class="main-content">
    <div class="container py-4">
        <!-- Floating Flash Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-auto-dismiss d-flex align-items-center gap-2 py-2 border-0 bg-success bg-opacity-10 text-success mb-4 shadow-sm">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-auto-dismiss d-flex align-items-center gap-2 py-2 border-0 bg-danger bg-opacity-10 text-danger mb-4 shadow-sm">
                <i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-auto-dismiss py-2 border-0 bg-danger bg-opacity-10 text-danger mb-4 shadow-sm">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>
</main>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->
<script src="{{ asset('assets/js/script.js') }}"></script>
@stack('scripts')
</body>
</html>
