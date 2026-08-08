<?php

namespace Tests\Feature;

use App\Models\Klien;
use App\Models\PaketMaster;
use App\Models\PaketMasterHarga;
use App\Models\TransaksiPenjualan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TransaksiPenjualanControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_updating_porsi_recalculates_total_penawaran_and_dp(): void
    {
        $sales = User::factory()->create(['role' => 'sales']);

        $client = Klien::create([
            'nama_klien' => 'Klien Edit',
            'email' => 'klien-edit@example.com',
            'no_hp' => '081234567890',
            'alamat' => 'Alamat Edit',
            'password' => Hash::make('password123'),
            'sales_id' => $sales->id,
        ]);

        $package = PaketMaster::create([
            'kode_paket' => 'PKT-001',
            'nama_paket' => 'Paket Uji',
            'deskripsi' => 'Deskripsi paket',
            'kategori_paket' => 'Test',
            'status_aktif' => true,
        ]);

        $variant = PaketMasterHarga::create([
            'paket_master_id' => $package->id,
            'nama_varian' => 'Varian 1',
            'harga_dasar' => 100000,
            'minimal_porsi' => 1,
            'maksimal_porsi' => 10,
            'keterangan' => 'Varian uji',
        ]);

        $transaction = TransaksiPenjualan::create([
            'kode_transaksi' => 'TRX-TEST-001',
            'sales_id' => $sales->id,
            'klien_id' => $client->id,
            'paket_master_id' => $package->id,
            'paket_master_harga_id' => $variant->id,
            'tanggal_transaksi' => now(),
            'tanggal_acara' => now()->addDay(),
            'jumlah_porsi' => 2,
            'lokasi_acara' => 'Lokasi Lama',
            'catatan' => 'Catatan Lama',
            'subtotal' => 200000,
            'diskon' => 0,
            'total_penawaran' => 200000,
            'nominal_dp' => 20000,
            'sisa_pelunasan' => 180000,
            'batas_pelunasan' => now()->addDays(10)->toDateString(),
            'status_transaksi' => 'menunggu_dp',
            'status_acara' => 'belum_berjalan',
        ]);

        $response = $this->actingAs($sales)->put(route('transaksi.update', $transaction), [
            'klien_id' => $client->id,
            'paket_master_id' => $package->id,
            'tanggal_acara' => now()->addDays(2)->toDateString(),
            'jumlah_porsi' => 5,
            'lokasi_acara' => 'Lokasi Baru',
            'catatan' => 'Catatan Baru',
        ]);

        $response->assertRedirect(route('transaksi.index'));

        $transaction->refresh();
        $this->assertSame(5, $transaction->jumlah_porsi);
        $this->assertSame(500000.0, (float) $transaction->total_penawaran);
        $this->assertSame(500000.0, (float) $transaction->subtotal);
        $this->assertSame(50000.0, (float) $transaction->nominal_dp);
        $this->assertSame(450000.0, (float) $transaction->sisa_pelunasan);
    }
    public function test_manual_price_override_is_saved_when_updating_transaction(): void
    {
        $sales = User::factory()->create(['role' => 'sales']);

        $client = Klien::create([
            'nama_klien' => 'Klien Edit Manual',
            'email' => 'klien-edit-manual@example.com',
            'no_hp' => '081234567891',
            'alamat' => 'Alamat Edit Manual',
            'password' => Hash::make('password123'),
            'sales_id' => $sales->id,
        ]);

        $package = PaketMaster::create([
            'kode_paket' => 'PKT-002',
            'nama_paket' => 'Paket Uji Manual',
            'deskripsi' => 'Deskripsi paket manual',
            'kategori_paket' => 'Test',
            'status_aktif' => true,
        ]);

        $variant = PaketMasterHarga::create([
            'paket_master_id' => $package->id,
            'nama_varian' => 'Varian Manual',
            'harga_dasar' => 80000,
            'minimal_porsi' => 1,
            'maksimal_porsi' => 8,
            'keterangan' => 'Varian uji manual',
        ]);

        $transaction = TransaksiPenjualan::create([
            'kode_transaksi' => 'TRX-TEST-002',
            'sales_id' => $sales->id,
            'klien_id' => $client->id,
            'paket_master_id' => $package->id,
            'paket_master_harga_id' => $variant->id,
            'tanggal_transaksi' => now(),
            'tanggal_acara' => now()->addDay(),
            'jumlah_porsi' => 3,
            'lokasi_acara' => 'Lokasi Lama',
            'catatan' => 'Catatan Lama',
            'subtotal' => 240000,
            'diskon' => 0,
            'total_penawaran' => 240000,
            'nominal_dp' => 24000,
            'sisa_pelunasan' => 216000,
            'batas_pelunasan' => now()->addDays(10)->toDateString(),
            'status_transaksi' => 'menunggu_dp',
            'status_acara' => 'belum_berjalan',
        ]);

        $response = $this->actingAs($sales)->put(route('transaksi.update', $transaction), [
            'klien_id' => $client->id,
            'paket_master_id' => $package->id,
            'paket_master_harga_id' => $variant->id,
            'tanggal_acara' => now()->addDays(2)->toDateString(),
            'jumlah_porsi' => 4,
            'lokasi_acara' => 'Lokasi Baru',
            'catatan' => 'Catatan Baru',
            'harga_penawaran' => 750000,
        ]);

        $response->assertRedirect(route('transaksi.index'));

        $transaction->refresh();
        $this->assertSame(750000.0, (float) $transaction->total_penawaran);
        $this->assertSame(750000.0, (float) $transaction->subtotal);
        $this->assertSame(75000.0, (float) $transaction->nominal_dp);
        $this->assertSame(675000.0, (float) $transaction->sisa_pelunasan);
    }}
