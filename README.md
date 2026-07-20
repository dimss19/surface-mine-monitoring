# Surface Mine Production — Sistem Absensi & Pemantauan Lapangan

<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

<p align="center">
  <a href="https://github.com/dimss19/surface-mine-monitoring"><img src="https://img.shields.io/badge/status-active-brightgreen" alt="Status"></a>
  <a href="https://laravel.com"><img src="https://img.shields.io/badge/Laravel-13.x-red" alt="Laravel 13.x"></a>
  <a href="https://www.php.net/releases/8.3/"><img src="https://img.shields.io/badge/PHP-8.3+-purple" alt="PHP 8.3+"></a>
  <a href="https://opensource.org/licenses/MIT"><img src="https://img.shields.io/badge/license-MIT-blue" alt="License"></a>
</p>

---

## 📋 Tentang Proyek

**Surface Mine Production** adalah sistem informasi berbasis web untuk mendigitalkan proses absensi *Hour Meter* (HM) alat berat di lapangan serta pemantauan area kerja tambang permukaan (surface mine). Sistem ini memiliki **3 role pengguna**:

| Role | Deskripsi |
|------|-----------|
| 👷 **Pegawai** | Mengisi absensi HM alat berat tanpa perlu login (form publik) |
| 👁️ **SPV** | Login untuk memantau area kerja, melihat rekap absensi, dan membuat laporan pemantauan lapangan |
| 🔧 **Admin** | Login untuk mengelola master data, akun SPV, serta melihat & mengekspor seluruh data |

### Fitur Utama

- ✅ Landing page publik untuk absensi pegawai (tanpa login)
- ✅ Login berbasis session untuk SPV & Admin (password Argon2id)
- ✅ Dashboard SPV: pemantauan area, rekap absensi, laporan pemantauan (foto, deskripsi, kendala, progress)
- ✅ Dashboard Admin: manajemen SPV, CRUD master data (Pegawai, Alat/Unit, Area)
- ✅ Upload foto (multiple) untuk laporan pemantauan lapangan
- ✅ Export data ke `.xlsx`
- ✅ PWA — bisa di-install ke homescreen mobile
- ✅ Full CRUD master data oleh Admin

---

## 🛠 Tech Stack

