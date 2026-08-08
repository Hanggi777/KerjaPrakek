@extends('layouts.client')

@section('title','Pembayaran Tunai')

@section('content')

<div class="card shadow border-0">

<div class="card-body">

<h3 class="fw-bold mb-4">

Pembayaran Tunai

</h3>

<form
    action="{{ route('klien.pembayaran.cash.store',$transaksi->id) }}"
    method="POST"
    enctype="multipart/form-data">

@csrf

@php
    $hasSuccessfulDp = $transaksi->pembayaran()->where('status_pembayaran', 'berhasil')->where('jenis_pembayaran', 'dp')->exists();
    $jenisPembayaran = 'dp';
    $maxPembayaran = (int) ($transaksi->nominal_dp ?? $transaksi->total_penawaran ?? 0);

    if ($hasSuccessfulDp) {
        $totalTerbayar = $transaksi->pembayaran()->where('status_pembayaran', 'berhasil')->sum('nominal_bayar');
        $remaining = max(0, (float) $transaksi->total_penawaran - (float) $totalTerbayar);
        $maxPembayaran = max(1, (int) $remaining);
        $jenisPembayaran = $remaining <= 0 ? 'pelunasan' : 'pembayaran';
    }

    $isDp = $jenisPembayaran === 'dp';
    $minPembayaran = $isDp ? $maxPembayaran : max(1, (int) ceil($maxPembayaran * 0.1));
@endphp

<div class="mb-4">

<label class="form-label">

Nominal Pembayaran

</label>

<input
type="number"
name="nominal_bayar"
class="form-control"
min="{{ $minPembayaran }}"
max="{{ $maxPembayaran }}"
required>

<small class="text-muted">
    @if($isDp)
        Minimal pembayaran DP: Rp {{ number_format($minPembayaran, 0, ',', '.') }} (harus lunas).
    @else
        Minimal pembayaran: 10% dari tagihan, yaitu Rp {{ number_format($minPembayaran, 0, ',', '.') }}.
    @endif
    Maksimal pembayaran: Rp {{ number_format($maxPembayaran, 0, ',', '.') }}.
</small>

</div>

<div class="mb-4">

    <label class="form-label">

        Upload Bukti / Foto Kwitansi
        <span class="text-danger">*</span>

    </label>

    <input
        type="file"
        name="bukti_pembayaran"
        class="form-control"
        accept="image/*"
        required>

    <small class="text-muted">

        Format: JPG, PNG, JPEG (maks. 2 MB)

    </small>

</div>

<button class="btn btn-success">

Simpan

</button>

</form>

</div>

</div>

@endsection