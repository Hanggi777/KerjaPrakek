<?php

namespace App\Http\Controllers;

use App\Models\TransaksiPenjualan;
use App\Models\Pembayaran;
use App\Models\TargetPenjualan;
use App\Models\Klien;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;



class DashboardController extends Controller
{
    /**
     * Dashboard Internal (Dynamically route based on role)
     */
    public function internal()
    {
        $user = Auth::user();

        if ($user->role === 'superadmin') {
            return $this->superadminDashboard();
        } elseif ($user->role === 'pemilik') {
            return $this->pemilikDashboard();
        } elseif ($user->role === 'sales') {
            return $this->salesDashboard();
        }

        abort(403, 'Akses tidak diizinkan');
    }

    /**
     * Dashboard Superadmin
     */
    public function superadminDashboard()
    {
        $totalUsers = User::count();
        $totalSales = User::where('role', 'sales')->count();
        $totalKlien = Klien::count();
        $totalTransaksiHariIni = TransaksiPenjualan::whereDate('tanggal_transaksi', Carbon::today())->count();

        $omzetBulanIni = TransaksiPenjualan::whereMonth('tanggal_transaksi', Carbon::now()->month)
            ->whereYear('tanggal_transaksi', Carbon::now()->year)
            ->where('status_transaksi', '!=', 'batal')
            ->sum('total_penawaran');

        $pembayaranBerhasil = Pembayaran::where('status_pembayaran', 'berhasil')->sum('nominal_bayar');

        $transaksiTerbaru = TransaksiPenjualan::with(['klien', 'sales'])
            ->latest('tanggal_transaksi')
            ->take(10)
            ->get();

        $transaksiH30 = TransaksiPenjualan::where('batas_pelunasan', '<=', Carbon::now()->addDays(30))
            ->where('batas_pelunasan', '>=', Carbon::now())
            ->where('status_transaksi', '!=', 'lunas')
            ->count();

        return view('dashboard.superadmin', compact(
            'totalUsers',
            'totalSales',
            'totalKlien',
            'totalTransaksiHariIni',
            'omzetBulanIni',
            'pembayaranBerhasil',
            'transaksiTerbaru',
            'transaksiH30'
        ));
    }

    /**
     * Dashboard Pemilik
     */
    public function pemilikDashboard()
    {
        $totalUsers = User::count();
        $totalSales = User::where('role', 'sales')->count();
        $totalKlien = Klien::count();

        $totalTransaksiBulanIni = TransaksiPenjualan::whereMonth('tanggal_transaksi', Carbon::now()->month)
            ->whereYear('tanggal_transaksi', Carbon::now()->year)
            ->where('status_transaksi', '!=', 'batal')
            ->count();

        $omzetBulanIni = TransaksiPenjualan::whereMonth('tanggal_transaksi', Carbon::now()->month)
            ->whereYear('tanggal_transaksi', Carbon::now()->year)
            ->where('status_transaksi', '!=', 'batal')
            ->sum('total_penawaran');

        $pembayaranDPMasuk = Pembayaran::where('jenis_pembayaran', 'dp')
            ->where('status_pembayaran', 'berhasil')
            ->whereMonth('tanggal_bayar', Carbon::now()->month)
            ->sum('nominal_bayar');

        $pembayaranPelunasanMasuk = Pembayaran::where('jenis_pembayaran', 'pelunasan')
            ->where('status_pembayaran', 'berhasil')
            ->whereMonth('tanggal_bayar', Carbon::now()->month)
            ->sum('nominal_bayar');

        // Target sales untuk bulan ini
        $targetBulanIni = TargetPenjualan::where('bulan', Carbon::now()->month)
            ->where('tahun', Carbon::now()->year)
            ->get();

        $transaksiTerbaru = TransaksiPenjualan::with(['klien', 'sales'])
            ->latest('tanggal_transaksi')
            ->take(10)
            ->get();

        $transaksiH30 = TransaksiPenjualan::where('batas_pelunasan', '<=', Carbon::now()->addDays(30))
            ->where('batas_pelunasan', '>=', Carbon::now())
            ->where('status_transaksi', '!=', 'lunas')
            ->count();

        return view('dashboard.pemilik', compact(
            'totalSales',
            'totalKlien',
            'totalUsers',
            'totalTransaksiBulanIni',
            'omzetBulanIni',
            'pembayaranDPMasuk',
            'pembayaranPelunasanMasuk',
            'targetBulanIni',
            'transaksiTerbaru',
            'transaksiH30'
        ));
    }
    /**
     * Progress Target
     */
    public function progressTarget()
    {
        $target = TargetPenjualan::with('sales')
            ->where('bulan', now()->month)
            ->where('tahun', now()->year)
            ->get();

        return view('dashboard.progress-target', compact('target'));
    }

