@extends('layouts.client')

@section('title','Detail Pembayaran')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Detail Pembayaran</h2>
        <p class="text-muted mb-0">
            Informasi pembayaran transaksi Anda
        </p>
    </div>

    <a href="{{ url()->previous() }}" class="btn btn-outline-success rounded-pill">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="transaction-card">

    <div class="transaction-header">

        <div>

            <div class="transaction-code">
                {{ strtoupper($pembayaran->jenis_pembayaran) }}
            </div>

            <div class="transaction-date">
                {{ optional($pembayaran->created_at)->format('d F Y H:i') }}
            </div>

        </div>

        <div>

            @if(in_array($pembayaran->status_pembayaran, ['pending', 'pending_verifikasi']))

                <span class="badge bg-warning text-dark">
                    Menunggu Verifikasi
                </span>

            @elseif($pembayaran->status_pembayaran=='berhasil')

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

</div>

<div class="transaction-card">

    <h5 class="fw-bold mb-4">
        Informasi Pembayaran
    </h5>

    <div class="row">

        <div class="col-md-6 mb-3">

            <div class="info-box">
                <small>Jenis Pembayaran</small>
                <strong>{{ ucfirst($pembayaran->jenis_pembayaran) }}</strong>
            </div>

        </div>

        <div class="col-md-6 mb-3">

            <div class="info-box">
                <small>Metode Pembayaran</small>

                <strong>

                    @if($pembayaran->metode_pembayaran=='transfer_bank')
                        Transfer Bank

                    @else
                        Cash
                    @endif

                </strong>

            </div>

        </div>

        <div class="col-md-6 mb-3">

            <div class="info-box">
                <small>Nominal</small>

                <strong>
                    Rp {{ number_format($pembayaran->nominal_tagihan,0,',','.') }}
                </strong>

            </div>

        </div>

        <div class="col-md-6 mb-3">

            <div class="info-box">
                <small>Status</small>

                <strong>

                    @if(in_array($pembayaran->status_pembayaran, ['pending', 'pending_verifikasi']))
                        Menunggu Verifikasi

                    @elseif($pembayaran->status_pembayaran=='berhasil')
                        Berhasil

                    @else
                        Ditolak
                    @endif

                </strong>

            </div>

        </div>

    </div>

</div>


{{-- ============================
TRANSFER BANK
============================= --}}

@if(in_array($pembayaran->metode_pembayaran, ['transfer', 'transfer_bank']))

<div class="transaction-card">

    <h5 class="fw-bold mb-4">
        Informasi Transfer Bank
    </h5>

    <div class="alert alert-light border">

        <strong>Bank Tujuan</strong><br>
        {{ $pembayaran->bank_tujuan ?? 'Bank BCA - 00727198919 (Hasmalina)' }}

    </div>

    @if(in_array($pembayaran->status_pembayaran, ['pending', 'pending_verifikasi']))

        <div class="alert alert-warning">

            Bukti transfer sedang menunggu verifikasi Admin Keuangan.

        </div>

    @elseif($pembayaran->status_pembayaran=='berhasil')

        <div class="alert alert-success">

            Pembayaran telah diverifikasi.

        </div>

    @endif

</div>

@endif





{{-- ============================
CASH
============================= --}}

@if($pembayaran->metode_pembayaran=='cash')

<div class="transaction-card">

    <h5 class="fw-bold mb-4">
        Pembayaran Tunai
    </h5>

    @if($pembayaran->status_pembayaran=='pending')

        <div class="alert alert-warning">

            <h5 class="fw-bold">
                Menunggu Pembayaran Tunai
            </h5>

            Silakan melakukan pembayaran kepada
            <b>Admin Keuangan Nabilla Catering.</b>

            <hr>

            <strong>Status :</strong>
            Pending

        </div>

    @elseif($pembayaran->status_pembayaran=='berhasil')

        <div class="alert alert-success">

            Pembayaran tunai telah dikonfirmasi oleh Admin Keuangan.

        </div>

    @endif

</div>

@endif


@if($pembayaran->status_pembayaran=='berhasil')

    <div class="mt-3">
        <a href="{{ route('klien.pembayaran.pdf',$pembayaran->id) }}"
           class="btn btn-danger">
            <i class="bi bi-file-earmark-pdf"></i>
            Download PDF
        </a>
    </div>

@endif


@if($pembayaran->bukti_pembayaran)

<div class="transaction-card">

    <h5 class="fw-bold mb-3">
        Bukti Transfer
    </h5>

    <img
        src="{{ asset('storage/'.$pembayaran->bukti_pembayaran) }}"
        class="img-fluid rounded border shadow">

</div>

@endif

@endsection