@extends('layouts.client')

@section('title','Transfer Bank')

@section('content')

<div class="transaction-card">

    <h3 class="fw-bold mb-4">
        Transfer Bank
    </h3>

<form action="{{ route('klien.pembayaran.transfer.store', $transaksi->id) }}"
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

<div class="mb-3">

<label>Nominal Pembayaran</label>

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

<div class="mb-3">

<label>Pilih Bank Tujuan</label>

<select name="bank_tujuan" class="form-select" required>
    <option value="">-- Pilih Bank --</option>
    <option value="mandiri">Bank Mandiri - 0060006117992 (a.n Muslihaini)</option>
    <option value="bni_1">Bank BNI - 0118269119 (a.n Hasmalina)</option>
    <option value="bni_2">Bank BNI - 1210006312890 (a.n Nabilla Pratama)</option>
    <option value="bca">Bank BCA - 00727198919 (a.n Hasmalina)</option>
</select>

<small class="text-muted">Pilih bank sesuai dengan rekening yang akan Anda transfer.</small>

</div>

<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-success text-white fw-bold">

        <i class="bi bi-bank2"></i>
        Rekening Tujuan Pembayaran

    </div>

    <div class="card-body">

        <div class="row g-3">

            <div class="col-md-4">

                <div class="border rounded-3 p-3 h-100">

                    <h5 class="fw-bold text-primary">
                        Bank Mandiri
                    </h5>

                    <p class="mb-1">
                        <strong>No. Rekening</strong>
                    </p>

                    <h5 class="fw-bold">
                        0060006117992
                    </h5>

                    <small class="text-muted">
                        a.n Muslihaini
                    </small>

                </div>

            </div>

            <div class="col-md-4">

                <div class="border rounded-3 p-3 h-100">

                    <h5 class="fw-bold text-warning">
                        Bank BNI
                    </h5>
                    
                    <p class="mb-1">
                        <strong>No. Rekening</strong>
                    </p>

                    <h5 class="fw-bold">
                        0118269119
                    </h5>
                    <small class="text-muted">
                        a.n Hasmalina
                    </small><p> </p>
                    
                    <p class="mb-1">
                        <strong>No. Rekening</strong>
                    </p>

                    <h5 class="fw-bold">
                        1210006312890
                    </h5>

                    <small class="text-muted">
                        nabilla pratama
                    </small>

                </div>

            </div>

            <div class="col-md-4">

                <div class="border rounded-3 p-3 h-100">

                    <h5 class="fw-bold text-primary">
                        Bank BCA
                    </h5>

                    <p class="mb-1">
                        <strong>No. Rekening</strong>
                    </p>

                    <h5 class="fw-bold">
                        00727198919
                    </h5>

                    <small class="text-muted">
                        a.n Hasmalina
                    </small>

                </div>

            </div>

        </div>

    </div>

</div>

<div class="mb-3">

<label>Upload Bukti</label>

<input
type="file"
name="bukti_pembayaran"
class="form-control"
required>

</div>

<button
class="btn btn-success">

Kirim Bukti

</button>

</form>


</div>


@endsection