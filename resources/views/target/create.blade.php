@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-soft shadow-sm border-0">
            <div class="card-body p-4">
                <h4 class="mb-3">Tambah Target Penjualan</h4>

                <form method="POST" action="{{ route('target.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Sales</label>
                        <select name="sales_id" class="form-select @error('sales_id') is-invalid @enderror" required>
                            <option value="">Pilih sales</option>
                            @foreach ($sales as $item)
                                <option value="{{ $item->id }}" {{ old('sales_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error('sales_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Bulan</label>
                            <input type="number" name="bulan" class="form-control @error('bulan') is-invalid @enderror" value="{{ old('bulan', now()->month) }}" min="1" max="12" required>
                            @error('bulan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tahun</label>
                            <input type="number" name="tahun" class="form-control @error('tahun') is-invalid @enderror" value="{{ old('tahun', now()->year) }}" min="2024" required>
                            @error('tahun') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3 mt-3">
                        <label class="form-label">Target Nominal</label>
                        <input type="number" name="target_nominal" class="form-control @error('target_nominal') is-invalid @enderror" value="{{ old('target_nominal') }}" min="0" required>
                        @error('target_nominal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan Target</button>
                    <a href="{{ route('target.index') }}" class="btn btn-secondary ms-2">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
