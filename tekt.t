Bertindaklah sebagai Senior Full-Stack Developer spesialis Laravel 11, PHP 8, MySQL, dan Bootstrap 5 yang memiliki keahlian UI/UX Design yang sangat baik.

Buatkan kode untuk aplikasi “Sistem Monitoring Sales Performance Berbasis Web dengan Fitur Real-Time Tracking Target Penjualan Bulanan pada NABILLA CATERING” berdasarkan alur sistem yang telah disusun dari Use Case Diagram, Activity Diagram, Sequence Diagram, ERD, Class Diagram, dan Deployment Diagram.

Aplikasi ini TIDAK BOLEH polos. Wajib menggunakan Bootstrap 5 UI modern seperti:

Cards
Shadows
Badges
Alerts
Gradient
Progress Bar
Modal
Table Responsive
Bootstrap Icons via CDN
1) KONSEP SISTEM YANG WAJIB DIIKUTI

Aplikasi ini adalah sistem monitoring performa penjualan sales catering yang juga menangani proses:

pengelolaan data klien,
pengelolaan paket & harga master,
pembuatan transaksi penjualan oleh sales,
pembayaran DP dan pelunasan,
integrasi Midtrans,
serta portal klien untuk melihat transaksi dan status pembayarannya.

Sistem harus mendukung tracking target penjualan bulanan secara real-time untuk memantau performa sales dan omzet perusahaan.

2) AKTOR / ROLE SISTEM

Sistem memiliki 4 aktor utama:

1. Superadmin

Aktor internal dengan akses tertinggi untuk mengelola sistem secara menyeluruh.

2. Pemilik

Aktor internal yang fokus pada monitoring bisnis, monitoring transaksi, monitoring pembayaran, dashboard performa sales, dan mengelola Paket & Harga Master.

3. Sales / Staf Internal

Aktor internal yang menangani klien, membuat transaksi penjualan, menyusun penawaran berdasarkan paket master, serta memantau target penjualan pribadinya.

4. Klien

Aktor eksternal yang memiliki akun portal klien dan hanya dapat melihat data transaksi miliknya sendiri, paket yang dipilih, status pembayaran, tagihan, dan riwayat pembayaran.

3) MODUL UTAMA SISTEM

Sistem harus memiliki modul berikut:

Login / Lupa Password
Monitoring & Dashboard
Data Klien
Paket & Harga Master
Transaksi Penjualan
Pembayaran
Integrasi Midtrans
Portal Klien
Manajemen User Internal (khusus Superadmin)
4) PEMBAGIAN HAK AKSES TIAP ROLE
A. SUPERADMIN

Superadmin dapat mengakses:

Login internal
Dashboard Superadmin
Manajemen user internal (Superadmin / Pemilik / Sales)
Kelola data klien
Melihat data paket & harga master
Melihat transaksi penjualan
Melihat pembayaran
Monitoring dashboard keseluruhan
Pengaturan sistem bila diperlukan

Catatan:
Superadmin adalah aktor internal tertinggi.
Namun Paket & Harga Master secara bisnis dikelola oleh Pemilik, bukan oleh Sales dan bukan oleh Klien.

B. PEMILIK

Pemilik dapat mengakses:

Login internal
Dashboard Pemilik
Monitoring performa seluruh sales
Monitoring target penjualan bulanan
Monitoring omzet
Monitoring transaksi dan pembayaran
Kelola Paket & Harga Master
Melihat data klien
Melihat reminder pelunasan H-30
Melihat status transaksi semua klien
Modul khusus Pemilik: Paket & Harga Master

Fitur ini hanya dapat diakses oleh Pemilik (dan boleh dipantau Superadmin bila diperlukan), bukan oleh Sales dan bukan oleh Klien.

Fungsi modul ini adalah untuk mengelola paket catering master/asli beserta harga dasar/master yang nantinya menjadi acuan Sales saat membuat penawaran transaksi ke klien.

C. SALES / STAF INTERNAL

Sales dapat mengakses:

Login internal
Dashboard Sales
Kelola data klien
Membuat transaksi penjualan
Memilih paket master sebagai acuan
Menentukan harga penawaran untuk klien
Menambahkan detail item transaksi
Melihat status pembayaran transaksi yang ditanganinya
Memantau target penjualan bulanannya sendiri
Melihat reminder transaksi yang belum lunas
Catatan penting untuk Sales:

Sales tidak mengelola Paket & Harga Master.
Sales hanya mengambil paket master dan harga dasar sebagai referensi, lalu saat membuat transaksi penjualan, Sales dapat membuat harga penawaran yang disesuaikan dengan kebutuhan klien.

D. KLIEN

Klien dapat mengakses:

Login Portal Klien menggunakan email yang sudah terdaftar pada data klien
Dashboard Portal Klien
Melihat transaksi miliknya sendiri
Melihat paket yang dipilih
Melihat detail penawaran
Melihat status pembayaran
Melihat tagihan
Melihat riwayat pembayaran
Melihat peringatan pelunasan H-30 sebelum acara
Batasan akses Klien:

Klien tidak boleh:

mengelola data klien,
mengelola paket master,
membuat transaksi,
mengubah pembayaran,
mengakses data klien lain,
mengakses dashboard internal.

Portal klien bersifat read-only / monitoring transaksi milik sendiri.

5) PEMISAHAN YANG WAJIB JELAS: HARGA MASTER vs HARGA PENAWARAN

Sistem harus membedakan secara tegas antara Harga Master Paket dan Harga Penawaran.

A. HARGA MASTER PAKET

Dikelola oleh Pemilik pada menu Paket & Harga Master.

Isi data:

daftar paket catering master/asli,
kategori paket,
varian harga,
harga dasar paket,
minimal porsi,
maksimal porsi,
keterangan paket.

Harga master ini menjadi acuan dasar untuk transaksi penjualan.

B. HARGA PENAWARAN

Digunakan oleh Sales saat membuat Transaksi Penjualan.

Harga penawaran:

berasal dari paket master,
boleh disesuaikan berdasarkan kebutuhan klien,
dapat berubah karena:
jumlah porsi,
tambahan menu,
tambahan dekorasi,
tambahan layanan,
permintaan custom,
lokasi acara,
kebutuhan acara tertentu.

Jadi alurnya harus seperti ini:

Pemilik membuat paket master & harga dasar
Sales membuka menu transaksi penjualan
Sales memilih klien
Sales memilih paket master
Sistem mengambil data harga dasar paket master
Sales menyesuaikan item penawaran jika diperlukan
Sistem menghitung subtotal dan total penawaran
Transaksi disimpan sebagai transaksi penjualan klien
6) ALUR TRANSAKSI PENJUALAN HARUS MELIBATKAN 2 AKTOR

Transaksi penjualan pada sistem ini melibatkan 2 aktor utama, yaitu:

1. Sales

Sales membuat transaksi penjualan, menyusun penawaran, dan memonitor pembayaran transaksi tersebut.

2. Klien

Klien tidak membuat transaksi, tetapi klien dapat masuk ke portal untuk melihat transaksi miliknya, paket yang dipilih, tagihan, status pembayaran, dan reminder pelunasan.

Jadi transaksi memang dibuat oleh Sales, tetapi ditampilkan juga ke Klien melalui Portal Klien.

7) ATURAN BISNIS PEMBAYARAN

Sistem pembayaran memiliki aturan bisnis berikut:

1. Pembayaran awal adalah DP 10%

Setiap transaksi penjualan yang disetujui memiliki pembayaran awal berupa DP sebesar 10% dari total transaksi / total penawaran.

Rumus:

DP = 10% x total_penawaran

Contoh:

total penawaran = Rp 20.000.000
DP = Rp 2.000.000
sisa pelunasan = Rp 18.000.000
2. Sisa pembayaran adalah pelunasan

Setelah DP dibayarkan, status transaksi berubah menjadi menunggu pelunasan sampai seluruh sisa pembayaran dilunasi.

3. Pelunasan wajib maksimal H-30 sebelum acara

Sistem harus memiliki aturan:

Pelunasan wajib dilakukan maksimal 1 bulan / H-30 sebelum tanggal acara.

Artinya, jika tanggal acara adalah 30 Desember 2026, maka pelunasan harus sudah selesai paling lambat 30 November 2026.

4. Reminder otomatis H-30

Jika transaksi belum lunas dan tanggal acara sudah mendekati H-30, sistem harus menampilkan alert / reminder pada:

Dashboard Pemilik
Dashboard Sales
Dashboard Portal Klien

Reminder ini bisa ditampilkan dalam bentuk:

alert warning,
badge status,
notifikasi reminder pelunasan.
8) PORTAL KLIEN

Portal klien harus disediakan dengan aturan berikut:

Login Klien

Klien login menggunakan:

email yang sudah terdaftar pada tabel data klien
password portal klien

Akun klien tidak dibuat lewat registrasi umum, melainkan berasal dari data klien yang sudah didaftarkan di sistem internal.

Portal Klien hanya menampilkan data berikut:
daftar transaksi milik klien tersebut
paket yang dipilih
detail penawaran
total tagihan
status transaksi
status DP
status pelunasan
riwayat pembayaran
reminder H-30 jika belum lunas

Portal klien tidak boleh menampilkan data klien lain.

9) REAL-TIME TRACKING TARGET PENJUALAN BULANAN

Aplikasi ini harus memiliki fitur Monitoring Sales Performance secara real-time.

Fitur ini terutama digunakan oleh:

Pemilik
Sales
dan dapat dipantau juga oleh Superadmin

Sistem harus dapat menampilkan:

target bulanan tiap sales
total transaksi bulan berjalan
total deal closing
total omzet bulan berjalan
progress pencapaian target dalam persen
progress bar target
badge status target
notifikasi bila target belum tercapai / hampir tercapai / sudah tercapai
10) TAHAP PENGERJAAN YANG DIMINTA

Keluarkan kode secara bertahap agar tidak terpotong, dimulai dari TAHAP 1 dan TAHAP 2 terlebih dahulu.

TAHAP 1 — KONFIGURASI, ANALISIS ROLE, DAN DATABASE

Pada TAHAP 1, buatkan secara lengkap, detail, dan rapi:

A. Analisis role dan hak akses

Jelaskan hak akses untuk:

Superadmin
Pemilik
Sales
Klien
B. Contoh konfigurasi .env

Berikan contoh konfigurasi database Laravel 11 untuk MySQL.

C. Struktur tabel database lengkap

Buat struktur tabel sesuai kebutuhan sistem.

D. Migration Laravel 11

Buat file migration lengkap.

E. Model Eloquent lengkap

Setiap model harus memiliki:

$fillable
relasi antar model
casting jika diperlukan
F. Seeder dummy data

Buat dummy data Paket & Harga Master agar bisa langsung dipakai sebagai contoh.

Dummy paket master minimal berisi contoh seperti:
Paket Prasmanan Silver
Paket Prasmanan Gold
Paket Wedding Premium
Paket Aqiqah
Paket Nasi Box Kantoran
Dummy harga master minimal berisi contoh seperti:
Silver 100 porsi – Rp xxx
Gold 200 porsi – Rp xxx
Wedding 500 porsi – Rp xxx
dst.
G. Penjelasan alur data antar tabel

Jelaskan bagaimana data mengalir dari:

user / sales
klien
paket master
transaksi
transaksi detail
pembayaran
target penjualan
reminder pelunasan
H. Penjelasan logika bisnis

Jelaskan juga:

alasan pemisahan paket master dan harga penawaran
bagaimana sistem menghitung DP 10%
bagaimana sistem menghitung sisa pelunasan
bagaimana sistem mendeteksi H-30 sebelum acara
bagaimana transaksi klien ditampilkan di portal klien
11) STRUKTUR DATABASE MINIMAL YANG WAJIB ADA
1. Tabel users

Untuk akun login internal (Superadmin, Pemilik, Sales)

Field minimal:

id
name
email
password
role (superadmin, pemilik, sales)
created_at
updated_at
2. Tabel klien

Untuk data klien sekaligus akun Portal Klien

Field minimal:

id
nama_klien
email
no_hp
alamat
nama_perusahaan / instansi (nullable)
password
status_aktif
created_at
updated_at
3. Tabel target_penjualan

Untuk target bulanan setiap sales

Field minimal:

id
sales_id (FK ke users)
bulan
tahun
target_nominal
created_at
updated_at
4. Tabel paket_master

Dikelola oleh Pemilik

Field minimal:

id
kode_paket
nama_paket
deskripsi
kategori_paket
status_aktif
created_at
updated_at
5. Tabel paket_master_harga

Harga dasar/master paket

Field minimal:

id
paket_master_id (FK)
nama_varian / nama_harga
harga_dasar
minimal_porsi
maksimal_porsi (nullable)
keterangan
created_at
updated_at
6. Tabel transaksi_penjualan

Dibuat oleh Sales

Field minimal:

id
kode_transaksi
sales_id (FK ke users)
klien_id (FK)
paket_master_id (FK, nullable jika custom penuh)
target_penjualan_id (nullable)
tanggal_transaksi
tanggal_acara
jumlah_porsi
lokasi_acara
catatan
subtotal
diskon
total_penawaran
nominal_dp
sisa_pelunasan
batas_pelunasan
status_transaksi (draft, menunggu_dp, dp_terbayar, menunggu_pelunasan, lunas, batal)
status_acara (belum_berjalan, berjalan, selesai)
created_at
updated_at

Catatan penting:
Karena sistem punya aturan DP 10% dan reminder H-30, maka pada tabel transaksi disarankan langsung menyimpan:

nominal_dp
sisa_pelunasan
batas_pelunasan
7. Tabel transaksi_detail

Untuk detail item penawaran transaksi

Field minimal:

id
transaksi_id (FK)
nama_item
qty
satuan
harga_satuan
subtotal
tipe_item (paket, tambahan, custom)
created_at
updated_at
8. Tabel pembayaran

Untuk data pembayaran transaksi

Field minimal:

id
transaksi_id (FK)
kode_pembayaran
jenis_pembayaran (dp, pelunasan)
metode_pembayaran (cash, transfer, midtrans)
nominal_tagihan
nominal_bayar
tanggal_bayar (nullable)
status_pembayaran (pending, berhasil, gagal)
midtrans_order_id (nullable)
bukti_pembayaran (nullable)
catatan
created_at
updated_at
9. Tabel reminder_pelunasan

Untuk alert / notifikasi pelunasan H-30

Field minimal:

