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
        Schema::create('transaksi_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_id')->constrained('transaksi_penjualan')->cascadeOnDelete();
            $table->string('nama_item');
            $table->unsignedInteger('qty');
            $table->string('satuan')->default('pcs');
            $table->decimal('harga_satuan', 20, 2);
            $table->decimal('subtotal', 20, 2);
            $table->string('tipe_item');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_detail');
    }
};
