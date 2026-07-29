# Operator Forms Design

## Overview
Design for operator form views in the Surface Mine Production application. This includes the operator layout, dashboard, and three form types: Ritasi, Non-Ritasi, and General.

## Context
- Laravel 13.x PWA application with offline-first capabilities
- Three roles: admin, spv, pegawai (operator)
- Frontend: Blade + Alpine.js + Tailwind CSS
- Must match PNG mockups in `revisi/operator/` folder

## Design Decisions

### 1. Operator Layout (`resources/views/layouts/operator.blade.php`)
- Reuses existing sidebar and topbar components
- Passes `role => 'pegawai'` to components
- Includes session flash message handling
- Matches admin layout structure

### 2. Operator Dashboard (`resources/views/operator/dashboard.blade.php`)
- Simple dashboard with quick access to form types
- Shows online/offline status
- Uses existing card components

### 3. Form Ritasi (`resources/views/operator/ritasi/create.blade.php`)
- Session info alert at top
- Data Dasar section: Shift, Tanggal, Nama Operator (readonly), Nomor Unit
- Hour Meter section: HM Awal, HM Akhir, Total Durasi HM (calculated)
- Detail Pekerjaan section: Jenis Material, Jumlah Ritasi, Lokasi Pekerjaan, Deskripsi
- Reset and Simpan buttons
- `data-offline-form` attribute for offline sync

### 4. Form Non-Ritasi (`resources/views/operator/non-ritasi/create.blade.php`)
- Similar structure to Form Ritasi but without material/ritasi fields
- Session info alert at top
- Data Dasar section: Shift, Tanggal, Nama Operator (readonly), Nomor Unit
- Hour Meter section: HM Awal, HM Akhir, Total Durasi HM (calculated)
- Detail Pekerjaan section: Lokasi Pekerjaan, Deskripsi
- Reset and Simpan buttons
- `data-offline-form` attribute for offline sync

### 5. Form General (`resources/views/operator/general/create.blade.php`)
- Session info alert at top
- Data Dasar section: Shift, Tanggal, Nama Operator (readonly), Nomor Unit, Supervisor, Senior SPV
- Jam Kerja section: Jam Mulai, Jam Akhir, Status Overtime toggle
- Detail Pekerjaan section: Lokasi Pekerjaan, Deskripsi
- Reset and Simpan buttons
- `data-offline-form` attribute for offline sync

## Component Structure

### Layout Components
- Sidebar: Reuse `components.sidebar` with `role => 'pegawai'`
- Topbar: Reuse `components.topbar` with `role => 'pegawai'`

### Form Components
- Form sections use consistent heading with material icons
- Form inputs use existing CSS classes (`.form-input`, `.form-label`)
- Buttons use existing CSS classes (`.btn-primary`, `.btn-secondary`)

## Data Flow
1. Controller passes data to view (pegawai, units, areas, materials)
2. View renders form with pre-filled data
3. Form submits to controller store method
4. Controller validates and saves to database
5. Offline: Form data stored in IndexedDB, synced when online

## Visual Design
- Dark navy sidebar (#0f172a)
- White topbar with page title
- White cards with subtle shadows
- Orange accent buttons (#f59e0b)
- Consistent spacing and typography

## Implementation Notes
- Use existing CSS variables and utility classes
- Maintain offline form functionality with `data-offline-form`
- Include Alpine.js for dynamic calculations (HM total)
- Follow existing code patterns from admin forms