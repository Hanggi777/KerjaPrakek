<?php

namespace App\Http\Controllers;

use App\Models\Klien;
use App\Models\TransaksiPenjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Pembayaran;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class KlienController extends Controller
{
    /**
     * Display a listing of klien
     */
    public function index()
    {
        $user = Auth::user();

        $query = Klien::with(['sales'])->withCount('transaksi')->latest();

        if ($user && $user->isSales()) {
            $query->where('sales_id', $user->id);
        }

        $klien = $query->paginate(15);

        return view('klien.index', compact('klien'));
    }

    /**
     * Show the form for creating a new klien
     */
    public function create()
    {
        return view('klien.create');
    }

    /**
     * Store a newly created klien in database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_klien' => 'required|string|max:255',
            'email' => 'required|email|unique:klien,email',
            'telepon' => 'required|string|max:20',
            'alamat' => 'required|string',
            'password' => 'required|min:6|confirmed',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        // Ubah telepon menjadi no_hp
        $validated['no_hp'] = $validated['telepon'];
        unset($validated['telepon']);

        $user = Auth::user();
        if ($user && $user->isSales()) {
            $validated['sales_id'] = $user->id;
        }

        Klien::create($validated);

        return redirect()->route('klien.index')
            ->with('success', 'Data klien berhasil ditambahkan!');
    }

    /**
     * Display the specified klien
     */
    public function show(Klien $klien)
    {
        $user = Auth::user();

        if ($user && $user->isSales() && $klien->sales_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke data klien ini.');
        }

        $transaksi = $klien->transaksi()
            ->with(['pembayaran', 'detail'])
            ->latest()
            ->paginate(10);
        
        $totalTransaksi = $klien->transaksi()->count();
        $totalTagihan = $klien->transaksi()
            ->where('status_transaksi', '!=', 'lunas')
            ->sum('total_penawaran');
        
        return view('klien.show', compact('klien', 'transaksi', 'totalTransaksi', 'totalTagihan'));
    }

    /**
     * Show the form for editing the specified klien
     */
    public function edit(Klien $klien)
    {
        $user = Auth::user();

        if ($user && $user->isSales() && $klien->sales_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke data klien ini.');
        }

        $salesList = User::where('role', 'sales')->orderBy('name')->get();

        return view('klien.edit', compact('klien', 'salesList'));
    }

    /**
     * Update the specified klien in database
     */
    public function update(Request $request, Klien $klien)
    {
        $user = Auth::user();

        if ($user && $user->isSales() && $klien->sales_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke data klien ini.');
        }

        $validated = $request->validate([
            'nama_klien' => 'required|string|max:255',
            'email' => 'required|email|unique:klien,email,' . $klien->id,
            'telepon' => 'required|string|max:20',
            'alamat' => 'required|string',
            'sales_id' => 'nullable|exists:users,id',
        ]);

       // Ubah telepon menjadi no_hp
        $validated['no_hp'] = $validated['telepon'];
        unset($validated['telepon']);

        if (!Auth::user()?->isSuperadmin()) {
            unset($validated['sales_id']);
        } elseif ($request->filled('sales_id')) {
            $validated['sales_id'] = $request->sales_id;
        } else {
            $validated['sales_id'] = null;
        }

        // Update password hanya jika diisi
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        $klien->update($validated);

        return redirect()->route('klien.show', $klien)
            ->with('success', 'Data klien berhasil diupdate!');
    }
        public function pemilikIndex()
    {
        $klien = Klien::latest()->paginate(10);

        return view('pemilik.klien.index', compact('klien'));
    }

    /**
     * Remove the specified klien from database
     */
    public function destroy(Klien $klien)
    {
        $user = Auth::user();

        if ($user && $user->isSales() && $klien->sales_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke data klien ini.');
        }

        // Check if klien has transactions
        if ($klien->transaksi()->count() > 0) {
            return redirect()->route('klien.index')
                ->with('error', 'Tidak bisa hapus klien yang memiliki transaksi!');
        }

        $klien->delete();

        return redirect()->route('klien.index')
            ->with('success', 'Data klien berhasil dihapus!');
    }
    public function detail($id)
{
    $transaksi = TransaksiPenjualan::with([
        'detail',
        'pembayaran',
        'paketMaster',
        'klien'
    ])->findOrFail($id);

    return view('klien.transaksi.show', compact('transaksi'));
}
public function detailPembayaran($id)
{
    $pembayaran = Pembayaran::with([
        'transaksi',
        'transaksi.klien'
    ])->findOrFail($id);

    return view(
        'klien.pembayaran.show',
        compact('pembayaran')
    );
}
}
