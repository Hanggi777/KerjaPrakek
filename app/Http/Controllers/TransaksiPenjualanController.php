<?php

namespace App\Http\Controllers;

use App\Models\Klien;
use App\Models\PaketMaster;
use App\Models\PaketMasterHarga;
use App\Models\TargetPenjualan;
use App\Models\TransaksiPenjualan;
use App\Models\Pembayaran;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TransaksiDetail;
use Barryvdh\DomPDF\Facade\Pdf;
class TransaksiPenjualanController extends Controller
{
public function index()
{
    $user = Auth::user();

    $query = TransaksiPenjualan::with([
        'klien',
        'paketMaster',
        'sales'
    ])
    ->where('status_transaksi', '!=', 'batal'); // Jangan tampilkan transaksi yang dibatalkan

    if ($user->isSales()) {
        $query->where('sales_id', $user->id);
    }

    $transactions = $query
        ->orderByDesc('tanggal_transaksi')
        ->get();

    return view('transaksi.index', compact('transactions'));
}

    public function create()
    {
        $user = Auth::user();

        $clientsQuery = Klien::where('status_aktif', true);

        if ($user->isSales()) {
            $clientsQuery->where('sales_id', $user->id);
        }

        $clients = $clientsQuery->get();
        $packages = PaketMaster::with('hargaMaster')->where('status_aktif', true)->get();
        $variants = PaketMasterHarga::with('paketMaster')
            ->whereHas('paketMaster', fn($query) => $query->where('status_aktif', true))
            ->orderBy('nama_varian')
            ->get();

        $sales = null;
        $targets = TargetPenjualan::where('bulan', now()->month)
            ->where('tahun', now()->year)
            ->when(!Auth::user()->isSales(), function ($query) {
                return $query;
            })
            ->when(Auth::user()->isSales(), function ($query) {
                return $query->where('sales_id', Auth::id());
            })
            ->get();

        if (!Auth::user()->isSales()) {
            $sales = User::where('role', 'sales')->orderBy('name')->get();
        }

        return view('transaksi.create', compact('clients', 'packages', 'targets', 'sales', 'variants'));
    }

        public function exportPdf(TransaksiPenjualan $transaksi)
    {
        $transaksi->load([
            'klien',
            'sales',
            'paketMaster',
            'detail',
            'pembayaran'
        ]);

        $pdf = Pdf::loadView(
            'transaksi.invoice-pdf',
            compact('transaksi')
        );

        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream(
            'Invoice-'.$transaksi->kode_transaksi.'.pdf'
        );

        // kalau ingin langsung download:
        // return $pdf->download('Invoice-'.$transaksi->kode_transaksi.'.pdf');
    }

