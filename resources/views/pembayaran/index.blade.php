@extends('layouts.app')

@section('title', 'Kelola Pembayaran')

@section('content')

<div class="content-header d-flex justify-content-between align-items-center mb-4">

    <div>

        <h1>
            <i class="bi bi-credit-card"></i>
            Kelola Pembayaran
        </h1>

        <p class="text-muted mb-0">
            Daftar seluruh pembayaran transaksi.
        </p>

    </div>

</div>

<div class="card shadow-sm">

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-bordered table-hover align-middle">

                <thead class="table-light">
                <tr class="text-center align-middle">

                    <th style="width:170px">Kode</th>
                    <th>Klien</th>
                    <th style="width:80px">Jenis</th>
                    <th style="width:130px">Metode</th>
                    <th style="width:200px">Bank Tujuan</th>
                    <th style="width:130px">Nominal</th>
                    <th style="width:120px">Bukti</th>
                    <th style="width:110px">Status</th>
                    <th style="width:150px">Aksi</th>

                </tr>
                </thead>

                <tbody>

                    @forelse($pembayaran as $item)

                        <tr>

    <td>{{ $item->kode_pembayaran }}</td>

    <td>{{ $item->transaksi->klien->nama_klien }}</td>

    <td>{{ strtoupper($item->jenis_pembayaran) }}</td>

    <td>{{ ucfirst($item->metode_pembayaran) }}</td>

    <td>{{ $item->bank_tujuan ?? '-' }}</td>

    <td>
        Rp {{ number_format($item->nominal_bayar,0,',','.') }}
    </td>

    <td class="text-center">

        @if($item->bukti_pembayaran)

            <a href="{{ asset('storage/'.$item->bukti_pembayaran) }}"
               target="_blank"
               class="btn btn-info btn-sm">

                <i class="bi bi-image"></i>

            </a>

        @else

            -

        @endif

    </td>

    <td class="text-center">

        @if($item->status_pembayaran=='pending')

            <span class="badge bg-warning">
                Pending
            </span>

        @elseif($item->status_pembayaran=='berhasil')

            <span class="badge bg-success">
                Berhasil
            </span>

        @else

            <span class="badge bg-danger">
                Gagal
            </span>

        @endif

    </td>

    <td>

        <div class="d-flex justify-content-center gap-1">

            <a href="{{ route('pembayaran.show',$item->id) }}"
               class="btn btn-info btn-sm">

                <i class="bi bi-eye"></i>

            </a>

            @if($item->status_pembayaran!='berhasil')

            <a href="{{ route('pembayaran.edit',$item->id) }}"
               class="btn btn-warning btn-sm">

                <i class="bi bi-pencil"></i>

            </a>

            <form action="{{ route('pembayaran.destroy',$item->id) }}"
                  method="POST"
                  class="d-inline">

                @csrf
                @method('DELETE')

                <button class="btn btn-danger btn-sm">

                    <i class="bi bi-trash"></i>

                </button>

            </form>

            @endif

        </div>

    </td>

</tr>

                    @empty

                        <tr>

                            <td colspan="7" class="text-center py-4">

                                Belum ada data pembayaran.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>
@endsection
