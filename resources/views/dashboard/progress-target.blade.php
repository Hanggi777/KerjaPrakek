@extends('layouts.app')

@section('content')

<div class="container">

    <h3 class="mb-4">
        Progress Target Penjualan
    </h3>

    <div class="card shadow-sm">

        <div class="card-body">

            <table class="table table-bordered table-hover">

                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>Sales</th>
                        <th>Bulan</th>
                        <th>Target</th>
                        <th>Tercapai</th>
                        <th>Progress</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($target as $no => $item)

                    @php

                        $tercapai = \App\Models\TransaksiPenjualan::where('sales_id',$item->sales_id)
                            ->whereMonth('tanggal_transaksi',$item->bulan)
                            ->whereYear('tanggal_transaksi',$item->tahun)
                            ->where('status_transaksi','!=','batal')
                            ->sum('total_penawaran');

                        $persen = $item->target_nominal > 0
                            ? ($tercapai/$item->target_nominal)*100
                            : 0;

                    @endphp

                    <tr>

                        <td>{{ $no+1 }}</td>

                        <td>{{ $item->sales->name }}</td>

                        <td>{{ $item->bulan }}/{{ $item->tahun }}</td>

                        <td>
                            Rp {{ number_format($item->target_nominal,0,',','.') }}
                        </td>

                        <td>
                            Rp {{ number_format($tercapai,0,',','.') }}
                        </td>

                        <td width="220">

                            <div class="progress">

                                <div class="progress-bar bg-success"
                                    style="width: {{ min($persen,100) }}%;">

                                    {{ round($persen) }}%

                                </div>

                            </div>

                        </td>

                        <td>

                            @if($persen >= 100)

                                <span class="badge bg-success">
                                    Target Tercapai
                                </span>

                            @elseif($persen >= 70)

                                <span class="badge bg-warning text-dark">
                                    Hampir Tercapai
                                </span>

                            @else

                                <span class="badge bg-danger">
                                    Belum Tercapai
                                </span>

                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="7" class="text-center">

                            Belum ada target penjualan.

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection