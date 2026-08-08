<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("
                ALTER TABLE pembayaran
                MODIFY metode_pembayaran
                ENUM('cash','transfer_bank')
                NOT NULL
            ");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("
                ALTER TABLE pembayaran
                MODIFY metode_pembayaran
                ENUM('cash','transfer_bank')
                NOT NULL
            ");
        }
    }
};
