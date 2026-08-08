@extends('layouts.app')

@section('content')
<div class="row gy-4">
    <div class="col-12">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1">Paket Master</h4>
                <p class="text-muted mb-0">
                    Kelola paket catering master beserta varian harga.
                </p>
            </div>

            <a href="{{ route('paket.create') }}" class="btn btn-primary">
                Tambah Paket
            </a>
        </div>

        {{-- Alert Success --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Alert Error --}}
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="card border-0 shadow-sm">
            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead class="table-light">
                            <tr>
                                <th width="120">Kode</th>
                                <th>Nama Paket</th>
                                <th>Kategori</th>
                                <th>Status</th>
                                <th>Varian Harga</th>
                                <th width="180">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>

                        @forelse($packages as $package)

                            <tr>

                                <td>
                                    {{ $package->kode_paket }}
                                </td>

                                <td>
                                    {{ $package->nama_paket }}
                                </td>

                                <td>
                                    {{ $package->kategori_paket }}
                                </td>

                                <td>

                                    @if($package->status_aktif)

                                        <span class="badge bg-success">
                                            Aktif
                                        </span>

                                    @else

                                        <span class="badge bg-secondary">
                                            Nonaktif
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    @forelse($package->hargaMaster as $harga)

                                        <div class="mb-2">

                                            <strong>
                                                {{ $harga->nama_varian }}
                                            </strong>

                                            <br>

                                            <small class="text-muted">

                                                Rp {{ number_format($harga->harga_dasar,0,',','.') }}
                                                / porsi

                                            </small>

                                        </div>

                                    @empty

                                        <span class="text-muted">
                                            Belum ada varian
                                        </span>

                                    @endforelse

                                </td>

                                <td>

                                    <div class="d-flex gap-2">

                                        <a href="{{ route('paket.edit',$package) }}"
                                           class="btn btn-sm btn-outline-primary">

                                            Ubah

                                        </a>

                                        <form action="{{ route('paket.destroy',$package) }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin ingin menghapus paket ini?')">

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-outline-danger">

                                                Hapus

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6" class="text-center py-4">

                                    Belum ada data paket.

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
@endsection