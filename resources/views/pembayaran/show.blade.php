@extends('layouts.app')

@section('title','Detail Pembayaran')

@section('content')

<div class="content-header d-flex justify-content-between align-items-center mb-4">

    <div>

        <h1>

            <i class="bi bi-credit-card"></i>

            Detail Pembayaran

        </h1>



<div class="card shadow-sm">

    <div class="card-body">

        <table class="table">

            <tr>
                <th width="250">Kode Pembayaran</th>
                <td>{{ $pembayaran->kode_pembayaran }}</td>
            </tr>

            <tr>
                <th>Kode Transaksi</th>
                <td>{{ $pembayaran->transaksi->kode_transaksi }}</td>
            </tr>

            <tr>
                <th>Klien</th>
                <td>{{ $pembayaran->transaksi->klien->nama_klien }}</td>
            </tr>

            <tr>
                <th>Jenis Pembayaran</th>
                <td>{{ strtoupper($pembayaran->jenis_pembayaran) }}</td>
            </tr>

            <tr>
                <th>Metode</th>
                <td>{{ ucfirst($pembayaran->metode_pembayaran) }}</td>
            </tr>

            <tr>
                <th>Nominal Tagihan</th>
                <td>
                    Rp {{ number_format($pembayaran->nominal_tagihan,0,',','.') }}
                </td>
            </tr>

            <tr>
                <th>Nominal Dibayar</th>
                <td>
                    Rp {{ number_format($pembayaran->nominal_bayar,0,',','.') }}
                </td>
            </tr>

            <tr>

                <th>Status</th>

                <td>

                    @if($pembayaran->status_pembayaran=='pending')

                        <span class="badge bg-warning">Pending</span>

                    @elseif($pembayaran->status_pembayaran=='berhasil')

                        <span class="badge bg-success">Berhasil</span>

                    @else

                        <span class="badge bg-danger">Gagal</span>

                    @endif

                </td>

            </tr>

            <tr>

                <th>Tanggal Bayar</th>

                <td>

                    {{ optional($pembayaran->tanggal_bayar)->format('d F Y H:i') }}

                </td>

            </tr>

            @if(in_array($pembayaran->metode_pembayaran, ['transfer', 'transfer_bank']))

            <tr>

                <th>Bank Tujuan</th>

                <td>

                    {{ $pembayaran->bank_tujuan ?? '-' }}

                </td>

            </tr>

            @endif

            <tr>

                <th>Bukti Pembayaran</th>

                <td>

                    @if($pembayaran->bukti_pembayaran)

                        <a href="{{ asset('storage/'.$pembayaran->bukti_pembayaran) }}"
                           target="_blank"
                           class="btn btn-success btn-sm">

                            Lihat Bukti

                        </a>

                    @else

                        -

                    @endif

                </td>

            </tr>
            <div class="mt-4">
                <a href="{{ route('pembayaran.index') }}"
                class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i>
                    Kembali
                <a href="{{ route('pembayaran.pdf', $pembayaran->id) }}"
                    class="btn btn-danger"
                    target="_blank">
                        <i class="bi bi-file-earmark-pdf"></i>
                        Download PDF
                    </a>

                

            </div>

        </table>

    </div>

</div>

@endsection