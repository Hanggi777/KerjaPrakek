<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Portal Klien') | NABILLA CATERING</title>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root{
            --primary:#0F7B45;
            --primary-dark:#0B5F35;
            --secondary:#F8F9FA;
            --border:#E9ECEF;
            --text:#2C3E50;
            --shadow:0 10px 25px rgba(0,0,0,.08);
        }

        body{
            background:#f5f7fb;
            font-family:'Poppins',sans-serif;
            color:var(--text);
        }

        /* Sidebar */

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

        .transaction-card{
            background:#fff;
            border-radius:18px;
            padding:25px;
            margin-bottom:25px;
            box-shadow:0 10px 30px rgba(0,0,0,.08);
            transition:.3s;
        }

        .transaction-card:hover{
            transform:translateY(-4px);
            box-shadow:0 15px 40px rgba(0,0,0,.12);
        }

        .transaction-header{
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:20px;
        }

        .transaction-code{
            font-size:22px;
            font-weight:700;
            color:#183153;
        }

        .transaction-date{
            color:#8c97a8;
            font-size:14px;
        }

        .transaction-grid{
            display:grid;
            grid-template-columns:repeat(2,minmax(220px,1fr));
            gap:18px;
            margin:20px 0;
        }

        .info-box{
            background:#f8fafc;
            border-radius:12px;
            padding:14px 18px;
        }

        .info-box small{
            color:#888;
            display:block;
        }

        .info-box strong{
            display:block;
            margin-top:4px;
            color:#1d2939;
        }

        .badge-status{
            padding:8px 18px;
            border-radius:30px;
            font-size:13px;
            font-weight:600;
        }

        .badge-pending{
            background:#fff4d6;
            color:#d68910;
        }

        .badge-paid{
            background:#d4f8e8;
            color:#198754;
        }

        .badge-lunas{
            background:#d1f5d3;
            color:#198754;
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
        .user-name{
    color: #000000 !important;
    font-size: 18px;
    font-weight: 600;
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

        .table {

            margin-bottom: 0;

        }

        .table thead {

            background: #0d6b43;

            color: #fff;

        }

        .table th {

            white-space: nowrap;

            padding: 15px;

        }

        .table td {

            vertical-align: middle;

            padding: 15px;

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
    </style>

    @stack('styles')
</head>

<body>

    <!-- Sidebar -->

        <div class="app-layout">

            <!-- SIDEBAR -->
            <aside class="sidebar">

                <!-- Logo -->
                <div class="logo-box">

                    <img src="{{ asset('assets/LOGO.png') }}" class="sidebar-logo" alt=>

                </div>

        <div class="sidebar-menu">

            <a href="{{ route('dashboard.internal') }}"
                            class="nav-link {{ request()->routeIs('dashboard.*') ? 'active' : '' }}">
                            <i class="bi bi-speedometer2"></i>
                            Dashboard
                        </a>
        </div>

        <div class="sidebar-footer">

            <form action="{{ route('client.logout') }}" method="POST">
                @csrf

                <button class="btn btn-light w-100 rounded-pill">
                    <i class="bi bi-box-arrow-right"></i>
                    Logout
                </button>

            </form>

        </div>

    </aside>

    <!-- Content -->

    <div class="content">

        <div class="topbar">

            <div>

                <h3 class="fw-bold mb-1">
                    @yield('title','Portal Klien')
                </h3>

                <small class="text-muted">
                    Sistem Monitoring Transaksi & Pembayaran
                </small>

            </div>

            <div class="user-info">

                <div class="d-flex align-items-center gap-3">

                <span class="badge-role">
                    KLIEN
                </span>

                <div class="user-avatar">
                    {{ strtoupper(substr(auth('klien')->user()->nama_klien, 0, 1)) }}
                </div>

                <div class="text-start">

                <strong class="user-name">
                    {{ auth('klien')->user()->nama_klien }}
                </strong>

            </div>

            </div>

            </div>

        </div>

        @yield('content')

    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')

</body>
</html>