    public function store(Request $request)
    {
        $rules = [
            'klien_id' => 'required|exists:klien,id',
            'paket_master_id' => 'required|exists:paket_master,id',
            'paket_master_harga_id' => 'nullable|exists:paket_master_harga,id',
            'target_penjualan_id' => 'nullable|exists:target_penjualan,id',
            'tanggal_acara' => 'required|date|after:today',
            'jumlah_porsi' => 'required|integer|min:1',
            'lokasi_acara' => 'required|string|max:255',
            'catatan' => 'nullable|string',
            'harga_penawaran' => 'required|numeric|min:0',
        ];

        if (!Auth::user()->isSales()) {
            $rules['sales_id'] = 'required|exists:users,id';
        }

        $request->validate($rules);
        $request->validate([
            'nama_item.*' => 'nullable|string|max:255',
            'qty.*' => 'nullable|integer|min:1',
            'satuan.*' => 'nullable|string|max:50',
            'harga_satuan.*' => 'nullable|numeric|min:0',
        ]);

        $user = Auth::user();
        $selectedClient = Klien::where('id', $request->klien_id)->first();

        if ($user->isSales() && $selectedClient && $selectedClient->sales_id !== $user->id) {
            return back()->withErrors(['klien_id' => 'Klien ini tidak tersedia untuk sales Anda.'])->withInput();
        }

        $salesId = $user->isSales() ? $user->id : $request->sales_id;
        $package = PaketMaster::findOrFail($request->paket_master_id);
        $variant = null;

        if ($request->filled('paket_master_harga_id')) {
            $variant = PaketMasterHarga::where('id', $request->paket_master_harga_id)
                ->where('paket_master_id', $request->paket_master_id)
                ->firstOrFail();
        }

        $hargaPenawaran = $request->input('harga_penawaran');

        if (blank($hargaPenawaran) && $variant) {
            $hargaPenawaran = (float) $variant->harga_dasar * max(1, (int) $request->jumlah_porsi);
        }

        $subtotal = max(0, (float) $hargaPenawaran);
        $dp = round($subtotal * 0.1);
        $sisa = $subtotal - $dp;

        $transaction = TransaksiPenjualan::create([
            'kode_transaksi' => 'TRX' . now()->format('YmdHis'),
            'sales_id' => $salesId,
            'klien_id' => $request->klien_id,
            'paket_master_id' => $package->id,
            'paket_master_harga_id' => $variant?->id,
            'target_penjualan_id' => $request->target_penjualan_id,
            'tanggal_transaksi' => now(),
            'tanggal_acara' => $request->tanggal_acara,
            'jumlah_porsi' => $request->jumlah_porsi,
            'lokasi_acara' => $request->lokasi_acara,
            'catatan' => $request->catatan,
            'subtotal' => $subtotal,
            'diskon' => 0,
            'total_penawaran' => $subtotal,
            'nominal_dp' => $dp,
            'sisa_pelunasan' => $sisa,
            'batas_pelunasan' => Carbon::parse($request->tanggal_acara)->subDays(30),
            'status_transaksi' => 'menunggu_dp',
            'status_acara' => 'belum_berjalan',
        ]);

        
        // ==============================
// SIMPAN DETAIL ITEM
// ==============================

        if ($request->filled('nama_item')) {

            foreach ($request->nama_item as $index => $namaItem) {

                if (empty($namaItem)) {
                    continue;
                }

                $qty = $request->qty[$index] ?? 1;
                $harga = $request->harga_satuan[$index] ?? 0;

                TransaksiDetail::create([
                    'transaksi_id' => $transaction->id,
                    'nama_item' => $namaItem,
                    'qty' => $qty,
                    'satuan' => $request->satuan[$index] ?? 'Paket',
                    'harga_satuan' => $harga,
                    'subtotal' => $qty * $harga,
                    'tipe_item' => 'custom',
                ]);
            }

        }
        Pembayaran::create([
            'transaksi_id' => $transaction->id,
            'kode_pembayaran' => 'PAY' . now()->format('YmdHis'),
            'jenis_pembayaran' => 'DP',
            'metode_pembayaran' => 'transfer_bank',
            'nominal_tagihan' => $dp,
            'nominal_bayar' => 0,
            'status_pembayaran' => 'pending',
        ]);

        return redirect()->route('transaksi.index')->with('success', 'Transaksi penjualan berhasil dibuat dan DP 10% ditetapkan.');
    }

public function show($id)
{
    $transaction = TransaksiPenjualan::with([
        'klien',
        'paketMaster',
        'paketMasterHarga',
        'sales',
        'pembayaran',
        'detail',
    ])->findOrFail($id);

    return view('transaksi.show', compact('transaction'));
}

