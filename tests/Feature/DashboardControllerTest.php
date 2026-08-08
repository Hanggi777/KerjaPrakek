<?php

namespace Tests\Feature;

use App\Models\Klien;
use App\Models\Pembayaran;
use App\Models\TransaksiPenjualan;
use App\Models\User;
use App\Http\Controllers\PembayaranController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_payment_history_is_created_only_when_payment_proof_is_submitted(): void
    {
        Storage::fake('public');

        $client = Klien::create([
            'nama_klien' => 'Klien Bayar',
            'email' => 'klien-bayar@example.com',
            'no_hp' => '081111111111',
            'alamat' => 'Alamat Bayar',
            'password' => Hash::make('password123'),
        ]);

        $sales = User::factory()->create(['role' => 'sales']);

        $transaction = TransaksiPenjualan::create([
            'kode_transaksi' => 'TRX-DP-001',
            'sales_id' => $sales->id,
            'klien_id' => $client->id,
            'paket_master_id' => null,
            'tanggal_transaksi' => now(),
            'tanggal_acara' => now()->addDay(),
            'jumlah_porsi' => 5,
            'lokasi_acara' => 'Lokasi',
            'catatan' => null,
            'subtotal' => 1000000,
            'diskon' => 0,
            'total_penawaran' => 1000000,
            'nominal_dp' => 100000,
            'sisa_pelunasan' => 900000,
            'batas_pelunasan' => now()->addDays(10)->toDateString(),
            'status_transaksi' => 'menunggu_dp',
            'status_acara' => 'belum_berjalan',
        ]);

        $response = $this->actingAs($client, 'klien')->post(route('klien.pembayaran.proses', $transaction->id), [
            'metode_pembayaran' => 'transfer_bank',
        ]);

        $response->assertRedirect();
        $this->assertSame(0, Pembayaran::where('transaksi_id', $transaction->id)->count());

        $uploadResponse = $this->actingAs($client, 'klien')->post(route('klien.pembayaran.transfer.store', $transaction->id), [
            'nominal_bayar' => 100000,
            'bank_tujuan' => 'mandiri',
            'bukti_pembayaran' => UploadedFile::fake()->create('bukti.jpg', 100, 'image/jpeg'),
        ]);

        $uploadResponse->assertRedirect();

        $payment = Pembayaran::where('transaksi_id', $transaction->id)->latest()->first();
        $this->assertNotNull($payment);
        $this->assertSame('dp', $payment->jenis_pembayaran);
        $this->assertSame(100000.0, (float) $payment->nominal_tagihan);
        $this->assertSame('pending', $payment->status_pembayaran);
    }

    public function test_transfer_payment_uses_database_compatible_method_value(): void
    {
        Storage::fake('public');

        $client = Klien::create([
            'nama_klien' => 'Klien Bayar 3',
            'email' => 'klien-bayar3@example.com',
            'no_hp' => '081111111113',
            'alamat' => 'Alamat Bayar 3',
            'password' => Hash::make('password123'),
        ]);

        $sales = User::factory()->create(['role' => 'sales']);

        $transaction = TransaksiPenjualan::create([
            'kode_transaksi' => 'TRX-DP-003',
            'sales_id' => $sales->id,
            'klien_id' => $client->id,
            'paket_master_id' => null,
            'tanggal_transaksi' => now(),
            'tanggal_acara' => now()->addDay(),
            'jumlah_porsi' => 5,
            'lokasi_acara' => 'Lokasi',
            'catatan' => null,
            'subtotal' => 1000000,
            'diskon' => 0,
            'total_penawaran' => 1000000,
            'nominal_dp' => 100000,
            'sisa_pelunasan' => 900000,
            'batas_pelunasan' => now()->addDays(10)->toDateString(),
            'status_transaksi' => 'menunggu_dp',
            'status_acara' => 'belum_berjalan',
        ]);

        $this->actingAs($client, 'klien')->post(route('klien.pembayaran.transfer.store', $transaction->id), [
            'nominal_bayar' => 100000,
            'bank_tujuan' => 'mandiri',
            'bukti_pembayaran' => UploadedFile::fake()->create('bukti.jpg', 100, 'image/jpeg'),
        ]);

        $payment = Pembayaran::where('transaksi_id', $transaction->id)->latest()->first();

        $this->assertNotNull($payment);
        $expectedMethod = DB::getDriverName() === 'sqlite' ? 'transfer' : 'transfer_bank';
        $this->assertSame($expectedMethod, $payment->metode_pembayaran);
    }

    public function test_second_client_payment_is_blocked_until_dp_is_confirmed(): void
    {
        Storage::fake('public');

        $client = Klien::create([
            'nama_klien' => 'Klien Bayar 2',
            'email' => 'klien-bayar2@example.com',
            'no_hp' => '081111111112',
            'alamat' => 'Alamat Bayar 2',
            'password' => Hash::make('password123'),
        ]);

        $sales = User::factory()->create(['role' => 'sales']);

        $transaction = TransaksiPenjualan::create([
            'kode_transaksi' => 'TRX-DP-002',
            'sales_id' => $sales->id,
            'klien_id' => $client->id,
            'paket_master_id' => null,
            'tanggal_transaksi' => now(),
            'tanggal_acara' => now()->addDay(),
            'jumlah_porsi' => 5,
            'lokasi_acara' => 'Lokasi',
            'catatan' => null,
            'subtotal' => 1000000,
            'diskon' => 0,
            'total_penawaran' => 1000000,
            'nominal_dp' => 100000,
            'sisa_pelunasan' => 900000,
            'batas_pelunasan' => now()->addDays(10)->toDateString(),
            'status_transaksi' => 'menunggu_dp',
            'status_acara' => 'belum_berjalan',
        ]);

        $this->actingAs($client, 'klien')->post(route('klien.pembayaran.proses', $transaction->id), [
            'metode_pembayaran' => 'transfer_bank',
        ]);

        $this->actingAs($client, 'klien')->post(route('klien.pembayaran.proses', $transaction->id), [
            'metode_pembayaran' => 'transfer_bank',
        ]);

        $this->assertSame(0, Pembayaran::where('transaksi_id', $transaction->id)->count());
    }

    public function test_transaction_status_updates_when_payment_confirmed(): void
    {
        Storage::fake('public');

        $client = Klien::create([
            'nama_klien' => 'Klien DP Payment',
            'email' => 'klien-dp@example.com',
            'no_hp' => '081111111114',
            'alamat' => 'Alamat DP',
            'password' => Hash::make('password123'),
        ]);

        $sales = User::factory()->create(['role' => 'sales']);

        $transaction = TransaksiPenjualan::create([
            'kode_transaksi' => 'TRX-STATUS-001',
            'sales_id' => $sales->id,
            'klien_id' => $client->id,
            'paket_master_id' => null,
            'tanggal_transaksi' => now(),
            'tanggal_acara' => now()->addDay(),
            'jumlah_porsi' => 5,
            'lokasi_acara' => 'Lokasi',
            'catatan' => null,
            'subtotal' => 1000000,
            'diskon' => 0,
            'total_penawaran' => 1000000,
            'nominal_dp' => 100000,
            'sisa_pelunasan' => 900000,
            'batas_pelunasan' => now()->addDays(10)->toDateString(),
            'status_transaksi' => 'menunggu_dp',
            'status_acara' => 'belum_berjalan',
        ]);

        // Submit DP payment
        $this->actingAs($client, 'klien')->post(route('klien.pembayaran.transfer.store', $transaction->id), [
            'nominal_bayar' => 100000,
            'bank_tujuan' => 'mandiri',
            'bukti_pembayaran' => UploadedFile::fake()->create('bukti.jpg', 100, 'image/jpeg'),
        ]);

        $payment = Pembayaran::where('transaksi_id', $transaction->id)->latest()->first();

        // Confirm payment
        $confirmResponse = $this->actingAs($client, 'klien')
            ->get(route('klien.pembayaran.konfirmasi', $payment->id));

        $confirmResponse->assertRedirect();

        // Refresh and verify transaction status updated
        $transaction->refresh();
        $this->assertSame('dp_terbayar', $transaction->status_transaksi);
        $this->assertSame(900000.0, (float) $transaction->sisa_pelunasan);

        $payment->refresh();
        $this->assertSame('berhasil', $payment->status_pembayaran);
    }

    public function test_transaction_status_remains_menunggu_dp_when_dp_payment_pending(): void
    {
        Storage::fake('public');

        $client = Klien::create([
            'nama_klien' => 'Klien Pending DP',
            'email' => 'klien-pending@example.com',
            'no_hp' => '081111111115',
            'alamat' => 'Alamat Pending',
            'password' => Hash::make('password123'),
        ]);

        $sales = User::factory()->create(['role' => 'sales']);

        $transaction = TransaksiPenjualan::create([
            'kode_transaksi' => 'TRX-PENDING-001',
            'sales_id' => $sales->id,
            'klien_id' => $client->id,
            'paket_master_id' => null,
            'tanggal_transaksi' => now(),
            'tanggal_acara' => now()->addDay(),
            'jumlah_porsi' => 5,
            'lokasi_acara' => 'Lokasi',
            'catatan' => null,
            'subtotal' => 1000000,
            'diskon' => 0,
            'total_penawaran' => 1000000,
            'nominal_dp' => 100000,
            'sisa_pelunasan' => 900000,
            'batas_pelunasan' => now()->addDays(10)->toDateString(),
            'status_transaksi' => 'menunggu_dp',
            'status_acara' => 'belum_berjalan',
        ]);

        // Submit DP payment (but don't confirm it yet)
        $this->actingAs($client, 'klien')->post(route('klien.pembayaran.transfer.store', $transaction->id), [
            'nominal_bayar' => 100000,
            'bank_tujuan' => 'mandiri',
            'bukti_pembayaran' => UploadedFile::fake()->create('bukti.jpg', 100, 'image/jpeg'),
        ]);

        // Refresh and verify transaction status still shows menunggu_dp (but payment is created as pending)
        $transaction->refresh();
        $this->assertSame('menunggu_dp', $transaction->status_transaksi);

        // Verify payment was created with pending status
        $payment = Pembayaran::where('transaksi_id', $transaction->id)->latest()->first();
        $this->assertNotNull($payment);
        $this->assertSame('pending', $payment->status_pembayaran);
    }

    public function test_transaction_status_recalculates_when_payment_deleted(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create(['role' => 'superadmin']);

        $client = Klien::create([
            'nama_klien' => 'Klien Delete Payment',
            'email' => 'klien-delete@example.com',
            'no_hp' => '081111111116',
            'alamat' => 'Alamat Delete',
            'password' => Hash::make('password123'),
        ]);

        $sales = User::factory()->create(['role' => 'sales']);

        $transaction = TransaksiPenjualan::create([
            'kode_transaksi' => 'TRX-DELETE-001',
            'sales_id' => $sales->id,
            'klien_id' => $client->id,
            'paket_master_id' => null,
            'tanggal_transaksi' => now(),
            'tanggal_acara' => now()->addDay(),
            'jumlah_porsi' => 5,
            'lokasi_acara' => 'Lokasi',
            'catatan' => null,
            'subtotal' => 1000000,
            'diskon' => 0,
            'total_penawaran' => 1000000,
            'nominal_dp' => 100000,
            'sisa_pelunasan' => 900000,
            'batas_pelunasan' => now()->addDays(10)->toDateString(),
            'status_transaksi' => 'menunggu_dp',
            'status_acara' => 'belum_berjalan',
        ]);

        // Create DP payment
        $dpPayment = Pembayaran::create([
            'kode_pembayaran' => 'PAY' . time(),
            'transaksi_id' => $transaction->id,
            'jenis_pembayaran' => 'dp',
            'metode_pembayaran' => 'cash',
            'nominal_tagihan' => 100000,
            'nominal_bayar' => 100000,
            'status_pembayaran' => 'berhasil',
            'tanggal_bayar' => now(),
        ]);

        // Manually update transaction status to simulate confirmation
        $controller = new PembayaranController();
        $reflectionMethod = new \ReflectionMethod($controller, 'updateTransactionStatus');
        $reflectionMethod->setAccessible(true);
        $reflectionMethod->invoke($controller, $transaction);

        // Verify transaction status updated to dp_terbayar
        $transaction->refresh();
        $this->assertSame('dp_terbayar', $transaction->status_transaksi);
        $this->assertSame(900000.0, (float) $transaction->sisa_pelunasan);

        // Admin deletes the payment via HTTP DELETE request
        $this->actingAs($admin)->delete(route('pembayaran.destroy', $dpPayment->id));

        // Verify transaction status reverts to menunggu_dp
        $transaction->refresh();
        $this->assertSame('menunggu_dp', $transaction->status_transaksi);
        $this->assertSame(1000000.0, (float) $transaction->sisa_pelunasan);
    }

    public function test_transaction_status_updates_when_admin_confirms_payment_via_kelola_pembayaran(): void
    {
        // Setup: Create admin, klien, transaksi, pembayaran
        $admin = User::factory()->create(['role' => 'pemilik']);

        $client = Klien::create([
            'nama_klien' => 'Admin Confirm Test',
            'email' => 'admin-confirm@example.com',
            'no_hp' => '081234567890',
            'alamat' => 'Test Address',
            'password' => Hash::make('password123'),
        ]);

        $sales = User::factory()->create(['role' => 'sales']);

        $transaction = TransaksiPenjualan::create([
            'kode_transaksi' => 'TRX-ADMIN-CONFIRM-001',
            'sales_id' => $sales->id,
            'klien_id' => $client->id,
            'paket_master_id' => null,
            'tanggal_transaksi' => now(),
            'tanggal_acara' => now()->addDay(),
            'jumlah_porsi' => 5,
            'lokasi_acara' => 'Test Location',
            'catatan' => null,
            'subtotal' => 1000000,
            'diskon' => 0,
            'total_penawaran' => 1000000,
            'nominal_dp' => 100000,
            'sisa_pelunasan' => 1000000,
            'batas_pelunasan' => now()->addDays(10)->toDateString(),
            'status_transaksi' => 'menunggu_dp',
        ]);

        // Create DP payment with status pending (as if client submitted bukti)
        $dpPayment = Pembayaran::create([
            'transaksi_id' => $transaction->id,
            'kode_pembayaran' => 'PEMBAYARAN-DP-001',
            'jenis_pembayaran' => 'dp',
            'nominal_tagihan' => 100000,
            'nominal_bayar' => 100000,
            'metode_pembayaran' => 'transfer',
            'status_pembayaran' => 'pending',
            'bukti_pembayaran' => 'path/to/proof.jpg',
        ]);

        // Verify initial status is menunggu_dp
        $transaction->refresh();
        $this->assertSame('menunggu_dp', $transaction->status_transaksi);

        // Admin updates payment status to berhasil via Kelola Pembayaran edit form
        $this->actingAs($admin)->put(route('pembayaran.update', $dpPayment->id), [
            'metode_pembayaran' => 'transfer',
            'status_pembayaran' => 'berhasil',
            'catatan' => 'DP confirmed by admin',
        ]);

        // Verify transaction status automatically updated to dp_terbayar
        $transaction->refresh();
        $this->assertSame('dp_terbayar', $transaction->status_transaksi);
        $this->assertSame(900000.0, (float) $transaction->sisa_pelunasan);

        // Verify payment status is berhasil
        $dpPayment->refresh();
        $this->assertSame('berhasil', $dpPayment->status_pembayaran);
    }
}
