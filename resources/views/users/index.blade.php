@extends('layouts.app')

@section('title','Kelola Data Sales & Pemilik')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h1>
            <i class="bi bi-people-fill"></i>
            Kelola Data Sales & Pemilik
        </h1>

        <p class="text-muted">
            Daftar akun Sales dan Pemilik.
        </p>

    </div>

    <a href="{{ route('users.create') }}" class="btn btn-success">

        <i class="bi bi-plus-circle"></i>

        Tambah Akun

    </a>

</div>

@if(session('success'))

<div class="alert alert-success">

    {{ session('success') }}

</div>

@endif

<div class="card shadow-sm">

<div class="card-body">

<table class="table table-bordered table-hover align-middle">

<thead class="table-light">

<tr>

<th>No</th>
<th>Nama</th>
<th>Email</th>
<th>Role</th>
<th width="170">Aksi</th>

</tr>

</thead>

<tbody>

@forelse($users as $user)

<tr>

<td>{{ $loop->iteration }}</td>

<td>{{ $user->name }}</td>

<td>{{ $user->email }}</td>

<td>

@if($user->role=='sales')

<span class="badge bg-primary">
Sales
</span>

@else

<span class="badge bg-success">
Pemilik
</span>

@endif

</td>

<td>

<a href="{{ route('users.edit',$user->id) }}"
class="btn btn-warning btn-sm">

<i class="bi bi-pencil-square"></i>

</a>

<form action="{{ route('users.destroy',$user->id) }}"
method="POST"
class="d-inline">

@csrf
@method('DELETE')

<button
class="btn btn-danger btn-sm"
onclick="return confirm('Hapus akun ini?')">

<i class="bi bi-trash"></i>

</button>

</form>

</td>

</tr>

@empty

<tr>

<td colspan="5" class="text-center">

Belum ada data.

</td>

</tr>

@endforelse

</tbody>

</table>

</div>

</div>

@endsection