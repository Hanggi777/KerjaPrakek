<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaketMasterHarga extends Model
{
    use HasFactory;

    protected $table = 'paket_master_harga';

    protected $fillable = [
        'paket_master_id',
        'nama_varian',
        'harga_dasar',
        'minimal_porsi',
        'maksimal_porsi',
        'keterangan',
    ];

    public function paketMaster()
    {
        return $this->belongsTo(PaketMaster::class, 'paket_master_id');
    }
}
