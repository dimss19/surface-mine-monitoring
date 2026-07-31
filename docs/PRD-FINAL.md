# Product Requirements Document (PRD) — Final

## Surface Mine Production Operational Record

**Versi:** 1.0 Final  
**Tanggal:** 29 Juli 2026  
**Status:** Production-Ready

---

## 1. Ringkasan Proyek

Aplikasi web PWA (Progressive Web App) untuk pencatatan dan pemantauan operasional harian pertambangan open pit, khususnya departemen civil. Aplikasi mendukung mode offline-first untuk area tambang yang tidak memiliki koneksi internet stabil.

---

## 2. Stack Teknologi & Alasan Penggunaan

### Backend
| Teknologi | Versi | Alasan Penggunaan |
|-----------|-------|-------------------|
| **PHP** | 8.3.24 | Bahasa server-side utama, performa tinggi dengan JIT compiler |
| **Laravel** | 13.20.0 | Framework PHP terkemuka; menyediakan ORM, routing, middleware, autentikasi, migrasi database, dan blade templating secara bawaan |
| **Laravel Breeze** | 2.4.2 | Starter kit autentikasi yang ringan; menyediakan login, register, logout, dan profil dasar — dikustom untuk login via username/email/nama |

### Frontend
| Teknologi | Versi | Alasan Penggunaan |
|-----------|-------|-------------------|
| **Tailwind CSS** | 3.4.19 | Utility-first CSS framework; mempercepat development UI tanpa menulis CSS custom. Digunakan di **semua komponen**: sidebar, topbar, dashboard cards, form inputs, tables, status badges, navigation, dan responsive layout |
| **@tailwindcss/forms** | ^0.5.x | Plugin Tailwind untuk styling form elements secara konsisten (input, select, textarea, checkbox) — digunakan di semua form input operator |
| **@tailwindcss/vite** | - | Integrasi Tailwind dengan Vite build system |
| **Alpine.js** | 3.15.12 | Lightweight JS framework untuk interaktivitas DOM (toggle sidebar, dropdown, modal) tanpa perlu full SPA framework |
| **Vite** | 8.1.5 | Build tool modern; HMR cepat saat development, optimasi bundle untuk production |
| **Laravel Vite Plugin** | ^3.1 | Integrasi Vite dengan Laravel Blade templates |
| **Chart.js** | CDN | Library charting untuk visualisasi data dashboard (bar chart, line chart) — digunakan di admin dan SPV dashboard |

### Database & Infrastructure
| Teknologi | Versi | Alasan Penggunaan |
|-----------|-------|-------------------|
| **SQLite** | Bawaan PHP | Database file-based untuk development & testing; tanpa konfigurasi server database |
| **MySQL/MariaDB** | - | Database production yang direkomendasikan |
| **Composer** | - | Dependency manager untuk package PHP |
| **npm** | - | Package manager untuk frontend dependencies |

### Keamanan
| Komponen | Implementasi |
|----------|-------------|
| **Password Hashing** | Argon2id (`HASH_DRIVER=argon2id`) — algoritma pemenang Password Hashing Competition; resistant terhadap GPU/ASIC attacks dengan parameter: memory=65536KB, threads=4, time=4 |
| **Session Encryption** | `SESSION_ENCRYPT=true` — mengenkripsi semua data session untuk mencegah manipulasi cookie |
| **CSRF Protection** | Laravel CSRF token pada semua form POST — mencegah Cross-Site Request Forgery |
| **Rate Limiting** | Login throttling max 5 attempt per IP — mencegah brute force attack pada halaman login |
| **Role-Based Access Control (RBAC)** | Middleware `role:admin`, `role:spv`, `role:pegawai` — setiap route dilindungi berdasarkan role user |
| **XSS Protection** | Blade template engine otomatis escape semua output `{{ }}` — mencegah Cross-Site Scripting |
| **SQL Injection Prevention** | Eloquent ORM menggunakan parameterized query — mencegah SQL injection |
| **Input Validation** | FormRequest validation pada semua endpoint store/update — memastikan data yang masuk sesuai aturan bisnis |
| **Security Headers** | Laravel middleware bawaan: `TrustProxies`, `HandleCors`, `PreventRequestsDuringMaintenance` |
| **Password Policy** | Minimum 8 karakter,Argon2id hash dengan parameter tinggi |

### Offline-First Architecture
| Komponen | Teknologi | Fungsi |
|----------|-----------|--------|
| **Service Worker** | Vanilla JS (`public/sw.js`) | Cache aset statis dan fallback offline |
| **IndexedDB** | Vanilla JS (`resources/js/offline-sync.js`) | Penyimpanan lokal untuk form data saat offline |
| **Offline Queue** | Custom implementation | Antrian data yang menunggu sync saat kembali online |
| **CSRF Refresh** | AJAX `/csrf-token` | Refresh CSRF token sebelum replay data offline |

