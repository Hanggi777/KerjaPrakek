<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi_penjualan', function (Blueprint $table) {
            $table->foreignId('paket_master_harga_id')
                  ->nullable()
                  ->after('paket_master_id')
                  ->constrained('paket_master_harga')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('transaksi_penjualan', function (Blueprint $table) {
            $table->dropForeign(['paket_master_harga_id']);
            $table->dropColumn('paket_master_harga_id');
        });
    }
};
