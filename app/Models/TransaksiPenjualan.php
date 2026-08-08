<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TransaksiDetail;
use App\Models\Pembayaran;

class TransaksiPenjualan extends Model
{
    use HasFactory;

    protected $table = 'transaksi_penjualan';

    protected $fillable = [
    'kode_transaksi',
    'sales_id',
    'klien_id',
    'paket_master_id',
    'paket_master_harga_id',
    'target_penjualan_id',
    'tanggal_transaksi',
    'tanggal_acara',
    'jumlah_porsi',
    'lokasi_acara',
    'catatan',
    'subtotal',
    'diskon',
    'total_penawaran',
    'nominal_dp',
    'sisa_pelunasan',
    'batas_pelunasan',
    'status_transaksi',
    'status_acara',
];

    protected $casts = [
        'tanggal_transaksi' => 'date',
        'tanggal_acara' => 'date',
        'subtotal' => 'decimal:2',
        'diskon' => 'decimal:2',
        'total_penawaran' => 'decimal:2',
        'nominal_dp' => 'decimal:2',
        'sisa_pelunasan' => 'decimal:2',
        'batas_pelunasan' => 'date',
    ];

    public function sales()
    {
        return $this->belongsTo(User::class, 'sales_id');
    }

    public function klien()
    {
        return $this->belongsTo(Klien::class, 'klien_id');
    }

    public function paketMaster()
    {
        return $this->belongsTo(PaketMaster::class, 'paket_master_id');
    }

    public function targetPenjualan()
    {
        return $this->belongsTo(TargetPenjualan::class, 'target_penjualan_id');
    }

    public function paketMasterHarga()
    {
        return $this->belongsTo(PaketMasterHarga::class, 'paket_master_harga_id');
    }


    public function detail()
    {
        return $this->hasMany(TransaksiDetail::class, 'transaksi_id');
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'transaksi_id');
    }

    public function notifikasiPelunasan()
    {
        return $this->hasMany(NotifikasiPelunasan::class, 'transaksi_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status_transaksi) {
            'draft' => 'Draft',
            'menunggu_dp' => 'Menunggu DP',
            'dp_terbayar' => 'DP Terbayar',
            'menunggu_pelunasan' => 'Menunggu Pelunasan',
            'lunas' => 'Lunas',
            'batal' => 'Batal',
            default => 'Unknown',
        };
    }

    /**
     * Compute current transaction status based on payment history
     * This ensures status always matches the Riwayat Pembayaran
     */
    public function computeCurrentStatus(): string
    {
        $totalBerhasil = Pembayaran::where('transaksi_id', $this->id)
            ->where('status_pembayaran', 'berhasil')
            ->sum('nominal_bayar');

        $totalPending = Pembayaran::where('transaksi_id', $this->id)
            ->whereIn('status_pembayaran', ['pending', 'pending_verifikasi'])
            ->sum('nominal_bayar');

        $pembayaranPertama = Pembayaran::where('transaksi_id', $this->id)
            ->oldest('created_at')
            ->first();

        $dpBerhasil = Pembayaran::where('transaksi_id', $this->id)
            ->where('jenis_pembayaran', 'dp')
            ->where('status_pembayaran', 'berhasil')
            ->exists();

        // Determine status
        $status = 'menunggu_dp'; // default status

        if ($totalBerhasil >= $this->total_penawaran) {
            // Sudah lunas
            $status = 'lunas';
        } elseif ($dpBerhasil && $totalBerhasil > 0) {
            // DP sudah berhasil
            $status = 'dp_terbayar';
        } elseif ($totalBerhasil > 0) {
            // Ada pembayaran yang berhasil (bukan DP) tapi belum lunas
            $status = 'menunggu_pelunasan';
        } elseif ($totalPending > 0) {
            // Ada pembayaran pending, tapi belum berhasil
            // Jika pending adalah DP, tetap di menunggu_dp
            if ($pembayaranPertama && $pembayaranPertama->jenis_pembayaran === 'dp') {
                $status = 'menunggu_dp';
            }
        }

        return $status;
    }
}
