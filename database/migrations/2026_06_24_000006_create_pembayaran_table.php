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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();

            $table->foreignId('transaksi_id')
                ->constrained('transaksi_penjualan')
                ->cascadeOnDelete();

            $table->string('kode_pembayaran')->unique();

            // nanti controller yang menentukan otomatis
            $table->enum('jenis_pembayaran', [
                'dp',
                'pembayaran',
                'pelunasan'
            ]);

            $table->enum('metode_pembayaran', [
                'cash',
                'transfer',
            ])->nullable();

            // total tagihan saat pembayaran dibuat
            $table->decimal('nominal_tagihan', 15, 2);

            // nominal yang dibayar klien
            $table->decimal('nominal_bayar', 15, 2)->default(0);

            $table->dateTime('tanggal_bayar')->nullable();

            // tambahkan status pending_verifikasi
            $table->enum('status_pembayaran', [
                'pending',
                'pending_verifikasi',
                'berhasil',
                'gagal'
            ])->default('pending');


            // bukti transfer
            $table->string('bukti_pembayaran')->nullable();

            // kapan bukti diupload
            $table->dateTime('tanggal_upload')->nullable();

            $table->text('catatan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
