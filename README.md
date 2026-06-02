# 🍫 Es Coklat Mas Lino — Sistem Pemesanan

Sistem pemesanan berbasis web untuk UMKM minuman **Es Coklat Mas Lino** di Jalan Bangau Sakti, Pekanbaru.

## Fitur Utama
- 📱 **Web Mobile Pelanggan** — Pesan via QR-Code tanpa instalasi
- 🖥️ **Dashboard Admin/Kasir** — Kelola pesanan & menu
- 🤖 **Rekomendasi SAW** — Rekomendasi paket bundling cerdas
- 💳 **Midtrans** — Pembayaran online via Snap
- ⭐ **Rating & Ulasan** — Feedback pelanggan

## Tech Stack
- **Backend**: Laravel 12 + PHP 8.2+
- **Database**: MySQL/MariaDB
- **Payment**: Midtrans Snap
- **QR Code**: simplesoftwareio/simple-qrcode

## Setup Project

```bash
# 1. Install dependencies
composer install

# 2. Konfigurasi environment
cp .env.example .env
php artisan key:generate

# 3. Konfigurasi database di .env
# DB_DATABASE=mas_lino_laravel
# DB_USERNAME=root
# DB_PASSWORD=

# 4. Konfigurasi Midtrans di .env
# MIDTRANS_SERVER_KEY=your_key
# MIDTRANS_CLIENT_KEY=your_key
# MIDTRANS_IS_PRODUCTION=false

# 5. Jalankan migrasi & seeder
php artisan migrate --seed

# 6. Link storage
php artisan storage:link

# 7. Jalankan server
php artisan serve
```

## Akun Admin Default
- **Username**: `admin`
- **Password**: `admin123`

## URL Akses
- Pelanggan: `http://localhost:8000/`
- Admin: `http://localhost:8000/admin/login`
