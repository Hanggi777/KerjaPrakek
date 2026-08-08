<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\TransaksiPenjualan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PembayaranController extends Controller
{
    /**
     * Display a listing of pembayaran
     */
    public function index()
    {
        $query = Pembayaran::with(['transaksi.klien', 'transaksi.sales']);

        // Filter by sales
        if (Auth::user()->role === 'sales') {
            $query->whereHas('transaksi', function ($q) {
                $q->where('sales_id', Auth::id());
            });
        }

        $pembayaran = $query->latest()->paginate(20);

        return view('pembayaran.index', compact('pembayaran'));
    }

    /**
     * Show form for recording new payment
     */
   public function create(TransaksiPenjualan $transaksi)
{
    // Cek hak akses
    if (Auth::user()->role === 'sales' && $transaksi->sales_id != Auth::id()) {
        abort(403);
    }

    // Ambil pembayaran yang masih pending
    $pembayaran = Pembayaran::where('transaksi_id', $transaksi->id)
    ->latest()
    ->first();

    if (!$pembayaran) {

    return redirect()
        ->route('transaksi.show', $transaksi->id)
        ->with('error', 'Belum ada data pembayaran.');

}
    if (!$pembayaran) {
        return redirect()
            ->route('transaksi.show', $transaksi->id)
            ->with('error', 'Belum ada pembayaran yang dapat diproses.');
    }

    return view('pembayaran.create', compact(
        'transaksi',
        'pembayaran'
    ));
}

    public function cancel(TransaksiPenjualan $transaction)
{
    $transaction->status_transaksi = 'batal';
    $transaction->save();

    return redirect()
        ->route('transaksi.index')
        ->with('success', 'Transaksi berhasil dibatalkan.');
}

    /**
     * Store a newly recorded payment
     */
    public function store(Request $request, TransaksiPenjualan $transaksi)
{
    if (Auth::user()->role === 'sales' && $transaksi->sales_id != Auth::id()) {
        abort(403);
    }

    $request->validate([
    'nominal_bayar' => 'required|numeric|min:1',
    'metode_pembayaran' => 'required|in:cash,transfer_bank',

    'bukti_pembayaran' => [
        'required_if:metode_pembayaran,transfer_bank',
        'image',
        'mimes:jpg,jpeg,png',
        'max:2048'
    ],
]);

    // Ambil pembayaran yang masih pending
    $totalBerhasil = Pembayaran::where('transaksi_id', $transaksi->id)
    ->where('status_pembayaran', 'berhasil')
    ->sum('nominal_bayar');

    $sisa = $transaksi->total_penawaran - $totalBerhasil;

    if ($request->nominal_bayar > $sisa) {

    return back()->with(
        'error',
        'Nominal melebihi sisa tagihan.'
    );

    }
    $kode = 'PAY-' . date('Y') . '-' .
    str_pad((Pembayaran::max('id') + 1), 4, '0', STR_PAD_LEFT);

    $pembayaran = Pembayaran::create([
        'kode_pembayaran'   => $kode,
        'transaksi_id'      => $transaksi->id,
        'jenis_pembayaran'  => 'pembayaran',
        'nominal_tagihan'   => $transaksi->total_penawaran,
        'nominal_bayar'     => 0,
        'status_pembayaran' => 'pending',
    ]);

    $bukti = $pembayaran->bukti_pembayaran;

    if ($request->hasFile('bukti_pembayaran')) {
        $file = $request->file('bukti_pembayaran');

        $namaFile = time().'_'.$file->getClientOriginalName();

        $file->storeAs('pembayaran', $namaFile, 'public');

        $bukti = 'pembayaran/'.$namaFile;
    }

    $status = 'pending';

    if ($request->metode_pembayaran == 'transfer_bank') {
        $status = 'pending';
    }

    $pembayaran->update([
        'nominal_bayar'     => $request->nominal_bayar,
        'metode_pembayaran' => $request->metode_pembayaran,
        'tanggal_bayar'     => $status == 'berhasil'
                                ? now()
                                : null,
        'status_pembayaran' => $status,
        'bukti_pembayaran'  => $bukti,
    ]);

    // Update status transaksi
    $this->updateTransactionStatus($transaksi);

    return redirect()
        ->route('transaksi.show', $transaksi->id)
        ->with('success', 'Pembayaran berhasil disimpan.');
}

    /**
     * Show payment detail
     */
    public function show(Pembayaran $pembayaran)
    {
        $pembayaran->load('transaksi.klien');

        if (Auth::user()->role === 'sales' && $pembayaran->transaksi->sales_id != Auth::id()) {
            abort(403);
        }

        return view('pembayaran.show', compact('pembayaran'));
    }

    /**
     * Edit pembayaran (only pending)
     */
    public function edit(Pembayaran $pembayaran)
{
    return view('pembayaran.edit', compact('pembayaran'));
}

    public function destroy(Pembayaran $pembayaran)
    {
        $transaksi = $pembayaran->transaksi;

        $pembayaran->delete();

        // Update status transaksi setelah pembayaran dihapus
        $this->updateTransactionStatus($transaksi);

        return redirect()
            ->route('pembayaran.index')
            ->with('success', 'Pembayaran berhasil dihapus dan status transaksi diperbarui.');
    }

    /**
     * Update pembayaran
     */
    public function update(Request $request, Pembayaran $pembayaran)
    {
        $request->validate([
            'metode_pembayaran' => 'required',
            'status_pembayaran' => 'required',
            'catatan' => 'nullable|string'
        ]);

        $pembayaran->metode_pembayaran = $request->metode_pembayaran;
        $pembayaran->status_pembayaran = $request->status_pembayaran;
        $pembayaran->catatan = $request->catatan;

        if ($request->status_pembayaran == 'berhasil') {
            $pembayaran->tanggal_bayar = Carbon::now();

            if ($pembayaran->nominal_bayar == 0) {
                $pembayaran->nominal_bayar = $pembayaran->nominal_tagihan;
            }
        }

        $pembayaran->save();

        // Update status dan sisa pelunasan transaksi
        $this->updateTransactionStatus($pembayaran->transaksi);

        return redirect()
            ->route('pembayaran.index')
            ->with('success','Pembayaran berhasil diperbarui.');
    }

    

    public function pdf($id)
    {
        $pembayaran = Pembayaran::with(['transaksi.klien'])->findOrFail($id);

        // Log untuk debugging redirect loop: siapa yang mengakses dan guard apa yang terpasang
        Log::info('Attempting to access pembayaran.pdf', [
            'pembayaran_id' => $id,
            'route' => optional(request()->route())->getName(),
            'guard_klien_check' => Auth::guard('klien')->check(),
            'guard_klien_id' => Auth::guard('klien')->id(),
            'auth_user_id' => Auth::id(),
            'auth_user_role' => Auth::user()->role ?? null,
        ]);

        // Jika yang mengakses adalah klien, pastikan hanya bisa mengunduh pembayaran miliknya
        if (Auth::guard('klien')->check()) {
            if ($pembayaran->transaksi->klien->id != Auth::guard('klien')->id()) {
                abort(403);
            }
        } else {
            // Untuk user internal: sales hanya boleh mengakses pembayaran dari transaksi mereka
            if (Auth::user() && Auth::user()->role === 'sales' && $pembayaran->transaksi->sales_id != Auth::id()) {
                abort(403);
            }
        }

        $pdf = Pdf::loadView('pembayaran.pdf', compact('pembayaran'));

        return $pdf->download('Bukti-Pembayaran-'.$pembayaran->kode_pembayaran.'.pdf');
    }

    /**
     * Update transaction status based on payment status
     * Mempertimbangkan pending, pending_verifikasi, dan berhasil payments
     */
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
