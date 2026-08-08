@extends('layouts.client')

@section('content')
<div class="row gy-4">
    <div class="col-12">
        <div class="card card-soft shadow-sm border-0 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="mb-1">Portal Klien</h4>
                    <p class="text-muted mb-0">Halo, {{ $client->nama_klien }}. Lihat status transaksi dan tagihan Anda.</p>
                </div>
                <span class="badge bg-info text-dark">Klien Aktif</span>
            </div>

            <div class="row g-3">
                <div class="col-md-4">
                    <div class="card card-soft border-0 p-3">
                        <h6 class="mb-2">Total Tagihan</h6>
                        <h3>Rp {{ number_format($totalTagihan, 0, ',', '.') }}</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-soft border-0 p-3">
                        <h6 class="mb-2">Total Outstanding</h6>
                        <h3>Rp {{ number_format($totalOutstanding, 0, ',', '.') }}</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-soft border-0 p-3">
                        <h6 class="mb-2">Reminder H-30</h6>
                        <h3>{{ $reminderH30->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card card-soft shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Daftar Transaksi</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Kode</th>
                                <th>Paket</th>
                                <th>Total Penawaran</th>
                                <th>Status</th>
                                <th>DP</th>
                                <th>Sisa</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transaksi as $item)
                                <tr>
                                    <td>{{ $item->kode_transaksi }}</td>
                                    <td>{{ $item->paketMaster?->nama_paket ?? '-' }}</td>
                                    <td>Rp {{ number_format($item->total_penawaran, 0, ',', '.') }}</td>
                                    <td><span class="badge bg-{{ $item->computeCurrentStatus() === 'lunas' ? 'success' : 'warning' }} text-dark">{{ ucfirst(str_replace('_', ' ', $item->computeCurrentStatus())) }}</span></td>
                                    <td>Rp {{ number_format($item->nominal_dp, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($item->sisa_pelunasan, 0, ',', '.') }}</td>
                                    <td><a href="{{ route('client.transaksi.show', $item) }}" class="btn btn-sm btn-outline-primary">Lihat</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
