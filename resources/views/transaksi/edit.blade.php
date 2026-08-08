@extends('layouts.app')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Edit Transaksi</h4>

        <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">
            Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <form action="{{ route('transaksi.update', $transaction->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Hidden supaya tetap terkirim --}}
                <input type="hidden" name="klien_id" value="{{ $transaction->klien_id }}">
                <input type="hidden" name="paket_master_id" value="{{ $transaction->paket_master_id }}">

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label>Klien</label>
                        <select class="form-select" disabled>
                            <option>{{ $transaction->klien->nama_klien }}</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Paket</label>
                        <select class="form-select" disabled>
                            <option>{{ $transaction->paketMaster->nama_paket }}</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Tanggal Acara</label>
                        <input
                            type="date"
                            name="tanggal_acara"
                            class="form-control"
                            value="{{ old('tanggal_acara', \Carbon\Carbon::parse($transaction->tanggal_acara)->format('Y-m-d')) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Varian Harga Master</label>
                        <select id="variant-select" name="paket_master_harga_id" class="form-select">
                            <option value="">Pilih varian harga</option>
                            @foreach ($variants as $variant)
                                @if ($variant->paket_master_id == $transaction->paket_master_id)
                                    <option value="{{ $variant->id }}"
                                        data-harga="{{ $variant->harga_dasar }}"
                                        {{ old('paket_master_harga_id', $transaction->paket_master_harga_id) == $variant->id ? 'selected' : '' }}>
                                        {{ $variant->nama_varian }} (Rp {{ number_format($variant->harga_dasar, 0, ',', '.') }})
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Jumlah Porsi</label>
                        <input
                            type="number"
                            name="jumlah_porsi"
                            id="jumlah-porsi"
                            class="form-control"
                            value="{{ old('jumlah_porsi', $transaction->jumlah_porsi) }}"
                            min="1">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label>Harga Penawaran</label>
                        <input
                            type="number"
                            name="harga_penawaran"
                            id="harga-penawaran"
                            class="form-control"
                            value="{{ old('harga_penawaran', $transaction->total_penawaran) }}"
                            min="0"
                            step="0.01">
                        <small class="text-muted">Harga otomatis dihitung dari varian harga master × jumlah porsi. Anda tetap bisa mengubah nilainya.</small>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label>Lokasi Acara</label>
                        <input
                            type="text"
                            name="lokasi_acara"
                            class="form-control"
                            value="{{ old('lokasi_acara', $transaction->lokasi_acara) }}">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label>Catatan</label>
                        <textarea
                            name="catatan"
                            rows="3"
                            class="form-control">{{ old('catatan', $transaction->catatan) }}</textarea>
                    </div>

                </div>

                <button type="submit" class="btn btn-primary">
                    Simpan Perubahan
                </button>

            </form>

        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const variantSelect = document.getElementById('variant-select');
    const porsiInput = document.getElementById('jumlah-porsi');
    const hargaInput = document.getElementById('harga-penawaran');

    if (!porsiInput || !hargaInput) {
        return;
    }

    let isManualEdit = false;

    const updateHarga = () => {
        if (isManualEdit) {
            return;
        }

        const selectedOption = variantSelect?.selectedOptions[0];
        const hargaDasar = Number(selectedOption?.dataset.harga || 0);
        const porsi = Math.max(1, Number(porsiInput.value || 1));
        const total = hargaDasar > 0 ? hargaDasar * porsi : 0;
        hargaInput.value = total;
    };

    if (variantSelect && !variantSelect.value && variantSelect.options.length > 1) {
        variantSelect.selectedIndex = 1;
    }

    variantSelect?.addEventListener('change', function () {
        isManualEdit = false;
        updateHarga();
    });

    porsiInput.addEventListener('input', updateHarga);
    hargaInput.addEventListener('input', function () {
        isManualEdit = true;
    });

    updateHarga();
});
</script>
@endpush