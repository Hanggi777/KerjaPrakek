@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card card-soft shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <img src="{{ asset('assets/LOGO.png') }}" alt="Logo" style="max-height: 90px; width: auto;" class="img-fluid">
                    </div>
                    <div class="mb-4 text-center">
                        <h4 class="fw-semibold">Login Portal Klien</h4>
                        <p class="text-muted">Masuk untuk melihat transaksi dan status pembayaran Anda.</p>
                    </div>

                    <form method="POST" action="{{ route('client.login.post') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Klien</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" required autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-success w-100">Masuk sebagai Klien</button>
                        <div class="text-center mt-3">
                            <small class="text-muted">
                                Lupa password? Silakan hubungi admin.
                            </small>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection