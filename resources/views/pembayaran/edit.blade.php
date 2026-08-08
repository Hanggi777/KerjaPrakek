@extends('layouts.app')

@section('title','Edit Pembayaran')

@section('content')

<div class="card shadow-sm">

    <div class="card-header">
        <h4>Edit Pembayaran</h4>
    </div>

    <div class="card-body">

        <form action="{{ route('pembayaran.update',$pembayaran->id) }}"
              method="POST">

            @csrf
            @method('PUT')

            <div class="mb-3">

                <label>Kode Pembayaran</label>

                <input type="text"
                       class="form-control"
                       value="{{ $pembayaran->kode_pembayaran }}"
                       readonly>

            </div>

            <div class="mb-3">

                <label>Nama Klien</label>

                <input type="text"
                       class="form-control"
                       value="{{ $pembayaran->transaksi->klien->nama_klien }}"
                       readonly>

            </div>

            <div class="mb-3">

                <label>Nominal</label>

                <input type="text"
                       class="form-control"
                       value="Rp {{ number_format($pembayaran->nominal_bayar,0,',','.') }}"
                       readonly>

            </div>

            <div class="mb-3">

                <label>Metode Pembayaran</label>

                <select name="metode_pembayaran"
                        class="form-select">

                    <option value="cash"
                        {{ $pembayaran->metode_pembayaran=='cash'?'selected':'' }}>
                        Cash
                    </option>

                    <option value="transfer_bank"
                        {{ $pembayaran->metode_pembayaran=='transfer_bank'?'selected':'' }}>
                        Transfer
                    </option>

                   

                </select>

            </div>

            <div class="mb-3">

                <label>Status Pembayaran</label>

                <select name="status_pembayaran"
                        class="form-select">

                    <option value="pending"
                        {{ $pembayaran->status_pembayaran=='pending'?'selected':'' }}>
                        Pending
                    </option>

                    <option value="berhasil"
                        {{ $pembayaran->status_pembayaran=='berhasil'?'selected':'' }}>
                        Berhasil
                    </option>

                    <option value="gagal"
                        {{ $pembayaran->status_pembayaran=='gagal'?'selected':'' }}>
                        Gagal
                    </option>

                </select>

            </div>

            @if(in_array($pembayaran->metode_pembayaran, ['transfer', 'transfer_bank']))

            <div class="mb-3">

                <label>Bank Tujuan</label>

                <input type="text"
                       class="form-control"
                       value="{{ $pembayaran->bank_tujuan ?? '-' }}"
                       readonly>

            </div>

            @endif

            <div class="mb-3">

                <label>Catatan</label>

                <textarea name="catatan"
                          class="form-control"
                          rows="4">{{ $pembayaran->catatan }}</textarea>

            </div>

            <button class="btn btn-success">

                <i class="bi bi-check-circle"></i>

                Simpan Perubahan

            </button>

            <a href="{{ route('pembayaran.index') }}"
               class="btn btn-secondary">

                Kembali

            </a>

        </form>

    </div>

</div>

@endsection