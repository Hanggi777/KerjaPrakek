<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Pembayaran;
use App\Models\TransaksiPenjualan;
use Carbon\Carbon;

class ClientDashboardController extends Controller
{
    public function index()
    {
        $client = Auth::guard('client')->user();

        $transaksi = $client->transaksi()->with('paketMaster', 'pembayaran')->get();
        $totalTagihan = $transaksi->sum('total_penawaran');
        $totalOutstanding = $transaksi->sum('sisa_pelunasan');
        $reminderH30 = $transaksi->filter(function ($item) {
            return $item->status_transaksi !== 'lunas' && $item->batas_pelunasan && $item->batas_pelunasan->lessThanOrEqualTo(now()->addDays(30));
        });

        return view('dashboard.client', compact('client', 'transaksi', 'totalTagihan', 'totalOutstanding', 'reminderH30'));
    }

    public function showTransaction(TransaksiPenjualan $transaction)
    {
        $client = Auth::guard('client')->user();

        if ($transaction->klien_id !== $client->id) {
            abort(403);
        }

        $transaction->load(['paketMaster', 'pembayaran']);

        return view('dashboard.client-transaction', compact('client', 'transaction'));
    }
}