    /**
     * Lihat Omzet
     */
    public function omzet()
    {
        $transaksi = TransaksiPenjualan::whereMonth('tanggal_transaksi', now()->month)
            ->whereYear('tanggal_transaksi', now()->year)
            ->get();

        $omzet = $transaksi->sum('total_penawaran');

        return view('dashboard.omzet', compact(
            'transaksi',
            'omzet'
        ));
    }

    


    /**
     * Dashboard Sales
     */
public function salesDashboard()
{
    $sales = Auth::user();
    $bulanIni = Carbon::now()->month;
    $tahunIni = Carbon::now()->year;

    // ==============================
// PERHITUNGAN KOMISI SALES
// ==============================

// Komisi 5% ketika DP sudah dibayar
$komisiDP = TransaksiPenjualan::where('sales_id', $sales->id)
    ->whereIn('status_transaksi', [
        'dp_terbayar',
        'menunggu_pelunasan',
        'lunas'
    ])
    ->sum('total_penawaran') * 0.05;

// Tambahan 5% ketika transaksi sudah lunas
$komisiLunas = TransaksiPenjualan::where('sales_id', $sales->id)
    ->where('status_transaksi', 'lunas')
    ->sum('total_penawaran') * 0.05;

// Total komisi
$komisiSales = $komisiDP + $komisiLunas;

    // Target sales bulan ini
    $targetSales = TargetPenjualan::where('sales_id', $sales->id)
        ->where('bulan', $bulanIni)
        ->where('tahun', $tahunIni)
        ->first();

    if (!$targetSales) {
        $targetSales = (object)[
            'id' => null,
            'target_nominal' => 0
        ];
    }

    // Total penjualan bulan 
    
    $totalPenjualanBulanIni = TransaksiPenjualan::where('sales_id', $sales->id)
        ->whereMonth('tanggal_transaksi', $bulanIni)
        ->whereYear('tanggal_transaksi', $tahunIni)
        ->where('status_transaksi', '!=', 'batal')
        ->sum('total_penawaran');

    // Persentase target
    $persentasePencapaian = $targetSales->target_nominal > 0
        ? ($totalPenjualanBulanIni / $targetSales->target_nominal) * 100
        : 0;

    $sisaTarget = max(0, $targetSales->target_nominal - $totalPenjualanBulanIni);

    // Statistik
    $totalTransaksi = TransaksiPenjualan::where('sales_id', $sales->id)
        ->where('status_transaksi', '!=', 'batal')
        ->count();

    $transaksiMenungguDP = TransaksiPenjualan::where('sales_id', $sales->id)
        ->where('status_transaksi', 'menunggu_dp')
        ->count();

    $transaksiMenungguPelunasan = TransaksiPenjualan::where('sales_id', $sales->id)
        ->where('status_transaksi', 'menunggu_pelunasan')
        ->count();

    // H-30
    $transaksiH30 = TransaksiPenjualan::where('sales_id', $sales->id)
        ->where('status_transaksi', '!=', 'batal')
        ->where('status_transaksi', '!=', 'lunas')
        ->whereDate('batas_pelunasan', '<=', now()->addDays(30))
        ->whereDate('batas_pelunasan', '>=', now())
        ->count();

    // Transaksi terbaru
    $transaksiTerbaru = TransaksiPenjualan::with('klien')
        ->where('sales_id', $sales->id)
        ->where('status_transaksi', '!=', 'batal')
        ->latest('tanggal_transaksi')
        ->take(10)
        ->get();

return view('dashboard.sales', compact(
    'targetSales',
    'totalPenjualanBulanIni',
    'persentasePencapaian',
    'sisaTarget',
    'totalTransaksi',
    'transaksiMenungguDP',
    'transaksiMenungguPelunasan',
    'transaksiH30',
    'transaksiTerbaru',
    'komisiDP',
    'komisiLunas',
    'komisiSales'
)); 
}
public function komisiSales()
{
    $sales = Auth::user();

    $komisiDP = 0;
    $komisiLunas = 0;

    $transaksi = TransaksiPenjualan::where('sales_id', $sales->id)
        ->where('status_transaksi', '!=', 'batal')
        ->latest()
        ->get();

    foreach ($transaksi as $trx) {

        if (in_array($trx->status_transaksi, [
            'dp_terbayar',
            'menunggu_pelunasan',
            'lunas'
        ])) {

            $komisiDP += $trx->total_penawaran * 0.05;
        }

        if ($trx->status_transaksi == 'lunas') {

            $komisiLunas += $trx->total_penawaran * 0.05;
        }
    }

    $komisiSales = $komisiDP + $komisiLunas;

    return view('dashboard.sales-komisi', compact(
        'transaksi',
        'komisiDP',
        'komisiLunas',
        'komisiSales'
    ));
}

public function komisiSalesAdmin()
{
    $salesList = User::where('role', 'sales')->get();

    $data = [];

    foreach ($salesList as $sales) {

        $deal = TransaksiPenjualan::where('sales_id', $sales->id)
            ->whereIn('status_transaksi', ['dp_terbayar','lunas'])
            ->sum('total_penawaran');

        $komisiDP = TransaksiPenjualan::where('sales_id', $sales->id)
            ->where('status_transaksi', 'dp_terbayar')
            ->sum('total_penawaran') * 0.05;

        $komisiLunas = TransaksiPenjualan::where('sales_id', $sales->id)
            ->where('status_transaksi', 'lunas')
            ->sum('total_penawaran') * 0.05;

        $data[] = [
            'nama' => $sales->name,
            'deal' => $deal,
            'dp' => $komisiDP,
            'lunas' => $komisiLunas,
            'komisi' => $komisiDP + $komisiLunas,
        ];
    }

    return view('dashboard.pemilik-komisi', compact('data'));
}


