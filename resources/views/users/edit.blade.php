@extends('layouts.app')

@section('title','Edit Akun')

@section('content')

<div class="card">

<div class="card-header">

<h3>Edit Akun</h3>

</div>

<div class="card-body">

<form action="{{ route('users.update',$user->id) }}"
method="POST">

@csrf
@method('PUT')

<div class="mb-3">

<label>Nama</label>

<input
type="text"
name="name"
value="{{ $user->name }}"
class="form-control">

</div>

<div class="mb-3">

<label>Email</label>

<input
type="email"
name="email"
value="{{ $user->email }}"
class="form-control">

</div>

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

<div class="mb-3">

<label>Role</label>

<select
name="role"
class="form-select">

<option value="sales"
{{ $user->role=='sales' ? 'selected':'' }}>

Sales

</option>

<option value="pemilik"
{{ $user->role=='pemilik' ? 'selected':'' }}>

Pemilik

</option>

</select>

</div>

<button class="btn btn-success">

Update

</button>

<a href="{{ route('users.index') }}"
class="btn btn-secondary">

Kembali

</a>

</form>

</div>

</div>

@endsection