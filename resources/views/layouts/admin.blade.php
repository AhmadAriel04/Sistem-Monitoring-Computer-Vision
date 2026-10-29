<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') — BSI Survey</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --sidebar-active: #2563eb;
            --topbar-height: 64px;
            --accent: #2563eb;
            --accent-light: #eff6ff;
            --surface: #ffffff;
            --bg: #f1f5f9;
            --text-primary: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --radius: 12px;
            --shadow-sm: 0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
            --shadow: 0 4px 6px -1px rgba(0,0,0,.07), 0 2px 4px -1px rgba(0,0,0,.04);
            --shadow-lg: 0 10px 15px -3px rgba(0,0,0,.08), 0 4px 6px -2px rgba(0,0,0,.03);
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg);
            color: var(--text-primary);
            margin: 0;
            padding: 0;
        }

        /* ── Sidebar ─────────────────────────────────── */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar-brand {
            padding: 24px 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }

        .sidebar-brand .brand-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .sidebar-brand .brand-icon {
            width: 38px; height: 38px;
            background: var(--accent);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: #fff;
            flex-shrink: 0;
        }

        .sidebar-brand .brand-text {
            color: #fff;
            font-weight: 700;
            font-size: 15px;
            line-height: 1.2;
        }

        .sidebar-brand .brand-sub {
            color: rgba(255,255,255,.45);
            font-size: 11px;
            font-weight: 400;
        }

        .sidebar-nav {
            padding: 16px 12px;
            flex: 1;
        }

        .nav-section-label {
            color: rgba(255,255,255,.3);
            font-size: 10px;
            font-weight: 600;
            letter-spacing: .08em;
            text-transform: uppercase;
            padding: 8px 8px 4px;
            margin-top: 8px;
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,.65);
            padding: 10px 12px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13.5px;
            font-weight: 500;
            transition: all .15s ease;
            text-decoration: none;
            margin-bottom: 2px;
        }

        .sidebar .nav-link i {
            font-size: 16px;
            width: 20px;
            text-align: center;
            flex-shrink: 0;
        }

        .sidebar .nav-link:hover {
            background: var(--sidebar-hover);
            color: #fff;
        }

        .sidebar .nav-link.active {
            background: var(--accent);
            color: #fff;
            box-shadow: 0 4px 12px rgba(37,99,235,.35);
        }

        .sidebar-footer {
            padding: 16px 12px;
            border-top: 1px solid rgba(255,255,255,.08);
        }

        .sidebar-footer .logout-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 10px 12px;
            border-radius: 8px;
            background: rgba(239,68,68,.12);
            color: #f87171;
            border: none;
            font-size: 13.5px;
            font-weight: 500;
            cursor: pointer;
            transition: all .15s ease;
        }

        .sidebar-footer .logout-btn:hover {
            background: rgba(239,68,68,.2);
            color: #fca5a5;
        }

        /* ── Main wrapper ─────────────────────────────── */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── Topbar ───────────────────────────────────── */
        .topbar {
            height: var(--topbar-height);
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: var(--shadow-sm);
        }

        .topbar-title {
            font-size: 17px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-badge {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 14px 6px 8px;
            background: var(--bg);
            border-radius: 50px;
            border: 1px solid var(--border);
        }

        .user-avatar {
            width: 32px; height: 32px;
            background: var(--accent);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 13px;
            font-weight: 600;
        }

        .user-name {
            font-size: 13px;
            font-weight: 500;
            color: var(--text-primary);
        }

        /* ── Page content ─────────────────────────────── */
        .page-content {
            padding: 28px;
            flex: 1;
        }

        /* ── Alerts ───────────────────────────────────── */
        .alert {
            border: none;
            border-radius: var(--radius);
            border-left: 4px solid;
            font-size: 14px;
        }

        .alert-success {
            background: #f0fdf4;
            border-left-color: #22c55e;
            color: #15803d;
        }

        .alert-danger {
            background: #fef2f2;
            border-left-color: #ef4444;
            color: #b91c1c;
        }

        /* ── Cards ────────────────────────────────────── */
        .card {
            border: 1px solid var(--border) !important;
            border-radius: var(--radius) !important;
            box-shadow: var(--shadow-sm) !important;
        }

        .card-header {
            background: transparent !important;
            border-bottom: 1px solid var(--border) !important;
            padding: 16px 20px !important;
            font-size: 14px;
            font-weight: 600;
        }

        .card-body {
            padding: 20px !important;
        }

        /* ── Tables ───────────────────────────────────── */
        .table {
            font-size: 13.5px;
        }

        .table thead th {
            background: var(--bg);
            color: var(--text-muted);
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .06em;
            border-bottom: 1px solid var(--border) !important;
            padding: 12px 16px;
        }

        .table tbody td {
            padding: 12px 16px;
            border-bottom: 1px solid #f1f5f9 !important;
            vertical-align: middle;
        }

        .table tbody tr:hover td {
            background: var(--accent-light);
        }

        .table-bordered { border: 1px solid var(--border) !important; }
        .table-bordered td, .table-bordered th { border-color: var(--border) !important; }

        /* ── Buttons ──────────────────────────────────── */
        .btn {
            font-size: 13px;
            font-weight: 500;
            border-radius: 8px;
            padding: 8px 16px;
            border: none;
            transition: all .15s ease;
        }

        .btn-primary {
            background: var(--accent);
            color: #fff;
        }
        .btn-primary:hover { background: #1d4ed8; color: #fff; }

        .btn-warning {
            background: #f59e0b;
            color: #fff;
        }
        .btn-warning:hover { background: #d97706; color: #fff; }

        .btn-danger {
            background: #ef4444;
            color: #fff;
        }
        .btn-danger:hover { background: #dc2626; color: #fff; }

        .btn-success {
            background: #22c55e;
            color: #fff;
        }
        .btn-success:hover { background: #16a34a; color: #fff; }

        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
            border-radius: 6px;
        }

        /* ── Badges ───────────────────────────────────── */
        .badge {
            font-weight: 500;
            border-radius: 6px;
            padding: 4px 8px;
            font-size: 11px;
        }

        /* ── Stat cards ───────────────────────────────── */
        .stat-card {
            border-radius: var(--radius) !important;
            border: none !important;
            position: relative;
            overflow: hidden;
        }

        .stat-card .stat-icon {
            font-size: 36px;
            opacity: .25;
            position: absolute;
            right: 16px;
            bottom: 10px;
        }

        .stat-card .stat-label {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .06em;
            opacity: .85;
            margin-bottom: 8px;
        }

        .stat-card .stat-value {
            font-size: 32px;
            font-weight: 700;
            line-height: 1;
        }
    </style>

    @stack('styles')
</head>
<body>

<!-- Sidebar -->
<aside class="sidebar">

    <div class="sidebar-brand">
        <a class="brand-logo" href="{{ route('dashboard') }}">
            <div class="brand-icon">
                <i class="bi bi-graph-up-arrow"></i>
            </div>
            <div>
                <div class="brand-text">BSI Survey</div>
                <div class="brand-sub">
                @if(auth()->user()?->isAdmin())

                Admin Panel

                @else

                Monitoring Panel

                @endif

                </div>
            </div>
        </a>
    </div>

    <nav class="sidebar-nav">

        <div class="nav-section-label">Menu Utama</div>

        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i>
            Dashboard
        </a>

        <a href="{{ route('monitor') }}" class="nav-link {{ request()->routeIs('monitor') ? 'active' : '' }}" target="_blank">
            <i class="bi bi-display"></i>
            Layar Monitor
        </a>

        <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
            <i class="bi bi-tags"></i>
            Kategori
        </a>

        <a href="{{ route('employees.index') }}" class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i>
            Pegawai
        </a>

        <div class="nav-section-label">Analitik</div>

        <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-bar-graph"></i>
            Laporan
        </a>

        <a href="{{ route('qrcode') }}" class="nav-link {{ request()->routeIs('qrcode') ? 'active' : '' }}">
            <i class="bi bi-qr-code"></i>
            QR Code Kiosk
        </a>

        @if(auth()->user()?->isAdmin())
        <div class="nav-section-label">Administrasi</div>

        <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <i class="bi bi-person-gear"></i>
            Kelola User
        </a>
        @endif

        <div class="nav-section-label">Eksternal</div>

        <a href="{{ config('services.kiosk.url') }}" class="nav-link" target="_blank">
            <i class="bi bi-box-arrow-up-right"></i>
            Buka Sistem Survey
        </a>

    </nav>

    <div class="sidebar-footer">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout-btn">
                <i class="bi bi-box-arrow-right"></i>
                Logout
            </button>
        </form>
    </div>

</aside>

<!-- Main -->
<div class="main-wrapper">

    <!-- Topbar -->
    <header class="topbar">
        <span class="topbar-title">@yield('page-title', 'Dashboard')</span>
        <div class="topbar-right">
            <div class="user-badge">
                <div class="user-avatar">
                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                </div>
                <span class="user-name">{{ Auth::user()->name ?? 'Administrator' }}</span>
                @if(Auth::user())
                    <span class="badge" style="background:#eef2ff;color:#4338ca;font-size:10.5px;font-weight:600">
                        {{ Auth::user()->roleLabel() }}
                    </span>
                @endif
            </div>
        </div>
    </header>

    <!-- Content -->
    <main class="page-content">

        @if(session('success'))
            <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger d-flex align-items-center gap-2 mb-4">
                <i class="bi bi-exclamation-circle-fill"></i>
                {{ session('error') }}
            </div>
        @endif

        @yield('content')

    </main>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@stack('scripts')
</body>
</html>