    public function edit($id)
    {
    $transaction = TransaksiPenjualan::findOrFail($id);

    $clients = Klien::where('status_aktif', true)->get();

    $packages = PaketMaster::where('status_aktif', true)
        ->with('hargaMaster')
        ->get();

    $variants = PaketMasterHarga::all();
 
    return view('transaksi.edit', compact(
        'transaction',
        'clients',
        'packages',
        'variants'
    ));
    }

public function update(Request $request, $id)
{
    $transaction = TransaksiPenjualan::findOrFail($id);

    $request->validate([
        'klien_id' => 'required|exists:klien,id',
        'paket_master_id' => 'required|exists:paket_master,id',
        'paket_master_harga_id' => 'nullable|exists:paket_master_harga,id',
        'tanggal_acara' => 'required|date',
        'jumlah_porsi' => 'required|integer|min:1',
        'lokasi_acara' => 'required|string|max:255',
        'catatan' => 'nullable|string',
        'harga_penawaran' => 'nullable|numeric|min:0',
    ]);

    $package = PaketMaster::findOrFail($request->paket_master_id);
    $variant = null;

    if ($request->filled('paket_master_harga_id')) {
        $variant = PaketMasterHarga::where('id', $request->paket_master_harga_id)
            ->where('paket_master_id', $package->id)
            ->first();
    }

    if (! $variant && $transaction->paket_master_harga_id) {
        $variant = PaketMasterHarga::where('id', $transaction->paket_master_harga_id)
            ->where('paket_master_id', $package->id)
            ->first();
    }

    if (! $variant) {
        $variant = $package->hargaMaster()->orderBy('id')->first();
    }

    $basePrice = $variant?->harga_dasar ?? 0;
    $newPorsi = max(1, (int) $request->jumlah_porsi);
    $manualPrice = $request->input('harga_penawaran');
    $subtotal = $manualPrice !== null && $manualPrice !== ''
        ? max(0, (float) $manualPrice)
        : ($basePrice > 0 ? $basePrice * $newPorsi : 0);
    $dp = round($subtotal * 0.1);
    $sisa = $subtotal - $dp;

    $transaction->update([
        'klien_id' => $request->klien_id,
        'paket_master_id' => $package->id,
        'paket_master_harga_id' => $variant?->id,
        'tanggal_acara' => $request->tanggal_acara,
        'jumlah_porsi' => $newPorsi,
        'lokasi_acara' => $request->lokasi_acara,
        'catatan' => $request->catatan,
        'subtotal' => $subtotal,
        'total_penawaran' => $subtotal,
        'nominal_dp' => $dp,
        'sisa_pelunasan' => $sisa,
    ]);

    return redirect()
        ->route('transaksi.index')
        ->with('success', 'Transaksi berhasil diperbarui.');
}
 
public function cancel($id)
{
    $transaction = TransaksiPenjualan::findOrFail($id);

    // Hapus semua data pembayaran
    $transaction->pembayaran()->delete();

    // Hapus semua detail transaksi
    $transaction->detail()->delete();

    // Hapus transaksi
    $transaction->delete();

    return redirect()->route('transaksi.index')
        ->with('success', 'Transaksi berhasil dihapus.');
} 
    public function storePayment(Request $request, TransaksiPenjualan $transaction)
    {
        $user = Auth::user();

        if ($user->isSales() && $transaction->sales_id !== $user->id) {
            abort(403);
        }

        $request->validate([
            'nominal_bayar' => 'required|numeric|min:1',
            'metode_pembayaran' => 'required|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        if ($transaction->status_transaksi == 'menunggu_dp') {

        if ($request->nominal_bayar < $transaction->nominal_dp) {
            return back()->with('error', 'Pembayaran DP harus minimal 10% dari total.');
        }

        // Nominal DP yang dibayar
        $amount = $transaction->nominal_dp;
        $paymentType = 'DP';

        // Kurangi sisa pelunasan
        $transaction->sisa_pelunasan =
            (float) $transaction->total_penawaran - (float) $amount;

        // Setelah DP dibayar, masuk tahap pelunasan
        $transaction->status_transaksi = 'menunggu_pelunasan';
    }

        else {

    if (now()->greaterThan($transaction->batas_pelunasan)) {
        return back()->with(
            'error',
            'Batas pelunasan telah lewat (H-30 sebelum acara).'
        );
    }

        if ($transaction->sisa_pelunasan <= 0) {
            return back()->with('error', 'Transaksi sudah lunas.');
        }

        $amount = min($transaction->sisa_pelunasan, $request->nominal_bayar);

        $paymentType = 'Pelunasan';

        $transaction->sisa_pelunasan =
            (float) max(0, (float) $transaction->sisa_pelunasan - (float) $amount);

        $transaction->status_transaksi =
            $transaction->sisa_pelunasan <= 0
                ? 'lunas'
                : 'menunggu_pelunasan';
    }

        Pembayaran::create([
            'transaksi_id' => $transaction->id,
            'kode_pembayaran' => 'PAY' . now()->format('YmdHis'),
            'jenis_pembayaran' => $paymentType,
            'metode_pembayaran' => $request->metode_pembayaran,
            'nominal_tagihan' => $amount,
            'nominal_bayar' => $amount,
            'tanggal_bayar' => now(),
            'status_pembayaran' => 'paid',
            'catatan' => $request->catatan,
        ]);

        $transaction->save();

        return redirect()->route('transaksi.show', $transaction)->with('success', 'Pembayaran berhasil dicatat.');
    }
}
