@extends('layouts.app')

@section('title', 'Dashboard Superadmin')

@section('content')
<div class="content-header">
    <h1><i class="bi bi-speedometer2"></i> Dashboard Superadmin</h1>
    <p>Monitoring global sistem NABILLA CATERING</p>
</div>

<!-- STAT CARDS -->
<div class="row mb-4">


<!-- OMZET & PEMBAYARAN -->
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
    <a href="{{ route('dashboard.komisi.admin') }}" class="text-decoration-none">

        <div class="stat-card warning">

            <i class="bi bi-cash-stack stat-card-icon" style="color:#28a745;"></i>

            <div class="stat-card-label">
                Komisi Sales
            </div>

            <div class="text-muted">
                Monitoring komisi
            </div>

        </div>

    </a>
</div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-credit-card"></i> Pembayaran Berhasil
            </div>
            <div class="card-body">
                <div style="text-align: center; padding: 20px 0;">
                    <div style="font-size: 32px; font-weight: 700; color: #27ae60;">
                        Rp {{ number_format($pembayaranBerhasil, 0, ',', '.') }}
                    </div>
                    <small style="color: #95a5a6;">Total pembayaran yang telah masuk</small>
                </div>
            </div>
        </div>
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
                        <th>Tanggal</th>
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
                                @else
                                    <span class="badge bg-secondary">{{ $trx->computeCurrentStatus() }}</span>
                                @endif
                            </td>
                            <td>{{ $trx->tanggal_transaksi->format('d/m/Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 30px;">
                                <i class="bi bi-inbox" style="font-size: 32px; color: #bdc3c7;"></i><br>
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