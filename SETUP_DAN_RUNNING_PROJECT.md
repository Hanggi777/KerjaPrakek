# PANDUAN SETUP & RUNNING PROJECT
## Sistem Monitoring Sales Performance - NABILLA CATERING

---

## PREREQUISITE

Pastikan sudah terinstall:
- PHP 8.1+
- Composer
- MySQL 5.7+ / MariaDB
- Git
- Node.js & NPM (optional, untuk frontend assets)

---

## STEP-BY-STEP SETUP

### 1. Clone Repository (Jika belum)
```bash
# Navigasi ke folder project
cd d:\APK-NabillaCetring
# atau folder Anda
```

### 2. Copy Environment File
```bash
# Copy .env.example ke .env
cp .env.example .env

# Atau manual:
# - Buka .env.example
# - Copy ke file baru bernama .env
```

### 3. Generate Application Key
```bash
php artisan key:generate
```

Hasil: `APP_KEY` di file `.env` akan terisi otomatis

### 4. Konfigurasi Database

**Edit file `.env`:**
```env
# JIKA MENGGUNAKAN MYSQL LOKAL
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_nabilla_catering
DB_USERNAME=root
DB_PASSWORD=

# ATAU JIKA PAKAI PASSWORD:
DB_PASSWORD=your_password_here
```

### 5. Buat Database
```bash
# Buka MySQL Client:
mysql -u root -p

# Atau gunakan command:
mysql -u root -p -e "CREATE DATABASE db_nabilla_catering CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 6. Install Dependencies
```bash
composer install
```

Tunggu hingga semua package terinstall (2-5 menit)

### 7. Run Migrations
```bash
php artisan migrate
```

Hasilnya: Semua tabel database dibuat otomatis

### 8. Seed Database (Dummy Data)
```bash
php artisan db:seed
```

Hasilnya:
- 4 User Internal (Superadmin, Pemilik, 2 Sales)
- 3 Data Klien
- 5 Paket Master dengan 8 varian
- 2 Target Penjualan
- 2 Transaksi Penjualan
- 3 Data Pembayaran

**Output di console:**
```
✅ Database berhasil di-seed dengan data dummy!
   - 4 User Internal (1 Superadmin, 1 Pemilik, 2 Sales)
   - 3 Data Klien
   - 5 Paket Master dengan 8 varian harga
   - 2 Target Penjualan
   - 2 Transaksi Penjualan dengan detail
   - 3 Data Pembayaran

 📝 Login Internal:
   Superadmin: admin@nabilla.local / password123
   Pemilik: pemilik@nabilla.local / password123
   Sales 1: rina@nabilla.local / password123
   Sales 2: budi@nabilla.local / password123

 🔑 Portal Klien Login:
   Klien 1: adi.wijaya@email.com / password123
   Klien 2: siti.nurhaliza@email.com / password123
   Klien 3: ahmad.rahman@email.com / password123
