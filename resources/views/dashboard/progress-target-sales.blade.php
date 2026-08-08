@extends('layouts.app')

@section('title','Progress Target Saya')

@section('content')

<div class="content-header">
    <h1><i class="bi bi-bullseye"></i> Progress Target Saya</h1>
    <p>Progress target penjualan bulan {{ now()->translatedFormat('F Y') }}</p>
</div>

<div class="card">
    <div class="card-body">

        <table class="table table-bordered">

            <tr>
                <th width="30%">Target Bulan Ini</th>
                <td>
                    Rp {{ number_format($targetSales->target_nominal,0,',','.') }}
                </td>
            </tr>

            <tr>
                <th>Total Penjualan</th>
                <td>
                    Rp {{ number_format($totalPenjualanBulanIni,0,',','.') }}
                </td>
            </tr>

            <tr>
                <th>Persentase</th>
                <td>
                    {{ number_format($persentasePencapaian,1) }} %
                </td>
            </tr>

            <tr>
                <th>Sisa Target</th>
                <td>
                    Rp {{ number_format($sisaTarget,0,',','.') }}
                </td>
            </tr>

        </table>
        <div class="progress mt-4" style="height:28px">

    <div class="progress-bar bg-success"
         style="width:{{ $persentasePencapaian }}%">

        {{ number_format($persentasePencapaian,1) }}%

    </div>

</div>

        <div class="progress mt-4" style="height:25px;">
            <div class="progress-bar bg-success"
                style="width: {{ min(100,$persentasePencapaian) }}%">
                {{ number_format($persentasePencapaian,1) }}%
            </div>
        </div>

    </div>
</div>

@endsection