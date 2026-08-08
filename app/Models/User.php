<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function transaksi()
    {
        return $this->hasMany(TransaksiPenjualan::class, 'sales_id');
    }

    public function targets()
    {
        return $this->hasMany(TargetPenjualan::class, 'sales_id');
    }

    public function isSuperadmin(): bool
    {
        return $this->role === 'superadmin';
    }

    public function isOwner(): bool
    {
        return $this->role === 'pemilik';
    }

    public function isPemilik(): bool
    {
        return $this->role === 'pemilik';
    }

    public function isSales(): bool
    {
        return $this->role === 'sales';
    }
}
