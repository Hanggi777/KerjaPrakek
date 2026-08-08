@extends('layouts.app')

@section('title', 'Detail Klien')

@section('content')

<div class="content-header mb-4">
    <h2><i class="bi bi-person-circle"></i> Detail Klien</h2>
    <p class="text-muted">Informasi lengkap data klien</p>
</div>

<div class="row">

    {{-- Informasi Klien --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                Informasi Klien
            </div>

            <div class="card-body">

                <p><strong>Nama</strong></p>
                <p>{{ $klien->nama_klien }}</p>

                <hr>

                <p><strong>Email</strong></p>
                <p>{{ $klien->email }}</p>

                <hr>

                <p><strong>No HP</strong></p>
                <p>{{ $klien->no_hp }}</p>

                <hr>

                <p><strong>Alamat</strong></p>
                <p>{{ $klien->alamat }}</p>

                @if($klien->nama_perusahaan)
                    <hr>

                    <p><strong>Perusahaan</strong></p>
                    <p>{{ $klien->nama_perusahaan }}</p>
                @endif

                <hr>

                <a href="{{ route('klien.edit',$klien) }}"
                    class="btn btn-warning w-100 mb-2">
                    <i class="bi bi-pencil-square"></i>
                    Edit Data
                </a>

                <a href="{{ route('klien.index') }}"
                    class="btn btn-secondary w-100">
                    <i class="bi bi-arrow-left"></i>
                    Kembali
                </a>

            </div>
        </div>
    </div>

    {{-- Statistik --}}
    <div class="col-md-8">

        <div class="row mb-4">

            <div class="col-md-6">
                <div class="card text-center">

                    <div class="card-body">

                        <h6>Total Transaksi</h6>

                        <h2 class="text-primary">
                            {{ $totalTransaksi }}
                        </h2>

                    </div>

                </div>
            </div>

            <div class="col-md-6">
                <div class="card text-center">

                    <div class="card-body">

                        <h6>Total Tagihan</h6>

                        <h2 class="text-danger">
                            Rp {{ number_format($totalTagihan,0,',','.') }}
                        </h2>

                    </div>

                </div>
            </div>

        </div>

        {{-- Riwayat Transaksi --}}
        <div class="card">

            <div class="card-header">
                Riwayat Transaksi
            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover">

                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>

                        @forelse($transaksi as $item)

                            <tr>

                                <td>{{ $loop->iteration }}</td>

                                <td>{{ $item->kode_transaksi }}</td>

                                <td>
                                    {{ \Carbon\Carbon::parse($item->tanggal_transaksi)->format('d-m-Y') }}
                                </td>

                                <td>
                                    Rp {{ number_format($item->total_penawaran,0,',','.') }}
                                </td>

                                <td>
                                    <span class="badge bg-primary">
                                        {{ ucfirst($item->computeCurrentStatus()) }}
                                    </span>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="5" class="text-center">
                                    Belum ada transaksi.
                                </td>
                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>

                <div class="mt-3">
                    {{ $transaksi->links() }}
                </div>

            </div>

        </div>

    </div>

</div>

@endsection