    public function progressTargetSales()
{
    $sales = Auth::user();
    $bulanIni = now()->month;
    $tahunIni = now()->year;

    $targetSales = TargetPenjualan::where('sales_id', $sales->id)
        ->where('bulan', $bulanIni)
        ->where('tahun', $tahunIni)
        ->first();

    if (!$targetSales) {
        $targetSales = (object)[
            'target_nominal' => 0
        ];
    }

    $totalPenjualanBulanIni = TransaksiPenjualan::where('sales_id', $sales->id)
        ->whereMonth('tanggal_transaksi', $bulanIni)
        ->whereYear('tanggal_transaksi', $tahunIni)
        ->where('status_transaksi', '!=', 'batal')
        ->sum('total_penawaran');

    $persentasePencapaian = $targetSales->target_nominal > 0
        ? ($totalPenjualanBulanIni / $targetSales->target_nominal) * 100
        : 0;

    $sisaTarget = max(0, $targetSales->target_nominal - $totalPenjualanBulanIni);

    return view('dashboard.progress-target-sales', compact(
        'targetSales',
        'totalPenjualanBulanIni',
        'persentasePencapaian',
        'sisaTarget'
    ));
}
       public function omzetSales()
{
    $sales = Auth::user();

    $transaksi = TransaksiPenjualan::with(['klien', 'paketMaster'])
        ->where('sales_id', $sales->id)
        ->whereMonth('tanggal_transaksi', now()->month)
        ->whereYear('tanggal_transaksi', now()->year)
        ->where('status_transaksi', '!=', 'batal')
        ->get();

    $omzet = $transaksi->sum('total_penawaran');

    return view('dashboard.omzet-sales', compact(
        'transaksi',
        'omzet'
    ));
}
public function kinerjaSales()
{
    $bulan = now()->month;
    $tahun = now()->year;

    $sales = User::where('role', 'sales')
        ->get()
        ->map(function ($user) use ($bulan, $tahun) {

            $target = TargetPenjualan::where('sales_id', $user->id)
                ->where('bulan', $bulan)
                ->where('tahun', $tahun)
                ->first();

            $totalPenjualan = TransaksiPenjualan::where('sales_id', $user->id)
                ->whereMonth('tanggal_transaksi', $bulan)
                ->whereYear('tanggal_transaksi', $tahun)
                ->where('status_transaksi', '!=', 'batal')
                ->sum('total_penawaran');

            $targetNominal = $target->target_nominal ?? 0;

            $persentase = $targetNominal > 0
                ? ($totalPenjualan / $targetNominal) * 100
                : 0;

            return (object)[
                'name' => $user->name,
                'target' => $targetNominal,
                'total_penjualan' => $totalPenjualan,
                'persentase' => round($persentase, 2),
            ];
        });

    return view('dashboard.kinerja-sales', compact('sales'));
}