id
transaksi_id
tipe_notifikasi
isi_notifikasi
tanggal_kirim
status_baca
created_at
updated_at
12) TAHAP 2 — AUTHENTICATION, LAYOUT, DASHBOARD, DAN UI DASAR

Pada TAHAP 2, buatkan kode lengkap untuk:

A. Layout utama resources/views/layouts/app.blade.php

Dengan ketentuan:

Bootstrap 5 CSS CDN
Bootstrap 5 JS CDN
Bootstrap Icons CDN
Sidebar modern
Top navbar modern
Layout responsive
Background lembut / soft
Card dashboard modern
Komponen badge, alert, modal, progress bar
UI tidak polos
B. Authentication multi-role

Buatkan login terpisah / disesuaikan untuk:

1. Login internal

Untuk:

Superadmin
Pemilik
Sales
2. Login portal klien

Untuk:

Klien, menggunakan email yang sudah terdaftar pada tabel klien
3. Lupa password

Sediakan alur lupa password dasar / mockup flow bila belum masuk mail configuration.

C. Dashboard Superadmin

Tampilkan minimal:

total user internal
total sales aktif
total klien
total transaksi bulan ini
total omzet bulan ini
ringkasan pembayaran
ringkasan target sales
transaksi terbaru
reminder transaksi belum lunas
D. Dashboard Pemilik

Tampilkan minimal:

total sales aktif
total klien
total transaksi bulan ini
total omzet bulan ini
progress target seluruh sales
daftar transaksi terbaru
transaksi yang belum lunas
alert pelunasan H-30
statistik pembayaran DP dan pelunasan
E. Dashboard Sales

Tampilkan minimal:

target pribadi bulan ini
total penjualan bulan ini
persentase pencapaian target
jumlah deal closing
daftar transaksi yang ditangani
transaksi menunggu DP
transaksi menunggu pelunasan
reminder pelunasan H-30
progress bar pencapaian target
F. Dashboard Portal Klien

Tampilkan minimal:

daftar transaksi milik klien tersebut
paket yang dipilih
total tagihan
status pembayaran
status DP
status pelunasan
riwayat pembayaran
alert jika belum lunas dan sudah mendekati H-30 acara
G. UI wajib modern

Gunakan:

gradient header
card dengan shadow-sm / shadow
badge warna status
alert sukses / warning / danger / info
progress bar target penjualan
tabel responsive
modal konfirmasi
statistik card modern
H. Route awal dan controller dasar

Sertakan:

route auth internal
route auth klien
route dashboard superadmin
route dashboard pemilik
route dashboard sales
route dashboard klien
middleware role sederhana
struktur controller dasar untuk dashboard tiap role
13) FORMAT OUTPUT YANG DIINGINKAN

Keluarkan kode lengkap TAHAP 1 dan TAHAP 2 terlebih dahulu, dengan format yang:

detail
rapi
siap copas ke Laravel 11
disertai nama file
disertai urutan pembuatan
disertai perintah artisan
disertai penjelasan singkat tiap bagian
disesuaikan penuh dengan studi kasus NABILLA CATERING
disesuaikan dengan role Superadmin, Pemilik, Sales, dan Klien
disesuaikan dengan Portal Klien, DP 10%, dan reminder pelunasan H-30
disesuaikan dengan pemisahan Paket & Harga Master vs Harga Penawaran
disesuaikan dengan monitoring target penjualan bulanan real-time

Kalau kamu mau, habis ini aku bisa lanjut 
rapihin prompt ini jadi versi paling final dan “siap tempel” dengan struktur:

PROMPT FINAL SIAP COPY
tanpa penjelasan tambahan
tanpa catatan dariku
tinggal kamu copy sekali blok ke chat baru

Kalau mau, tinggal bilang:
“iya, bikin PROMPT FINAL SIAP COPY”

revisi alur di atas role nya ada superadmin, pemilik, sales dan klien

Siap sayang — aku revisiin alur sistem / prompt finalnya supaya role-nya fix jadi 4 aktor: Superadmin, Pemilik, Sales, dan Klien, dan aturan bisnisnya sesuai sama yang kamu jelasin:

Superadmin → kelola user/role dan kontrol penuh sistem
Pemilik → kelola paket & harga master/asli + monitoring performa
Sales → kelola klien, transaksi penjualan, penawaran, pembayaran
Klien → hanya login portal klien untuk melihat transaksi miliknya, paket yang dipilih, tagihan, dan status pembayaran
DP awal 10%
Pelunasan wajib maksimal H-30 sebelum acara
Login klien dari email yang sudah terdaftar di data klien
Harga Master Paket ≠ Harga Penawaran
Transaksi memang melibatkan 2 aktor utama: Sales dan Klien

Di bawah ini aku tulis PROMPT FINAL yang sudah direvisi dan dirapikan supaya nanti tinggal kamu pakai sebagai prompt utama untuk generate kode Laravel bertahap.

PROMPT FINAL (VERSI REVISI SESUAI ROLE SUPERADMIN, PEMILIK, SALES, KLIEN)

Bertindaklah sebagai Senior Full-Stack Developer spesialis Laravel 11, PHP 8, MySQL, dan Bootstrap 5 yang memiliki keahlian UI/UX Design yang sangat baik.

Buatkan kode untuk aplikasi “Sistem Monitoring Sales Performance Berbasis Web dengan Fitur Real-Time Tracking Target Penjualan Bulanan pada NABILLA CATERING” berdasarkan alur sistem yang telah disusun dari use case, activity diagram, sequence diagram, class diagram, ERD, dan deployment diagram.

Aplikasi ini TIDAK BOLEH polos. Wajib menggunakan Bootstrap 5 UI modern seperti:

Cards
Shadows
Badges
Alerts
Gradient
Progress Bar
Modal
Table Responsive
Bootstrap Icons via CDN
A. KONSEP SISTEM YANG HARUS DIIKUTI

Aplikasi ini adalah sistem monitoring penjualan catering berbasis web yang digunakan untuk:

mengelola data klien,
mengelola paket catering master dan harga dasar,
membuat transaksi penjualan / penawaran catering kepada klien,
memproses pembayaran DP dan pelunasan,
memantau target penjualan bulanan setiap sales secara real-time,
menyediakan portal klien agar klien dapat melihat transaksi dan status pembayarannya sendiri.
B. ROLE / AKTOR SISTEM

Sistem memiliki 4 role utama, yaitu:

1) Superadmin

Role dengan hak akses tertinggi untuk mengelola sistem secara keseluruhan.

Hak akses Superadmin:

login ke sistem internal
kelola user internal
kelola role user
melihat seluruh dashboard sistem
melihat seluruh data transaksi
melihat seluruh data pembayaran
melihat data target penjualan
membantu maintenance data master bila diperlukan
dapat melihat data paket master, harga master, klien, transaksi, pembayaran, dan monitoring keseluruhan

Catatan: Superadmin bukan aktor utama proses bisnis penjualan, tetapi memiliki akses administratif tertinggi.

2) Pemilik

Role manajemen/pimpinan NABILLA CATERING.

Hak akses Pemilik:

login ke sistem internal
melihat dashboard monitoring keseluruhan
melihat performa penjualan seluruh sales
melihat total omzet, total deal closing, total transaksi, target penjualan
mengelola Paket & Harga Master
melihat transaksi penjualan
melihat pembayaran
melihat reminder pelunasan H-30
memantau progress target bulanan seluruh sales

PENTING:
Pemilik adalah aktor yang mengakses fitur Paket & Harga Master / Paket & Harga Asli, bukan Sales dan bukan Klien.

3) Sales / Staf Internal

Role operasional penjualan.

Hak akses Sales:

login ke sistem internal
kelola data klien
membuat transaksi penjualan / penawaran
memilih paket master sebagai acuan penawaran
menyesuaikan harga penawaran sesuai kebutuhan klien
menginput detail acara, porsi, tambahan layanan, catatan
mengelola pembayaran transaksi tertentu
memantau target penjualannya sendiri
melihat dashboard penjualan pribadinya

PENTING:
Sales tidak mengelola harga master/asli.
Sales hanya menggunakan paket master sebagai acuan, lalu membuat harga penawaran pada transaksi klien.

4) Klien

Role portal klien.

Hak akses Klien:

login menggunakan email yang sudah terdaftar di data klien
tidak bisa mengelola data master
tidak bisa membuat transaksi
tidak bisa mengubah paket
hanya bisa melihat data transaksi miliknya sendiri

Klien hanya dapat melihat:

daftar transaksi miliknya
paket yang dipilih
detail penawaran
total tagihan
status pembayaran DP
status pelunasan
riwayat pembayaran
reminder jika pembayaran belum lunas mendekati H-30 acara
C. MODUL UTAMA SISTEM

Sistem harus memiliki modul utama berikut:

Login / Lupa Password
Monitoring & Dashboard
Data Klien
Paket & Harga Master
Transaksi Penjualan
Pembayaran
Integrasi Midtrans
Portal Klien
Manajemen User & Role (khusus Superadmin)
D. ATURAN BISNIS PENTING YANG WAJIB DIIKUTI
1. Paket & Harga Master dikelola Pemilik

Sistem harus membedakan dua jenis harga secara jelas:

(1) Harga Master Paket

Dikelola oleh Pemilik melalui menu Paket & Harga Master.

Fungsinya:

menyimpan data paket catering asli / master
menyimpan harga dasar paket
menjadi referensi/acuan ketika Sales membuat penawaran ke klien

Contoh data paket master:

Paket Silver
Paket Gold
Paket Platinum
Paket Pernikahan 500 Pax
Paket Aqiqah
Paket Corporate Meeting

Contoh komponen data:

nama paket
kategori paket
deskripsi
harga dasar
minimal porsi
maksimal porsi
keterangan layanan
(2) Harga Penawaran

Digunakan oleh Sales pada proses transaksi penjualan.

Karakteristik:

diambil dari paket master sebagai acuan
boleh disesuaikan berdasarkan kebutuhan klien
dapat berubah karena:
jumlah porsi
tambahan menu
dekorasi
sewa alat
permintaan khusus
lokasi acara
custom item lain

Jadi:

Harga Master = harga dasar resmi dari Pemilik
Harga Penawaran = harga hasil negosiasi / penyesuaian oleh Sales untuk transaksi klien tertentu
E. KONSEP TRANSAKSI PENJUALAN
Transaksi melibatkan 2 aktor utama:
1) Sales
2) Klien

Alur umumnya:

Sales memilih / menambahkan data klien
Sales memilih paket master sebagai acuan
Sales menyusun detail penawaran transaksi
Sistem menghitung subtotal dan total penawaran
Transaksi disimpan
Sistem membuat tagihan pembayaran
Klien dapat login ke portal untuk melihat transaksi miliknya
Klien dapat melihat paket yang dipilih, tagihan, dan status pembayaran
F. PORTAL KLIEN

Portal klien harus dibuat terpisah secara hak akses dari dashboard internal.

Login Klien

Klien login menggunakan:

email yang sudah terdaftar di data klien
password klien yang tersimpan di tabel klien / akun klien
Portal Klien hanya menampilkan:
data transaksi miliknya
daftar paket / item yang dipilih
detail penawaran
total tagihan
status pembayaran
riwayat pembayaran
reminder pelunasan H-30
Portal klien tidak boleh menampilkan:
dashboard internal perusahaan
data klien lain
data sales lain
data target penjualan
menu master paket dan harga
menu manajemen user
G. ATURAN PEMBAYARAN

Sistem pembayaran memiliki aturan bisnis berikut:

1) Pembayaran awal = DP 10%

Saat transaksi dibuat dan disetujui, sistem otomatis membuat tagihan:

DP = 10% dari total transaksi
Sisa tagihan = 90% untuk pelunasan

Contoh:

Total transaksi = Rp20.000.000
DP = Rp2.000.000
Pelunasan = Rp18.000.000
2) Pelunasan wajib maksimal H-30 sebelum acara

Jika tanggal acara misalnya 30 Desember 2026, maka pelunasan harus sudah dilakukan maksimal 30 November 2026.

Sistem harus dapat:

menghitung tanggal jatuh tempo pelunasan = tanggal_acara - 30 hari
menampilkan alert jika pembayaran belum lunas dan sudah mendekati H-30
menandai transaksi yang belum lunas
menampilkan reminder di dashboard Pemilik, Sales, dan Portal Klien
3) Metode pembayaran

Sistem harus mendukung:

Cash
Transfer manual
Midtrans
4) Integrasi Midtrans

Untuk pembayaran online:

sistem membuat transaksi pembayaran
sistem generate midtrans_order_id
redirect / snap payment ke Midtrans
menerima callback / notifikasi pembayaran
update status pembayaran otomatis
H. FITUR REAL-TIME TRACKING TARGET PENJUALAN BULANAN

Aplikasi harus mendukung monitoring target penjualan bulanan secara real-time, khususnya untuk Pemilik, Superadmin, dan Sales.

Data yang perlu dimonitor:

target bulanan per sales
total transaksi bulan berjalan
total transaksi deal closing
total omzet per bulan
persentase pencapaian target
sisa target yang belum tercapai
transaksi pending DP
transaksi pending pelunasan
reminder transaksi yang belum lunas H-30

Visualisasi dashboard harus menampilkan:

statistic cards
progress bar target
badge status
alert reminder
tabel transaksi terbaru
ringkasan performa sales
I. ALUR HAK AKSES BERDASARKAN USE CASE REVISI
1. Superadmin

Dapat mengakses:

Login internal
Monitoring & Dashboard global
Data user & role
Data klien
Paket & Harga Master (view / kontrol sistem)
Transaksi Penjualan
Pembayaran
Laporan / monitoring keseluruhan
2. Pemilik

Dapat mengakses:

Login internal
Monitoring & Dashboard
Paket & Harga Master
Lihat data klien
Lihat transaksi penjualan
Lihat pembayaran
Lihat performa sales
Lihat reminder pelunasan H-30
3. Sales

Dapat mengakses:

Login internal
Dashboard sales
Kelola data klien
Transaksi penjualan
Kelola pembayaran transaksi
Lihat target penjualan pribadi
Lihat reminder pelunasan transaksi kliennya
4. Klien

Dapat mengakses:

Login portal klien
Lihat transaksi miliknya
Lihat paket yang dipilih
Lihat detail tagihan
Lihat status pembayaran
Lihat riwayat pembayaran
Lihat reminder pelunasan H-30
J. OUTPUT YANG HARUS DIBUAT BERTAHAP

Keluarkan kode bertahap agar tidak terpotong, dimulai dari:

