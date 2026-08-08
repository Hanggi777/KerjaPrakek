@extends('layouts.app')

@section('title','Komisi Sales')

@section('content')

<div class="content-header mb-4">
    <h2>
        <i class="bi bi-cash-stack"></i>
        Komisi Sales
    </h2>

    <p class="text-muted">
        Monitoring komisi seluruh sales.
    </p>
</div>

<div class="card shadow-sm">

    <div class="card-body">

        <table class="table table-bordered table-striped">

            <thead class="table-success">

                <tr>
                    <th>Sales</th>
                    <th>Total Deal</th>
                    <th>Komisi DP (5%)</th>
                    <th>Komisi Lunas (5%)</th>
                    <th>Total Komisi</th>
                </tr>

            </thead>

            <tbody>

                @foreach($data as $item)

                <tr>

                    <td>{{ $item['nama'] }}</td>

                    <td>
                        Rp {{ number_format($item['deal'],0,',','.') }}
                    </td>

                    <td>
                        Rp {{ number_format($item['dp'],0,',','.') }}
                    </td>

                    <td>
                        Rp {{ number_format($item['lunas'],0,',','.') }}
                    </td>

                    <td class="fw-bold text-success">
                        Rp {{ number_format($item['komisi'],0,',','.') }}
                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

@endsection