    /**
     * Dashboard Portal Klien
     */
    public function klienDashboard()
{
    $klien = Auth::guard('klien')->user();

    // Semua transaksi milik klien
    $transaksiKlien = TransaksiPenjualan::with([
            'detail',
            'pembayaran',
            'paketMaster',
            'sales'
        ])
        ->where('klien_id', $klien->id)
        ->latest('tanggal_transaksi')
        ->paginate(10);

    // Ringkasan Dashboard
    $totalTransaksi = $transaksiKlien->total();

    $totalTagihan = TransaksiPenjualan::where('klien_id', $klien->id)
        ->sum('total_penawaran');

    $totalTerbayar = Pembayaran::whereHas('transaksi', function ($q) use ($klien) {
            $q->where('klien_id', $klien->id);
        })
        ->where('status_pembayaran', 'berhasil')
        ->sum('nominal_bayar');

    $sisaTagihan = $totalTagihan - $totalTerbayar;

    // Notifikasi H-30
    $transaksiH30 = TransaksiPenjualan::where('klien_id', $klien->id)
        ->whereDate('tanggal_acara', '<=', now()->addDays(30))
        ->whereDate('tanggal_acara', '>=', now())
        ->where('status_transaksi', '!=', 'lunas')
        ->count();

    return view('dashboard.klien', compact(
        'klien',
        'transaksiKlien',
        'totalTransaksi',
        'totalTagihan',
        'totalTerbayar',
        'sisaTagihan',
        'transaksiH30'
    ));
    }
    public function detailTransaksi($id)
{
    $klien = Auth::guard('klien')->user();

    $transaksi = TransaksiPenjualan::with([
        'klien',
        'sales',
        'paketMaster',
        'detail',
        'pembayaran'
    ])
    ->where('klien_id', $klien->id)
    ->findOrFail($id);

    $totalTerbayar = $transaksi->pembayaran
        ->where('status_pembayaran','berhasil')
        ->sum('nominal_bayar');

    $sisa = $transaksi->total_penawaran - $totalTerbayar;

    return view('klien.transaksi.show', compact(
        'transaksi',
        'totalTerbayar',
        'sisa'
    ));
}

public function detailPembayaran($id)
{
    $klien = Auth::guard('klien')->user();

    $pembayaran = Pembayaran::with('transaksi')
        ->whereHas('transaksi', function ($q) use ($klien) {
            $q->where('klien_id', $klien->id);
        })
        ->findOrFail($id);

    $transaksi = $pembayaran->transaksi;

    return view('klien.pembayaran.show', compact(
        'pembayaran',
        'transaksi'
    ));
}
public function pilihMetode($id)
{
    $klien = Auth::guard('klien')->user();

    $transaksi = TransaksiPenjualan::with('pembayaran')
        ->where('klien_id', $klien->id)
        ->findOrFail($id);

    return view('klien.pembayaran.bayar', compact('transaksi'));
}

public function prosesPembayaran(Request $request, $id)
{
    $request->validate([
        'metode_pembayaran' => 'required|in:transfer_bank,cash,transfer',
    ]);

    $klien = Auth::guard('klien')->user();

    $transaksi = TransaksiPenjualan::where('klien_id', $klien->id)
        ->findOrFail($id);

    $hasSuccessfulDp = Pembayaran::where('transaksi_id', $transaksi->id)
        ->where('status_pembayaran', 'berhasil')
        ->where('jenis_pembayaran', 'dp')
        ->exists();

    $hasAnyPayment = Pembayaran::where('transaksi_id', $transaksi->id)->exists();

    if ($hasAnyPayment && ! $hasSuccessfulDp) {
        return back()->with('error', 'DP harus dibayar dan dikonfirmasi terlebih dahulu sebelum pembayaran selanjutnya dapat dibuat.');
    }

    if ($request->metode_pembayaran == 'transfer_bank' || $request->metode_pembayaran == 'transfer') {
        return redirect()->route('klien.pembayaran.transfer', $transaksi->id);
    }

    return redirect()->route('klien.pembayaran.cash', $transaksi->id);
}

public function showTransfer($id)
{
    $klien = Auth::guard('klien')->user();

    $transaksi = TransaksiPenjualan::where('klien_id', $klien->id)
        ->findOrFail($id);

    return view('klien.pembayaran.transfer', compact('transaksi'));
}



public function cash($id)
{
    $klien = Auth::guard('klien')->user();

    $transaksi = TransaksiPenjualan::where('klien_id', $klien->id)
        ->findOrFail($id);

    return view('klien.pembayaran.cash', compact('transaksi'));
}

public function uploadTransfer(Request $request, $id)
{
    $klien = Auth::guard('klien')->user();

    $transaksi = TransaksiPenjualan::where('klien_id', $klien->id)
        ->findOrFail($id);

    $hasSuccessfulDp = Pembayaran::where('transaksi_id', $transaksi->id)
        ->where('status_pembayaran', 'berhasil')
        ->where('jenis_pembayaran', 'dp')
        ->exists();

    $jenis = 'dp';
    $maxNominalTransfer = (float) $transaksi->nominal_dp;

    if ($hasSuccessfulDp) {
        $totalTerbayar = Pembayaran::where('transaksi_id', $transaksi->id)
            ->where('status_pembayaran', 'berhasil')
            ->sum('nominal_bayar');

        $remaining = max(0, (float) $transaksi->total_penawaran - (float) $totalTerbayar);
        $maxNominalTransfer = $remaining;
        $jenis = $remaining <= 0 ? 'pelunasan' : 'pembayaran';
    }

    $maxNominalTransfer = max(1, (int) $maxNominalTransfer);
    $minNominalTransfer = $jenis === 'dp'
        ? $maxNominalTransfer
        : max(1, (int) ceil($maxNominalTransfer * 0.1));

    $request->validate([
        'nominal_bayar'     => ['required', 'numeric', 'min:' . $minNominalTransfer, 'max:' . $maxNominalTransfer],
        'bank_tujuan'       => 'required|in:mandiri,bni_1,bni_2,bca',
        'bukti_pembayaran'  => 'required|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $path = $request->file('bukti_pembayaran')
        ->store('bukti-transfer', 'public');

    $metodeUntukDB = DB::getDriverName() === 'sqlite' ? 'transfer' : 'transfer_bank';

    $bankNama = $this->getBankName($request->bank_tujuan);

    $pembayaran = Pembayaran::create([
        'kode_pembayaran'   => 'PAY' . time(),
        'transaksi_id'      => $transaksi->id,
        'jenis_pembayaran'  => $jenis,
        'metode_pembayaran' => $metodeUntukDB,
        'nominal_tagihan'   => $maxNominalTransfer,
        'nominal_bayar'     => $request->nominal_bayar,
        'bukti_pembayaran'  => $path,
        'bank_tujuan'       => $bankNama,
        'status_pembayaran' => 'pending',
    ]);

    // Update transaction status untuk reflect perubahan pembayaran
    $this->updateTransactionStatus($transaksi);

    return redirect()
        ->route('klien.pembayaran.show', $pembayaran->id)
        ->with('success', 'Bukti transfer berhasil dikirim.');
}

public function showCash($id)
{
    $pembayaran = Pembayaran::findOrFail($id);

    return view('klien.pembayaran.cash', compact('pembayaran'));
}

public function storeCash(Request $request, $id)
{
    $klien = Auth::guard('klien')->user();

    $transaksi = TransaksiPenjualan::where('klien_id', $klien->id)
        ->findOrFail($id);

    $hasSuccessfulDp = Pembayaran::where('transaksi_id', $transaksi->id)
        ->where('status_pembayaran', 'berhasil')
        ->where('jenis_pembayaran', 'dp')
        ->exists();

    $jenis = 'dp';
    $maxNominalCash = (float) $transaksi->nominal_dp;

    if ($hasSuccessfulDp) {
        $totalTerbayar = Pembayaran::where('transaksi_id', $transaksi->id)
            ->where('status_pembayaran', 'berhasil')
            ->sum('nominal_bayar');

        $remaining = max(0, (float) $transaksi->total_penawaran - (float) $totalTerbayar);
        $maxNominalCash = $remaining;
        $jenis = $remaining <= 0 ? 'pelunasan' : 'pembayaran';
    }

    $maxNominalCash = max(1, (int) $maxNominalCash);
    $minNominalCash = $jenis === 'dp'
        ? $maxNominalCash
        : max(1, (int) ceil($maxNominalCash * 0.1));

    $request->validate([
        'nominal_bayar' => ['required', 'numeric', 'min:' . $minNominalCash, 'max:' . $maxNominalCash],
        'bukti_pembayaran' => 'required|image|mimes:jpg,jpeg,png|max:2048'
    ]);

    $bukti = $request->file('bukti_pembayaran')
                     ->store('bukti_pembayaran','public');

    $pembayaran = Pembayaran::create([
        'kode_pembayaran'   => 'PAY' . time(),
        'transaksi_id'      => $transaksi->id,
        'jenis_pembayaran'  => $jenis,
        'metode_pembayaran' => 'cash',
        'nominal_tagihan'   => $maxNominalCash,
        'nominal_bayar'     => $request->nominal_bayar,
        'bukti_pembayaran' => $bukti,
        'status_pembayaran' => 'pending',
        'tanggal_bayar'     => null,
    ]);

    // Update transaction status untuk reflect perubahan pembayaran
    $this->updateTransactionStatus($transaksi);

    return redirect()
        ->route('klien.pembayaran.show',$pembayaran->id)
        ->with('success','Bukti pembayaran berhasil dikirim.');
}

public function konfirmasi(Pembayaran $pembayaran)
{
    $transaksi = $pembayaran->transaksi;

    // Update pembayaran status ke berhasil
    $pembayaran->update([
        'status_pembayaran' => 'berhasil',
        'tanggal_bayar' => now()
    ]);

    // Recalculate transaction status berdasarkan semua pembayaran
    $this->updateTransactionStatus($transaksi);

    return back()->with(
        'success',
        'Pembayaran berhasil dikonfirmasi.'
    );
}

private function getBankName($bankCode)
{
    $banks = [
        'mandiri' => 'Bank Mandiri - 0060006117992 (Muslihaini)',
        'bni_1' => 'Bank BNI - 0118269119 (Hasmalina)',
        'bni_2' => 'Bank BNI - 1210006312890 (Nabilla Pratama)',
        'bca' => 'Bank BCA - 00727198919 (Hasmalina)',
    ];

    return $banks[$bankCode] ?? $bankCode;
}

protected function updateTransactionStatus(TransaksiPenjualan $transaksi)
{
    // Hitung pembayaran dengan status berhasil
    $totalBerhasil = Pembayaran::where('transaksi_id', $transaksi->id)
        ->where('status_pembayaran', 'berhasil')
        ->sum('nominal_bayar');

    // Hitung pembayaran dengan status pending/verifikasi (belum dikonfirmasi)
    $totalPending = Pembayaran::where('transaksi_id', $transaksi->id)
        ->whereIn('status_pembayaran', ['pending', 'pending_verifikasi'])
        ->sum('nominal_bayar');

    // Tentukan jenis pembayaran pertama
    $pembayaranPertama = Pembayaran::where('transaksi_id', $transaksi->id)
        ->oldest('created_at')
        ->first();

    // Hitung DP yang berhasil
    $dpBerhasil = Pembayaran::where('transaksi_id', $transaksi->id)
        ->where('jenis_pembayaran', 'dp')
        ->where('status_pembayaran', 'berhasil')
        ->exists();

    // Hitung sisa pelunasan berdasarkan pembayaran yang berhasil
    $sisaPelunasan = max(0, (float) $transaksi->total_penawaran - (float) $totalBerhasil);

    // Tentukan status transaksi
    $statusTransaksi = 'menunggu_dp'; // default status

    if ($totalBerhasil >= $transaksi->total_penawaran) {
        // Sudah lunas
        $statusTransaksi = 'lunas';
    } elseif ($dpBerhasil && $totalBerhasil > 0) {
        // DP sudah berhasil
        $statusTransaksi = 'dp_terbayar';
    } elseif ($totalBerhasil > 0) {
        // Ada pembayaran yang berhasil (bukan DP) tapi belum lunas
        $statusTransaksi = 'menunggu_pelunasan';
    } elseif ($totalPending > 0) {
        // Ada pembayaran pending, tapi belum berhasil
        // Jika pending adalah DP, status menunggu_dp
        if ($pembayaranPertama && $pembayaranPertama->jenis_pembayaran === 'dp') {
            $statusTransaksi = 'menunggu_dp';
        }
    }

    // Update transaksi
    $transaksi->update([
        'status_transaksi' => $statusTransaksi,
        'sisa_pelunasan' => $sisaPelunasan,
    ]);
}
}