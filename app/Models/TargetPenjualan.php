<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TargetPenjualan extends Model
{
    use HasFactory;

    protected $table = 'target_penjualan';

    protected $fillable = [
        'sales_id',
        'bulan',
        'tahun',
        'target_nominal',
    ];

    protected $casts = [
        'target_nominal' => 'decimal:2',
    ];

    public function sales()
    {
        return $this->belongsTo(User::class, 'sales_id');
    }

    public function transaksi()
    {
        return $this->hasMany(TransaksiPenjualan::class, 'target_penjualan_id');
    }
}
