@extends('layouts.app')

@section('content')
<div class="row gy-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1">Target Penjualan</h4>
                <p class="text-muted mb-0">Kelola target penjualan bulanan untuk tim sales.</p>
            </div>
            <a href="{{ route('target.create') }}" class="btn btn-primary">Tambah Target</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card card-soft border-0 shadow-sm">
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Sales</th>
                                <th>Bulan</th>
                                <th>Tahun</th>
                                <th>Target Nominal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($targets as $target)
                                <tr>
                                    <td>{{ $target->sales?->name ?? '-' }}</td>
                                    <td>{{ $target->bulan }}</td>
                                    <td>{{ $target->tahun }}</td>
                                    <td>Rp {{ number_format($target->target_nominal, 0, ',', '.') }}</td>
                                    <td>
                                        <a href="{{ route('target.edit', $target) }}" class="btn btn-sm btn-outline-primary">Ubah</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
