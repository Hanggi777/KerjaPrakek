@extends('layouts.client')

@section('title','Pembayaran')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h2 class="fw-bold mb-1">
            Pembayaran
        </h2>

        <p class="text-muted mb-0">
            Pilih metode pembayaran transaksi Anda
        </p>
    </div>

    <a href="{{ url()->previous() }}"
       class="btn btn-outline-success rounded-pill">

        <i class="bi bi-arrow-left"></i>
        Kembali

    </a>

</div>

<div class="card shadow border-0 rounded-4">

    <div class="card-body p-4">

        <h5 class="fw-bold mb-4">
            Ringkasan Tagihan
        </h5>

        <table class="table table-borderless">

            <tr>
                <td width="220">Kode Transaksi</td>
                <td>: {{ $transaksi->kode_transaksi }}</td>
            </tr>

            <tr>
                <td>Paket</td>
                <td>: {{ optional($transaksi->paketMaster)->nama_paket }}</td>
            </tr>

            <tr>
                <td>Total Tagihan</td>
                <td class="fw-bold text-success">
                    : Rp {{ number_format($transaksi->total_penawaran,0,',','.') }}
                </td>
            </tr>

        </table>

        <hr>

        <h5 class="fw-bold mb-3">
            Pilih Metode Pembayaran
        </h5>

        <form action="{{ route('klien.pembayaran.proses',$transaksi->id) }}"
              method="POST">

            @csrf

            <div class="form-check border rounded p-3 mb-3">

                <input class="form-check-input"
                    type="radio"
                    name="metode_pembayaran"
                    value="transfer_bank"
                    id="transfer_bank">

                <label class="form-check-label ms-2" for="transfer_bank">

                    <strong>Transfer Bank</strong>

                    <br>

                    <small class="text-muted">
                        Upload bukti transfer, kemudian menunggu verifikasi Admin Keuangan.
                    </small>

                </label>

            </div>


            <div class="form-check border rounded p-3 mb-4">

                <input class="form-check-input"
                       type="radio"
                       name="metode_pembayaran"
                       value="cash"
                       id="cash">

                <label class="form-check-label ms-2" for="cash">

                    <strong>Cash</strong>

                    <br>

                    <small class="text-muted">
                        Pembayaran dilakukan langsung kepada Admin Keuangan.
                        Status akan berubah setelah pembayaran dikonfirmasi.
                    </small>

                </label>

            </div>

            <div class="text-end">

                    <button type="submit"
                            class="btn btn-success btn-lg rounded-pill">

                        <i class="bi bi-credit-card"></i>
                        Lanjutkan Pembayaran

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

@endsection