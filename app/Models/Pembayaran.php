<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';

    protected $fillable = [
        'transaksi_id',
        'kode_pembayaran',
        'jenis_pembayaran',
        'metode_pembayaran',
        'nominal_tagihan',
        'nominal_bayar',
        'tanggal_bayar',
        'status_pembayaran',
        'bukti_pembayaran',
        'catatan',
        'bank_tujuan',
    ];

    protected $casts = [
        'nominal_tagihan' => 'decimal:2',
        'nominal_bayar' => 'decimal:2',
        'tanggal_bayar' => 'datetime',
    ];

    public function transaksi()
    {
        return $this->belongsTo(TransaksiPenjualan::class, 'transaksi_id');
    }
}
