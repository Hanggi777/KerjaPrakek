<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\PaketMaster;
use App\Models\TransaksiPenjualan;
use App\Models\User;
use App\Models\Klien;
use App\Models\TargetPenjualan;
use Carbon\Carbon;

class InternalDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $now = Carbon::now();
        $month = $now->month;
        $year = $now->year;

        $totalUsers = User::count();
        $totalSales = User::where('role', 'sales')->count();
        $totalClients = Klien::count();
        $packageCount = PaketMaster::count();

        $baseQuery = TransaksiPenjualan::whereMonth('tanggal_transaksi', $month)->whereYear('tanggal_transaksi', $year);
        $reminderQuery = TransaksiPenjualan::where('status_transaksi', '!=', 'lunas')
            ->whereDate('batas_pelunasan', '<=', $now->copy()->addDays(30));

        if ($user->isSales()) {
            $baseQuery->where('sales_id', $user->id);
            $reminderQuery->where('sales_id', $user->id);
        }

        $totalTransactions = $baseQuery->count();
        $totalOmzet = $baseQuery->sum('total_penawaran');
        $reminderH30 = $reminderQuery->get();

        $target = null;
        $targetProgress = 0;
        $totalTargets = 0;
        $totalTargetNominal = 0;
        $overallTargetProgress = 0;

        if ($user->isSales()) {
            $target = TargetPenjualan::where('sales_id', $user->id)
                ->where('bulan', $month)
                ->where('tahun', $year)
                ->first();

            if ($target) {
                $targetProgress = $target->target_nominal > 0
                    ? min(100, ($totalOmzet / $target->target_nominal) * 100)
                    : 0;
            }
        }

        if ($user->isPemilik() || $user->isSuperadmin()) {
            $totalTargets = TargetPenjualan::where('bulan', $month)
                ->where('tahun', $year)
                ->count();

            $totalTargetNominal = TargetPenjualan::where('bulan', $month)
                ->where('tahun', $year)
                ->sum('target_nominal');

            if ($totalTargetNominal > 0) {
                $overallTargetProgress = min(100, ($totalOmzet / $totalTargetNominal) * 100);
            }
        }

        return view('dashboard.internal', compact(
            'user',
            'totalUsers',
            'totalSales',
            'totalClients',
            'packageCount',
            'totalTransactions',
            'totalOmzet',
            'reminderH30',
            'target',
            'targetProgress',
            'totalTargets',
            'totalTargetNominal',
            'overallTargetProgress'
        ));
    }
}