TAHAP 1 — KONFIGURASI, ROLE, DAN DATABASE

Pada tahap ini, buatkan secara lengkap dan detail:

1. Analisis role dan hak akses

Jelaskan hak akses untuk:

Superadmin
Pemilik
Sales
Klien
2. Contoh konfigurasi .env

Buat contoh konfigurasi database Laravel 11 untuk MySQL.

3. Struktur database lengkap

Buat migration Laravel 11, model, relasi, $fillable, dan penjelasan alur data antar tabel.

4. Seeder dummy data

Tambahkan dummy data paket master dan harga master agar sesuai revisi alur bahwa Pemilik mengelola paket dan harga asli.

Contoh dummy:

Paket Silver
Paket Gold
Paket Platinum
Paket Pernikahan Premium
Paket Syukuran / Aqiqah

dengan dummy harga master dan minimal porsi.

5. Penjelasan aturan bisnis di level database

Jelaskan:

kenapa paket master dipisah dari transaksi penawaran
bagaimana DP 10% dihitung
bagaimana sistem mendeteksi H-30
bagaimana transaksi dikaitkan dengan target penjualan sales
K. STRUKTUR DATABASE MINIMAL YANG WAJIB ADA
1) Tabel users

Untuk akun internal: superadmin, pemilik, sales

Field:

id
name
email
password
role (superadmin, pemilik, sales)
created_at
updated_at
2) Tabel klien

Untuk data klien dan akun portal klien

Field:

id
nama_klien
email
no_hp
alamat
nama_perusahaan (nullable)
password
status_aktif
created_at
updated_at

Klien login menggunakan email yang sudah terdaftar di tabel ini.

3) Tabel target_penjualan

Target bulanan setiap sales

Field:

id
sales_id (FK ke users)
bulan
tahun
target_nominal
created_at
updated_at
4) Tabel paket_master

Dikelola oleh Pemilik

Field:

id
kode_paket
nama_paket
deskripsi
kategori_paket
status_aktif
created_at
updated_at
5) Tabel paket_master_harga

Harga dasar / harga master paket

Field:

id
paket_master_id (FK)
nama_varian
harga_dasar
minimal_porsi
maksimal_porsi (nullable)
keterangan
created_at
updated_at
6) Tabel transaksi_penjualan

Dibuat oleh Sales

Field:

id
kode_transaksi
sales_id (FK ke users)
klien_id (FK)
paket_master_id (FK, nullable jika custom penuh)
target_penjualan_id (nullable)
tanggal_transaksi
tanggal_acara
jumlah_porsi
lokasi_acara
catatan
subtotal
diskon
total_penawaran
status_transaksi (draft, menunggu_dp, dp_terbayar, menunggu_pelunasan, lunas, batal)
status_acara (belum_berjalan, berjalan, selesai)
created_at
updated_at
7) Tabel transaksi_detail

Detail item penawaran transaksi

Field:

id
transaksi_id (FK)
nama_item
qty
satuan
harga_satuan
subtotal
tipe_item (paket, tambahan, custom)
created_at
updated_at
8) Tabel pembayaran

Data pembayaran transaksi

Field:

id
transaksi_id (FK)
kode_pembayaran
jenis_pembayaran (dp, pelunasan)
metode_pembayaran (cash, transfer, midtrans)
nominal_tagihan
nominal_bayar
tanggal_bayar (nullable)
status_pembayaran (pending, berhasil, gagal)
midtrans_order_id (nullable)
bukti_pembayaran (nullable)
catatan
created_at
updated_at
9) Tabel notifikasi_pelunasan

Untuk reminder H-30

Field:

id
transaksi_id
tipe_notifikasi
isi_notifikasi
tanggal_kirim
status_baca
created_at
updated_at
L. TAHAP 2 — AUTHENTICATION, LAYOUT, DASHBOARD, DAN UI DASAR

Setelah selesai TAHAP 1, lanjutkan ke TAHAP 2 dan keluarkan kode lengkap untuk:

1. Layout utama resources/views/layouts/app.blade.php

Wajib memuat:

Bootstrap 5 CSS & JS CDN
Bootstrap Icons CDN
sidebar modern
top navbar
layout responsive
background lembut
card dashboard modern
alert dan badge modern
2. Login multi-role

Buat:

login internal untuk superadmin / pemilik / sales
login portal klien
halaman lupa password
3. Dashboard Superadmin

Tampilkan:

total user internal
total sales aktif
total klien
total transaksi bulan ini
total omzet bulan ini
transaksi terbaru
reminder pembayaran
ringkasan data master
4. Dashboard Pemilik

Tampilkan:

total sales aktif
total klien
total transaksi bulan ini
total omzet bulan ini
progress target seluruh sales
daftar transaksi terbaru
transaksi belum lunas
alert pelunasan H-30
ringkasan paket master & harga master
5. Dashboard Sales

Tampilkan:

target pribadi bulan ini
total penjualan bulan ini
persentase pencapaian target
daftar transaksi yang ditangani
transaksi menunggu DP
transaksi menunggu pelunasan
progress bar target sales
6. Dashboard Portal Klien

Tampilkan:

transaksi miliknya
paket yang dipilih
detail tagihan
status pembayaran
status pelunasan
riwayat pembayaran
alert jika belum lunas dan sudah mendekati H-30 acara
7. UI wajib modern

Gunakan:

gradient header
cards dengan shadow-sm / shadow
badge warna status
alert sukses / warning / danger
progress bar target penjualan
tabel responsive
modal konfirmasi
8. Route awal dan controller dasar

Sertakan:

route auth internal
route auth klien
route dashboard superadmin
route dashboard pemilik
route dashboard sales
route dashboard klien
middleware role sederhana
M. FORMAT OUTPUT YANG DIMINTA

Keluarkan jawaban bertahap, lengkap, rapi, dan siap implementasi Laravel 11, dimulai dari:

TAHAP 1 — KONFIGURASI, ROLE, DATABASE, MIGRATION, MODEL, RELASI, SEEDER

lalu setelah itu lanjut ke:

TAHAP 2 — AUTHENTICATION, LAYOUT, DASHBOARD, UI DASAR

Jangan lompat ke tahap berikutnya sebelum TAHAP 1 dan TAHAP 2 selesai lengkap.

Kalau kamu mau, habis ini aku bisa bantu naikin prompt final ini jadi versi paling siap pakai dengan format yang lebih “keras” ke AI generator, misalnya aku bikin versi:

PROMPT FINAL SUPER RAPI

yang isinya sudah sekalian:

urutan folder Laravel-nya
nama migration satu per satu
nama model
nama controller
route web.php
middleware role
guard klien
urutan pengerjaan Tahap 1–8

Jadi nanti kamu tinggal copas prompt itu ke chat baru dan AI bakal lebih susah ngaco.
Kalau mau, bilang aja: “lanjut bikin PROMPT FINAL SUPER RAPI” ya sayang 💛

lanjut bikin PROMPT FINAL SUPER RAPI dan detail

Siap sayang — ini aku rapihin jadi PROMPT FINAL yang sudah disesuaikan dengan revisi alur terbaru:

role ada 4: Superadmin, Pemilik, Sales, Klien
Pemilik yang mengelola Paket & Harga Master/Asli
Sales membuat transaksi penjualan + harga penawaran berdasarkan paket master
Klien hanya login ke portal klien untuk melihat transaksi miliknya
Pembayaran awal DP 10%
Ada reminder H-30 sebelum acara harus lunas
Superadmin ditambahkan sebagai role pengelola sistem level tertinggi
Portal klien login dari email klien yang sudah terdaftar di data klien
Transaksi melibatkan 2 aktor utama: Sales dan Klien
Klien tidak boleh mengelola master data, hanya melihat transaksi, paket yang dipilih, status pembayaran, tagihan, dan riwayat pembayaran

Di bawah ini versi prompt final super rapi, detail, dan siap dipakai 👇

PROMPT FINAL

Bertindaklah sebagai Senior Full-Stack Developer spesialis Laravel 11, PHP 8, MySQL, dan Bootstrap 5 yang memiliki keahlian UI/UX Design yang sangat baik.

Buatkan kode aplikasi “Sistem Monitoring Sales Performance Berbasis Web dengan Fitur Real-Time Tracking Target Penjualan Bulanan pada NABILLA CATERING” berdasarkan alur sistem yang telah disusun dari Use Case Diagram, Activity Diagram, Sequence Diagram, ERD, Class Diagram, dan Deployment Diagram.

Aplikasi ini TIDAK BOLEH polos.
Wajib menggunakan Bootstrap 5 UI modern seperti:

Cards
Shadows
Badges
Alerts
Gradient
Progress Bar
Modal
Table Responsive
Bootstrap Icons via CDN
1) KONSEP SISTEM YANG WAJIB DIIKUTI

Aplikasi ini adalah sistem untuk monitoring performa penjualan sales catering, mengelola transaksi penawaran catering, mengelola pembayaran DP dan pelunasan, serta menyediakan portal klien agar klien dapat memantau transaksi dan tagihannya sendiri.

Sistem harus mendukung tracking target penjualan bulanan secara real-time, sehingga pihak internal dapat memantau:

target penjualan tiap sales
total transaksi bulan berjalan
total deal closing
total omzet
progres pencapaian target per sales
transaksi yang belum lunas
reminder H-30 sebelum acara
2) ROLE SISTEM

Sistem memiliki 4 role utama, yaitu:

A. Superadmin

Role tertinggi sistem. Bertugas mengelola sistem secara global.

Hak akses Superadmin:

mengelola akun internal (Superadmin, Pemilik, Sales)
melihat dashboard keseluruhan
melihat data klien
melihat data transaksi
melihat pembayaran
melihat monitoring target penjualan
mengelola / mengawasi data master bila dibutuhkan
memiliki akses penuh terhadap seluruh modul sistem
B. Pemilik

Pemilik bukan sales dan bukan klien.
Pemilik memiliki akses terhadap monitoring bisnis dan modul khusus Paket & Harga Master.

Hak akses Pemilik:

melihat dashboard monitoring bisnis
melihat performa seluruh sales
melihat omzet dan pencapaian target
melihat transaksi penjualan
melihat pembayaran
menerima alert transaksi yang belum lunas
mengelola Paket & Harga Master/Asli
melihat reminder pelunasan H-30

Penting:
Pemilik adalah aktor yang mengakses fitur Paket & Harga Master/Asli, bukan Sales dan bukan Klien.

C. Sales / Staf Internal

Sales adalah pihak yang menangani klien dan membuat transaksi penjualan.

Hak akses Sales:

login ke sistem internal
melihat dashboard target pribadinya
mengelola data klien
membuat transaksi penjualan
memilih paket master sebagai acuan penawaran
menyesuaikan harga penawaran sesuai kebutuhan klien
menginput detail item penawaran
membuat transaksi DP / pelunasan
melihat progres target penjualan bulanannya
melihat transaksi yang ditangani
melihat status pembayaran klien

Sales TIDAK mengelola Paket & Harga Master.
Sales hanya menggunakan paket master sebagai dasar penawaran, lalu membuat harga penawaran pada transaksi.

D. Klien

Klien adalah pengguna eksternal yang memiliki akses ke Portal Klien.

Hak akses Klien:

login ke portal klien menggunakan email yang sudah terdaftar pada data klien
melihat transaksi miliknya saja
melihat paket yang dipilih
melihat detail penawaran
melihat tagihan
melihat status pembayaran
melihat riwayat pembayaran
melihat alert pelunasan H-30 jika belum lunas

Klien tidak boleh:

mengelola data master
mengubah transaksi
mengelola paket
mengelola user internal
mengelola pembayaran milik klien lain
3) MODUL SISTEM YANG HARUS ADA

Sistem memiliki modul utama berikut:

Login / Lupa Password
Monitoring & Dashboard
Data Klien
Paket & Harga Master
Transaksi Penjualan
Pembayaran
Integrasi Midtrans
Portal Klien
4) KONSEP PAKET & HARGA: WAJIB DIBEDAKAN DENGAN JELAS

Sistem harus membedakan secara tegas antara Harga Master Paket dan Harga Penawaran.

5) HARGA MASTER PAKET vs HARGA PENAWARAN
A. Harga Master Paket

Harga master adalah data paket catering asli / dasar milik NABILLA CATERING.

Karakteristik:

dikelola oleh Pemilik (dan bisa dipantau Superadmin)
berada pada menu Paket & Harga
berisi paket catering master/asli
berisi harga dasar / harga acuan
menjadi referensi saat Sales membuat penawaran ke klien
tidak langsung menjadi transaksi
bisa memiliki beberapa varian harga / minimal porsi

Contoh isi:

Paket Pernikahan Silver
Paket Pernikahan Gold
Paket Aqiqah
Paket Syukuran
Paket Corporate Catering
Paket Nasi Box Seminar

Setiap paket memiliki harga dasar / varian harga master.

B. Harga Penawaran

Harga penawaran adalah harga yang dipakai dalam transaksi penjualan oleh Sales kepada klien.

Karakteristik:

dibuat oleh Sales
muncul di Transaksi Penjualan
mengambil acuan dari Paket Master
boleh disesuaikan berdasarkan kebutuhan klien
bisa berubah tergantung:
jumlah porsi
tambahan menu
dekorasi
permintaan khusus
lokasi acara
layanan tambahan
diskon / negosiasi

Dengan demikian:

Paket & Harga Master = dikelola Pemilik
Harga Penawaran = dipakai Sales saat membuat transaksi penjualan
6) ALUR BISNIS TRANSAKSI YANG HARUS DIIKUTI
Alur transaksi melibatkan 2 aktor utama:
Sales
Klien
Alur utama:
Sales login ke sistem.
Sales membuka menu Transaksi Penjualan.
Sales memilih klien dari data klien yang sudah terdaftar.
Sales memilih paket master sebagai dasar penawaran.
Sistem menampilkan detail paket dan harga dasar.
Sales dapat menyesuaikan penawaran:
jumlah porsi
tambahan item
dekorasi
custom request
diskon
Sistem menghitung subtotal dan total penawaran.
Sales menyimpan transaksi penjualan.
Sistem membuat data tagihan pembayaran.
Pembayaran pertama adalah DP 10% dari total penawaran.
Sisa tagihan menjadi pelunasan.
Klien login ke portal klien dan melihat:
transaksi miliknya
paket yang dipilih
total tagihan
status DP
status pelunasan
riwayat pembayaran
Jika acara mendekati H-30 dan pembayaran belum lunas, sistem menampilkan peringatan pelunasan.
7) ATURAN PEMBAYARAN YANG WAJIB DITERAPKAN

