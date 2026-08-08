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
        Schema::create('target_penjualan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_id')->constrained('users')->cascadeOnDelete();
            $table->integer('bulan'); // 1-12
            $table->integer('tahun'); // 2026, 2027, etc
            $table->decimal('target_nominal', 15, 2); // Rp target
            $table->timestamps();

            // Unique constraint: satu sales, satu target per bulan/tahun
            $table->unique(['sales_id', 'bulan', 'tahun']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('target_penjualan');
    }
};