| Teknologi | Versi / Keterangan |
|-----------|--------------------|
| **Framework** | [Laravel 13.x](https://laravel.com) |
| **Bahasa** | PHP ^8.3 |
| **Database** | MySQL via Eloquent ORM |
| **Frontend** | Laravel Blade + Alpine.js + Tailwind CSS |
| **Admin Panel** | [Filament](https://filamentphp.com) |
| **Auth** | Session-based (guard `web`), 2 role login: `spv` & `admin` |
| **Hashing** | Argon2id |
| **File Upload** | Local disk storage (`storage/app/public/pemantauan`) |
| **Export Excel** | [Maatwebsite/Laravel-Excel](https://docs.laravel-excel.com) |
| **Media Library** | [Spatie Laravel MediaLibrary](https://spatie.be/docs/laravel-medialibrary) |

---

## 📦 Persyaratan Sistem

Sebelum memulai instalasi, pastikan sistem Anda sudah memiliki:

1. **PHP** >= 8.3 ([unduh](https://www.php.net/downloads))
2. **Composer** ([unduh](https://getcomposer.org/download/))
3. **MySQL** / MariaDB (disarankan via [Laragon](https://laragon.org) atau [XAMPP](https://www.apachefriends.org/))
4. **Node.js** & **NPM** ([unduh](https://nodejs.org/))
5. **Git** ([unduh](https://git-scm.com/downloads))

> **💡 Rekomendasi:** Gunakan [Laragon](https://laragon.org) untuk kemudahan pengembangan — Laragon menyediakan Apache/Nginx, PHP, MySQL, dan Node.js dalam satu paket.

---

## 🚀 Instalasi

### Langkah 1 — Clone Repository

```bash
git clone https://github.com/dimss19/surface-mine-monitoring.git
cd surface-mine-production
```

Atau jika menggunakan **Laragon**, letakkan folder project di dalam direktori `laragon/www/`:

```bash
cd D:\laragon\www
git clone https://github.com/dimss19/surface-mine-monitoring.git
```

### Langkah 2 — Install Dependency PHP (Composer)

```bash
composer install
```

> Jika ada error terkait ekstensi PHP, pastikan ekstensi yang dibutuhkan sudah aktif (biasanya tercantum di pesan error, misal `ext-fileinfo`, `ext-pdo`, `ext-mbstring`, `ext-gd`, dll).

### Langkah 3 — Install Dependency Frontend (NPM)

```bash
npm install
npm run build
```

### Langkah 4 — Konfigurasi Environment

---

### Langkah 5 — Buat Database MySQL

#### 📘 Tutorial Membuat Database

Berikut adalah beberapa cara membuat database **`surface_mine_production`** yang diperlukan oleh aplikasi ini.

---

##### Opsi A — Melalui phpMyAdmin (Termudah)

1. Buka **phpMyAdmin** di browser:
   - Laragon: <http://localhost/phpmyadmin>
   - XAMPP: <http://localhost/phpmyadmin>
2. Login dengan user `root` (password kosong jika default).
3. Klik tab **Databases** / **New**.
4. Masukkan nama database: **`surface_mine_production`**
5. Pilih _Collation_: **`utf8mb4_unicode_ci`**
6. Klik **Create**.

![phpMyAdmin Create Database](https://docs.phpmyadmin.net/en/latest/_images/phpmyadmin-create-database.png)

---

##### Opsi B — Melalui Command Line (MySQL CLI)

```bash
# Masuk ke MySQL
mysql -u root -p

# Setelah masuk ke prompt MySQL, jalankan:
CREATE DATABASE IF NOT EXISTS surface_mine_production
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

# Keluar
EXIT;
```

---

##### Opsi C — Melalui Laragon MySQL CLI

1. Buka **Laragon** → Klik kanan icon Laragon → **MySQL** → **Open MySQL**
2. Atau buka terminal dan jalankan:

```bash
# Cari direktori MySQL di Laragon
cd D:\laragon\bin\mysql\mysql-8.0.30\bin
mysql -u root
```

> **Catatan:** Versi folder MySQL menyesuaikan dengan versi yang terinstall di Laragon Anda.

3. Setelah masuk ke prompt MySQL (`mysql>`), jalankan:

```sql
CREATE DATABASE IF NOT EXISTS surface_mine_production
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
```

4. Verifikasi database telah dibuat:

```sql
SHOW DATABASES;
```

5. Keluar:

```sql
EXIT;
```

---

##### Opsi D — Melalui Laravel Artisan (Custom Command)

Jika Anda sudah membuat custom Artisan command untuk membuat database:

```bash
php artisan db:create
```

---

### Langkah 7 — Jalankan Migration & Seeder

Migration akan membuat tabel-tabel yang dibutuhkan secara otomatis. Seeder akan mengisi data awal (master data Pegawai, Alat/Unit, Area, dan akun default SPV/Admin).

```bash
php artisan migrate --seed
```

Proses ini akan membuat tabel-tabel berikut (dan lainnya):

| Tabel | Fungsi |
|-------|--------|
| `users` | Akun SPV & Admin |
| `pegawais` | Data pegawai |
| `alats` | Data alat/unit |
| `areas` | Data area/lokasi |
| `area_spv` | Relasi area dengan SPV (many-to-many) |
| `absensis` | Data absensi pegawai |
| `pemantauans` | Laporan pemantauan lapangan |
| `sessions` | Session login |
| `cache` | Cache aplikasi |
| `jobs` & `failed_jobs` | Antrian job |

#### Akun Default (Seeder)

Setelah migrasi + seeder selesai, akun default yang tersedia untuk login:

| Role | Email | Password |
|------|-------|----------|
| **Admin** | `admin@example.com` | `password` |
| **SPV** | `spv@example.com` | `password` |

> **⚠️ Peringatan:** Ganti password default ini segera setelah pertama kali login pada lingkungan production!

---

### Langkah 8 — Buat Symlink Storage

```bash
php artisan storage:link
```

Perintah ini membuat link simbolis dari `public/storage` ke `storage/app/public` agar file upload (foto pemantauan) dapat diakses dari browser.

---

### Langkah 9 — Jalankan Aplikasi

#### ✅ Mode Development (via Laragon)

Jika menggunakan **Laragon**, cukup:
1. Klik tombol **Start All**
2. Akses di browser: <http://surface-mine-production.test> (jika auto-virtual-host aktif)
3. Atau akses via <http://localhost/surface-mine-production/public>

#### ✅ Mode Development (via Artisan Serve)

```bash
php artisan serve
```

Akses di browser: <http://localhost:8000>

#### ✅ Mode Development Lengkap (Queue + Vite + Logs)

Menjalankan server, queue listener, logs, dan Vite secara bersamaan:

```bash
composer run dev
```

Atau jalankan secara terpisah di 4 terminal:

```bash
# Terminal 1: Laravel dev server
php artisan serve

# Terminal 2: Queue worker (untuk job & export excel)
php artisan queue:listen --tries=1 --timeout=0

# Terminal 3: Log viewer (Pail)
php artisan pail --timeout=0

# Terminal 4: Vite dev server (hot reload untuk frontend)
npm run dev
```

---

## 🧪 Menjalankan Test

```bash
php artisan test
```

Atau melalui Composer script:

```bash
composer run test
```

> **Catatan:** Perintah `db:create` bukan bawaan Laravel. Jika belum ada, gunakan opsi A, B, atau C.

---

### Langkah 6 — Sesuaikan Koneksi Database di `.env`

Edit file `.env` dan sesuaikan bagian database dengan konfigurasi MySQL Anda:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=surface_mine_production
DB_USERNAME=root
DB_PASSWORD=
```

**Penyesuaian:**

| Skenario | Konfigurasi |
|----------|-------------|
| **Laragon / XAMPP (default)** | `DB_USERNAME=root` dan `DB_PASSWORD=` (kosongkan) |
| **MySQL dengan password** | Isi `DB_PASSWORD` dengan password MySQL Anda |
| **MySQL di port berbeda** | Sesuaikan `DB_PORT` (misal `3307`) |
| **Nama database berbeda** | Ubah `DB_DATABASE` sesuai keinginan (misal `surface_mine`) |

> **Pastikan nama database di `.env` sudah sesuai dengan database yang Anda buat di Langkah 5.**

Salin file `.env.example` menjadi `.env`:

```bash
cp .env.example .env
```

Atau di Windows:

```bash
copy .env.example .env
```

Kemudian generate **APP_KEY**:

```bash
php artisan key:generate
```