---

## 3. Arsitektur Aplikasi

```
┌─────────────────────────────────────────────┐
│                  Client                      │
│  Blade Templates + Tailwind CSS + Alpine.js  │
│  Service Worker + IndexedDB (Offline)        │
└──────────────────┬──────────────────────────┘
                   │ HTTP/HTTPS
┌──────────────────▼──────────────────────────┐
│              Laravel 13 Backend              │
│  Routes → Middleware → Controllers → Models  │
│  Breeze Auth → RoleMiddleware → RBAC         │
│  Eloquent ORM → SQLite/MySQL                 │
└─────────────────────────────────────────────┘
```

---

## 4. Database Schema & Relasi

### Tabel Utama

#### `users`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint PK | Auto-increment |
| name | string | Nama lengkap |
| email | string, unique | Email (opsional) |
| username | string, unique | Username untuk login |
| password | string | Argon2id hash |
| role | enum('admin','spv','pegawai') | Role user |
| pegawai_id | bigint FK nullable | Relasi ke tabel pegawai |
| area_id | bigint FK nullable | Area kerja |
| status | enum('active','inactive') | Status akun |

**Relasi:**
- `belongsTo(Pegawai)`, `belongsTo(Area)`, `belongsTo(Unit)`
- `belongsToMany(Area, 'area_spv')` — untuk SPV yang mengawasi beberapa area
- `hasMany(Ritasi, 'validated_by')` — data yang divalidasi

#### `pegawais`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint PK | Auto-increment |
| nama | string | Nama pegawai |
| nip | string | Nomor induk pegawai |
| jabatan | string | Jabatan |
| status | enum | Status kepegawaian |

**Relasi:**
- `hasOne(User)`, `hasMany(Ritasi)`, `hasMany(NonRitasi)`

#### `areas`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint PK | Auto-increment |
| nama | string | Nama area |
| kode | string, unique | Kode area |
| penanggung_jawab | string | Nama PJ |
| status | enum('aktif','cuti','non-aktif') | Status area |

**Relasi:**
- `belongsToMany(User, 'area_spv')` — SPV
- `belongsToMany(Unit, 'unit_area')` — Unit yang beroperasi
- `hasMany(Ritasi)`, `hasMany(NonRitasi)`

#### `units`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint PK | Auto-increment |
| kode | string, unique | Kode unit (DT-1042) |
| nama | string | Nama unit |
| tipe | enum('excavator','dump_truck','bulldozer','motor_grader','loader','other') | Tipe unit |
| model | string nullable | Model unit |
| tahun | integer nullable | Tahun pembuatan |
| status | enum('active','maintenance','breakdown','standby') | Status unit |

**Relasi:**
- `belongsToMany(Area, 'unit_area')`
- `belongsToMany(Material, 'material_unit')` — dengan pivot `consumption_rate`
- `hasMany(Ritasi)`, `hasMany(NonRitasi)`

#### `materials`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint PK | Auto-increment |
| nama | string | Nama material |
| kode | string, unique | Kode material |
| satuan | string | Satuan (tonnes, kg, liters) |
| stok | numeric | Stok saat ini |
| status | enum('active','low_stock','inactive','restricted') | Status material |

**Relasi:**
- `belongsToMany(Unit, 'material_unit')`
- `hasMany(Ritasi)`, `hasMany(MaterialMovement)`

#### `ritasis`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint PK | Auto-increment |
| pegawai_id | bigint FK | Operator |
| unit_id | bigint FK | Unit yang digunakan |
| area_id | bigint FK | Area kerja |
| material_id | bigint FK | Material |
| shift | enum('siang','malam') | Shift kerja |
| tanggal | date | Tanggal pencatatan |
| hm_awal | decimal | Hour meter awal |
| hm_akhir | decimal | Hour meter akhir |
| jumlah_ritasi | integer | Jumlah trip |
| lokasi_pekerjaan | string nullable | Pit/Disposal |
| deskripsi_pekerjaan | text nullable | Keterangan |
| status | enum('pending','validated','rejected') | Status validasi |
| validated_by | bigint FK nullable | SPV/Admin yang validasi |

**Relasi:**
- `belongsTo(Pegawai)`, `belongsTo(Unit)`, `belongsTo(Area)`, `belongsTo(Material)`
- `belongsTo(User, 'validated_by')`

