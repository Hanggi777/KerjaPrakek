@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-soft shadow-sm border-0">
            <div class="card-body p-4">
                <h4 class="mb-3">Tambah Paket Master</h4>

                <form method="POST" action="{{ route('paket.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Kode Paket</label>
                        <input type="text" name="kode_paket" class="form-control @error('kode_paket') is-invalid @enderror" value="{{ old('kode_paket') }}" required>
                        @error('kode_paket') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Paket</label>
                        <input type="text" name="nama_paket" class="form-control @error('nama_paket') is-invalid @enderror" value="{{ old('nama_paket') }}" required>
                        @error('nama_paket') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kategori Paket</label>
                        <input type="text" name="kategori_paket" class="form-control @error('kategori_paket') is-invalid @enderror" value="{{ old('kategori_paket') }}" required>
                        @error('kategori_paket') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="3">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Harga Dasar</label>
                            <input type="number" name="harga" class="form-control @error('harga') is-invalid @enderror" value="{{ old('harga') }}" min="0">
                            @error('harga') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Varian</label>
                            <input type="text" name="nama_varian" class="form-control @error('nama_varian') is-invalid @enderror" value="{{ old('nama_varian') }}">
                            @error('nama_varian') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row g-3 mt-3">
                        <div class="col-md-6">
                            <label class="form-label">Minimal Porsi</label>
                            <input type="number" name="minimal_porsi" class="form-control" value="{{ old('minimal_porsi', 0) }}" min="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Maksimal Porsi</label>
                            <input type="number" name="maksimal_porsi" class="form-control" value="{{ old('maksimal_porsi') }}" min="0">
                        </div>
                    </div>

                    <div class="mb-3 mt-3">
                        <label class="form-label">Keterangan Varian</label>
                        <textarea name="keterangan" class="form-control" rows="2">{{ old('keterangan') }}</textarea>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="status_aktif" id="status_aktif" value="1" checked>
                        <label class="form-check-label" for="status_aktif">Aktifkan Paket</label>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan Paket</button>
                    <a href="{{ route('paket.index') }}" class="btn btn-secondary ms-2">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
