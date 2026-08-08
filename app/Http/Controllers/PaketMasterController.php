<?php

namespace App\Http\Controllers;

use App\Models\PaketMaster;
use App\Models\PaketMasterHarga;
use Illuminate\Http\Request;

class PaketMasterController extends Controller
{
    public function index()
    {
        $packages = PaketMaster::with('hargaMaster')
            ->orderBy('nama_paket')
            ->get();

        return view('paket.index', compact('packages'));
    }

    public function create()
    {
        return view('paket.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_paket'      => 'required|unique:paket_master,kode_paket',
            'nama_paket'      => 'required',
            'kategori_paket'  => 'required',
            'deskripsi'       => 'nullable',
            'status_aktif'    => 'nullable',
        ]);

        $package = PaketMaster::create([
            'kode_paket'      => $request->kode_paket,
            'nama_paket'      => $request->nama_paket,
            'kategori_paket'  => $request->kategori_paket,
            'deskripsi'       => $request->deskripsi,
            'status_aktif'    => $request->has('status_aktif'),
        ]);

        if ($request->filled('harga')) {

            $package->hargaMaster()->create([

                'nama_varian'     => $request->nama_varian,

                'harga_dasar'     => $request->harga,

                'minimal_porsi'   => $request->minimal_porsi,

                'maksimal_porsi'  => $request->maksimal_porsi,

                'keterangan'      => $request->keterangan,

            ]);
        }

        return redirect()
            ->route('paket.index')
            ->with('success','Paket berhasil ditambahkan.');
    }

    public function show(PaketMaster $paket)
    {
        $paket->load('hargaMaster');

        return view('paket.show',[
            'package'=>$paket
        ]);
    }

    public function edit(PaketMaster $paket)
    {
        $paket->load('hargaMaster');

        return view('paket.edit',[
            'package'=>$paket
        ]);
    }

    public function update(Request $request,PaketMaster $paket)
    {
        $request->validate([

            'nama_paket'=>'required',

            'kategori_paket'=>'required',

            'deskripsi'=>'nullable',

        ]);

        $paket->update([

            'nama_paket'=>$request->nama_paket,

            'kategori_paket'=>$request->kategori_paket,

            'deskripsi'=>$request->deskripsi,

            'status_aktif'=>$request->has('status_aktif'),

        ]);

        return redirect()
            ->route('paket.index')
            ->with('success','Paket berhasil diperbarui.');
    }

    public function destroy(PaketMaster $paket)
    {
        if($paket->transaksi()->count()>0){

            return redirect()
                ->route('paket.index')
                ->with('error','Paket sudah dipakai transaksi sehingga tidak bisa dihapus.');

        }

        $paket->hargaMaster()->delete();

        $paket->delete();

        return redirect()
            ->route('paket.index')
            ->with('success','Paket berhasil dihapus.');
    }

    /*
    |--------------------------------------------------------------------------
    | VARIAN HARGA
    |--------------------------------------------------------------------------
    */

    public function storeHarga(Request $request,PaketMaster $paket)
    {
        $request->validate([

            'nama_varian'=>'required',

            'harga_dasar'=>'required|numeric',

            'minimal_porsi'=>'nullable|numeric',

            'maksimal_porsi'=>'nullable|numeric',

            'keterangan'=>'nullable',

        ]);

        $paket->hargaMaster()->create([

            'nama_varian'=>$request->nama_varian,

            'harga_dasar'=>$request->harga_dasar,

            'minimal_porsi'=>$request->minimal_porsi,

            'maksimal_porsi'=>$request->maksimal_porsi,

            'keterangan'=>$request->keterangan,

        ]);

        return back()->with('success','Varian berhasil ditambahkan.');
    }

    public function updateHarga(Request $request,PaketMaster $paket,PaketMasterHarga $harga)
    {
        if($harga->paket_master_id != $paket->id){

            abort(404);

        }

        $request->validate([

            'nama_varian'=>'required',

            'harga_dasar'=>'required|numeric',

            'minimal_porsi'=>'nullable|numeric',

            'maksimal_porsi'=>'nullable|numeric',

            'keterangan'=>'nullable',

        ]);

        $harga->update([

            'nama_varian'=>$request->nama_varian,

            'harga_dasar'=>$request->harga_dasar,

            'minimal_porsi'=>$request->minimal_porsi,

            'maksimal_porsi'=>$request->maksimal_porsi,

            'keterangan'=>$request->keterangan,

        ]);

        return back()->with('success','Varian berhasil diperbarui.');
    }
    

    public function deleteVariant(PaketMaster $paket,PaketMasterHarga $harga)
    {
        if($harga->paket_master_id != $paket->id){

            abort(404);

        }

        $harga->delete();

        return back()->with('success','Varian berhasil dihapus.');
    }
}