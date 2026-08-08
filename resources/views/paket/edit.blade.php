@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">

        <div class="card shadow-sm border-0">
            <div class="card-body">

                <h4 class="mb-4">Ubah Paket Master</h4>

                <form action="{{ route('paket.update',$package->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label>Kode Paket</label>
                        <input
                            type="text"
                            class="form-control"
                            value="{{ $package->kode_paket }}"
                            disabled>
                    </div>

                    <div class="mb-3">
                        <label>Nama Paket</label>

                        <input
                            type="text"
                            name="nama_paket"
                            class="form-control"
                            value="{{ old('nama_paket',$package->nama_paket) }}">
                    </div>

                    <div class="mb-3">
                        <label>Kategori Paket</label>

                        <input
                            type="text"
                            name="kategori_paket"
                            class="form-control"
                            value="{{ old('kategori_paket',$package->kategori_paket) }}">
                    </div>

                    <div class="mb-3">
                        <label>Deskripsi</label>

                        <textarea
                            name="deskripsi"
                            class="form-control"
                            rows="3">{{ old('deskripsi',$package->deskripsi) }}</textarea>
                    </div>

                    <div class="form-check mb-4">
                        <input
                            type="checkbox"
                            class="form-check-input"
                            id="status_aktif"
                            name="status_aktif"
                            value="1"
                            {{ $package->status_aktif ? 'checked' : '' }}>

                        <label for="status_aktif" class="form-check-label">
                            Aktif
                        </label>
                    </div>

                    <button class="btn btn-primary">
                        Simpan Perubahan
                    </button>

                    <a href="{{ route('paket.index') }}"
                        class="btn btn-secondary">
                        Kembali
                    </a>

                </form>

            </div>
        </div>

         {{-- Card Varian Harga --}}
    <div class="card shadow-sm border-0 mt-4">
        <div class="card-body">

            <h5 class="mb-3">Varian Harga Master</h5>

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>Varian</th>
                            <th>Harga</th>
                            <th>Min</th>
                            <th>Max</th>
                            <th>Keterangan</th>
                            <th width="170">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                    @forelse($package->hargaMaster as $harga)

                        <tr>
                            <td>{{ $harga->nama_varian }}</td>

                            <td>
                                Rp {{ number_format($harga->harga_dasar,0,',','.') }}
                            </td>

                            <td>{{ $harga->minimal_porsi }}</td>

                            <td>{{ $harga->maksimal_porsi }}</td>

                            <td>{{ $harga->keterangan }}</td>

                            <td>

                                <button
                                    class="btn btn-warning btn-sm"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#editVarian{{ $harga->id }}">

                                    Ubah

                                </button>

                                <form
                                    action="{{ route('paket.harga.destroy',[$package,$harga]) }}"
                                    method="POST"
                                    class="d-inline">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Hapus varian ini?')">

                                        Hapus

                                    </button>

                                </form>

                            </td>

                        </tr>

                        <tr class="collapse" id="editVarian{{ $harga->id }}">
                            <td colspan="6">

                                <div class="card border-0 bg-light">
                                    <div class="card-body">

                                        <form
                                            action="{{ route('paket.harga.update',[$package,$harga]) }}"
                                            method="POST">

                                            @csrf
                                            @method('PUT')

                                            <div class="row g-3">

                                                <div class="col-md-6">
                                                    <label class="form-label">
                                                        Nama Varian
                                                    </label>

                                                    <input
                                                        type="text"
                                                        name="nama_varian"
                                                        class="form-control"
                                                        value="{{ $harga->nama_varian }}">
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">
                                                        Harga
                                                    </label>

                                                    <input
                                                        type="number"
                                                        name="harga_dasar"
                                                        class="form-control"
                                                        value="{{ $harga->harga_dasar }}">
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">
                                                        Minimal Porsi
                                                    </label>

                                                    <input
                                                        type="number"
                                                        name="minimal_porsi"
                                                        class="form-control"
                                                        value="{{ $harga->minimal_porsi }}">
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">
                                                        Maksimal Porsi
                                                    </label>

                                                    <input
                                                        type="number"
                                                        name="maksimal_porsi"
                                                        class="form-control"
                                                        value="{{ $harga->maksimal_porsi }}">
                                                </div>

                                                <div class="col-md-12">
                                                    <label class="form-label">
                                                        Keterangan
                                                    </label>

                                                    <textarea
                                                        name="keterangan"
                                                        class="form-control"
                                                        rows="2">{{ $harga->keterangan }}</textarea>
                                                </div>

                                                <div class="col-md-12">
                                                    <button
                                                        type="submit"
                                                        class="btn btn-success">

                                                        Simpan Perubahan

                                                    </button>
                                                </div>

                                            </div>

                                        </form>

                                    </div>
                                </div>

                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="text-center">
                                Belum ada varian harga.
                            </td>
                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>

</div>
</div>

<<div class="card shadow-sm border-0 mt-4">
    <div class="card-body p-4">

        <h4 class="mb-4">Tambah Varian Harga</h4>

        <form action="{{ route('paket.harga.store',$package) }}" method="POST">
            @csrf

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Varian</label>

                    <input
                        type="text"
                        name="nama_varian"
                        class="form-control"
                        required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Harga Dasar</label>

                    <input
                        type="number"
                        name="harga_dasar"
                        class="form-control"
                        required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Minimal Porsi</label>

                    <input
                        type="number"
                        name="minimal_porsi"
                        class="form-control"
                        required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Maksimal Porsi</label>

                    <input
                        type="number"
                        name="maksimal_porsi"
                        class="form-control">
                </div>

                <div class="col-12 mb-3">
                    <label class="form-label">Keterangan</label>

                    <textarea
                        name="keterangan"
                        rows="3"
                        class="form-control"></textarea>
                </div>

                <div class="col-12">

                    <button type="submit" class="btn btn-primary">
                        Tambah Varian
                    </button>

                </div>

            </div>

        </form>

    </div>
</div>
@endsection