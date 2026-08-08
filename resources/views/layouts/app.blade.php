<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'NABILLA CATERING')</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #f5f7fb;
            overflow-x: hidden;
        }

        .app-layout {
            display: flex;
            min-height: 100vh;
        }

        /* ===========================
   SIDEBAR
=========================== */

        .sidebar{
        position: fixed;
        top: 0;
        left: 0;
        width: 290px;
        height: 100vh;
        background: linear-gradient(180deg,#0d6b43,#075f37);
        display: flex;
        flex-direction: column;
        box-shadow: 6px 0 18px rgba(0,0,0,.15);

        overflow-y: auto;
        overflow-x: hidden;
    }

        /* Logo */

        .logo-box {
            background: #fff;
            padding: 20px;
            text-align: center;
        }

        .sidebar-logo {
            width: 210px;
            max-width: 100%;
        }

        /* Menu */

        /* ===========================
   SIDEBAR MENU
=========================== */

        .sidebar-menu {
            flex: 1;
            padding: 25px 20px;
        }

        .menu-title {
            color: rgba(255, 255, 255, .65);
            font-size: 13px;
            letter-spacing: 1px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .sidebar .nav-link {
            display: flex;
            align-items: center;
            gap: 14px;
            color: #fff;
            text-decoration: none;
            padding: 15px 18px;
            margin-bottom: 10px;
            border-radius: 14px;
            transition: .3s;
            font-size: 16px;
            font-weight: 500;
        }

        .sidebar .nav-link i {
            width: 24px;
            font-size: 20px;
            text-align: center;
        }

        .sidebar .nav-link:hover {
            background: rgba(255, 255, 255, .12);
            transform: translateX(5px);
        }

        .sidebar .nav-link.active {
            background: rgba(255, 255, 255, .18);
            box-shadow: 0 8px 20px rgba(0, 0, 0, .15);
        }

        /* ===========================
   SIDEBAR FOOTER
=========================== */

        .sidebar-footer {
            margin-top: auto;
            padding: 20px;
            border-top: 1px solid rgba(255, 255, 255, .15);
        }

        .user-card {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }

        .user-info {
            color: #fff;
        }

        .user-name {
            font-weight: 600;
            font-size: 15px;
        }

        .user-info small {
            color: rgba(255, 255, 255, .75);
        }

        .sidebar button {
            width: 100%;
            height: 48px;
            border-radius: 30px;
            font-weight: 600;
        }

        /* ===========================
   CONTENT
=========================== */

        .content{
        margin-left: 290px;
        width: calc(100% - 290px);
        min-height: 100vh;
        padding: 30px;
        overflow-y: auto;
    }

        /* ===========================
   TOPBAR
=========================== */

        .topbar {

            background: #fff;

            border-radius: 22px;

            padding: 24px 28px;

            display: flex;

            justify-content: space-between;

            align-items: center;

            margin-bottom: 30px;

            box-shadow: 0 12px 30px rgba(0, 0, 0, .08);

        }

        .topbar h5 {

            font-size: 36px;

            font-weight: 700;

            color: #0b5132;

        }

        .topbar small {

            color: #666;

            font-size: 20px;

        }

        .badge-role {

            background: #0d6b43;

            color: #fff;

            padding: 9px 20px;

            border-radius: 30px;

            font-size: 14px;

            font-weight: 600;

        }

        .user-avatar {

            width: 52px;

            height: 52px;

            border-radius: 50%;

            background: #0d6b43;

            color: #fff;

            display: flex;

            align-items: center;

            justify-content: center;

            font-weight: bold;

            font-size: 22px;

        }

        /* ===========================
   CARD
=========================== */

        .card {

            border: none;

            border-radius: 22px;

            box-shadow: 0 12px 25px rgba(0, 0, 0, .08);

            overflow: hidden;

        }

        .card-header {

            background: #fff;

            border-bottom: 1px solid #eee;

            font-weight: 600;

        }

        .card-body {

            padding: 25px;

        }

        /* Dashboard Card */

        .stat-card {

            background: #fff;

            border-radius: 20px;

            text-align: center;

            padding: 28px;

            transition: .3s;

            box-shadow: 0 10px 20px rgba(0, 0, 0, .08);

            border-bottom: 4px solid #0d6b43;

        }

        .stat-card:hover {

            transform: translateY(-6px);

        }

        .stat-card-icon {

            width: 70px;

            height: 70px;

            border-radius: 50%;

            background: #edf8f2;

            color: #0d6b43;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 34px;

            margin: auto auto 18px;

        }

        .stat-card-label {

            color: #555;

            font-size: 18px;

        }

        .stat-card-value {

            font-size: 34px;

            font-weight: 700;

            color: #111;

        }

        /* ===========================
   TABLE
=========================== */

.table{
    margin-bottom:0;
}

.table thead{
    background:#0d6b43;
    color:#fff;
}

.table th,
.table td{
    vertical-align:middle;
    padding:15px;
}

.table th{
    white-space:nowrap;
}

.table td:nth-child(2){
    white-space:normal;
}

/* tombol icon */
.btn-icon{
    width:36px;
    height:36px;
    padding:0;
    display:inline-flex;
    align-items:center;
    justify-content:center;
}

/* tombol aksi sejajar */
.action-buttons{
    display:flex;
    justify-content:center;
    align-items:center;
    gap:8px;
    flex-wrap:nowrap;
}

        /* ===========================
   BUTTON
=========================== */

        .btn-primary {

            background: #0d6b43;

            border: none;

            border-radius: 12px;

        }

        .btn-primary:hover {

            background: #0a5a39;

        }

        /* ===========================
   PROGRESS
=========================== */

        .progress {

            height: 14px;

            border-radius: 20px;

            background: #e8ecef;

        }

        .progress-bar {

            border-radius: 20px;

        }

        /* ===========================
   RESPONSIVE
=========================== */

        @media(max-width:991px) {

            .sidebar {

                position: relative;

                width: 100%;

                height: auto;

            }

            .content {

                width: 100%;

                margin-left: 0;

            }

            .topbar {

                flex-direction: column;

                gap: 20px;

                align-items: flex-start;

            }

        }
    </style>

    @stack('styles')

</head>

<body>

    @php

        $internalUser = Auth::user();
        $clientUser = Auth::guard('klien')->user();

    @endphp

    @if(Auth::check() || Auth::guard('klien')->check())

        <div class="app-layout">

            <!-- SIDEBAR -->
            <aside class="sidebar">

                <!-- Logo -->
                <div class="logo-box">

                    <img src="{{ asset('assets/LOGO.png') }}" class="sidebar-logo" alt=>

                </div>

                <!-- Menu -->
                <div class="sidebar-menu">

                    <div class="menu-title">
                        MENU
                    </div>

                    @if($internalUser)

                        {{-- Dashboard --}}
                        <a href="{{ route('dashboard.internal') }}"
                            class="nav-link {{ request()->routeIs('dashboard.*') ? 'active' : '' }}">
                            <i class="bi bi-speedometer2"></i>
                            Dashboard
                        </a>

                        {{-- SALES --}}
                        @if($internalUser->isSales())

                            <a href="{{ route('klien.index') }}"
                                class="nav-link {{ request()->routeIs('klien.*') ? 'active' : '' }}">
                                <i class="bi bi-people"></i>
                                Kelola Data Klien
                            </a>

                            <a href="{{ route('transaksi.index') }}"
                                class="nav-link {{ request()->routeIs('transaksi.*') ? 'active' : '' }}">
                                <i class="bi bi-receipt"></i>
                                Transaksi Penjualan
                            </a>

                        @endif

                        {{-- PEMILIK  --}}
                        @if( $internalUser->isPemilik())

                            <a href="{{ route('paket.index') }}"
                                class="nav-link {{ request()->routeIs('paket.*') ? 'active' : '' }}">
                                <i class="bi bi-box-seam"></i>
                                Paket Master
                            </a>

                        @endif

                             {{--  SUPERADMIN --}}
                        @if($internalUser->isSuperadmin() )

                            <a href="{{ route('paket.index') }}"
                                class="nav-link {{ request()->routeIs('paket.*') ? 'active' : '' }}">
                                <i class="bi bi-box-seam"></i>
                                Paket Master
                            </a>

                            <a href="{{ route('pembayaran.index') }}"
                                class="nav-link {{ request()->routeIs('pembayaran.*') ? 'active' : '' }}">
                                <i class="bi bi-cash-stack"></i>
                                Kelola Pembayaran
                            </a>
                            @if(auth()->user()->role == 'superadmin')

                            <a href="{{ route('users.index') }}"
                            class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                                <i class="bi bi-people"></i>
                                <span>Kelola Data Sales & Pemilik</span>
                            </a>
                            <a href="{{ route('klien.index') }}"
                                class="nav-link {{ request()->routeIs('klien.*') ? 'active' : '' }}">
                                <i class="bi bi-people"></i>
                                Kelola Data Klien
                            </a>

                            @endif

                        @endif

                    @endif

                    @if($clientUser)

                        <a href="{{ route('dashboard.klien') }}" class="nav-link">

                            <i class="bi bi-person-circle"></i>
                            Portal Klien

                        </a>

                    @endif

                </div>

                <!-- Footer Sidebar -->
                <div class="sidebar-footer">

                    <div class="user-card">

                        <div class="user-avatar">

                            {{ substr($internalUser->name ?? $clientUser->nama_klien, 0, 1) }}

                        </div>

                        <div class="user-info">

                            <div class="user-name">
                                {{ $internalUser->name ?? $clientUser->nama_klien }}
                            </div>

                            <small>
                                {{ ucfirst($internalUser->role ?? 'Klien') }}
                            </small>

                        </div>

                    </div>

                    <form method="POST" action="{{ Auth::check() ? route('logout') : route('client.logout') }}">

                        @csrf

                        <button class="btn btn-light w-100 rounded-pill">

                            <i class="bi bi-box-arrow-right me-2"></i>
                            Logout

                        </button>

                    </form>

                </div>

            </aside>

            <!-- CONTENT -->
            <div class="content">

                <!-- TOPBAR -->
                <div class="topbar d-flex justify-content-between align-items-center">

                    <div>

                        <h4 class="fw-bold mb-1">

                            @yield('title', 'Dashboard')

                        </h4>

                        <small class="text-muted">

                            Sistem Monitoring Sales Performance

                        </small>

                    </div>

                    <div class="d-flex align-items-center gap-3">

                        <span class="badge badge-role">

                            {{ strtoupper($internalUser->role ?? 'KLIEN') }}

                        </span>

                        <div class="user-avatar">

                            {{ substr($internalUser->name ?? $clientUser->nama_klien, 0, 1) }}

                        </div>

                        <strong>

                            {{ $internalUser->name ?? $clientUser->nama_klien }}

                        </strong>

                    </div>

                </div>

                @yield('content')

            </div>

        </div>
    @else

        <div class="container py-5">

            @yield('content')

        </div>

    @endif

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <script
        src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@3.0.1/dist/chartjs-plugin-annotation.min.js"></script>

    @stack('scripts')


</body>

</html>