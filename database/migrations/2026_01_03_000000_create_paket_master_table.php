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
        Schema::create('paket_master', function (Blueprint $table) {
            $table->id();
            $table->string('kode_paket')->unique(); // PKT001, PKT002, etc
            $table->string('nama_paket');
            $table->text('deskripsi')->nullable();
            $table->string('kategori_paket'); // Pernikahan, Aqiqah, Corporate, etc
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paket_master');
    }
};
