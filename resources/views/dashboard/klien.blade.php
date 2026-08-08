@extends('layouts.client')

@section('title', 'Dashboard Portal Klien')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-person-circle"></i> Portal Klien</h1>
    <p>Kelola transaksi dan pembayaran Anda</p>
</div>

<!-- STAT CARDS -->
<div class="row g-4 mb-4">

    <div class="col-lg-4 col-md-6">
        <div class="stat-card">
            <div class="stat-card-icon">
                <i class="bi bi-receipt"></i>
            </div>

            <div class="stat-card-label">
                Total Transaksi
            </div>

            <div class="stat-card-value">
                {{ $totalTransaksi }}
            </div>

            <small class="text-muted">
                Transaksi Anda
            </small>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="stat-card">
            <div class="stat-card-icon">
                <i class="bi bi-wallet2"></i>
            </div>

            <div class="stat-card-label">
                Total Tagihan
            </div>

            <div class="stat-card-value">
                Rp {{ number_format($totalTagihan,0,',','.') }}
            </div>

            <small class="text-muted">
                Belum Lunas
            </small>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="stat-card">
            <div class="stat-card-icon">
                <i class="bi bi-check-circle-fill"></i>
            </div>

            <div class="stat-card-label">
                Total Terbayar
            </div>

            <div class="stat-card-value">
                Rp {{ number_format($totalTerbayar,0,',','.') }}
            </div>

            <small class="text-muted">
                Pembayaran Masuk
            </small>
        </div>
    </div>

</div>

<!-- ALERT H-30 -->
@if($transaksiH30>0)

<div class="alert alert-warning shadow-sm rounded-4 mb-4">

    <i class="bi bi-exclamation-triangle-fill"></i>

    Ada

    <strong>{{ $transaksiH30 }}</strong>

    transaksi yang mendekati batas pelunasan.

</div>

@endif

<!-- TRANSAKSI LIST -->
<div style="margin-top: 30px;">
    <h3 style="font-size: 20px; font-weight: 700; color: var(--dark-color); margin-bottom: 20px;">
        <i class="bi bi-receipt"></i> Daftar Transaksi Anda
    </h3>


@forelse($transaksiKlien as $transaksi)

<div class="transaction-card">

    {{-- Header --}}
    <div class="transaction-header">

        <div>
            <div class="transaction-code">
                {{ $transaksi->kode_transaksi }}
            </div>

            <div class="transaction-date">
                {{ $transaksi->tanggal_transaksi->format('d F Y') }}
            </div>
        </div>

        <div>

            @if($transaksi->computeCurrentStatus()=='menunggu_dp')
                <span class="badge-status badge-pending">
                    Menunggu DP
                </span>

            @elseif($transaksi->computeCurrentStatus()=='dp_terbayar')
                <span class="badge-status badge-paid">
                    DP Terbayar
                </span>

            @elseif($transaksi->computeCurrentStatus()=='menunggu_pelunasan')
                <span class="badge-status badge-pending">
                    Menunggu Pelunasan
                </span>

            @elseif($transaksi->computeCurrentStatus()=='lunas')
                <span class="badge-status badge-lunas">
                    Lunas
                </span>
            @endif

        </div>

    </div>

    {{-- Informasi Transaksi --}}
    <div class="transaction-grid">

        <div class="info-box">
            <small>Paket</small>
            <strong>{{ optional($transaksi->paketMaster)->nama_paket ?? 'Custom' }}</strong>
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

    {{-- Pembayaran --}}
    <div class="row g-4">

        <div class="col-md-4">

            <div class="info-box">

                <small>DP (10%)</small>

                <strong>
                    Rp {{ number_format($transaksi->nominal_dp,0,',','.') }}
                </strong>

                @php
                    $dpPayment = $transaksi->pembayaran()->where('jenis_pembayaran','dp')->first();
                @endphp

                <span class="badge-status {{ $dpPayment && $dpPayment->status_pembayaran=='berhasil' ? 'badge-paid' : 'badge-pending' }}">
                    {{ $dpPayment ? ucfirst($dpPayment->status_pembayaran) : 'Pending' }}
                </span>

            </div>

        </div>

        <div class="col-md-4">

            <div class="info-box">

                <small>Pelunasan (90%)</small>

                <strong>
                    Rp {{ number_format($transaksi->sisa_pelunasan,0,',','.') }}
                </strong>

                @php
                    $pelunasanPayment = $transaksi->pembayaran()->where('jenis_pembayaran','pelunasan')->first();
                @endphp

                <span class="badge-status {{ $pelunasanPayment && $pelunasanPayment->status_pembayaran=='berhasil' ? 'badge-paid' : 'badge-pending' }}">
                    {{ $pelunasanPayment ? ucfirst($pelunasanPayment->status_pembayaran) : 'Pending' }}
                </span>

            </div>

        </div>

        <div class="col-md-4">

            <div class="info-box">

                <small>Batas Pelunasan</small>

                <strong>
                    {{ $transaksi->batas_pelunasan?->format('d F Y') }}
                </strong>

            </div>

        </div>

    </div>

    {{-- Item --}}
    @if($transaksi->detail->count())

    <hr>

    <h6 class="fw-bold mb-3">
        Item Penawaran
    </h6>

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

    @endif

    {{-- Riwayat --}}
    @if($transaksi->pembayaran->count())

    <hr>

    <h6 class="fw-bold mb-3">
        Riwayat Pembayaran
    </h6>

    @foreach($transaksi->pembayaran as $pembayaran)

        <div class="d-flex justify-content-between align-items-center mb-3">

            <div>

                <strong>
                    {{ ucfirst($pembayaran->jenis_pembayaran) }}
                </strong>

                <br>

                <small class="text-muted">
                    {{ ucfirst(str_replace('_',' ',$pembayaran->metode_pembayaran)) }}
                </small>

            </div>

            <div class="text-end">

                <strong>
                    Rp {{ number_format($pembayaran->nominal_tagihan,0,',','.') }}
                </strong>

                <br>

                <span class="badge-status {{ $pembayaran->status_pembayaran=='berhasil' ? 'badge-paid' : 'badge-pending' }}">
                    {{ ucfirst($pembayaran->status_pembayaran) }}
                </span>

            </div>

        </div>

    @endforeach

    @endif

    <div class="text-end mt-4">

        <a href="{{ route('klien.transaksi.detail',$transaksi->id) }}"
            class="btn btn-success rounded-pill">

            <i class="bi bi-eye"></i>

            Detail Transaksi

        </a>

    </div>

</div>

    @empty
        <div style="text-align: center; padding: 60px 20px; background: white; border-radius: 10px;">
            <i class="bi bi-inbox" style="font-size: 64px; color: #bdc3c7;"></i><br><br>
            <h4 style="color: #95a5a6;">Belum Ada Transaksi</h4>
            <p style="color: #bdc3c7;">Hubungi sales NABILLA CATERING untuk membuat transaksi baru.</p>
        </div>
    @endforelse
</div>

<!-- Pagination -->
@if ($transaksiKlien->count() > 0)
    <div style="margin-top: 20px; display: flex; justify-content: center;">
        {{ $transaksiKlien->links('pagination::bootstrap-5') }}
    </div>
@endif
@endsection
