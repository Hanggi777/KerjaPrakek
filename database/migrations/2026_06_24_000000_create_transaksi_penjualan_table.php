<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksi_penjualan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique();
            $table->foreignId('sales_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('klien_id')->constrained('klien')->cascadeOnDelete();
            $table->foreignId('paket_master_id')->nullable()->constrained('paket_master')->cascadeOnDelete();
            $table->foreignId('target_penjualan_id')->nullable()->constrained('target_penjualan')->cascadeOnDelete();
            $table->dateTime('tanggal_transaksi');
            $table->dateTime('tanggal_acara');
            $table->unsignedInteger('jumlah_porsi');
            $table->string('lokasi_acara');
            $table->text('catatan')->nullable();
            $table->decimal('subtotal', 15, 2);
            $table->decimal('diskon', 15, 2)->default(0);
            $table->decimal('total_penawaran', 15, 2);
            $table->decimal('nominal_dp', 15, 2)->default(0);
            $table->decimal('sisa_pelunasan', 15, 2)->default(0);
            $table->date('batas_pelunasan')->nullable();
            $table->enum('status_transaksi', ['draft', 'menunggu_dp', 'dp_terbayar', 'menunggu_pelunasan', 'lunas', 'batal'])->default('draft');
            $table->enum('status_acara', ['belum_berjalan', 'berjalan', 'selesai'])->default('belum_berjalan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_penjualan');
    }
};