Sistem pembayaran harus mengikuti aturan berikut:

A. Pembayaran awal adalah DP 10%
Saat transaksi penjualan dibuat, sistem harus menghitung:
DP = 10% × total_penawaran
Contoh:
total penawaran = Rp10.000.000
DP = Rp1.000.000
sisa pelunasan = Rp9.000.000
B. Sisa pembayaran adalah pelunasan
Setelah DP dibayar, status transaksi berubah menjadi menunggu pelunasan atau dp_terbayar
Saat seluruh sisa dibayar, status menjadi lunas
C. Reminder H-30 sebelum acara
Jika tanggal acara tinggal 30 hari / 1 bulan lagi
dan transaksi belum lunas
maka sistem harus menampilkan:
alert warning
notifikasi pelunasan
penanda di dashboard
alert di portal klien
D. Metode pembayaran

Sistem mendukung:

Cash / tunai
Transfer manual
Midtrans / payment gateway
8) PORTAL KLIEN: ATURAN WAJIB

Portal klien harus dibuat terpisah secara jelas dari dashboard internal.

Login portal klien:
klien login menggunakan email yang sudah terdaftar pada tabel klien
password klien tersimpan pada data klien
klien tidak menggunakan role internal user
Yang dapat dilihat klien:
daftar transaksi miliknya
detail transaksi
paket yang dipilih
detail penawaran
status transaksi
tagihan DP
tagihan pelunasan
riwayat pembayaran
bukti pembayaran bila ada
alert H-30 jika belum lunas
Yang tidak dapat dilakukan klien:
tidak boleh melihat transaksi klien lain
tidak boleh mengedit transaksi
tidak boleh mengubah harga
tidak boleh mengakses menu internal
tidak boleh mengelola master data
9) FITUR REAL-TIME TRACKING TARGET PENJUALAN

Aplikasi harus memiliki fitur monitoring target penjualan bulanan secara real-time, terutama untuk Superadmin, Pemilik, dan Sales.

Dashboard harus dapat menampilkan:
target bulanan tiap sales
total transaksi bulan berjalan
total deal closing
total omzet bulan berjalan
total DP masuk
total pelunasan masuk
jumlah transaksi pending
jumlah transaksi belum lunas
progress pencapaian target per sales
persentase pencapaian target
transaksi terbaru
reminder transaksi yang mendekati H-30 dan belum lunas
Visual wajib:
Card statistik
Progress bar
Badge status
Alert warning / danger / success
Chart sederhana bila perlu
Tabel transaksi terbaru
10) RELASI AKSES FITUR PER ROLE
Superadmin dapat mengakses:
Dashboard global
Monitoring performa
Data user internal
Data klien
Transaksi penjualan
Pembayaran
Monitoring target
melihat paket master
pengawasan sistem penuh
Pemilik dapat mengakses:
Dashboard monitoring bisnis
Monitoring sales performance
Paket & Harga Master
Data transaksi
Pembayaran
reminder H-30
laporan omzet dan target
Sales dapat mengakses:
Dashboard target pribadi
Data klien
Transaksi penjualan
Pembayaran transaksi
melihat status transaksi klien yang ditangani
membuat penawaran dari paket master
Klien dapat mengakses:
Portal klien
transaksi miliknya
paket yang dipilih
status pembayaran
tagihan
riwayat pembayaran
reminder pelunasan
11) TAHAP PENGERJAAN YANG DIMINTA

Keluarkan kode secara bertahap agar tidak terpotong, dimulai dari TAHAP 1 dan TAHAP 2 terlebih dahulu.

TAHAP 1 — KONFIGURASI, ANALISIS ROLE, DATABASE, MODEL, DAN SEEDER

Pada tahap ini, buatkan secara lengkap, detail, dan rapi:

A. Analisis Role dan Hak Akses

Buat penjelasan role berikut:

Superadmin
Pemilik
Sales
Klien

Sertakan:

hak akses tiap role
menu yang bisa diakses
menu yang tidak bisa diakses
hubungan role dengan modul sistem
B. Konfigurasi Project & .env

Berikan:

contoh konfigurasi file .env
konfigurasi database MySQL
nama database, misalnya db_nabilla_catering
konfigurasi APP_URL
konfigurasi mail dasar bila diperlukan
catatan env Midtrans bila nanti digunakan
C. Struktur Tabel Database Lengkap

Buat struktur tabel lengkap berikut.

1. Tabel users

Untuk akun login internal (superadmin, pemilik, sales)

Field minimal:

id
name
email
password
role → enum/string: superadmin, pemilik, sales
created_at
updated_at
2. Tabel klien

Untuk data klien sekaligus akun portal klien

Field minimal:

id
nama_klien
email
no_hp
alamat
nama_perusahaan (nullable)
password
status_aktif
created_at
updated_at

Catatan:

email klien dipakai untuk login portal klien
password klien disimpan di tabel klien
klien hanya mengakses transaksi miliknya
3. Tabel target_penjualan

Untuk target bulanan setiap sales

Field minimal:

id
sales_id (FK ke users)
bulan
tahun
target_nominal
created_at
updated_at
4. Tabel paket_master

Untuk data paket catering asli / master yang dikelola Pemilik

Field minimal:

id
kode_paket
nama_paket
deskripsi
kategori_paket
status_aktif
created_at
updated_at
5. Tabel paket_master_harga

Untuk harga dasar/master dari paket

Field minimal:

id
paket_master_id (FK ke paket_master)
nama_varian
harga_dasar
minimal_porsi
maksimal_porsi (nullable)
keterangan
created_at
updated_at
6. Tabel transaksi_penjualan

Untuk transaksi penjualan yang dibuat oleh Sales

Field minimal:

id
kode_transaksi
sales_id (FK ke users)
klien_id (FK ke klien)
paket_master_id (nullable, jika transaksi custom)
target_penjualan_id (nullable, bila ingin dikaitkan ke target bulan berjalan)
tanggal_transaksi
tanggal_acara
jumlah_porsi
lokasi_acara
catatan
subtotal
diskon
total_penawaran
nominal_dp → hasil 10% dari total penawaran
sisa_pelunasan
status_transaksi
enum contoh:
draft
menunggu_dp
dp_terbayar
menunggu_pelunasan
lunas
batal
status_acara
enum contoh:
belum_berjalan
berjalan
selesai
created_at
updated_at
7. Tabel transaksi_detail

Untuk detail item penawaran dalam transaksi

Field minimal:

id
transaksi_id (FK ke transaksi_penjualan)
nama_item
qty
satuan
harga_satuan
subtotal
tipe_item
enum contoh:
paket
tambahan
custom
created_at
updated_at
8. Tabel pembayaran

Untuk data pembayaran transaksi

Field minimal:

id
transaksi_id (FK ke transaksi_penjualan)
kode_pembayaran
jenis_pembayaran
dp
pelunasan
metode_pembayaran
cash
transfer
midtrans
nominal_tagihan
nominal_bayar
tanggal_bayar (nullable)
status_pembayaran
pending
berhasil
gagal
midtrans_order_id (nullable)
bukti_pembayaran (nullable)
catatan
created_at
updated_at
9. Tabel notifikasi_pelunasan

Untuk reminder H-30 sebelum acara

Field minimal:

id
transaksi_id
tipe_notifikasi
isi_notifikasi
tanggal_kirim
status_baca
created_at
updated_at
D. Migration Laravel 11

Buatkan semua file migration Laravel 11 untuk seluruh tabel di atas secara lengkap, rapi, dan siap pakai.

Gunakan:

foreign key
cascade yang relevan
tipe data yang sesuai
enum / string status yang jelas
default value yang masuk akal
E. Model Eloquent Lengkap

Buatkan semua model Laravel lengkap dengan:

$fillable
relasi antar model
casting bila diperlukan
helper method sederhana bila perlu

Model minimal:

User
Klien
TargetPenjualan
PaketMaster
PaketMasterHarga
TransaksiPenjualan
TransaksiDetail
Pembayaran
NotifikasiPelunasan
F. Seeder Dummy Data

Buatkan dummy data khusus untuk paket dan harga master, karena pada revisi alur disebutkan bahwa paket dan harga asli perlu ada contoh data.

Buatkan seeder data dummy misalnya:

Paket master dummy:
Paket Pernikahan Silver
Paket Pernikahan Gold
Paket Aqiqah
Paket Syukuran
Paket Nasi Box Kantoran
Harga master dummy:

contoh tiap paket memiliki varian harga, misalnya:

Paket Pernikahan Silver – 300 porsi – Rp35.000/porsi
Paket Pernikahan Silver – 500 porsi – Rp33.000/porsi
Paket Pernikahan Gold – 300 porsi – Rp50.000/porsi
Paket Aqiqah – 100 box – Rp28.000/box
Paket Nasi Box Kantoran – 50 box – Rp22.000/box

Silakan buat dummy data yang realistis untuk bisnis catering.

Selain itu, buatkan juga dummy:

1 superadmin
1 pemilik
2 sales
3 klien
contoh target penjualan bulanan sales
G. Penjelasan Alur Data Antar Tabel

Setelah migration dan model dibuat, jelaskan:

relasi antar tabel
hubungan paket master dengan transaksi penjualan
kenapa harga master dipisahkan dari harga penawaran transaksi
bagaimana transaksi detail menyimpan item penawaran
bagaimana pembayaran DP dan pelunasan dihubungkan ke transaksi
H. Logika Bisnis yang Wajib Dijelaskan di TAHAP 1

Jelaskan juga secara teknis:

1. Cara sistem menghitung DP 10%

Contoh:

nominal_dp = total_penawaran * 10 / 100
sisa_pelunasan = total_penawaran - nominal_dp
2. Cara sistem mendeteksi H-30 sebelum acara

Contoh:

jika tanggal_acara - hari_ini <= 30
dan status transaksi belum lunas
maka sistem membuat notifikasi / reminder
3. Cara target penjualan sales dihitung

Misalnya:

total penjualan sales bulan ini
dibandingkan dengan target nominal pada tabel target_penjualan
TAHAP 2 — AUTHENTICATION, MULTI-ROLE DASHBOARD, LAYOUT, DAN UI DASAR

Pada tahap ini, buatkan kode lengkap, rapi, modern, dan siap pakai untuk:

A. Layout Utama resources/views/layouts/app.blade.php

Layout internal untuk Superadmin, Pemilik, dan Sales.

Wajib berisi:

Bootstrap 5 CSS CDN
Bootstrap 5 JS Bundle CDN
Bootstrap Icons CDN
sidebar modern
top navbar modern
responsive layout
warna lembut / modern
card dashboard dengan shadow
komponen alert, badge, dropdown user
menu sidebar sesuai role

Gunakan desain modern:

sidebar gelap / gradient
topbar putih dengan shadow-sm
card statistik berwarna
area content dengan background lembut
B. Layout Portal Klien

Buat layout terpisah untuk klien, misalnya:

resources/views/layouts/client.blade.php

Karena portal klien harus terpisah dari dashboard internal.

Portal klien wajib:

modern
sederhana
mobile-friendly
ada navbar / header khusus klien
card status transaksi
alert reminder pelunasan
C. Authentication Multi Login

Buatkan autentikasi untuk:

1. Login internal

Untuk:

superadmin
pemilik
sales
2. Login portal klien

Untuk:

klien
3. Lupa password

Sediakan halaman / struktur awal untuk lupa password, minimal:

form email
halaman reset sederhana / placeholder bila belum full implementasi
D. Middleware Role Sederhana

Buat middleware sederhana untuk membatasi akses:

superadmin only
pemilik only
sales only
klien only
internal only
E. Route Awal

Buat route dasar untuk:

Auth internal
login
logout
forgot password
Auth klien
login klien
logout klien
Dashboard
dashboard superadmin
dashboard pemilik
dashboard sales
dashboard klien
Awal modul
route monitoring
route data klien
route paket & harga
route transaksi penjualan
route pembayaran
F. Controller Dasar Tahap 2

Buat controller dasar minimal untuk:

AuthController
ClientAuthController
DashboardController
OwnerDashboardController / atau gabung dengan dashboard berbasis role
SalesDashboardController
ClientDashboardController

Silakan pilih struktur controller yang rapi, konsisten, dan mudah dikembangkan.

12) DASHBOARD YANG HARUS DIBUAT PADA TAHAP 2
A. Dashboard Superadmin

Tampilkan:

total user internal
total sales
total klien
total transaksi bulan ini
total omzet bulan ini
total pembayaran berhasil
daftar transaksi terbaru
transaksi belum lunas
reminder H-30
statistik performa sales
B. Dashboard Pemilik

Tampilkan:

total sales aktif
total klien
total transaksi bulan ini
total omzet bulan ini
progress target seluruh sales
daftar transaksi terbaru
transaksi belum lunas
alert pelunasan H-30
ringkasan pembayaran DP dan pelunasan
C. Dashboard Sales

Tampilkan:

target pribadi bulan ini
total penjualan bulan ini
persentase pencapaian target
daftar transaksi yang ditangani
transaksi menunggu DP
transaksi menunggu pelunasan
progress bar pencapaian target
reminder transaksi klien yang mendekati H-30
D. Dashboard Klien / Portal Klien

Tampilkan:

detail transaksi miliknya
paket yang dipilih
total tagihan
status pembayaran
status DP
status pelunasan
riwayat pembayaran
alert jika belum lunas dan sudah mendekati H-30 acara
13) UI / UX WAJIB PADA TAHAP 2

Semua halaman harus modern dan tidak polos.

Gunakan elemen berikut:

gradient header
card shadow-sm / shadow
badge status warna-warni
alert sukses / warning / danger
progress bar target penjualan
tabel responsive
dropdown profile
modal konfirmasi sederhana
empty state yang rapi jika data kosong
14) OUTPUT YANG DIINGINKAN

Keluarkan jawaban bertahap dan jangan langsung semua modul sekaligus.

Mulai dari:
TAHAP 1 — KONFIGURASI, ROLE, DATABASE, MIGRATION, MODEL, SEEDER

lalu lanjutkan ke

TAHAP 2 — AUTHENTICATION, LAYOUT, DASHBOARD, ROUTE, CONTROLLER DASAR

NOTE : DESAINYAA PAKAI UI/UX PRO JANGAN POLOS, JANGAN KLASIK, JANGAN BIASA SAJA.WARNA HIJAU,KUNING,OREN EMAS INI WARNANYA . PAKAI UI/UX PRO POKONYAA...