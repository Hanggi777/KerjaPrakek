@extends('layouts.app')

@section('title', 'Edit Klien')

@section('content')

<div class="content-header mb-4">
    <h2><i class="bi bi-pencil-square"></i> Edit Data Klien</h2>
    <p class="text-muted">Perbarui informasi data klien</p>
</div>

@if ($errors->any())
<div class="alert alert-danger">
    <strong>Terjadi Kesalahan!</strong>
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="row">

    <div class="col-lg-8">

        <div class="card">
            <div class="card-header">
                <i class="bi bi-person-lines-fill"></i>
                Form Edit Klien
            </div>

            <div class="card-body">

                <form action="{{ route('klien.update',$klien) }}" method="POST">

                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">
                            Nama Klien
                        </label>

                        <input
                            type="text"
                            name="nama_klien"
                            class="form-control"
                            value="{{ old('nama_klien',$klien->nama_klien) }}"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            value="{{ old('email',$klien->email) }}"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Nomor HP
                        </label>

                        <input
                            type="text"
                            name="telepon"
                            class="form-control"
                            value="{{ old('telepon',$klien->no_hp) }}"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Alamat
                        </label>

                        <textarea
                            name="alamat"
                            class="form-control"
                            rows="3"
                            required>{{ old('alamat',$klien->alamat) }}</textarea>
                    </div>

                    @if (Auth::user()?->isSuperadmin())
                        <div class="mb-3">
                            <label class="form-label">Sales Pemilik</label>
                            <select name="sales_id" class="form-select">
                                <option value="">Belum ditetapkan</option>
                                @foreach ($salesList as $sales)
                                    <option value="{{ $sales->id }}" {{ old('sales_id', $klien->sales_id) == $sales->id ? 'selected' : '' }}>
                                        {{ $sales->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Superadmin dapat mengubah sales pemilik klien ini.</small>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>

                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            autocomplete="new-password">

                        <small class="text-muted">
                            Kosongkan jika tidak ingin mengubah password.
                        </small>
                    </div>

                    <div class="d-flex gap-2">

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i>
                            Simpan Perubahan
                        </button>

                        <a href="{{ route('klien.show',$klien) }}"
                            class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i>
                            Kembali
                        </a>

                    </div>

                </form>

            </div>

        </div>

    </div>

    <div class="col-lg-4">

        <div class="card bg-light">

            <div class="card-body">

                <h5>
                    <i class="bi bi-info-circle"></i>
                    Informasi
                </h5>

                <hr>

                <p><strong>Total Transaksi</strong></p>
                <p>{{ $klien->transaksi()->count() }}</p>

                <p><strong>Status Akun</strong></p>

                @if($klien->status_aktif)
                    <span class="badge bg-success">
                        Aktif
                    </span>
                @else
                    <span class="badge bg-danger">
                        Nonaktif
                    </span>
                @endif

                <hr>

                <small class="text-muted">
                    Password hanya akan berubah apabila kolom password diisi.
                </small>

            </div>

        </div>

    </div>

</div>

@endsection