```

### 9. (Optional) Install Frontend Assets
```bash
npm install
npm run dev
```

---

## RUNNING PROJECT

### Development Server

#### Opsi 1: Menggunakan Laravel Artisan
```bash
php artisan serve
```

**Output:**
```
   INFO  Server running on [http://127.0.0.1:8000].

   Press Ctrl+C to stop the server.
```

**Akses via browser:**
- http://localhost:8000
- http://127.0.0.1:8000

#### Opsi 2: Menggunakan PHP Built-in Server
```bash
php -S localhost:8000 -t public/
```

#### Opsi 3: Menggunakan Apache/Nginx (Production-like)
```bash
# Setup virtual host di Apache/Nginx
# Point DocumentRoot ke: d:\APK-NabillaCetring\public
```

---

## URL PENTING

| Halaman | URL |
|---------|-----|
| Login Internal | `http://localhost:8000/login` |
| Login Portal Klien | `http://localhost:8000/client/login` |
| Dashboard Internal | `http://localhost:8000/dashboard` |
| Dashboard Superadmin | `http://localhost:8000/dashboard/superadmin` |
| Dashboard Pemilik | `http://localhost:8000/dashboard/pemilik` |
| Dashboard Sales | `http://localhost:8000/dashboard/sales` |
| Dashboard Portal Klien | `http://localhost:8000/dashboard/klien` |

---

## LOGIN CREDENTIALS

### Internal Dashboard

#### Superadmin
- **Email:** admin@nabilla.local
- **Password:** password123
- **Akses:** Dashboard Superadmin, Semua Menu

#### Pemilik
- **Email:** pemilik@nabilla.local
- **Password:** password123
- **Akses:** Dashboard Pemilik, Kelola Paket Master, Monitoring Target

#### Sales 1
- **Email:** rina@nabilla.local
- **Password:** password123
- **Akses:** Dashboard Sales, Kelola Klien, Transaksi

#### Sales 2
- **Email:** budi@nabilla.local
- **Password:** password123
- **Akses:** Dashboard Sales, Kelola Klien, Transaksi

### Portal Klien

#### Klien 1
- **Email:** adi.wijaya@email.com
- **Password:** password123

#### Klien 2
- **Email:** siti.nurhaliza@email.com
- **Password:** password123

#### Klien 3
- **Email:** ahmad.rahman@email.com
- **Password:** password123

---

## STRUKTUR PROJECT

```
d:\APK-NabillaCetring\
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── DashboardController.php
│   │   │   └── [...]
│   │   ├── Middleware/
│   │   │   ├── CheckRole.php
│   │   │   ├── EnsureClientIsAuthenticated.php
│   │   │   └── [...]
│   │   └── Kernel.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Klien.php
│   │   ├── TransaksiPenjualan.php
│   │   ├── Pembayaran.php
│   │   ├── TargetPenjualan.php
│   │   ├── PaketMaster.php
│   │   ├── PaketMasterHarga.php
│   │   ├── TransaksiDetail.php
│   │   ├── NotifikasiPelunasan.php
│   │   └── [...]
│   └── Providers/
├── bootstrap/
├── config/
│   ├── auth.php (Guard & Provider Config)
│   ├── app.php
│   ├── database.php
│   └── [...]
├── database/
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 2026_01_01_000001_create_klien_table.php
│   │   ├── 2026_01_02_000000_create_target_penjualan_table.php
│   │   └── [...]
│   ├── seeders/
│   │   └── DatabaseSeeder.php
│   └── factories/
├── public/
│   ├── index.php
│   ├── .htaccess
│   └── [...]
├── resources/
│   ├── views/
│   │   ├── auth/
│   │   │   ├── login.blade.php
│   │   │   ├── client-login.blade.php
│   │   │   └── [...]
│   │   ├── dashboard/
│   │   │   ├── superadmin.blade.php
│   │   │   ├── pemilik.blade.php
│   │   │   ├── sales.blade.php
│   │   │   └── klien.blade.php
│   │   ├── layouts/
│   │   │   ├── app.blade.php
│   │   │   └── client.blade.php
│   │   └── [...]
│   ├── css/
│   ├── js/
│   └── [...]
├── routes/
│   ├── web.php (Routes Utama)
│   ├── api.php
│   ├── console.php
│   └── channels.php
├── storage/
│   ├── app/
│   ├── framework/
│   ├── logs/
│   └── [...]
├── tests/
├── vendor/ (Dependencies - Auto Generated)
├── .env (Auto Generated)
├── .env.example
├── .gitignore
├── composer.json
├── composer.lock
├── artisan
├── package.json
├── vite.config.js
├── phpunit.xml
├── ANALISIS_ROLE_DAN_HAK_AKSES.md
├── TAHAP1_ALUR_DATA_DAN_LOGIKA_BISNIS.md
├── TAHAP2_AUTHENTICATION_LAYOUT_DASHBOARD.md
├── SETUP_DAN_RUNNING_PROJECT.md (File ini)
└── README.md
```

---

## TROUBLESHOOTING

### Error: "SQLSTATE[HY000] [2002] No such file or directory"
**Penyebab:** Database server tidak berjalan

**Solusi:**
```bash
# Windows: Start MySQL service
# atau via Services control panel

# Linux: 
sudo service mysql start

# Mac: 
brew services start mysql
```

### Error: "Class 'Klien' not found"
**Penyebab:** Autoloader belum diupdate

**Solusi:**
```bash
composer dump-autoload
```

### Error: "No application encryption key has been specified"
**Penyebab:** APP_KEY belum di-generate

**Solusi:**
```bash
php artisan key:generate
```

### Error: "Column not found in database"
**Penyebab:** Migration belum dijalankan

**Solusi:**
```bash
php artisan migrate
# Atau jika perlu rollback:
php artisan migrate:rollback
php artisan migrate
```

### Error: "No table entries found"
**Penyebab:** Database belum di-seed

**Solusi:**
```bash
php artisan db:seed
```

### Error: "Session path ... is not writable"
**Penyebab:** Folder storage tidak writable

**Solusi:**
```bash
# Windows: 
# Klik kanan folder storage → Properties → Security → Edit Permissions
# Grant write access ke user current

# Linux/Mac:
chmod -R 775 storage bootstrap/cache
sudo chown -R www-data:www-data storage bootstrap/cache
```

### Error: "Composer dependency issues"
**Penyebab:** Versi PHP atau dependency tidak sesuai

**Solusi:**
```bash
# Clear composer cache
composer clear-cache

# Reinstall dependencies
rm -rf vendor
composer install
```

---

## DEVELOPMENT TIPS

### Database Refresh (Buat DB dari 0)
```bash
# Rollback semua migrations & re-seed
php artisan migrate:refresh --seed

# Atau gunakan untuk development:
php artisan migrate:fresh --seed
```

### View Logs
```bash
# Real-time log viewer
tail -f storage/logs/laravel.log

# Windows PowerShell:
Get-Content storage/logs/laravel.log -Wait
```

### Test Database Connection
```bash
php artisan tinker

# Di tinker console:
>>> DB::connection()->getPdo()
>>> DB::table('users')->count()
```

### Cache Clear
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Generate Model & Migration
```bash
# Existing table:
php artisan make:model MyModel

# With migration:
php artisan make:model MyModel -m

# With controller:
php artisan make:model MyModel -m -c
```

---

## PRODUCTION DEPLOYMENT CHECKLIST

- [ ] Set `APP_DEBUG=false` di .env
- [ ] Set `APP_ENV=production` di .env
- [ ] Generate strong `APP_KEY`
- [ ] Setup SSL Certificate (HTTPS)
- [ ] Configure proper database backups
- [ ] Setup proper error logging & monitoring
- [ ] Optimize autoloader: `composer install --optimize-autoloader --no-dev`
- [ ] Cache config: `php artisan config:cache`
- [ ] Cache routes: `php artisan route:cache`
- [ ] Setup task scheduler (cron jobs) untuk notifikasi H-30

---

## NEXT STEPS

Setelah project running, lanjutkan dengan TAHAP 3:
- CRUD Paket Master (Create, Read, Update, Delete)
- CRUD Transaksi Penjualan
- CRUD Pembayaran
- CRUD Data Klien
- API endpoints (optional)
- Advanced features (export, email, SMS notification)

---

## SUPPORT & DOCUMENTATION

### Laravel Documentation
- Official: https://laravel.com/docs/11.x
- API Reference: https://laravel.com/api/11.x

### Bootstrap 5 Documentation
- Official: https://getbootstrap.com/docs/5.3/

### Database Schema
- Lihat: TAHAP1_ALUR_DATA_DAN_LOGIKA_BISNIS.md (bagian ERD)

### Role & Permissions
- Lihat: ANALISIS_ROLE_DAN_HAK_AKSES.md

### Authentication & Dashboard
- Lihat: TAHAP2_AUTHENTICATION_LAYOUT_DASHBOARD.md

---

**Happy Coding! 🚀**
