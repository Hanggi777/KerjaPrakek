@extends('layouts.app')

@section('title', 'Dashboard Sales')

@section('content')
<div class="content-header">
    <h1><i class="bi bi-speedometer2"></i> Dashboard Sales</h1>
    <p>Monitoring target penjualan pribadi Anda</p>
</div>

<!-- TARGET CARDS -->
<div class="row mb-4">

    <!-- Target -->
    <div class="col-md-3 mb-3">
        <a href="{{ route('dashboard.sales.progress') }}" class="text-decoration-none text-dark">
            <div class="stat-card primary">
                <i class="bi bi-bullseye stat-card-icon"
                    style="color: var(--primary-color);"></i>

                <div class="stat-card-label">
                    Progress Target Bulan Ini
                </div>

                <div class="stat-card-value" style="font-size:18px">
                    Rp {{ number_format($targetSales->target_nominal / 1000000, 1, ',', '.') }}Jt
                </div>
            </div>
        </a>
    </div>

    <!-- Total Penjualan -->
    <div class="col-md-3 mb-3">
        <a href="{{ route('dashboard.sales.omzet') }}" class="text-decoration-none text-dark">
            <div class="stat-card secondary">
                <i class="bi bi-graph-up stat-card-icon"
                    style="color: var(--secondary-color);"></i>

                <div class="stat-card-label">
                    Omzet Penjualan
                </div>

                <div class="stat-card-value" style="font-size:18px">
                    Rp {{ number_format($totalPenjualanBulanIni / 1000000, 1, ',', '.') }}Jt
                </div>
            </div>
        </a>
    </div>
<div class="col-md-3 mb-3">
    <a href="{{ route('dashboard.sales.komisi') }}"
       class="text-decoration-none text-dark">

        <div class="stat-card primary">

            <i class="bi bi-cash-coin stat-card-icon"
               style="color: var(--primary-color);"></i>

            <div class="stat-card-label">
                Komisi Saya
            </div>

            <div class="stat-card-value" style="font-size:18px">
                Rp {{ number_format($komisiSales,0,',','.') }}
            </div>

        </div>

    </a>
</div>

    <!-- Kinerja Sales -->
    <div class="col-md-3 mb-3">
        <a href="{{ route('dashboard.sales.kinerja') }}"
            class="text-decoration-none text-dark">

            <div class="stat-card info">
                <i class="bi bi-person-check stat-card-icon"
                    style="color:#3498db;"></i>

                <div class="stat-card-label">
                    Kinerja Saya
                </div>

                <div class="stat-card-value" style="font-size:18px">
                    {{ number_format($persentasePencapaian,1) }}%
                </div>
            </div>

        </a>
    </div>


</div>

<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-header" style="background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);">
                <i class="bi bi-clock"></i> Menunggu DP
            </div>
            <div class="card-body">
                <div style="text-align: center; padding: 20px 0;">
                    <div style="font-size: 32px; font-weight: 700; color: #ff9800;">{{ $transaksiMenungguDP }}</div>
                    <small style="color: #95a5a6;">Transaksi</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-header" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);">
                <i class="bi bi-clock"></i> Menunggu Pelunasan
            </div>
            <div class="card-body">
                <div style="text-align: center; padding: 20px 0;">
                    <div style="font-size: 32px; font-weight: 700; color: #17a2b8;">{{ $transaksiMenungguPelunasan }}</div>
                    <small style="color: #95a5a6;">Transaksi</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-header" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);">
                <i class="bi bi-exclamation-triangle"></i> Alert H-30
            </div>
            <div class="card-body">
                <div style="text-align: center; padding: 20px 0;">
                    <div style="font-size: 32px; font-weight: 700; color: #e74c3c;">{{ $transaksiH30 }}</div>
                    <small style="color: #95a5a6;">Transaksi</small>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- ALERT H-30 -->
@if ($transaksiH30 > 0)
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle"></i>
        <strong>Perhatian Penting!</strong> Ada {{ $transaksiH30 }} transaksi klien Anda yang mendekati H-30 sebelum acara dan belum lunas. Segera koordinasikan dengan klien!
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- TRANSAKSI TERBARU -->
<div class="card">
    <div class="card-header">
        <i class="bi bi-receipt"></i> Transaksi Terbaru
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Klien</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Tanggal Acara</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transaksiTerbaru as $trx)
                        <tr>
                            <td><strong>{{ $trx->kode_transaksi }}</strong></td>
                            <td>{{ $trx->klien->nama_klien }}</td>
                            <td>Rp {{ number_format($trx->total_penawaran, 0, ',', '.') }}</td>
                            <td>
                                @if ($trx->computeCurrentStatus() == 'menunggu_dp')
                                    <span class="badge bg-warning">Menunggu DP</span>
                                @elseif ($trx->computeCurrentStatus() == 'dp_terbayar')
                                    <span class="badge bg-info">DP Terbayar</span>
                                @elseif ($trx->computeCurrentStatus() == 'menunggu_pelunasan')
                                    <span class="badge bg-warning">Menunggu Pelunasan</span>
                                @elseif ($trx->computeCurrentStatus() == 'lunas')
                                    <span class="badge bg-success">Lunas</span>
                                @endif
                            </td>
                            <td>{{ $trx->tanggal_acara->format('d/m/Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 30px;">
                                <span style="color: #95a5a6;">Belum ada transaksi</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
