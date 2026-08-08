<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotifikasiPelunasan extends Model
{
    use HasFactory;

    protected $table = 'notifikasi_pelunasan';

    protected $fillable = [
        'transaksi_id',
        'tipe_notifikasi',
        'isi_notifikasi',
        'tanggal_kirim',
        'status_baca',
    ];

    protected $casts = [
        'tanggal_kirim' => 'datetime',
        'status_baca' => 'boolean',
    ];

    public function transaksi()
    {
        return $this->belongsTo(TransaksiPenjualan::class, 'transaksi_id');
    }
}
