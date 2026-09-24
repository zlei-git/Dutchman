# WALKEN — Shoes & Lifestyle
> **Tagline:** *“Walk Your Way.”*  
> **Project:** Tugas E-Commerce & Try-On Booking Sepatu Modern (Proyek Pribadi / Tugas No. 5)

---

## 1. Overview

**WALKEN** adalah aplikasi web e-commerce sepatu dan reservasi fitting toko (*In-Store Try On Booking*) modern yang dibangun menggunakan **Laravel (PHP)** dengan arsitektur server-rendered menggunakan **Blade Templating**, styling responsif kustom **CSS3**, dan interaktivitas modular menggunakan **Vanilla JavaScript**.

WALKEN dirancang bukan sekadar landing page atau mockup visual, melainkan aplikasi fungsional menyeluruh yang mencakup:
- Katalog produk dinamis dengan pencarian, multi-filter (kategori, ukuran, warna, harga, ketersediaan stok), dan pengurutan (*sorting*).
- Halaman detail produk dengan pemilihan warna, ukuran (39–45), indikator live stok, kuantitas, *Add to Cart*, *Buy Now*, dan *Wishlist*.
- Keranjang belanja (*Shopping Cart*) yang memvalidasi stok dan harga secara aman di server, didukung kupon voucher promo.
- Proses Checkout lengkap dengan alamat pengiriman, opsi kurir/pengambilan di toko, metode pembayaran simulasi (Bank Transfer Virtual Account, QRIS Demo, E-Wallet Demo, COD), transaksi database atomik (`DB::transaction`), dan pengurangan stok otomatis.
- Fitur pembeda **Booking Try On** untuk reservasi jadwal mencoba sepatu langsung di cabang toko fisik (dengan validasi anti-bentrok slot waktu dan kapasitas per jam).
- Riwayat pesanan & pelacakan status (*tracking timeline*), simulasi konfirmasi pembayaran, serta fitur *Reorder*.
- Panel Administrasi (**Admin Panel**) lengkap untuk mengelola produk & varian, kategori, pesanan, reservasi try-on, inventaris stok, cabang toko, voucher diskon, pengguna, dan laporan analitik penjualan.

---

## 2. Tech Stack

- **Backend:** Laravel 11/12, PHP 8.4, Eloquent ORM, Laravel Migration & Seeder, Laravel Middleware, Form Validation.
- **Frontend:** Laravel Blade, HTML5 Semantic, CSS3 Custom Design System (Mobile-First, Breakpoints 320px, 768px, 1024px+), Vanilla JavaScript.
- **Asset Bundler:** Vite (hanya untuk asset bundling/build).
- **Database:** MySQL / MariaDB (`walken`).
- **Aturan Ketat:** Tidak menggunakan SPA framework (React, Vue, Next.js, Nuxt, Angular, Inertia, Livewire) maupun Node.js backend.

---

## 3. Brand Identity & Design System

- **Primary Dark:** `#111111`
- **Primary Light:** `#262626`
- **Accent Orange:** `#FF5A1F`
- **Background Light:** `#F6F6F6`
- **Surface Card:** `#FFFFFF`
- **Typography:** Plus Jakarta Sans & Inter
- **Style Direction:** Modern, Minimalist, Sporty, Clean, Youthful, dan Premium.
- **Touch Targets:** &ge; 44x44px nyaman untuk navigasi layar sentuh di perangkat mobile.

---

## 4. Akun Demo Bawaan (Seed Data)

Database telah dilengkapi dengan akun demo siap pakai:

| Role | Email | Password | Keterangan |
|---|---|---|---|
| **Administrator** | `admin@walken.test` | `password` | Akses penuh dashboard `/admin` |
| **Customer / User** | `user@walken.test` | `password` | Pelanggan dengan data order & booking riwayat |
| **Customer 2** | `sarah@walken.test` | `password` | Pelanggan kedua untuk pengujian multi-user |

*Tersedia tombol satu-klik "Quick Fill" di halaman `/login` untuk memudahkan pengujian.*

---

## 5. Struktur Halaman & Rute Utama

### Halaman Publik
- `/` — Homepage (Hero, Kategori, Produk Unggulan, New Arrivals, Promo Banner, Kenapa WALKEN, Footer)
- `/about` — Cerita Brand WALKEN, Visi, Misi, Informasi Cabang Toko Fisik, Kontak
- `/products` — Katalog sepatu dengan pencarian, filter ukuran/warna/harga/stok, pagination
- `/products/{identifier}` — Detail produk, galeri gambar, pemilih ukuran/warna, live stock, CTA
- `/login` & `/register` — Autentikasi akun

### Halaman Pelanggan (Memerlukan Login)
- `/user/dashboard` — Ringkasan akun, pesanan aktif, jadwal booking try-on mendatang, wishlist
- `/user/cart` — Keranjang belanja, ubah kuantitas, klaim kode promo (`WALK10`)
- `/user/checkout` — Formulir alamat, pemilihan kurir, simulasi pembayaran, ringkasan harga
- `/user/orders` — Daftar riwayat pesanan dengan filter status
- `/user/orders/{id}` — Detail pesanan, timeline status, simulasi konfirmasi bayar, reorder
- `/user/bookings` — Daftar reservasi In-Store Try On
- `/user/bookings/create` — Formulir booking try on dengan pemilihan slot waktu real-time
- `/user/wishlist` — Daftar sepatu favorit tersimpan, tombol *Move to Cart*
- `/user/profile` — Pengaturan profil, ganti password, buku alamat tersimpan

