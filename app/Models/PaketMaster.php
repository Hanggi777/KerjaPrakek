<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaketMaster extends Model
{
    use HasFactory;

    protected $table = 'paket_master';

    protected $fillable = [
        'kode_paket',
        'nama_paket',
        'deskripsi',
        'kategori_paket',
        'status_aktif',
    ];

    public function hargaMaster()
    {
        return $this->hasMany(PaketMasterHarga::class, 'paket_master_id');
    }

    public function transaksi()
    {
        return $this->hasMany(TransaksiPenjualan::class, 'paket_master_id');
    }
}
