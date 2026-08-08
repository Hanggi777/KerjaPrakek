@extends('layouts.app')

@section('title', 'Tambah Klien Baru')

@section('content')
<div class="content-header">
    <h1><i class="bi bi-person-plus"></i> Tambah Klien Baru</h1>
    <p>Daftar klien baru ke portal catering</p>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Validation Error!</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-form-check"></i> Form Input Data Klien
            </div>
            <div class="card-body">
                <form action="{{ route('klien.store') }}" method="POST">
                    @csrf

                    <div class="form-group mb-3">
                        <label class="form-label">Nama Klien <span style="color: red;">*</span></label>
                        <input type="text" name="nama_klien" class="form-control @error('nama_klien') is-invalid @enderror" 
                               placeholder="Masukkan nama klien" value="{{ old('nama_klien') }}" required>
                        @error('nama_klien')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Email <span style="color: red;">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                               placeholder="email@example.com" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Telepon <span style="color: red;">*</span></label>
                        <input type="text" name="telepon" class="form-control @error('telepon') is-invalid @enderror" 
                               placeholder="08xxxxxxxxxx" value="{{ old('telepon') }}" required>
                        @error('telepon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Alamat <span style="color: red;">*</span></label>
                        <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" 
                                  rows="3" placeholder="Alamat lengkap klien" required>{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Password <span style="color: red;">*</span></label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                               placeholder="Minimal 6 karakter" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Konfirmasi Password <span style="color: red;">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" 
                               placeholder="Ulangi password" required>
                    </div>

                    <div style="display: flex; gap: 10px;">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle"></i> Simpan
                        </button>
                        <a href="{{ route('klien.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-light">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-info-circle"></i> Informasi
                </h5>
                <p>Data klien yang dibuat akan mendapat akses ke portal klien untuk:</p>
                <ul style="font-size: 13px;">
                    <li>Melihat transaksi mereka</li>
                    <li>Melacak status pembayaran</li>
                    <li>Menerima notifikasi H-30</li>
                    <li>Melihat detail paket & pricing</li>
                </ul>
                <hr>
                <p style="font-size: 12px; color: #666;">
                    Password akan di-hash dan tidak bisa dilihat lagi. Bagikan password ke klien dengan aman.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