#### `non_ritasis`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint PK | Auto-increment |
| pegawai_id | bigint FK | Operator |
| unit_id | bigint FK | Unit |
| area_id | bigint FK | Area |
| shift | enum | Shift kerja |
| tanggal | date | Tanggal |
| hm_awal | decimal | HM awal |
| hm_akhir | decimal | HM akhir |
| lokasi_pekerjaan | string nullable | Lokasi |
| deskripsi_pekerjaan | text nullable | Keterangan |
| status | enum | Status validasi |
| validated_by | bigint FK nullable | Validator |

#### `permissions` & `role_permissions`
Tabel RBAC untuk mengelola izin akses per role.

### Diagram Relasi

```
User ──belongsTo── Pegawai
User ──belongsTo── Area
User ──belongsTo── Unit
User ──belongsToMany── Area (via area_spv)

Ritasi ──belongsTo── Pegawai
Ritasi ──belongsTo── Unit
Ritasi ──belongsTo── Area
Ritasi ──belongsTo── Material
Ritasi ──belongsTo── User (validated_by)

NonRitasi ──belongsTo── Pegawai
NonRitasi ──belongsTo── Unit
NonRitasi ──belongsTo── Area

Unit ──belongsToMany── Area (via unit_area)
Unit ──belongsToMany── Material (via material_unit)

Material ──hasMany── MaterialMovement
```

---

## 5. Fitur Per Role

### 5.1 Admin
| Fitur | Endpoint | Keterangan |
|-------|----------|------------|
| Monitoring Dashboard | `/admin/dashboard` | KPI cards, Daily Breakdown Activity (bar chart), Grafik All Hauling WTD, Monthly MTD Report |
| Laporan Pemantauan | `/admin/laporan` | Tabel data ritasi + non-ritasi, filter tanggal/shift/tipe unit, export Excel |
| Master Data - User | `/admin/master-data?tab=user` | CRUD user, pencarian, filter, pagination |
| Master Data - Area | `/admin/master-data?tab=area` | CRUD area, pencarian, pagination |
| Master Data - Unit | `/admin/master-data?tab=unit` | CRUD unit, pencarian, filter status, pagination |
| Master Data - Material | `/admin/master-data?tab=material` | CRUD material, pencarian, filter status, pagination |
| Master Data - Hak Akses | `/admin/master-data?tab=hak-akses` | Kelola izin per role |
| Profile | `/profile` | Edit profil, ubah password |

### 5.2 SPV (Supervisor)
| Fitur | Endpoint | Keterangan |
|-------|----------|------------|
| Monitoring Dashboard | `/spv/dashboard` | KPI cards, chart, sama seperti admin |
| Laporan Harian | `/spv/laporan/harian` | Data ritasi + non-ritasi per hari, filter tanggal |
| Laporan Mingguan | `/spv/laporan/mingguan` | Data per minggu, filter date range |
| Laporan Bulanan | `/spv/laporan/bulanan` | Data per bulan, filter bulan/tahun |
| Export | `/spv/laporan/export/{type}` | Export ke XLS |
| Profile | `/profile` | Edit profil |

### 5.3 Pegawai (Operator)
| Fitur | Endpoint | Keterangan |
|-------|----------|------------|
| Dashboard | `/pegawai` | Quick access ke form input |
| Form Ritasi | `/pegawai/ritasi/create` | Input: shift, tanggal, unit, HM awal/akhir, material, jumlah ritasi, lokasi, deskripsi |
| Form Non-Ritasi | `/pegawai/non-ritasi/create` | Input: shift, tanggal, unit, HM awal/akhir, lokasi, deskripsi |
| Form General | `/pegawai/general/create` | Input: shift, tanggal, unit, jam kerja, supervisor, lokasi, deskripsi |
| Riwayat | `/pegawai/ritasi/riwayat` | Daftar data yang sudah diinput |

---

## 6. UI/UX Design System

### Warna
| Token | Hex | Penggunaan |
|-------|-----|------------|
| Primary (Navy) | `#1e3a5f` | Judul, heading, sidebar active state |
| Sidebar BG | `#0f1d36` | Background sidebar |
| Accent (Gold) | `#d4a843` | Tombol, sidebar active border, logo |
| Background | `#f5f7fa` | Body background |
| White | `#ffffff` | Cards, topbar |

### Layout
- **Sidebar:** Fixed kiri, lebar 64 (w-64), dark navy
- **Topbar:** Fixed atas, lebar calc(100%-w-64), putih
- **Content:** `ml-64 pt-16 p-6`

### Komponen
- **Stat Card:** Border-kiri berwarna (biru/hijau/amber/ungu) dengan icon + value
- **Section Title:** Bold, navy color
- **Form Input:** Border abu-abu, focus ring biru
- **Status Badge:** Warna berbeda per status (active=hijau, maintenance=kuning, breakdown=merah, standby=abu)
- **Button Primary:** Amber/gold background
- **Button Secondary:** White background + border

