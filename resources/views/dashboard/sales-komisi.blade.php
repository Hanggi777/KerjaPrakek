@extends('layouts.app')

@section('title','Komisi Sales')

@section('content')

<div class="content-header mb-4">
    <h2>
        <i class="bi bi-cash-stack"></i>
        Komisi Sales
    </h2>

    <p class="text-muted">
        Perhitungan komisi berdasarkan status transaksi.
    </p>
</div>

<div class="card shadow-sm">
    <div class="card-body">

        <table class="table">

            <tr>
                <th>Komisi DP (5%)</th>
                <td>
                    Rp {{ number_format($komisiDP,0,',','.') }}
                </td>
            </tr>

            <tr>
                <th>Komisi Pelunasan (5%)</th>
                <td>
                    Rp {{ number_format($komisiLunas,0,',','.') }}
                </td>
            </tr>

            <tr class="table-success">
                <th>Total Komisi</th>
                <th class="text-success">
                    Rp {{ number_format($komisiSales,0,',','.') }}
                </th>
            </tr>

        </table>

    </div>
</div>

<div class="card shadow-sm mt-4">

    <div class="card-header">
        Riwayat Komisi Transaksi
    </div>

    <div class="card-body">

        <table class="table table-bordered table-hover">

            <thead class="table-light">

                <tr>
                    <th>Kode</th>
                    <th>Klien</th>
                    <th>Status</th>
                    <th>Total Transaksi</th>
                    <th>Komisi</th>
                </tr>

            </thead>

            <tbody>

                @forelse($transaksi as $trx)

                    @php

                        $komisi = 0;

                        if(in_array($trx->computeCurrentStatus(),['dp_terbayar','menunggu_pelunasan'])){
                            $komisi = $trx->total_penawaran * 0.05;
                        }

                        if($trx->computeCurrentStatus() == 'lunas'){
                            $komisi = $trx->total_penawaran * 0.10;
                        }

                    @endphp

                    <tr>

                        <td>{{ $trx->kode_transaksi }}</td>

                        <td>{{ $trx->klien->nama_klien }}</td>

                        <td>

                            @switch($trx->computeCurrentStatus())

                                @case('dp_terbayar')
                                    <span class="badge bg-warning text-dark">
                                        DP Terbayar
                                    </span>
                                    @break

                                @case('menunggu_pelunasan')
                                    <span class="badge bg-info">
                                        Menunggu Pelunasan
                                    </span>
                                    @break

                                @case('lunas')
                                    <span class="badge bg-success">
                                        Lunas
                                    </span>
                                    @break

                                @case('batal')
                                    <span class="badge bg-danger">
                                        Batal
                                    </span>
                                    @break

                                @default
                                    <span class="badge bg-secondary">
                                        {{ ucfirst(str_replace('_',' ',$trx->computeCurrentStatus())) }}
                                    </span>

                            @endswitch

                        </td>

                        <td>
                            Rp {{ number_format($trx->total_penawaran,0,',','.') }}
                        </td>

                        <td class="fw-bold text-success">
                            Rp {{ number_format($komisi,0,',','.') }}
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            Belum ada transaksi.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection