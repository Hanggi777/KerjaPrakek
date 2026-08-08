@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-soft shadow-sm border-0">
                <div class="card-body p-4">
                    <h4 class="mb-3">Buat Transaksi Penjualan</h4>

                    <form method="POST" action="{{ route('transaksi.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Klien</label>
                            <select class="form-select @error('klien_id') is-invalid @enderror" name="klien_id" required>
                                <option value="">Pilih klien</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}" {{ old('klien_id') == $client->id ? 'selected' : '' }}>
                                        {{ $client->nama_klien }} - {{ $client->nama_perusahaan }}</option>
                                @endforeach
                            </select>
                            @error('klien_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Paket Master</label>
                            <select id="package-select" class="form-select @error('paket_master_id') is-invalid @enderror"
                                name="paket_master_id" required>
                                <option value="">Pilih paket</option>
                                @foreach ($packages as $package)
                                    <option value="{{ $package->id }}" {{ old('paket_master_id') == $package->id ? 'selected' : '' }}>{{ $package->nama_paket }} ({{ $package->kategori_paket }})</option>
                                @endforeach
                            </select>
                            @error('paket_master_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Varian Harga Master (opsional)</label>
                            <select id="variant-select" name="paket_master_harga_id"
                                data-selected="{{ old('paket_master_harga_id') }}"
                                class="form-select @error('paket_master_harga_id') is-invalid @enderror">
                                <option value="">Pilih varian harga</option>
                                @foreach ($variants as $variant)
                                    <option value="{{ $variant->id }}" data-paket="{{ $variant->paket_master_id }}"
                                        data-harga="{{ $variant->harga_dasar }}">
                                        {{ $variant->paketMaster?->nama_paket }} - {{ $variant->nama_varian }} (Rp
                                        {{ number_format($variant->harga_dasar, 0, ',', '.') }})
                                    </option>
                                @endforeach
                            </select>
                            @error('paket_master_harga_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="form-text">Pilih varian harga untuk memudahkan penawaran, lalu sesuaikan Harga
                                Penawaran jika perlu.</div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Acara</label>
                                <input type="date" name="tanggal_acara"
                                    class="form-control @error('tanggal_acara') is-invalid @enderror"
                                    value="{{ old('tanggal_acara') }}" required>
                                @error('tanggal_acara') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Jumlah Porsi</label>
                                <input type="number" name="jumlah_porsi"
                                    class="form-control @error('jumlah_porsi') is-invalid @enderror"
                                    value="{{ old('jumlah_porsi', 1) }}" min="1" required>
                                @error('jumlah_porsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-3 mt-3">
                            <label class="form-label">Lokasi Acara</label>
                            <input type="text" name="lokasi_acara"
                                class="form-control @error('lokasi_acara') is-invalid @enderror"
                                value="{{ old('lokasi_acara') }}" required>
                            @error('lokasi_acara') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        @if (!empty($sales))
                            <div class="mb-3">
                                <label class="form-label">Sales</label>
                                <select name="sales_id" class="form-select @error('sales_id') is-invalid @enderror" required>
                                    <option value="">Pilih sales</option>
                                    @foreach ($sales as $salesMember)
                                        <option value="{{ $salesMember->id }}" {{ old('sales_id') == $salesMember->id ? 'selected' : '' }}>{{ $salesMember->name }}</option>
                                    @endforeach
                                </select>
                                @error('sales_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label">Target Penjualan (opsional)</label>
                            @if ($targets->isEmpty())
                                <div class="alert alert-warning py-2">Tidak ada target penjualan aktif untuk bulan ini. Anda
                                    dapat menyimpan transaksi tanpa target.</div>
                            @endif
                            <select name="target_penjualan_id"
                                class="form-select @error('target_penjualan_id') is-invalid @enderror">
                                <option value="">Tanpa target</option>
                                @foreach ($targets as $target)
                                    <option value="{{ $target->id }}" {{ old('target_penjualan_id') == $target->id ? 'selected' : '' }}>
                                        {{ $target->sales?->name ?? 'Sales belum ada' }} -
                                        {{ $target->bulan }}/{{ $target->tahun }} (Rp
                                        {{ number_format($target->target_nominal, 0, ',', '.') }})
                                    </option>
                                @endforeach
                            </select>
                            @error('target_penjualan_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Harga Penawaran</label>
                            <input id="harga-penawaran" type="number" name="harga_penawaran"
                                class="form-control @error('harga_penawaran') is-invalid @enderror"
                                value="{{ old('harga_penawaran') }}" min="0" required>
                            @error('harga_penawaran') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="form-text">Harga awal otomatis dihitung dari varian harga master × jumlah porsi. Sales dapat mengubah nilai ini.</div>
                            <div id="harga-acuan-info" class="form-text text-muted"></div>
                        </div>

                        <div class="alert alert-info py-2 mb-4">
                            <strong>Acuan harga:</strong> pilih varian harga master untuk melihat harga dasar per pax, lalu sistem menghitung total penawaran otomatis berdasarkan jumlah porsi.
                        </div>
                        <hr class="my-4">
                        <div class="mb-3">
                            <label class="form-label">Catatan</label>
                            <textarea name="catatan" class="form-control @error('catatan') is-invalid @enderror"
                                rows="3">{{ old('catatan') }}</textarea>
                            @error('catatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
                        <a href="{{ route('transaksi.index') }}" class="btn btn-secondary ms-2">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const variantSelect = document.getElementById('variant-select');
    const porsiInput = document.querySelector('input[name="jumlah_porsi"]');
    const hargaInput = document.getElementById('harga-penawaran');
    const infoBox = document.getElementById('harga-acuan-info');

    let isManualEdit = Boolean(hargaInput?.value && Number(hargaInput.value) > 0);

    const formatCurrency = (value) => new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(value);

    function updateHargaPenawaran() {
        if (isManualEdit) {
            return;
        }

        const selectedOption = variantSelect?.selectedOptions[0];
        const hargaDasar = Number(selectedOption?.dataset.harga || 0);
        const porsi = Number(porsiInput?.value || 1);
        const total = hargaDasar > 0 ? hargaDasar * porsi : 0;

        if (hargaInput) {
            hargaInput.value = total;
        }

        if (infoBox) {
            if (hargaDasar > 0) {
                infoBox.innerHTML = `Acuan varian: ${formatCurrency(hargaDasar)} per pax × ${porsi} porsi = <strong>${formatCurrency(total)}</strong>`;
            } else {
                infoBox.textContent = 'Pilih varian harga master untuk melihat acuan harga otomatis.';
            }
        }
    }

    variantSelect?.addEventListener('change', function () {
        isManualEdit = false;
        updateHargaPenawaran();
    });

    porsiInput?.addEventListener('input', function () {
        updateHargaPenawaran();
    });

    hargaInput?.addEventListener('input', function () {
        isManualEdit = true;
    });

    updateHargaPenawaran();
});
</script>
@endpush