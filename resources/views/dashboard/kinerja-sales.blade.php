@extends('layouts.app')

@section('title', 'Kinerja Sales')

@section('content')

    @php

        $totalPenjualan = $sales->sum('total_penjualan');
        $totalTarget = $sales->sum('target');

        $persentase = $totalTarget > 0
            ? ($totalPenjualan / $totalTarget) * 100
            : 0;

        $salesTerbaik = $sales->sortByDesc('persentase')->first();

    @endphp

    <style>
        .card {
            border: none;
            transition: .35s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 18px 35px rgba(0, 0, 0, .12);
        }

        .summary-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: auto;
            font-size: 30px;
        }
    </style>

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h2 class="fw-bold mb-1">
                    <i class="bi bi-bar-chart-line-fill text-primary"></i>
                    Dashboard Kinerja Sales
                </h2>

                <p class="text-muted">
                    Monitoring pencapaian target penjualan setiap sales.
                </p>

            </div>

        </div>


        <div class="row g-4 mb-4">

            <div class="col-lg-4">

                <div class="card shadow rounded-4">

                    <div class="card-body text-center">

                        <div class="summary-icon bg-success bg-opacity-10 text-success">

                            <i class="bi bi-cash-stack"></i>

                        </div>

                        <h6 class="text-muted mt-3">

                            Total Penjualan

                        </h6>

                        <h2 class="fw-bold text-success">

                            Rp {{ number_format($totalPenjualan, 0, ',', '.') }}

                        </h2>

                    </div>

                </div>

            </div>

            <div class="col-lg-4">

                <div class="card shadow rounded-4">

                    <div class="card-body text-center">

                        <div class="summary-icon bg-primary bg-opacity-10 text-primary">

                            <i class="bi bi-bullseye"></i>

                        </div>

                        <h6 class="text-muted mt-3">

                            Total Target

                        </h6>

                        <h2 class="fw-bold text-primary">

                            Rp {{ number_format($totalTarget, 0, ',', '.') }}

                        </h2>

                    </div>

                </div>

            </div>

            <div class="col-lg-4">

                <div class="card shadow rounded-4">

                    <div class="card-body text-center">

                        <div class="summary-icon bg-warning bg-opacity-10 text-warning">

                            <i class="bi bi-graph-up-arrow"></i>

                        </div>

                        <h6 class="text-muted mt-3">

                            Rata-rata Pencapaian

                        </h6>

                        <h2 class="fw-bold text-warning">

                            {{ number_format($persentase, 1) }}%

                        </h2>

                    </div>

                </div>

            </div>

        </div>



        <div class="card shadow rounded-4 mb-4">

            <div class="card-header bg-white border-0 pt-4">

                <h5 class="fw-bold">

                    📊 Grafik Kinerja Sales

                </h5>

                <small class="text-muted">

                    Persentase pencapaian target setiap sales

                </small>

            </div>

            <div class="card-body">

                <div style="height:520px">

                    <canvas id="salesChart"></canvas>

                </div>

            </div>

        </div>



        @if($salesTerbaik)

            <div class="card shadow rounded-4">

                <div class="card-body">

                    <h4 class="fw-bold mb-3">

                        🏆 Sales Terbaik Bulan Ini

                    </h4>

                    <hr>

                    <div class="row text-center">

                        <div class="col-md-3">

                            <h6 class="text-muted">

                                Nama Sales

                            </h6>

                            <h4 class="text-primary">

                                {{ $salesTerbaik->name }}

                            </h4>

                        </div>

                        <div class="col-md-3">

                            <h6 class="text-muted">

                                Target

                            </h6>

                            <h5>

                                Rp {{ number_format($salesTerbaik->target, 0, ',', '.') }}

                            </h5>

                        </div>

                        <div class="col-md-3">

                            <h6 class="text-muted">

                                Penjualan

                            </h6>

                            <h5 class="text-success">

                                Rp {{ number_format($salesTerbaik->total_penjualan, 0, ',', '.') }}

                            </h5>

                        </div>

                        <div class="col-md-3">

                            <h6 class="text-muted">

                                Pencapaian

                            </h6>

                            <span class="badge bg-success fs-5">

                                {{ number_format($salesTerbaik->persentase, 1) }}%

                            </span>

                        </div>

                    </div>

                </div>

            </div>

        @endif

    </div>

@endsection


@push('scripts')

    <script>

        const ctx = document.getElementById('salesChart').getContext('2d');

        const gradient = ctx.createLinearGradient(0, 0, 0, 500);

        gradient.addColorStop(0, '#2563EB');
        gradient.addColorStop(.5, '#3B82F6');
        gradient.addColorStop(1, '#60A5FA');

        new Chart(ctx, {

            type: 'bar',

            data: {

                labels: [

                    @foreach($sales as $item)

                        "{{ $item->name }}",

                    @endforeach

    ],

                datasets: [{

                    label: 'Pencapaian (%)',

                    data: [

                        @foreach($sales as $item)

                            {{ round($item->persentase, 1) }},

                        @endforeach

    ],

                    backgroundColor: gradient,

                    hoverBackgroundColor: '#1D4ED8',

                    borderRadius: 18,

                    borderSkipped: false,

                    barThickness: 55

                }]

            },

            plugins: [ChartDataLabels],

            options: {

                responsive: true,

                maintainAspectRatio: false,

                animation: {
                    duration: 2000,
                    easing: 'easeOutQuart'
                },

                plugins: {

                    legend: {
                        display: false
                    },

                    tooltip: {

                        padding: 15,

                        displayColors: false,

                        callbacks: {

                            label: function (context) {

                                return " " + context.raw + " %";

                            }

                        }

                    },

                    datalabels: {

                        anchor: 'end',

                        align: 'top',

                        color: '#111827',

                        font: {

                            size: 13,

                            weight: 'bold'

                        },

                        formatter: function (value) {

                            return value + "%";

                        }

                    },

                    annotation: {

                        annotations: {

                            line1: {

                                type: 'line',

                                yMin: 100,

                                yMax: 100,

                                borderColor: '#EF4444',

                                borderWidth: 2,

                                borderDash: [8, 5],

                                label: {

                                    display: true,

                                    content: 'Target 100%',

                                    backgroundColor: '#EF4444'

                                }

                            }

                        }

                    }

                },

                scales: {

                    y: {

                        beginAtZero: true,

                        max: 100,

                        ticks: {

                            stepSize: 20,

                            callback: function (value) {

                                return value + "%";

                            }

                        }

                    },

                    x: {

                        grid: {

                            display: false

                        }

                    }

                }

            }

        });

    </script>

@endpush