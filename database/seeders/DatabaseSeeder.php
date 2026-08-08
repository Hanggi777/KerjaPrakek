<?php

namespace Database\Seeders;

use App\Models\Klien;
use App\Models\PaketMaster;
use App\Models\PaketMasterHarga;
use App\Models\TargetPenjualan;
use App\Models\TransaksiPenjualan;
use App\Models\TransaksiDetail;
use App\Models\Pembayaran;
use App\Models\NotifikasiPelunasan;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // =====================================================
        // 1. BUAT USER INTERNAL (Superadmin, Pemilik, Sales)
        // =====================================================
        $superadmin = User::create([
            'name' => 'Admin System',
            'email' => 'admin@nabilla.local',
            'password' => Hash::make('password123'),
            'role' => 'superadmin',
        ]);

        $pemilik = User::create([
            'name' => 'Nabilla Catering',
            'email' => 'pemilik@nabilla.local',
            'password' => Hash::make('password123'),
            'role' => 'pemilik',
        ]);

        $sales1 = User::create([
            'name' => 'Rina Sales',
            'email' => 'rina@nabilla.local',
            'password' => Hash::make('password123'),
            'role' => 'sales',
        ]);

        $sales2 = User::create([
            'name' => 'Budi Sales',
            'email' => 'budi@nabilla.local',
            'password' => Hash::make('password123'),
            'role' => 'sales',
        ]);

        // =====================================================
        // 2. BUAT DATA KLIEN
        // =====================================================
        $klien1 = Klien::create([
            'nama_klien' => 'Adi Wijaya',
            'email' => 'adi.wijaya@email.com',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Merdeka No.10, Jakarta',
            'nama_perusahaan' => 'PT. Mitra Jaya',
            'password' => Hash::make('password123'),
            'status_aktif' => true,
        ]);

        $klien2 = Klien::create([
            'nama_klien' => 'Siti Nurhaliza',
            'email' => 'siti.nurhaliza@email.com',
            'no_hp' => '085678901234',
            'alamat' => 'Jl. Sudirman No.25, Bandung',
            'nama_perusahaan' => 'CV. Berkah Rezeki',
            'password' => Hash::make('password123'),
            'status_aktif' => true,
        ]);

        $klien3 = Klien::create([
            'nama_klien' => 'Ahmad Rahman',
            'email' => 'ahmad.rahman@email.com',
            'no_hp' => '082345678901',
            'alamat' => 'Jl. Gatot Subroto No.15, Surabaya',
            'nama_perusahaan' => null,
            'password' => Hash::make('password123'),
            'status_aktif' => true,
        ]);

        // =====================================================
        // 3. BUAT PAKET MASTER DAN HARGA MASTER
        // =====================================================

        // Paket 1: Pernikahan Silver
        $paketSilver = PaketMaster::create([
            'kode_paket' => 'PKT001',
            'nama_paket' => 'Paket Pernikahan Silver',
            'deskripsi' => 'Paket pernikahan yang elegan dengan menu pilihan utama dan dekorasi standar.',
            'kategori_paket' => 'Pernikahan',
            'status_aktif' => true,
        ]);

        PaketMasterHarga::create([
            'paket_master_id' => $paketSilver->id,
            'nama_varian' => 'Silver 300 Pax',
            'harga_dasar' => 35000,
            'minimal_porsi' => 300,
            'maksimal_porsi' => 400,
            'keterangan' => 'Untuk 300-400 tamu',
        ]);

        PaketMasterHarga::create([
            'paket_master_id' => $paketSilver->id,
            'nama_varian' => 'Silver 500 Pax',
            'harga_dasar' => 33000,
            'minimal_porsi' => 500,
            'maksimal_porsi' => 600,
            'keterangan' => 'Untuk 500-600 tamu',
        ]);

        // Paket 2: Pernikahan Gold
        $paketGold = PaketMaster::create([
            'kode_paket' => 'PKT002',
            'nama_paket' => 'Paket Pernikahan Gold',
            'deskripsi' => 'Paket pernikahan premium dengan berbagai pilihan menu dan dekorasi mewah.',
            'kategori_paket' => 'Pernikahan',
            'status_aktif' => true,
        ]);

        PaketMasterHarga::create([
            'paket_master_id' => $paketGold->id,
            'nama_varian' => 'Gold 300 Pax',
            'harga_dasar' => 50000,
            'minimal_porsi' => 300,
            'maksimal_porsi' => 400,
            'keterangan' => 'Untuk 300-400 tamu',
        ]);

        PaketMasterHarga::create([
            'paket_master_id' => $paketGold->id,
            'nama_varian' => 'Gold 500 Pax',
            'harga_dasar' => 48000,
            'minimal_porsi' => 500,
            'maksimal_porsi' => 600,
            'keterangan' => 'Untuk 500-600 tamu',
        ]);

        // Paket 3: Aqiqah
        $paketAqiqah = PaketMaster::create([
            'kode_paket' => 'PKT003',
            'nama_paket' => 'Paket Aqiqah',
            'deskripsi' => 'Paket aqiqah dengan menu pilihan nasi kuning, lauk, dan hidangan pelengkap.',
            'kategori_paket' => 'Acara Keluarga',
            'status_aktif' => true,
        ]);

        PaketMasterHarga::create([
            'paket_master_id' => $paketAqiqah->id,
            'nama_varian' => 'Aqiqah 100 Box',
            'harga_dasar' => 28000,
            'minimal_porsi' => 100,
            'maksimal_porsi' => 200,
            'keterangan' => 'Nasi kuning dalam box styrofoam',
        ]);

        // Paket 4: Nasi Box Kantoran
        $paketNasiBox = PaketMaster::create([
            'kode_paket' => 'PKT004',
            'nama_paket' => 'Paket Nasi Box Kantoran',
            'deskripsi' => 'Paket nasi box praktis untuk kebutuhan rapat, meeting, atau acara kantoran.',
            'kategori_paket' => 'Kantoran',
            'status_aktif' => true,
        ]);

        PaketMasterHarga::create([
            'paket_master_id' => $paketNasiBox->id,
            'nama_varian' => 'Nasi Box 50 Box',
            'harga_dasar' => 22000,
            'minimal_porsi' => 50,
            'maksimal_porsi' => 200,
            'keterangan' => 'Nasi dengan lauk pauk standar kantoran',
        ]);

        // Paket 5: Syukuran
        $paketSyukuran = PaketMaster::create([
            'kode_paket' => 'PKT005',
            'nama_paket' => 'Paket Syukuran',
            'deskripsi' => 'Paket syukuran dengan berbagai pilihan nasi, lauk, dan hidangan spesial.',
            'kategori_paket' => 'Acara Keluarga',
            'status_aktif' => true,
        ]);

        PaketMasterHarga::create([
            'paket_master_id' => $paketSyukuran->id,
            'nama_varian' => 'Syukuran 150 Pax',
            'harga_dasar' => 40000,
            'minimal_porsi' => 150,
            'maksimal_porsi' => 200,
            'keterangan' => 'Untuk 150-200 tamu',
        ]);

        // =====================================================
        // 4. BUAT TARGET PENJUALAN SALES (BULAN JULI 2026)
        // =====================================================
        $target1 = TargetPenjualan::create([
            'sales_id' => $sales1->id,
            'bulan' => 7,
            'tahun' => 2026,
            'target_nominal' => 50000000,
        ]);

        $target2 = TargetPenjualan::create([
            'sales_id' => $sales2->id,
            'bulan' => 7,
            'tahun' => 2026,
            'target_nominal' => 40000000,
        ]);

        // =====================================================
        // 5. BUAT TRANSAKSI PENJUALAN CONTOH
        // =====================================================

        // Transaksi 1
        $transaksi1 = TransaksiPenjualan::create([
            'kode_transaksi' => 'TRX-2026-0001',
            'sales_id' => $sales1->id,
            'klien_id' => $klien1->id,
            'paket_master_id' => $paketSilver->id,
            'target_penjualan_id' => $target1->id,
            'tanggal_transaksi' => now(),
            'tanggal_acara' => now()->addDays(60),
            'jumlah_porsi' => 350,
            'lokasi_acara' => 'Grand Ball Room, Jakarta',
            'catatan' => 'Penawaran khusus untuk klien.',
            'subtotal' => 12250000,
            'diskon' => 0,
            'total_penawaran' => 12250000,
            'nominal_dp' => 1225000,
            'sisa_pelunasan' => 11025000,
            'batas_pelunasan' => now()->addDays(30),
            'status_transaksi' => 'menunggu_dp',
            'status_acara' => 'belum_berjalan',
        ]);

        TransaksiDetail::create([
            'transaksi_id' => $transaksi1->id,
            'nama_item' => 'Paket Pernikahan Silver 350 Pax',
            'qty' => 350,
            'satuan' => 'porsi',
            'harga_satuan' => 35000,
            'subtotal' => 12250000,
            'tipe_item' => 'paket',
        ]);

        // Transaksi 2
        $transaksi2 = TransaksiPenjualan::create([
            'kode_transaksi' => 'TRX-2026-0002',
            'sales_id' => $sales2->id,
            'klien_id' => $klien2->id,
            'paket_master_id' => $paketAqiqah->id,
            'target_penjualan_id' => $target2->id,
            'tanggal_transaksi' => now(),
            'tanggal_acara' => now()->addDays(45),
            'jumlah_porsi' => 150,
            'lokasi_acara' => 'Rumah Keluarga Siti, Bandung',
            'catatan' => 'Aqiqah untuk calon bayi.',
            'subtotal' => 4200000,
            'diskon' => 200000,
            'total_penawaran' => 4000000,
            'nominal_dp' => 400000,
            'sisa_pelunasan' => 3600000,
            'batas_pelunasan' => now()->addDays(15),
            'status_transaksi' => 'dp_terbayar',
            'status_acara' => 'belum_berjalan',
        ]);

        TransaksiDetail::create([
            'transaksi_id' => $transaksi2->id,
            'nama_item' => 'Paket Aqiqah 150 Box',
            'qty' => 150,
            'satuan' => 'box',
            'harga_satuan' => 28000,
            'subtotal' => 4200000,
            'tipe_item' => 'paket',
        ]);

        // =====================================================
        // 6. BUAT DATA PEMBAYARAN
        // =====================================================

        Pembayaran::create([
            'transaksi_id' => $transaksi2->id,
            'kode_pembayaran' => 'PAY-2026-0001',
            'jenis_pembayaran' => 'dp',
            'metode_pembayaran' => 'transfer_bank',
            'nominal_tagihan' => 400000,
            'nominal_bayar' => 400000,
            'tanggal_bayar' => now()->subDays(15),
            'status_pembayaran' => 'berhasil',
            'bukti_pembayaran' => 'bukti_transfer_001.jpg',
            'catatan' => 'DP dari transfer bank',
        ]);

        Pembayaran::create([
            'transaksi_id' => $transaksi1->id,
            'kode_pembayaran' => 'PAY-2026-0002',
            'jenis_pembayaran' => 'dp',
            'metode_pembayaran' => 'transfer_bank',
            'nominal_tagihan' => 1225000,
            'nominal_bayar' => 0,
            'tanggal_bayar' => null,
            'status_pembayaran' => 'pending',
            'bukti_pembayaran' => null,
            'catatan' => 'Menunggu pembayaran DP',
        ]);

        Pembayaran::create([
            'transaksi_id' => $transaksi2->id,
            'kode_pembayaran' => 'PAY-2026-0003',
            'jenis_pembayaran' => 'pelunasan',
            'metode_pembayaran' => 'transfer_bank',
            'nominal_tagihan' => 3600000,
            'nominal_bayar' => 0,
            'tanggal_bayar' => null,
            'status_pembayaran' => 'pending',
            'bukti_pembayaran' => null,
            'catatan' => 'Menunggu pelunasan',
        ]);

        echo "\n ✅ Database berhasil di-seed dengan data dummy!\n";
        echo "   - 4 User Internal (1 Superadmin, 1 Pemilik, 2 Sales)\n";
        echo "   - 3 Data Klien\n";
        echo "   - 5 Paket Master dengan 8 varian harga\n";
        echo "   - 2 Target Penjualan\n";
        echo "   - 2 Transaksi Penjualan dengan detail\n";
        echo "   - 3 Data Pembayaran\n\n";
        echo " 📝 Login Internal:\n";
        echo "   Superadmin: admin@nabilla.local / password123\n";
        echo "   Pemilik: pemilik@nabilla.local / password123\n";
        echo "   Sales 1: rina@nabilla.local / password123\n";
        echo "   Sales 2: budi@nabilla.local / password123\n\n";
        echo " 🔑 Portal Klien Login:\n";
        echo "   Klien 1: adi.wijaya@email.com / password123\n";
        echo "   Klien 2: siti.nurhaliza@email.com / password123\n";
        echo "   Klien 3: ahmad.rahman@email.com / password123\n";
    }
}
