# 🍽️ Sistem Monitoring Sales Performance NABILLA CATERING

Platform web modern untuk monitoring dan tracking target penjualan bulanan dengan dashboard real-time untuk 4 role berbeda.

## ✨ Status Project

✅ **TAHAP 1 & 2 COMPLETE 100%**

- Database schema & models ✓
- Authentication multi-role ✓
- 4 Role-specific dashboards ✓
- Modern Bootstrap 5 UI ✓
- Comprehensive documentation ✓

## 🚀 Quick Start

```bash
# Setup
cp .env.example .env
php artisan key:generate

# Database
php artisan migrate
php artisan db:seed

# Run
php artisan serve
```

**Access:** http://localhost:8000/login  
**Demo:** admin@nabilla.local / password123

## 📚 Dokumentasi

1. **[BACA_INI_DULU.md](BACA_INI_DULU.md)** - Start here!
2. **[SETUP_DAN_RUNNING_PROJECT.md](SETUP_DAN_RUNNING_PROJECT.md)** - Setup guide
3. **[PROJECT_STATUS_AND_SUMMARY.md](PROJECT_STATUS_AND_SUMMARY.md)** - Status detail
4. **[SELESAI_TAHAP1_DAN_2.md](SELESAI_TAHAP1_DAN_2.md)** - What's done
5. **[TAHAP2_AUTHENTICATION_LAYOUT_DASHBOARD.md](TAHAP2_AUTHENTICATION_LAYOUT_DASHBOARD.md)** - Auth docs
6. **[TAHAP1_ALUR_DATA_DAN_LOGIKA_BISNIS.md](TAHAP1_ALUR_DATA_DAN_LOGIKA_BISNIS.md)** - Database docs
7. **[ANALISIS_ROLE_DAN_HAK_AKSES.md](ANALISIS_ROLE_DAN_HAK_AKSES.md)** - Roles & permissions

## 🎯 Fitur Utama

### 4 Dashboards
- **Superadmin** - Global metrics (users, sales, klien, omzet)
- **Pemilik** - Business metrics (target, pembayaran, transaksi)
- **Sales** - Personal metrics (target achievement, pipeline)
- **Klien** - Read-only portal (transaksi, pembayaran, H-30 alerts)

### Business Logic
- DP 10% calculation & tracking
- H-30 reminder system
- Monthly target tracking
- Payment status flow
- Real-time metrics

### Technology
- **Backend:** Laravel 11
- **Database:** MySQL/MariaDB
- **Frontend:** Bootstrap 5 + Blade
- **Auth:** Multi-guard system

## 📊 Database

9 tables dengan proper relationships:
- users (internal users)
- klien (external portal users)
- paket_master (catering packages)
- paket_master_harga (price variants)
- target_penjualan (monthly targets)
- transaksi_penjualan (sales transactions)
- transaksi_detail (transaction items)
- pembayaran (payment records)
- notifikasi_pelunasan (H-30 reminders)

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
