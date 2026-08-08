<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Klien extends Authenticatable
{
    use HasFactory;

    protected $table = 'klien';

    protected $fillable = [
        'nama_klien',
        'email',
        'no_hp',
        'alamat',
        'nama_perusahaan',
        'password',
        'status_aktif',
        'sales_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'status_aktif' => 'boolean',
    ];

    public function transaksi()
    {
        return $this->hasMany(TransaksiPenjualan::class, 'klien_id');
    }

    public function sales()
    {
        return $this->belongsTo(User::class, 'sales_id');
    }

    public function latestPayment()
    {
        return $this->hasOneThrough(Pembayaran::class, TransaksiPenjualan::class, 'klien_id', 'transaksi_id', 'id', 'id')->latestOfMany();
    }
}