---

## 7. Testing

### Manual Testing via Playwright
| Role | Halaman | Status |
|------|---------|--------|
| Admin | Dashboard | ✅ OK |
| Admin | Laporan Pemantauan | ✅ OK |
| Admin | Master Data (User/Area/Unit/Material/Hak Akses) | ✅ OK |
| Admin | Hak Akses | ✅ OK |
| Admin | Profile | ✅ OK |
| SPV | Dashboard | ✅ OK |
| SPV | Laporan Harian | ✅ OK |
| SPV | Laporan Mingguan | ✅ OK |
| SPV | Laporan Bulanan | ✅ OK |
| SPV | Export | ✅ OK |
| SPV | Profile | ✅ OK |
| Operator | Dashboard | ✅ OK |
| Operator | Form Ritasi (isi + submit) | ✅ OK |
| Operator | Form Non-Ritasi (isi + submit) | ✅ OK |
| Operator | Form General (isi + submit) | ✅ OK |
| Operator | Profile | ✅ OK |
| Public | Landing Page | ✅ Matches mockup |
| Public | Login Page | ✅ Matches mockup |

---

## 8. Deployment

### Requirements
- PHP 8.2+ dengan extension: OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON, BCMath
- Composer 2.x
- Node.js 18+ & npm
- Web server (Nginx/Apache) atau `php artisan serve`

### Setup
```bash
composer install
npm install && npm run build
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
```

### Default Users
| Username | Password | Role |
|----------|----------|------|
| admin | password | Admin |
| spv1 | password | SPV |
| pegawai.1 | password | Pegawai/Operator |

---

## 9. Folder Structure

```
├── app/
│   ├── Http/Controllers/     # 9 controllers
│   │   ├── Auth/             # Login, Register (Breeze)
│   │   ├── AdminController.php
│   │   ├── AdminUnitController.php
│   │   ├── AdminMaterialController.php
│   │   ├── AdminAreaController.php
│   │   ├── AdminPermissionController.php
│   │   ├── AdminLaporanController.php
│   │   ├── SpvController.php
│   │   ├── SpvLaporanController.php
│   │   ├── PegawaiController.php
│   │   ├── PegawaiRitasiController.php
│   │   ├── PegawaiNonRitasiController.php
│   │   └── PegawaiGeneralController.php
│   ├── Http/Middleware/       # RoleMiddleware
│   ├── Http/Requests/Auth/   # LoginRequest (custom)
│   └── Models/               # 9 models
├── database/migrations/      # 26 migrations
├── public/
│   ├── images/               # Assets statis
│   ├── sw.js                 # Service Worker
│   └── offline.html          # Fallback offline
├── resources/
│   ├── css/app.css           # Tailwind + custom components
│   ├── js/offline-sync.js    # IndexedDB offline queue
│   └── views/
│       ├── layouts/          # admin, operator, guest
│       ├── components/       # sidebar, topbar, kpi-card
│       ├── admin/            # Dashboard, master-data, laporan
│       ├── spv/              # Dashboard, laporan
│       ├── operator/         # Dashboard, ritasi, non-ritasi, general
│       └── auth/             # login
├── revisi/                   # Mockup design reference
└── routes/
    ├── web.php               # Main routes
    └── auth.php              # Breeze auth routes
```

---

## 10. catatan Teknis

1. **Tailwind CSS 3.4.19** digunakan di seluruh komponen UI — dari layout (sidebar, topbar, main content area) sampai komponen kecil (stat cards, form inputs, status badges, table rows, navigation items). CSS custom hanya ditambahkan di `resources/css/app.css` untuk component classes (`.sidebar`, `.sidebar-nav-item`, `.topbar`, `.stat-card`, `.form-label`, `.form-input`, `.btn-primary`, `.btn-secondary`).

2. **Alpine.js** digunakan untuk interaktivitas ringan seperti toggle sidebar, dropdown menu, dan modal — tidak memerlukan React/Vue karena aplikasi bukan SPA.

3. **Chart.js** dimuat via CDN untuk visualisasi chart di dashboard admin/SPV (bar chart daily hauling, line chart monthly report, doughnut gauge availability).

4. **Offline-first** diimplementasikan menggunakan Service Worker untuk caching aset statis dan IndexedDB untuk menyimpan form data saat offline, dengan queue system yang me-replay data ke server saat kembali online.

5. **Mockup reference** tersedia di folder `revisi/` dengan subfolder `admin/`, `spv/`, `operator/` yang berisi screenshot PNG dari setiap halaman.
