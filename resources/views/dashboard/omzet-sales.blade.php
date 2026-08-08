@extends('layouts.app')

@section('title','Omzet Penjualan Saya')

@section('content')

<div class="content-header">
    <h1><i class="bi bi-cash-stack"></i> Omzet Penjualan Saya</h1>
    <p>Daftar transaksi bulan {{ now()->translatedFormat('F Y') }}</p>
</div>

<div class="card">

    <div class="card-header">
        Total Omzet :
        <strong>
            Rp {{ number_format($omzet,0,',','.') }}
        </strong>
    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-striped">

                <thead>

                    <tr>
                        <th>Kode</th>
                        <th>Klien</th>
                        <th>Paket</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($transaksi as $trx)

                    <tr>

                        <td>{{ $trx->kode_transaksi }}</td>

                        <td>{{ $trx->klien->nama_klien }}</td>

                        <td>{{ $trx->paketMaster->nama_paket }}</td>

                        <td>
                            Rp {{ number_format($trx->total_penawaran,0,',','.') }}
                        </td>

                        <td>

                            @if($trx->computeCurrentStatus()=='menunggu_dp')
                                <span class="badge bg-warning">Menunggu DP</span>

                            @elseif($trx->computeCurrentStatus()=='dp_terbayar')
                                <span class="badge bg-info">DP Terbayar</span>

                            @elseif($trx->computeCurrentStatus()=='menunggu_pelunasan')
                                <span class="badge bg-primary">Menunggu Pelunasan</span>

                            @elseif($trx->computeCurrentStatus()=='lunas')
                                <span class="badge bg-success">Lunas</span>

                            @endif

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="5" class="text-center">
                            Tidak ada transaksi
                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection