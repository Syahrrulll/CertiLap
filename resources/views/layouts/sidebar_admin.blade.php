<!-- Sidebar Desktop Admin -->
<div class="sidebar d-none d-lg-flex flex-column" style="width:260px; min-height:100vh; position:fixed; top:0; left:0; z-index:1000; background: #FFFFFF; border-right: 1px solid var(--border-light);">
    <div class="p-4 border-bottom" style="border-color: var(--border-light) !important;">
        <a href="{{ route('admin.dashboard') }}" class="text-decoration-none d-flex align-items-center gap-3">
            <div class="p-2 d-flex align-items-center justify-content-center rounded"
                 style="background: var(--accent-red-subtle); border: 1px solid var(--accent-red-border); box-shadow: 0 0 10px var(--accent-red-glow);">
                <i class="bi bi-laptop text-accent-red fs-4"></i>
            </div>
            <div>
                <div class="font-heading fs-5 text-dark lh-1">CERTI<span class="text-accent-red">LAP</span></div>
                <small class="text-muted" style="font-size:11px;">Panel Administrator</small>
            </div>
        </a>
    </div>

    <!-- Admin Profile Badge -->
    <div class="p-3 border-bottom" style="border-color: var(--border-light) !important;">
        <div class="d-flex align-items-center gap-3 p-2 rounded" style="background: var(--surface-light); border: 1px solid var(--border-light);">
            <div class="rounded d-flex align-items-center justify-content-center text-white fw-bold"
                 style="width:38px; height:38px; background: linear-gradient(135deg, var(--accent-red), #BE123C); box-shadow: 0 0 10px var(--accent-red-glow);">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
            <div class="overflow-hidden">
                <div class="fw-bold small text-dark text-truncate">{{ auth()->user()->name }}</div>
                <span class="badge" style="background: var(--accent-red-subtle); color: var(--accent-red); font-size:10px;">Pakar / Admin</span>
            </div>
        </div>
    </div>

    <nav class="flex-grow-1 p-3">
        <div class="text-muted small fw-bold mb-2 px-2" style="font-size:11px; letter-spacing:0.5px;">MENU UTAMA</div>
        <ul class="nav flex-column gap-1">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-speedometer2 me-2 text-accent-cyan"></i>Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.kerusakan.*') ? 'active' : '' }}" href="{{ route('admin.kerusakan.index') }}">
                    <i class="bi bi-exclamation-triangle me-2 text-accent-red"></i>Data Kerusakan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.gejala.*') ? 'active' : '' }}" href="{{ route('admin.gejala.index') }}">
                    <i class="bi bi-clipboard-pulse me-2 text-accent-amber"></i>Data Gejala
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.rules.*') ? 'active' : '' }}" href="{{ route('admin.rules.index') }}">
                    <i class="bi bi-diagram-3 me-2 text-accent-red"></i>Basis Aturan CF
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                    <i class="bi bi-people me-2 text-accent-green"></i>Data Pengguna
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.riwayat.*') ? 'active' : '' }}" href="{{ route('admin.riwayat.index') }}">
                    <i class="bi bi-clock-history me-2 text-accent-cyan"></i>Riwayat Diagnosa
                </a>
            </li>
        </ul>

        <div class="text-muted small fw-bold mb-2 px-2 mt-4" style="font-size:11px; letter-spacing:0.5px;">LAINNYA</div>
        <ul class="nav flex-column gap-1">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.profil') ? 'active' : '' }}" href="{{ route('admin.profil') }}">
                    <i class="bi bi-person-gear me-2 text-muted"></i>Profil Admin
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/') }}">
                    <i class="bi bi-house me-2 text-muted"></i>Halaman Depan
                </a>
            </li>
        </ul>
    </nav>

    <!-- Logout Button -->
    <div class="p-3 border-top" style="border-color: var(--border-light) !important;">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-gaming-outline w-100 py-2 small">
                <i class="bi bi-box-arrow-right me-2"></i>Keluar Sistem
            </button>
        </form>
    </div>
</div>
