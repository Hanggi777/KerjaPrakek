@extends('layouts.app')

@section('title', 'Dashboard Pemilik')

@section('content')
    <div class="content-header">
        <h1><i class="bi bi-speedometer2"></i> Dashboard Pemilik</h1>
        <p>Monitoring performa bisnis NABILLA CATERING</p>
    </div>

    <!-- STAT CARDS -->
     <div class="row mb-4"> 

       <!-- Omzet -->
<div class="col-md-3 mb-3">
    <a href="{{ route('dashboard.omzet') }}" class="text-decoration-none">
        <div class="stat-card danger">
            <i class="bi bi-graph-up stat-card-icon" style="color: var(--danger-color);"></i>

            <div class="stat-card-label">
                Omzet Bulan Ini
            </div>

            <div class="stat-card-value" style="font-size:18px;">
                Rp {{ number_format($omzetBulanIni / 1000000, 1, ',', '.') }}Jt
            </div>

            <small class="text-muted">
                Lihat omzet
            </small>
        </div>
    </a>
</div>

<!-- Progress -->
<div class="col-md-3 mb-3">
    <a href="{{ route('dashboard.progressTarget') }}" class="text-decoration-none">
        <div class="stat-card success">
            <i class="bi bi-graph-up-arrow stat-card-icon" style="color:#198754;"></i>

            <div class="stat-card-label">
                Progress Target
            </div>


            <small class="text-muted">
                Monitoring target
            </small>
        </div>
    </a>
</div>

<!-- Kinerja -->
<div class="col-md-3 mb-3">
    <a href="{{ route('dashboard.kinerja-sales') }}" class="text-decoration-none">
        <div class="stat-card danger">
            <i class="bi bi-bar-chart-line stat-card-icon" style="color:#dc3545;"></i>

            <div class="stat-card-label">
                Kinerja Sales
            </div>
            <small class="text-muted">
                Performa sales
            </small>
        </div>
    </a>
</div>

<div class="col-md-3 mb-3">
    <a href="{{ route('dashboard.pemilik.komisi') }}" class="text-decoration-none text-dark">
        <div class="stat-card success">
            <i class="bi bi-cash-stack stat-card-icon text-success"></i>

            <div class="stat-card-label">
                Komisi Sales
            </div>

            <div class="text-muted">
                Lihat Komisi
            </div>
        </div>
    </a>
</div>

    </div>
    
    <!-- TARGET PENJUALAN SALES -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="bi bi-bullseye"></i> Progress Target Penjualan Sales
        </div>
        <div class="card-body">
            @forelse ($targetBulanIni as $target)
                @php
                    $pencapaiSales = \App\Models\TransaksiPenjualan::where('sales_id', $target->sales_id)
                        ->whereMonth('tanggal_transaksi', \Carbon\Carbon::now()->month)
                        ->whereYear('tanggal_transaksi', \Carbon\Carbon::now()->year)
                        ->where('status_transaksi', '!=', 'batal')
                        ->sum('total_penawaran');
                    $persentase = $target->target_nominal > 0 ? ($pencapaiSales / $target->target_nominal) * 100 : 0;
                @endphp
                <div style="margin-bottom: 25px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <strong>{{ $target->sales->name }}</strong>
                        <span style="font-weight: 700; color: var(--primary-color);">
                            {{ number_format($persentase, 1) }}%
                        </span>
                    </div>
                    <div class="progress" style="height: 12px;">
                        <div class="progress-bar" style="width: {{ min(100, $persentase) }}%;"></div>
                    </div>
                    <small style="color: #95a5a6;">
                        Target: Rp {{ number_format($target->target_nominal, 0, ',', '.') }} |
                        Tercapai: Rp {{ number_format($pencapaiSales, 0, ',', '.') }}
                    </small>
                </div>
            @empty
                <p style="color: #95a5a6; text-align: center;">Tidak ada target untuk bulan ini</p>
            @endforelse
        </div>
    </div>

    <!-- ALERT H-30 -->
    @if ($transaksiH30 > 0)
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i>
            <strong>Perhatian!</strong> Ada {{ $transaksiH30 }} transaksi yang mendekati H-30 sebelum acara dan belum lunas.
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
                            <th>Sales</th>
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
                                <td>{{ $trx->sales->name }}</td>
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
                                <td colspan="6" style="text-align: center; padding: 30px;">
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
</div>