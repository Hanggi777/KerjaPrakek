@extends('layouts.app')

@section('content')

<div class="container">

    <h3 class="mb-4">

        Omzet Bulan Ini

    </h3>

    <div class="card shadow">

        <div class="card-body">

            <h2 class="text-success">

                Rp {{ number_format($omzet,0,',','.') }}

            </h2>

        </div>

    </div>

    <div class="card mt-4 shadow">

        <div class="card-body">

            <table class="table table-striped">

                <thead>

                    <tr>

                        <th>Kode</th>
                        <th>Klien</th>
                        <th>Total</th>

                    </tr>

                </thead>

                <tbody>

                @foreach($transaksi as $trx)

                    <tr>

                        <td>{{ $trx->kode_transaksi }}</td>

                        <td>{{ $trx->klien->nama_klien }}</td>

                        <td>

                            Rp {{ number_format($trx->total_penawaran,0,',','.') }}

                        </td>

                    </tr>

                @endforeach

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection