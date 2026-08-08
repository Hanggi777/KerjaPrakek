@extends('layouts.app')

@section('title', 'Kelola Data Klien')

@section('content')
<div class="content-header d-flex justify-content-between align-items-center">
    <div>
        <h1><i class="bi bi-people"></i> Kelola Data Klien</h1>
        <p>Kelola semua data klien portal</p>
    </div>

    <a href="{{ route('klien.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Klien
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Klien</th>
                        <th>Sales</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th class="text-center">Transaksi</th>
                        <th class="text-center">Tanggal Daftar</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($klien as $k)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>
                                <strong>{{ $k->nama_klien }}</strong>
                            </td>

                            <td>
                                {{ $k->sales ? $k->sales->name : '-' }}
                            </td>

                            <td>{{ $k->email }}</td>

                            <td>{{ $k->no_hp ?? '-' }}</td>

                            <td class="text-center">
                                <span class="badge bg-info">
                                    {{ $k->transaksi_count }}
                                </span>
                            </td>

                            <td class="text-center">
                                {{ $k->created_at->format('d/m/Y') }}
                            </td>

                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('klien.show', $k) }}"
                                       class="btn btn-info btn-sm"
                                       title="Lihat">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <a href="{{ route('klien.edit', $k) }}"
                                       class="btn btn-warning btn-sm"
                                       title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <form action="{{ route('klien.destroy', $k) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin hapus klien ini?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-danger btn-sm"
                                                title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="bi bi-inbox fs-1 text-secondary"></i>
                                <br>
                                <span class="text-muted">
                                    Belum ada data klien
                                </span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $klien->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection