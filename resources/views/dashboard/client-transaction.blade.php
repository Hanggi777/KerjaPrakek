@extends('layouts.client')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card card-soft shadow-sm border-0">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h4 class="mb-1">Detail Transaksi Klien</h4>
                        <p class="text-muted mb-0">Kode: {{ $transaction->kode_transaksi }}</p>
                    </div>
                    <span class="badge bg-{{ $transaction->computeCurrentStatus() === 'lunas' ? 'success' : 'warning' }} text-dark">{{ ucfirst(str_replace('_', ' ', $transaction->computeCurrentStatus())) }}</span>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="card card-soft border-0 p-3">
                            <h6>Informasi Transaksi</h6>
                            <p class="mb-1"><strong>Paket:</strong> {{ $transaction->paketMaster?->nama_paket }}</p>
                            <p class="mb-1"><strong>Varian:</strong> {{ $transaction->paketMasterHarga?->nama_varian ?? 'Standar' }}</p>
                            <p class="mb-1"><strong>Tanggal Acara:</strong> {{ $transaction->tanggal_acara?->format('d M Y') }}</p>
                            <p class="mb-1"><strong>Jumlah Porsi:</strong> {{ $transaction->jumlah_porsi }}</p>
                            <p class="mb-0"><strong>Lokasi:</strong> {{ $transaction->lokasi_acara }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-soft border-0 p-3">
                            <h6>Ringkasan Keuangan</h6>
                            <p class="mb-1"><strong>Total Penawaran:</strong> Rp {{ number_format($transaction->total_penawaran, 0, ',', '.') }}</p>
                            <p class="mb-1"><strong>DP:</strong> Rp {{ number_format($transaction->nominal_dp, 0, ',', '.') }}</p>
                            <p class="mb-1"><strong>Sisa Pelunasan:</strong> Rp {{ number_format($transaction->sisa_pelunasan, 0, ',', '.') }}</p>
                            <p class="mb-0"><strong>Batas Pelunasan:</strong> {{ $transaction->batas_pelunasan?->format('d M Y') ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <div class="card card-soft border-0 p-3 mb-4">
                    <h6>Riwayat Pembayaran</h6>
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jenis</th>
                                    <th>Nominal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transaction->pembayaran as $payment)
                                    <tr>
                                        <td>{{ $payment->tanggal_bayar?->format('d M Y') ?? '-' }}</td>
                                        <td>{{ $payment->jenis_pembayaran }}</td>
                                        <td>Rp {{ number_format($payment->nominal_bayar, 0, ',', '.') }}</td>
                                        <td>{{ ucfirst($payment->status_pembayaran) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <a href="{{ route('dashboard.klien') }}" class="btn btn-secondary">Kembali ke Dashboard</a>
            </div>
        </div>
    </div>
</div>
@endsection