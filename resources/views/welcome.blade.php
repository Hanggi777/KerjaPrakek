<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'NABILLA CATERING') }}</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-IQsoLXlqLhBTd6gYTOa0xj7fLh510bx5r6w7ypgE9cya1pQ+3VVVYUefy8Zj0SmF" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" integrity="sha512-YzmO5hQFh5A1L+UO+QXyBKG1D58D6ZGgEg9SZZZ7jrKEw70L0Zq+Gn4h7NvrVTCb1XAU1rXYqk4Z6E4b5ed5I0A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <style>
            body { background: #eef2ff; color: #0f172a; }
            .hero { min-height: 100vh; display: flex; align-items: center; }
            .hero-card { border: none; border-radius: 1rem; box-shadow: 0 28px 70px rgba(15, 23, 42, 0.1); }
            .hero-card .btn-portal { min-width: 200px; }
        </style>
    </head>
    <body>
        <div class="container py-5 hero">
            <div class="row justify-content-center w-100">
                <div class="col-xl-9 col-lg-10">
                    <div class="card hero-card overflow-hidden">
                        <div class="row g-0">
                            <div class="col-lg-6 p-5">
                                <h1 class="display-5 fw-bold mb-3">NABILLA CATERING</h1>
                                <p class="text-muted fs-5 mb-4">Sistem Monitoring Sales Performance Berbasis Web untuk pengelolaan paket, transaksi penjualan, DP/pelunasan, dan portal klien.</p>

                                <div class="d-grid gap-3 mb-4">
                                    <a href="{{ route('login') }}" class="btn btn-primary btn-portal py-3">
                                        <i class="bi bi-box-arrow-in-right me-2"></i> Login Internal
                                    </a>
                                    <a href="{{ route('client.login') }}" class="btn btn-outline-primary btn-portal py-3">
                                        <i class="bi bi-person-lines-fill me-2"></i> Login Klien
                                    </a>
                                </div>

                                <div class="bg-light rounded-4 p-4">
                                    <h6 class="mb-3">Akun Demo</h6>
                                    <ul class="list-unstyled mb-0">
                                        <li><strong>Superadmin:</strong> superadmin@example.com / password</li>
                                        <li><strong>Pemilik:</strong> pemilik@example.com / password</li>
                                        <li><strong>Sales:</strong> sales@example.com / password</li>
                                        <li><strong>Klien:</strong> klien@example.com / password</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-6 d-none d-lg-flex align-items-center justify-content-center bg-primary text-white p-5">
                                <div class="text-center">
                                    <div class="mb-4">
                                        <i class="bi bi-graph-up-arrow display-3"></i>
                                    </div>
                                    <h2 class="fw-semibold">Pantau performa sales, kelola paket, dan ingatkan pelunasan H-30.</h2>
                                    <p class="text-white-75 mt-3">Akses internal dan portal klien dalam satu sistem yang mudah digunakan.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYf5Y0nENbYmgG2sAQQk7lqALNTy7O4lsn1zYd3eVld7+Hi1DNyK4Za+P" crossorigin="anonymous"></script>
    </body>
</html>
