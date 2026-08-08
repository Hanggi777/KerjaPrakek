@extends('layouts.client')

@section('title','Detail Transaksi')

@section('content')

<div class="transaction-card">

    <div class="transaction-header">

        <div>
            <h3 class="transaction-code">
                {{ $transaksi->kode_transaksi }}
            </h3>

            <div class="transaction-date">
                {{ $transaksi->tanggal_transaksi->format('d F Y') }}
            </div>
        </div>

        <div>

            @if($transaksi->computeCurrentStatus()=='menunggu_dp')
                <span class="badge-status badge-pending">Menunggu DP</span>

            @elseif($transaksi->computeCurrentStatus()=='dp_terbayar')
                <span class="badge-status badge-paid">DP Terbayar</span>

            @elseif($transaksi->computeCurrentStatus()=='menunggu_pelunasan')
                <span class="badge-status badge-pending">Menunggu Pelunasan</span>

            @elseif($transaksi->computeCurrentStatus()=='lunas')
                <span class="badge-status badge-lunas">Lunas</span>
            @endif

        </div>

    </div>

    <div class="transaction-grid">

        <div class="info-box">
            <small>Paket</small>
            <strong>{{ optional($transaksi->paketMaster)->nama_paket }}</strong>
        </div>

        <div class="info-box">
            <small>Porsi</small>
            <strong>{{ $transaksi->jumlah_porsi }}</strong>
        </div>

        <div class="info-box">
            <small>Lokasi</small>
            <strong>{{ $transaksi->lokasi_acara }}</strong>
        </div>

        <div class="info-box">
            <small>Tanggal Acara</small>
            <strong>{{ $transaksi->tanggal_acara->format('d F Y') }}</strong>
        </div>

    </div>

    <hr>

    <h5>Item Penawaran</h5>

    @foreach($transaksi->detail as $detail)

        <div class="d-flex justify-content-between py-2">

            <span>
                {{ $detail->nama_item }}
                ({{ $detail->qty }} {{ $detail->satuan }})
            </span>

            <strong>
                Rp {{ number_format($detail->subtotal,0,',','.') }}
            </strong>

        </div>

    @endforeach

    <hr>

<h3 class="fw-bold mb-4">
    Riwayat Pembayaran
</h3>

@php
$totalBayar = 0;
@endphp

@foreach($transaksi->pembayaran()->orderBy('created_at')->get() as $index => $bayar)

@php
$totalBayar += $bayar->nominal_bayar;
@endphp

<div class="card shadow-sm border-0 mb-3">

    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center">

            <div>

                <h5 class="fw-bold">

                    @if($index==0)
                        DP
                    @else
                        Pembayaran {{ $index+1 }}
                    @endif

                </h5>

                <small class="text-muted">

                    {{ ucfirst(str_replace('_',' ',$bayar->status_pembayaran)) }}

                </small>

            </div>

            <div class="text-end">

                <h4 class="fw-bold text-success">

                    Rp {{ number_format($bayar->nominal_bayar,0,',','.') }}

                </h4>

                @if($bayar->status_pembayaran=='pending')

                    <span class="badge bg-warning text-dark">
                        Pending
                    </span>

                @elseif($bayar->status_pembayaran=='pending_verifikasi')

                    <span class="badge bg-info">
                        Pending Verifikasi
                    </span>

                @elseif($bayar->status_pembayaran=='berhasil')

                    <span class="badge bg-success">
                        Berhasil
                    </span>

                @else

                    <span class="badge bg-danger">
                        Ditolak
                    </span>

                @endif

            </div>

        </div>

        <div class="mt-3 text-end">

            <a href="{{ route('klien.pembayaran.show',$bayar->id) }}"
               class="btn btn-outline-success">

                Detail Pembayaran

            </a>

        </div>

    </div>

</div>

@endforeach
@php

$sisa = $transaksi->total_penawaran - $totalBayar;

@endphp

<div class="card border-success shadow-sm">

    <div class="card-body">

        <div class="row align-items-center">

            <div class="col-md-6">

                <h5 class="fw-bold">
                    Sisa Tagihan
                </h5>

                <h3 class="text-danger">

                    Rp {{ number_format($sisa,0,',','.') }}

                </h3>

            </div>

            <div class="col-md-6 text-end">

                @if($sisa > 0)

                    <a href="{{ route('klien.transaksi.bayar',$transaksi->id) }}"
                       class="btn btn-success btn-lg">

                        <i class="bi bi-plus-circle"></i>

                        Tambah Pembayaran

                    </a>

                @else

                    <span class="badge bg-success fs-6">

                        ✓ Lunas

                    </span>

                @endif

            </div>

        </div>

    </div>

</div>

    <div class="mt-4">

        <a href="{{ route('dashboard.klien') }}"
           class="btn btn-success">

            <i class="bi bi-arrow-left"></i>
            Kembali

        </a>

    </div>

</div>

@endsection