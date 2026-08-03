# Memory - Surface Mine Production

## Yang Sudah Dikerjakan

### 1. Admin Dashboard
**File:** `resources/views/admin/dashboard.blade.php`
- Period switch (Daily/Weekly/Monthly) via Alpine.js
- Breakdown Activity: CSS bar chart (Ore + Material lain), Shift Gantt (Day/Night)
- Hauling: horizontal bars per material, Availability donut, UoA donut
- Stat strip: Total Jam, Capaian %, Total BD, Standby

### 2. SPV Dashboard
**File:** `resources/views/spv/dashboard.blade.php`
- Layout sama dengan admin (`@extends('layouts.admin')`)
- CSS variables hardcoded (navy, blue, ore, grey, dll)
- Fitur sama: breakdown + hauling cards

### 3. Pegawai Dashboard
**File:** `resources/views/pegawai/dashboard.blade.php`
- Card "Isi Absensi" (ritasi) + Card "Riwayat"
- Dark theme, mobile-first

### 4. Controllers
- `app/Http/Controllers/AdminController.php`: `dashboard()` → data materials, shifts, targets, stats
- `app/Http/Controllers/SpvController.php`: struktur sama

### 5. Dashboard Seeder
**File:** `database/seeders/DashboardSeeder.php`
- Materials: Ore, Overburden, Waste, Coal, Dirt
- Units: HD785, CAT777, PC2000, excavator, dll
- Target per material, fake ritasi data (daily/weekly/monthly)

### 6. Sidebar
**File:** `resources/views/components/sidebar.blade.php`
- Profil dihapus
- Logout di paling bawah

### 7. Chart Fix
- Sebelumnya chart stretching karena semua Chart.js inisialisasi bersamaan
- Fix: lazy init per tab, `destroy()` sebelum re-create
- Monthly chart: `stacked:false` untuk mixed bar+line

## File Penting Lainnya
- `resources/css/app.css` — CSS variables & component styles (sidebar, topbar, card, badge, btn)
- `resources/views/layouts/admin.blade.php` — layout admin (sidebar + topbar + content)
- `resources/views/components/topbar.blade.php` — topbar component
- `database/seeders/DatabaseSeeder.php` — panggil DashboardSeeder

## Yang Belum / TBD
- Standalone HTML files (ada tapi pakai desain lama, belum dipakai)
