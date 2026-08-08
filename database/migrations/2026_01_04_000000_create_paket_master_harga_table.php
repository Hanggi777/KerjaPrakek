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
        Schema::create('paket_master_harga', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paket_master_id')->constrained('paket_master')->cascadeOnDelete();
            $table->string('nama_varian'); // "Silver 300 Pax", "Gold 500 Pax", etc
            $table->decimal('harga_dasar', 15, 2); // Harga dasar/master
            $table->integer('minimal_porsi'); // Minimal 100 pax, 50 box, etc
            $table->integer('maksimal_porsi')->nullable(); // Maksimal porsi (bisa unlimited jika null)
            $table->text('keterangan')->nullable(); // Deskripsi varian
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paket_master_harga');
    }
};
