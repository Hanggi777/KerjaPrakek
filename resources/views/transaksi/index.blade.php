@extends('layouts.app')

@section('content')
<div class="row gy-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1">Transaksi Penjualan</h4>
                <p class="text-muted mb-0">Kelola penawaran, DP, dan pelunasan transaksi klien.</p>
            </div>
            <a href="{{ route('transaksi.create') }}" class="btn btn-success">Buat Transaksi</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card card-soft border-0 shadow-sm">
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Kode</th>
                                <th>Klien</th>
                                <th>Paket</th>
                                <th>Sales</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $item)
                                <tr>
                                    <td>{{ $item->kode_transaksi }}</td>
                                    <td>{{ $item->klien?->nama_klien }}</td>
                                    <td>{{ $item->paketMaster?->nama_paket }}</td>
                                    <td>{{ $item->sales?->name }}</td>
                                    <td>Rp {{ number_format($item->total_penawaran, 0, ',', '.') }}</td>

                                    <td>
                                        @if($item->computeCurrentStatus() == 'menunggu_dp')
                                            <span class="badge bg-warning text-dark">Menunggu DP</span>
                                        @elseif($item->computeCurrentStatus() == 'dp_terbayar')
                                            <span class="badge bg-info">DP Terbayar</span>
                                        @elseif($item->computeCurrentStatus() == 'menunggu_pelunasan')
                                            <span class="badge bg-primary">Menunggu Pelunasan</span>
                                    @elseif($item->computeCurrentStatus() == 'lunas')
                                        <span class="badge bg-success">Lunas</span>

                                    @elseif($item->computeCurrentStatus() == 'batal')
                                        <span class="badge bg-danger">Batal</span>

                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($item->computeCurrentStatus()) }}</span>
                                    @endif
                                    </td>

                                    <td>
                                        <div class="d-flex align-items-center gap-2">

                                            <a href="{{ route('transaksi.show',$item) }}" class="btn btn-info btn-sm">
                                                <i class="bi bi-eye"></i>
                                            </a>

                                            <a href="{{ route('transaksi.edit',$item) }}" class="btn btn-warning btn-sm">
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                            @if($item->computeCurrentStatus() != 'lunas' && $item->computeCurrentStatus() != 'batal')
                                                <form action="{{ route('transaksi.cancel',$item) }}" method="POST" class="m-0">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Batalkan transaksi ini?')">
                                                        <i class="bi bi-x-circle"></i>
                                                    </button>
                                                </form>
                                            @endif

                                        </div>
                                    </td>
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
