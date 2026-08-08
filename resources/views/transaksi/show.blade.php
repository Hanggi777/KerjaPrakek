@extends('layouts.app')

@section('content')

    @php

    $statusColor = [
        'menunggu_dp' => 'warning',
        'dp_terbayar' => 'info',
        'menunggu_pelunasan' => 'primary',
        'lunas' => 'success',
        'batal' => 'danger',
    ];

    $persen = 0;

    if ($transaction->total_penawaran > 0) {
        $persen = (
            ($transaction->total_penawaran - $transaction->sisa_pelunasan)
            /
            $transaction->total_penawaran
        ) * 100;
    }

    @endphp

    <div class="row justify-content-center">

        <div class="col-lg-10">

            <div class="card card-soft shadow-sm border-0">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-center mb-4">

                        <div>

                            <h3 class="fw-bold">

                                <i class="bi bi-receipt-cutoff"></i>

                                Detail Transaksi

                            </h3>

                            <p class="text-muted mb-0">

                                Kode :

                                <b>{{ $transaction->kode_transaksi }}</b>

                            </p>

                        </div>

                        <div>

                            <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">

                                <i class="bi bi-arrow-left"></i>

                                Kembali

                            </a>

                        </div>

                    </div>


                    @if(session('success'))

                        <div class="alert alert-success">

                            {{ session('success') }}

                        </div>

                    @endif

                    @if(session('error'))

                        <div class="alert alert-danger">

                            {{ session('error') }}

                        </div>

                    @endif


                    <div class="row g-4">

                        <div class="col-md-6">

                            <div class="card border-0 shadow-sm">

                                <div class="card-header bg-primary text-white">

                                    Informasi Klien

                                </div>

                                <div class="card-body">

                                    <table class="table table-borderless">

                                        <tr>

                                            <th width="35%">Nama</th>

                                            <td>{{ $transaction->klien?->nama_klien }}</td>

                                        </tr>

                                        <tr>

                                            <th>Perusahaan</th>

                                            <td>{{ $transaction->klien->nama_perusahaan }}</td>

                                        </tr>

                                        <tr>

                                            <th>Email</th>

                                            <td>{{ $transaction->klien->email }}</td>

                                        </tr>

                                        <tr>

                                            <th>No HP</th>

                                            <td>{{ $transaction->klien->no_hp }}</td>

                                        </tr>

                                    </table>

                                </div>

                            </div>

                        </div>



                        <div class="col-md-6">

                            <div class="card border-0 shadow-sm">

                                <div class="card-header bg-success text-white">

                                    Informasi Paket

                                </div>

                                <div class="card-body">

                                    <table class="table table-borderless">

                                        <tr>

                                            <th width="40%">Paket</th>

                                            <td>

                                                {{ $transaction->paketMaster?->nama_paket }}

                                            </td>

                                        </tr>

                                        <tr>

                                            <th>Varian</th>

                                            <td>

                                                {{ $transaction->paketMasterHarga->nama_varian ?? 'Standar' }}

                                            </td>

                                        </tr>

                                        <tr>

                                            <th>Jumlah Porsi</th>

                                            <td>

                                                {{ $transaction->jumlah_porsi }}

                                            </td>

                                        </tr>

                                        <tr>

                                            <th>Tanggal Acara</th>

                                            <td>

                                                {{ optional($transaction->tanggal_acara)->format('d M Y') }}

                                            </td>

                                        </tr>

                                        <tr>

                                            <th>Lokasi</th>

                                            <td>

                                                {{ $transaction->lokasi_acara }}

                                            </td>

                                        </tr>

                                        <tr>

                                            <th>Status</th>

                                            <td>

                                                <span
                                                    class="badge bg-{{ $statusColor[$transaction->computeCurrentStatus()] ?? 'secondary' }}">

                                                    {{ ucwords(str_replace('_', ' ', $transaction->computeCurrentStatus())) }}

                                                </span>

                                            </td>

                                        </tr>

                                        <tr>

                                            <th>Batas Pelunasan</th>

                                            <td>

                                                {{ $transaction->batas_pelunasan->format('d M Y') }}

                                                @if(
                                                        now()->greaterThan($transaction->batas_pelunasan)
                                                        &&
                                                        $transaction->computeCurrentStatus() != 'lunas'
                                                    )

                                                    <span class="badge bg-danger">

                                                        Lewat Deadline

                                                    </span>

                                                @endif

                                            </td>

                                        </tr>

                                    </table>

                                </div>

                            </div>

                        </div>

                    </div>


                    <hr class="my-4">


                    <h5 class="mb-3">

                        Ringkasan Transaksi

                    </h5>


                    <div class="row">

                        <div class="col-md-3">

                            <div class="card border-0 shadow-sm">

                                <div class="card-body text-center">

                                    <p class="text-muted">

                                        Subtotal

                                    </p>

                                    <h5>

                                        Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}

                                    </h5>

                                </div>

                            </div>

                        </div>


                        <div class="col-md-3">

                            <div class="card border-0 shadow-sm">

                                <div class="card-body text-center">

                                    <p class="text-muted">

                                        DP

                                    </p>

                                    <h5 class="text-primary">

                                        Rp {{ number_format($transaction->nominal_dp, 0, ',', '.') }}

                                    </h5>

                                </div>

                            </div>

                        </div>


                        <div class="col-md-3">

                            <div class="card border-0 shadow-sm">

                                <div class="card-body text-center">

                                    <p class="text-muted">

                                        Sisa

                                    </p>

                                    <h5 class="text-danger">

                                        Rp {{ number_format($transaction->sisa_pelunasan, 0, ',', '.') }}

                                    </h5>

                                </div>

                            </div>

                        </div>


                        <div class="col-md-3">

                            <div class="card border-0 shadow-sm">

                                <div class="card-body text-center">

                                    <p class="text-muted">

                                        Total

                                    </p>

                                    <h5 class="text-success">

                                        Rp {{ number_format($transaction->total_penawaran, 0, ',', '.') }}

                                    </h5>

                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="card mt-4 border-0 shadow-sm">

                        <div class="card-body">

                            <h6 class="mb-3">

                                Progress Pembayaran

                            </h6>

                            <div class="progress" style="height:28px">

                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                    style="width:{{ $persen }}%">

                                    {{ number_format($persen, 0) }}%

                                </div>

                            </div>

                        </div>

                    </div>

                    <hr class="my-4">
                    <div class="row">

                        {{-- DATA TRANSAKSI --}}
                        <div class="col-md-5">

                            <div class="card border-0 shadow-sm mb-4">

                                <div class="card-header bg-dark text-white">
                                    Informasi Transaksi
                                </div>

                                <div class="card-body">

                                    <table class="table table-borderless mb-0">

                                        <tr>
                                            <th width="40%">Sales</th>
                                            <td>{{ $transaction->sales?->name ?? '-' }}/td>
                                        </tr>

                                        <tr>
                                            <th>Tanggal Transaksi</th>
                                            <td>{{ $transaction->tanggal_transaksi->format('d M Y') }}</td>
                                        </tr>

                                        <tr>
                                            <th>Catatan</th>
                                            <td>{{ $transaction->catatan ?: '-' }}</td>
                                        </tr>

                                    </table>

                                </div>

                            </div>

                        </div>

