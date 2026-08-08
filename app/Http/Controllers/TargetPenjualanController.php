<?php

namespace App\Http\Controllers;

use App\Models\TargetPenjualan;
use App\Models\User;
use Illuminate\Http\Request;

class TargetPenjualanController extends Controller
{
    public function index()
    {
        $targets = TargetPenjualan::with('sales')->orderByDesc('tahun')->orderByDesc('bulan')->get();

        return view('target.index', compact('targets'));
    }

    public function create()
    {
        $sales = User::where('role', 'sales')->orderBy('name')->get();

        return view('target.create', compact('sales'));
    }

    public function store(Request $request)
    {
        $attributes = $request->validate([
            'sales_id' => 'required|exists:users,id',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2024',
            'target_nominal' => 'required|numeric|min:0',
        ]);

        TargetPenjualan::create($attributes);

        return redirect()->route('target.index')->with('success', 'Target penjualan berhasil disimpan.');
    }

    public function edit(TargetPenjualan $target)
    {
        $sales = User::where('role', 'sales')->orderBy('name')->get();

        return view('target.edit', compact('target', 'sales'));
    }

    public function update(Request $request, TargetPenjualan $target)
    {
        $attributes = $request->validate([
            'sales_id' => 'required|exists:users,id',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2024',
            'target_nominal' => 'required|numeric|min:0',
        ]);

        $target->update($attributes);

        return redirect()->route('target.index')->with('success', 'Target penjualan berhasil diperbarui.');
    }
}
