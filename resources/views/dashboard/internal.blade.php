@extends('layouts.app')

@section('content')
<div class="row gy-4">
    <div class="col-12">
        <div class="card card-soft mb-4">
            <div class="card-body gradient-header rounded-4 p-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
                    <div>
                        <h2 class="text-white mb-1">Dashboard Internal</h2>
                        <p class="text-white-50 mb-0">Selamat datang, {{ $user->name }}. Pantau performa perusahaan secara real-time.</p>
                    </div>
                    <span class="badge bg-white text-primary py-2 px-3 shadow-sm">{{ strtoupper($user->role) }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-sm-6">
        <div class="card card-soft shadow-sm border-0 p-3">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="badge bg-primary">Users</span>
                <i class="bi bi-people fa-lg text-primary"></i>
            </div>
            <h3 class="mb-1">{{ number_format($totalUsers) }}</h3>
            <p class="text-muted mb-0">Total user internal</p>
        </div>
    </div>

    <div class="col-lg-3 col-sm-6">
        <div class="card card-soft shadow-sm border-0 p-3">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="badge bg-success">Sales</span>
                <i class="bi bi-person-badge fa-lg text-success"></i>
            </div>
            <h3 class="mb-1">{{ number_format($totalSales) }}</h3>
            <p class="text-muted mb-0">Total sales aktif</p>
        </div>
    </div>

    <div class="col-lg-3 col-sm-6">
        <div class="card card-soft shadow-sm border-0 p-3">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="badge bg-warning">Klien</span>
                <i class="bi bi-person-lines-fill fa-lg text-warning"></i>
            </div>
            <h3 class="mb-1">{{ number_format($totalClients) }}</h3>
            <p class="text-muted mb-0">Total klien terdaftar</p>
        </div>
    </div>

    <div class="col-lg-3 col-sm-6">
        <div class="card card-soft shadow-sm border-0 p-3">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="badge bg-info">Paket</span>
                <i class="bi bi-card-list fa-lg text-info"></i>
            </div>
            <h3 class="mb-1">{{ number_format($packageCount) }}</h3>
            <p class="text-muted mb-0">Paket master tersedia</p>
        </div>
    </div>

    <div class="col-lg-3 col-sm-6">
        <div class="card card-soft shadow-sm border-0 p-3">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="badge bg-danger">Omzet</span>
                <i class="bi bi-currency-dollar fa-lg text-danger"></i>
            </div>
            <h3 class="mb-1">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</h3>
            <p class="text-muted mb-0">Omzet bulan ini</p>
        </div>
    </div>

    @if ($user->isSales() && $target)
        <div class="col-12">
            <div class="card card-soft shadow-sm border-0 p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="mb-1">Target Penjualan Bulanan</h5>
                        <p class="text-muted mb-0">{{ strtoupper($user->role) }} - {{ $target->bulan }}/{{ $target->tahun }}</p>
                    </div>
                    <span class="badge bg-success">{{ number_format($targetProgress, 0) }}%</span>
                </div>
                <div class="progress" style="height: 12px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $targetProgress }}%;" aria-valuenow="{{ $targetProgress }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="mt-3 text-muted">Target: Rp {{ number_format($target->target_nominal, 0, ',', '.') }}</div>
            </div>
        </div>
    @endif

    @if (($user->isPemilik() || $user->isSuperadmin()) && $totalTargets > 0)
        <div class="col-12">
            <div class="card card-soft shadow-sm border-0 p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="mb-1">Target Penjualan Tim</h5>
                        <p class="text-muted mb-0">Jumlah target aktif: {{ $totalTargets }}</p>
                    </div>
                    <span class="badge bg-info">{{ number_format($overallTargetProgress, 0) }}%</span>
                </div>
                <div class="progress" style="height: 12px;">
                    <div class="progress-bar bg-info" role="progressbar" style="width: {{ $overallTargetProgress }}%;" aria-valuenow="{{ $overallTargetProgress }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="mt-3 text-muted">Total target: Rp {{ number_format($totalTargetNominal, 0, ',', '.') }}</div>
            </div>
        </div>
    @endif

    <div class="col-12">
        <div class="card card-soft shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Reminder Pelunasan H-30</h5>
                    <span class="badge bg-danger">{{ $reminderH30->count() }} transaksi</span>
                </div>

                @if ($reminderH30->isEmpty())
                    <div class="alert alert-info">Tidak ada transaksi mendekati H-30.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Kode Transaksi</th>
                                    <th>Klien</th>
                                    <th>Tanggal Acara</th>
                                    <th>Status</th>
                                    <th>Jumlah Sisa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reminderH30 as $item)
                                    <tr>
                                        <td>{{ $item->kode_transaksi }}</td>
                                        <td>{{ $item->klien?->nama_klien }}</td>
                                        <td>{{ $item->tanggal_acara?->format('d M Y') }}</td>
                                        <td><span class="badge bg-warning text-dark">{{ ucfirst(str_replace('_', ' ', $item->computeCurrentStatus())) }}</span></td>
                                        <td>Rp {{ number_format($item->sisa_pelunasan, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