<div class="card border-0 shadow-sm mt-4">
    <div class="card-header bg-info text-white">
        Detail Item
    </div>

    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Satuan</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaction->detail as $item)
                    <tr>
                        <td>{{ $item->nama_item }}</td>
                        <td>{{ $item->qty }}</td>
                        <td>{{ $item->satuan }}</td>
                        <td>Rp {{ number_format($item->harga_satuan,0,',','.') }}</td>
                        <td>Rp {{ number_format($item->subtotal,0,',','.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">
                            Tidak ada detail item.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

                        {{-- RIWAYAT PEMBAYARAN --}}
                        <div class="col-md-7">

                            <div class="card border-0 shadow-sm">

                                <div class="card-header bg-success text-white">

                                    Riwayat Pembayaran

                                </div>

                                <div class="card-body">

                                    <div class="table-responsive">

                                        <table class="table table-bordered align-middle">

                                            <thead class="table-light">

                                                <tr>

                                                    <th>Tanggal</th>

                                                    <th>Jenis</th>

                                                    <th>Metode</th>

                                                    <th>Nominal</th>

                                                    <th>Status</th>

                                                </tr>

                                            </thead>

                                            <tbody>

                                                @forelse($transaction->pembayaran as $payment)

                                                    <tr>

                                                        <td>

                                                            {{ $payment->tanggal_bayar ? $payment->tanggal_bayar->format('d/m/Y') : '-' }}

                                                        </td>

                                                        <td>

                                                            {{ $payment->jenis_pembayaran }}

                                                        </td>

                                                        <td>

                                                            {{ $payment->metode_pembayaran }}

                                                        </td>

                                                        <td>

                                                            Rp {{ number_format($payment->nominal_bayar, 0, ',', '.') }}

                                                        </td>

                                                        <td>

                                                            @if($payment->status_pembayaran == 'berhasil')

                                                                <span class="badge bg-success">

                                                                    Berhasil

                                                                </span>

                                                            @elseif($payment->status_pembayaran == 'pending')

                                                                <span class="badge bg-warning text-dark">

                                                                    Pending

                                                                </span>

                                                            @elseif($payment->status_pembayaran == 'pending_verifikasi')

                                                                <span class="badge bg-info">

                                                                    Pending Verifikasi

                                                                </span>

                                                            @else

                                                                <span class="badge bg-danger">

                                                                    Gagal

                                                                </span>

                                                            @endif

                                                        </td>

                                                    </tr>

                                                @empty

                                                    <tr>

                                                        <td colspan="5" class="text-center text-muted">

                                                            Belum ada pembayaran.

                                                        </td>

                                                    </tr>

                                                @endforelse

                                            </tbody>

                                        </table>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>



                    @if(auth()->user()->role == 'superadmin' && $transaction->computeCurrentStatus() != 'lunas')

    <hr class="my-4">

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-primary text-white">
            Input Pembayaran
        </div>

        <div class="card-body">

            <form method="POST" action="{{ route('pembayaran.store', $transaction) }}">
                @csrf

                <div class="row">

                    <div class="col-md-4">
                        <label class="form-label">Nominal Bayar</label>

                        <input type="number"
                               name="nominal_bayar"
                               class="form-control"
                               value="{{ old('nominal_bayar', $transaction->sisa_pelunasan) }}"
                               required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Metode Pembayaran</label>

                        <select name="metode_pembayaran" class="form-select">
                            <option value="transfer_bank">Transfer Bank</option>
                            <option value="cash">Cash</option>
                          
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Catatan</label>

                        <input type="text"
                               name="catatan"
                               class="form-control">
                    </div>

                </div>

                <div class="mt-4">
                    <button class="btn btn-success">
                        <i class="bi bi-credit-card"></i>
                        Simpan Pembayaran
                    </button>
                </div>

            </form>

        </div>

    </div>

@endif

                    <hr>

                    <div class="d-flex justify-content-end gap-2">

                        <a href="{{ route('transaksi.pdf', $transaction) }}"
                        target="_blank"
                        class="btn btn-primary">
                            <i class="bi bi-printer"></i> Cetak Invoice
                        </a>

                        <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">

                            Kembali

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection