<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KlienController;
use App\Http\Controllers\PaketMasterController;
use App\Http\Controllers\TransaksiPenjualanController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\TargetPenjualanController;
use App\Http\Controllers\UserController;

// Halaman welcome
Route::get('/', function () {
    if (auth('klien')->check()) {
        return redirect()->route('dashboard.klien');
    }
    if (auth()->check()) {
        return redirect()->route('dashboard.internal');
    }
    return redirect()->route('login');
});

// ===== INTERNAL LOGIN =====
Route::middleware(['guest', 'guest:klien'])->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'doLogin'])->name('login.post');
    Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'handleForgotPassword'])->name('password.email');
});

// ===== PORTAL KLIEN LOGIN =====
Route::middleware(['guest', 'guest:klien'])->group(function () {
    Route::get('/client/login', [AuthController::class, 'loginClient'])->name('client.login');

    Route::post('/client/login', [AuthController::class, 'doLoginClient'])->name('client.login.post');
    Route::get('/client/forgot-password', [AuthController::class, 'forgotPassword'])
        ->name('client.password.request');

    Route::post('/client/forgot-password', [AuthController::class, 'handleForgotPassword'])
        ->name('client.password.email');

});

// ===== INTERNAL DASHBOARD & LOGOUT =====
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard Internal
    Route::get('/dashboard', [DashboardController::class, 'internal'])->name('dashboard.internal');
    Route::get('/dashboard/superadmin', [DashboardController::class, 'superadminDashboard'])->name('dashboard.superadmin')->middleware('check.role:superadmin');
    Route::get('/dashboard/pemilik', [DashboardController::class, 'pemilikDashboard'])->name('dashboard.pemilik')->middleware('check.role:pemilik');
    Route::get('/dashboard/sales', [DashboardController::class, 'salesDashboard'])->name('dashboard.sales')->middleware('check.role:sales');

    // ===========================
    // Monitoring Dashboard
    // ===========================

    Route::get('/dashboard/progress-target', [DashboardController::class, 'progressTarget'])
        ->name('dashboard.progressTarget');

    Route::get('/dashboard/omzet', [DashboardController::class, 'omzet'])
        ->name('dashboard.omzet');

    Route::get('/dashboard/kinerja-sales', [DashboardController::class, 'kinerjaSales'])
        ->name('dashboard.kinerja-sales');

    // Dashboard Sales Detail
    Route::get('/dashboard/sales/progress-target', [DashboardController::class, 'progressTargetSales'])
        ->name('dashboard.sales.progress')
        ->middleware('check.role:sales');

    Route::get('/dashboard/sales/omzet', [DashboardController::class, 'omzetSales'])
        ->name('dashboard.sales.omzet')
        ->middleware('check.role:sales');
        
    Route::get('/dashboard/kinerja-sales', [DashboardController::class, 'kinerjaSales'])
    ->name('dashboard.kinerja-sales');
    
    Route::get('/dashboard/sales/kinerja', [DashboardController::class, 'kinerjaSales'])
        ->name('dashboard.sales.kinerja')
        ->middleware('check.role:sales');

    Route::get('/transaksi/{transaksi}/pdf',[TransaksiPenjualanController::class, 'exportPdf'])
        ->name('transaksi.pdf');

        Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard/sales/komisi', [DashboardController::class, 'komisiSales'])
        ->name('dashboard.sales.komisi');


    Route::get('/dashboard/komisi-sales', [DashboardController::class, 'komisiSalesAdmin'])
        ->name('dashboard.komisi.admin');

        Route::get('/dashboard/pemilik/komisi-sales',
    [DashboardController::class,'komisiSalesAdmin'])
    ->name('dashboard.pemilik.komisi');

});

    // ===== user MANAGEMENT =====
    Route::resource('users', UserController::class)
    ->middleware('check.role:superadmin');


    // ===== KLIEN MANAGEMENT =====
    Route::resource('klien', KlienController::class)
        ->middleware('check.role:sales,pemilik,superadmin');


    // ===== PAKET MASTER MANAGEMENT =====
    Route::resource('paket', PaketMasterController::class)
        ->middleware('check.role:pemilik,superadmin');

    // Paket harga management
    Route::post('/paket/{paket}/harga', [PaketMasterController::class, 'storeHarga'])
        ->name('paket.harga.store')
        ->middleware('check.role:pemilik,superadmin');
    Route::put('/paket/{paket}/harga/{harga}', [PaketMasterController::class, 'updateHarga'])
        ->name('paket.harga.update')
        ->middleware('check.role:pemilik,superadmin');
    Route::delete('/paket/{paket}/harga/{harga}', [PaketMasterController::class, 'deleteVariant'])
        ->name('paket.harga.destroy')
        ->middleware('check.role:pemilik,superadmin');
    
    // ===== TRANSAKSI PENJUALAN MANAGEMENT =====
    Route::resource('transaksi', TransaksiPenjualanController::class)
        ->middleware('check.role:sales,pemilik,superadmin')
        ->except(['destroy']);

    Route::post('/transaksi/{transaksi}/cancel', [TransaksiPenjualanController::class, 'cancel'])
        ->name('transaksi.cancel')
        ->middleware('check.role:sales,pemilik,superadmin');

        

    // ===== PEMBAYARAN MANAGEMENT =====
    Route::get('/pembayaran', [PembayaranController::class, 'index'])
        ->name('pembayaran.index')
        ->middleware('check.role:sales,pemilik,superadmin');

    Route::get('/transaksi/{transaksi}/pembayaran/create', [PembayaranController::class, 'create'])
        ->name('pembayaran.create')
        ->middleware('check.role:sales,pemilik,superadmin');

    Route::post('/transaksi/{transaksi}/pembayaran', [PembayaranController::class, 'store'])
        ->name('pembayaran.store')
        ->middleware('check.role:sales,pemilik,superadmin');

    Route::get('/pembayaran/{pembayaran}', [PembayaranController::class, 'show'])
        ->name('pembayaran.show')
        ->middleware('check.role:sales,pemilik,superadmin');

    Route::get('/pembayaran/{pembayaran}/edit', [PembayaranController::class, 'edit'])
        ->name('pembayaran.edit')
        ->middleware('check.role:sales,pemilik,superadmin');

    Route::put('/pembayaran/{pembayaran}', [PembayaranController::class, 'update'])
        ->name('pembayaran.update')
        ->middleware('check.role:sales,pemilik,superadmin');

    Route::delete('/pembayaran/{pembayaran}', [PembayaranController::class, 'destroy'])
    ->name('pembayaran.destroy')
    ->middleware('check.role:sales,pemilik,superadmin');

    Route::post(
        '/pembayaran/{pembayaran}/konfirmasi',
        [PembayaranController::class,'konfirmasi']
        )->name('pembayaran.konfirmasi');

    Route::get('/pembayaran/{id}/pdf', [PembayaranController::class, 'pdf'])
    ->name('pembayaran.pdf');

    // ===== TARGET PENJUALAN MANAGEMENT =====
    Route::resource('target', TargetPenjualanController::class)
        ->middleware('check.role:pemilik,superadmin');

    Route::get('/target/bulk/create', [TargetPenjualanController::class, 'bulkCreate'])
        ->name('target.bulk.create')
        ->middleware('check.role:pemilik,superadmin');

    Route::post('/target/bulk', [TargetPenjualanController::class, 'bulkStore'])
        ->name('target.bulk.store')
        ->middleware('check.role:pemilik,superadmin');
});

// ===== PORTAL KLIEN =====
Route::middleware('auth:klien')->group(function () {

    Route::post('/client/logout', [AuthController::class,'logoutClient'])
        ->name('client.logout');

    Route::get('/dashboard/klien',
        [DashboardController::class,'klienDashboard'])
        ->name('dashboard.klien');

    // Detail transaksi
    Route::get('/dashboard/klien/transaksi/{transaksi}',
        [DashboardController::class,'detailTransaksi'])
        ->name('klien.transaksi.detail');

    // Detail pembayaran
    Route::get(
    '/dashboard/klien/pembayaran/{id}',
    [DashboardController::class, 'detailPembayaran']
    )->name('klien.pembayaran.show');

    Route::get(
    '/dashboard/klien/transaksi/{id}/bayar',
    [DashboardController::class,'pilihMetode']
    )->name('klien.transaksi.bayar');

    Route::post(
    '/dashboard/klien/transaksi/{id}/proses-pembayaran',
    [DashboardController::class, 'prosesPembayaran']
    )->name('klien.pembayaran.proses');


    Route::get(
        '/klien/pembayaran/{id}/cash',
        [DashboardController::class, 'cash']
    )->name('klien.pembayaran.cash');

    Route::get(
    '/pembayaran/{id}/transfer_bank',
        [DashboardController::class,'showTransfer']
    )->name('klien.pembayaran.transfer');

    Route::post(
        '/pembayaran/{id}/transfer_bank',
        [DashboardController::class,'uploadTransfer']
    )->name('klien.pembayaran.transfer.store');


    Route::post(
    '/pembayaran/{id}/cash',
    [DashboardController::class,'storeCash']
    )->name('klien.pembayaran.cash.store');

    Route::get(
        '/dashboard/klien/pembayaran/{pembayaran}/konfirmasi',
        [DashboardController::class, 'konfirmasi']
    )->name('klien.pembayaran.konfirmasi');
    
    Route::get('/dashboard/klien/pembayaran/{id}/pdf', [\App\Http\Controllers\PembayaranController::class, 'pdf'])
        ->name('klien.pembayaran.pdf');
    
});
