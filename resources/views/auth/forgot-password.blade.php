@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card card-soft shadow-sm border-0">
            <div class="card-body p-4">
                <div class="mb-4 text-center">
                    <h4 class="fw-semibold">{{ $title ?? 'Lupa Password' }}</h4>
                    <p class="text-muted">Masukkan email untuk menerima instruksi reset password.</p>
                </div>

                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                <form method="POST" action="{{ $action }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100">{{ $buttonLabel }}</button>
                    <a href="{{ $backRoute }}" class="btn btn-link w-100 mt-2">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
