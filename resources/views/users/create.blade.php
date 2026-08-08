@extends('layouts.app')

@section('title','Tambah Akun')

@section('content')

<div class="card">

<div class="card-header">

<h3>Tambah Akun</h3>

</div>

<div class="card-body">

<form action="{{ route('users.store') }}" method="POST">

@csrf

<div class="mb-3">

<label>Nama</label>

<input type="text"
name="name"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Email</label>

<input type="email"
name="email"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Password</label>

<input type="password"
name="password"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Role</label>

<select name="role"
class="form-select">

<option value="sales">Sales</option>

<option value="pemilik">Pemilik</option>

</select>

</div>

<button class="btn btn-success">

Simpan

</button>

<a href="{{ route('users.index') }}"
class="btn btn-secondary">

Kembali

</a>

</form>

</div>

</div>

@endsection