### Halaman Admin (`/admin`, Dilindungi Middleware Role Admin)
- `/admin/dashboard` — Metrik pendapatan, total order, total booking, peringatan stok menipis
- `/admin/products` — CRUD produk, varian ukuran, warna, stok, dan foto
- `/admin/categories` — Manajemen kategori produk
- `/admin/orders` — Manajemen status pesanan & status pembayaran
- `/admin/bookings` — Manajemen status reservasi try-on dan catatan staf toko
- `/admin/stocks` — Monitoring inventaris dan penyesuaian stok kilat
- `/admin/branches` — Manajemen lokasi cabang toko fisik & kapasitas slot per jam
- `/admin/promotions` — Manajemen kode voucher diskon
- `/admin/users` — Manajemen akun pengguna (aktivasi/suspend)
- `/admin/reports` — Laporan analitik penjualan dan tren booking

---

## 6. Instalasi & Menjalankan Aplikasi Secara Lokal

### Prasyarat
- PHP >= 8.2 (Direkomendasikan PHP 8.4) dengan ekstensi PDO, OpenSSL, Mbstring, Tokenizer, XML, Ctype, JSON.
- Composer >= 2.x
- MySQL / MariaDB Server (misal via Laragon atau XAMPP)
- Node.js & npm (untuk build aset)

### Langkah-Langkah

1. **Buka folder proyek:**
   ```bash
   cd d:\walken
   ```

2. **Instal dependensi PHP:**
   ```bash
   composer install
   ```

3. **Konfigurasi Environment File:**
   Salin `.env.example` menjadi `.env` jika belum ada:
   ```bash
   cp .env.example .env
   ```
   Pastikan konfigurasi database di `.env` sudah sesuai dengan server MySQL lokal Anda:
   ```ini
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=walken
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Generate Application Key:**
   ```bash
   php artisan key:generate
   ```

5. **Jalankan Migrasi Database & Seeder:**
   Perintah ini akan membuat semua tabel dan mengisi data demo (14 produk sepatu orisinil, varian lengkap 39–45, cabang toko, akun demo, promo `WALK10`, dan contoh transaksi):
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Build Asset Frontend (Vite):**
   ```bash
   npm install
   npm run build
   ```

7. **Jalankan Server Lokal Laravel:**
   ```bash
   php artisan serve
   ```
   Akses aplikasi melalui browser di: **http://127.0.0.1:8000**

---

## 7. Verifikasi & Pengujian Otomatis

Aplikasi dilengkapi suite pengujian otomatis fitur (*Feature Tests*) untuk memastikan seluruh flow utama berjalan tanpa galat:

```bash
php artisan test
```

Pengujian mencakup:
- Ketersediaan rute publik (Home, About, Products, Detail)
- API dinamis `/api/booking-slots`
- Proteksi otorisasi Role Middleware pada area `/admin` (penolakan akses guest & pembatasan role user biasa).

---

## 8. Panduan Deployment

### Catatan Penting tentang GitHub Pages
> **PERHATIAN:** **GitHub Pages TIDAK DAPAT digunakan untuk menjalankan backend Laravel/PHP.**  
> GitHub Pages hanya dirancang untuk hosting file statis HTML/CSS/JS client-side saja.  
> GitHub dapat digunakan sebagai **repository version control source code**, tetapi aplikasi WALKEN membutuhkan server yang menjalankan PHP dan database MySQL/MariaDB.

### Opsi Hosting yang Didukung
Untuk menjalankan WALKEN di lingkungan produksi publik, gunakan:
1. **Cloud VPS / PaaS:** DigitalOcean, Linode, AWS EC2, Railway, atau Render yang menyediakan runtime PHP 8.2+ dan MySQL service.
2. **Laravel Forge / Ploi:** Untuk manajemen deployment otomatis dari git repository ke server VPS.
3. **cPanel Shared Hosting:** Hosting berbasis Linux dengan PHP 8.2+, ekstensi MySQL, dan akses terminal/Composer.

### Checklist Deployment Produksi:
1. Set `APP_ENV=production` dan `APP_DEBUG=false` di `.env`.
2. Generate key: `php artisan key:generate`.
3. Jalankan migrasi: `php artisan migrate --force`.
4. Optimasi cache Laravel:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```
5. Jangan pernah mempublikasikan file `.env` ke repository publik. Gunakan `.env.example`.

---

## 9. Lisensi & Kredit

Project pribadi ini dikembangkan untuk keperluan demonstrasi akademik tugas pengembangan aplikasi web e-commerce modern (Tugas No. 5). Seluruh aset visual, logo, dan nama produk sepatu WALKEN dibuat orisinil tanpa menyalin identitas brand komersial lain.
