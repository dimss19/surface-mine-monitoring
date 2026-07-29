# Surface Mine Production - Total Redesign Design Document

**Date**: 2026-07-29  
**Version**: 2.0  
**Status**: Draft for Review  
**Project**: Surface Mine Production Monitoring System (Laravel 13.x PWA)

---

## 1. Executive Summary

Complete redesign of the Surface Mine Production application based on new UI/UX designs in `revisi/` folder. The redesign includes:

- **Database**: 9 new tables, 2 pivot tables, permission matrix, split attendance into Ritasi/Non-Ritasi
- **Features**: Master Material, Master Unit, Master Hak Akses, SPV Monitoring Reports, Landing Page, Operator Forms
- **UI/UX**: Full redesign matching PNG mockups, mobile-first, component-based
- **Offline**: Extended to all new forms (Ritasi, Non-Ritasi, Pemantauan)
- **PRD**: Updated to v2.0 with complete specifications

---

## 2. Database Architecture

### 2.1 New Tables

| Table | Purpose | Key Fields |
|-------|---------|------------|
| `units` | Master Unit/Alat with specs | kode, nama, tipe, kapasitas, fuel_rate, status |
| `materials` | Master Material with stock | kode, nama, satuan, harga, min_stock, safety_stock |
| `unit_area` | Unit ↔ Area assignment | unit_id, area_id, assigned_at |
| `material_unit` | Material ↔ Unit compatibility | material_id, unit_id, consumption_rate |
| `material_movements` | Stock in/out/adjustment | material_id, area_id, unit_id, tipe, qty, reference |
| `absensi_ritasi` | Hauling attendance | pegawai_id, area_id, unit_id, material_id, hm_awal, hm_akhir, ritase, jarak, fuel, foto |
| `absensi_non_ritasi` | Non-hauling attendance | pegawai_id, area_id, unit_id, jenis_pekerjaan, durasi, fuel, foto |
| `unit_fuel_logs` | Detailed fuel tracking per unit | unit_id, material_id, tanggal, shift, fuel_awal, fuel_isi, fuel_konsumsi, jam_operasional |
| `permissions` | Permission definitions | name, label, group |
| `role_permissions` | Role ↔ Permission matrix | role, permission_id, allowed |

### 2.2 Extended Tables

| Table | New Fields |
|-------|------------|
| `users` | unit_id, area_id |
| `alats` | Keep as legacy, migrate to `units` |

### 2.3 Relationships

```
users (1) ─────< pegawais (1:1)
users (1) ─────< pemantauan_lapangans (spv_id)
users (1) ─────< role_permissions
users (1) ─────< ritasis (operator_id)
users (1) ─────< non_ritasis (operator_id)

areas (1) ─────< ritasis
areas (1) ─────< non_ritasis
areas (1) ─────< pemantauan_lapangans
areas (1) ─────< material_movements
areas (1) ─────< units (via unit_area)

units (1) ─────< ritasis
units (1) ─────< non_ritasis
units (1) ─────< unit_fuel_logs
units (1) ─────< material_movements
units ∞──────∞ materials (via material_unit)

materials (1) ─────< ritasis
materials (1) ─────< material_movements
materials ∞──────∞ units (via material_unit)

pegawais (1) ─────< ritasis
pegawais (1) ─────< non_ritasis
```

### 2.4 Migration Strategy (Incremental)

```bash
# Phase 1: New tables (safe, no data loss)
php artisan make:migration create_materials_table
php artisan make:migration create_units_table
php artisan make:migration create_unit_area_table
php artisan make:migration create_material_unit_table
php artisan make:migration create_material_movements_table
php artisan make:migration create_permissions_table
php artisan make:migration create_role_permissions_table

# Phase 2: Attendance split
php artisan make:migration create_absensi_ritasi_table
php artisan make:migration create_absensi_non_ritasi_table
php artisan make:migration create_unit_fuel_logs_table

# Phase 3: Extend users
php artisan make:migration add_unit_area_to_users_table

# Phase 4: Data migration (after verification)
php artisan make:migration migrate_absensi_to_ritasi_non_ritasi
```

---

## 3. Feature Architecture per Role

### 3.1 Admin (Filament Panel)

| Feature | Description | Tables |
|---------|-------------|--------|
| Dashboard | KPI cards: total units, active SPV, today ritasi, fuel, stock alerts | units, areas, pegawais, material_movements |
| Master Unit | CRUD: kode, nama, tipe, kapasitas, fuel_rate, status, areas | units, unit_area |
| Master Material | CRUD: kode, nama, satuan, harga, min_stock, compatible units | materials, material_unit |
| Master Area | CRUD: nama, lokasi, koordinator, status | areas |
| Master Hak Akses | Permission matrix: 3 roles × 40 permissions | permissions, role_permissions |
| Pegawai | CRUD: NIK, nama, jabatan, shift, area default | pegawais, users |
| SPV | CRUD + assign areas | users (role=spv), area_spv |
| Pemantauan | Read-only all + export | pemantauan_lapangans |
| Export | Multi-sheet Excel: absensi, pemantauan, fuel, stock | all |

### 3.2 SPV (Supervisor)

| Feature | Description | Tables |
|---------|-------------|--------|
| Dashboard | My areas KPIs: ritasi count, volume, fuel, progress %, unit status | areas (assigned), pemantauan_lapangans, ritasis |
| Pemantauan Lapangan | Create/edit daily report per area+shift+unit, progress %, kendala, photos (max 10) | pemantauan_lapangans, pemantauan_fotos |
| Laporan Pemantauan | Harian/Mingguan/Bulanan, Unit Performance (utilization, fuel efficiency, downtime), Export PDF/Excel | pemantauan_lapangans, ritasis, unit_fuel_logs |
| Profil | Edit profile, change password, photo | users |

### 3.3 Pegawai (Operator)

| Feature | Description | Tables |
|---------|-------------|--------|
| Dashboard | Today shift status, quick actions, recent submissions | ritasis, non_ritasis |
| Form Ritasi | Unit, area, material, shift, HM awal/akhir, ritase, jarak, fuel, photos (offline) | absensi_ritasi, materials, units |
| Form Non-Ritasi | Unit, area, jenis pekerjaan, shift, waktu mulai/selesai, durasi, fuel, photos (offline) | absensi_non_ritasi |
| Riwayat | Paginated history with filters, export personal | ritasis, non_ritasis |
| Profil | View profile, change password | users, pegawais |

### 3.4 Public (Landing Page)

- Hero: Company branding, project overview
- Public Dashboard: Active units, today's production, safety stats (read-only)
- Login link

---

## 4. UI/UX Redesign Approach

### 4.1 Design System (from revisi PNGs)

| Token | Value |
|-------|-------|
| Primary | #1B3A5C (Mining Blue) |
| Secondary | #FF6B35 (Safety Orange) |
| Success | #1B5E20 |
| Warning | #F57F17 |
| Error | #C62828 |
| Font Heading | Plus Jakarta Sans |
| Font Body | Inter |
| Icons | Material Symbols Outlined |
| Base Unit | 4px |
| Radius Card | 8px |
| Radius Modal | 12px |

### 4.2 Layout Structure

```
┌─────────────────────────────────────────────────┐
│ Topbar: Brand | Search | Notifications | User   │
├─────────────┬───────────────────────────────────┤
│ Sidebar     │ Content Area                      │
│ (collapsible)│                                   │
│ Nav Items   │ Page Title                        │
│             │ KPI Cards / Table / Forms         │
│             │                                   │
└─────────────┴───────────────────────────────────┘
```

### 4.3 Reusable Components

| Component | File | Usage |
|-----------|------|-------|
| `Card` | `components/card.blade.php` | KPI cards, form sections |
| `DataTable` | `components/data-table.blade.php` | All list views |
| `FormInput` | `components/form-input.blade.php` | Text, number, date, select |
| `SelectDropdown` | `components/select-dropdown.blade.php` | Searchable selects |
| `PhotoUploader` | `components/photo-uploader.blade.php` | Multi-photo with preview |
| `ProgressInput` | `components/progress-input.blade.php` | Progress % + status |
| `KPICard` | `components/kpi-card.blade.php` | Dashboard metrics |
| `Sidebar` | `components/sidebar.blade.php` | Role-based navigation |
| `Topbar` | `components/topbar.blade.php` | User menu, online/offline badge |

### 4.4 Page-to-Route Mapping (from PNGs)

| PNG | Route | Layout |
|-----|-------|--------|
| `landing.png` | `/` | Public (no sidebar) |
| `login.png` | `/login` | Centered card |
| `admin/dashboard.png` | `/admin/dashboard` | Admin |
| `admin/master unit.png` | `/admin/unit` | Admin |
| `admin/master material.png` | `/admin/material` | Admin |
| `admin/master area.png` | `/admin/area` | Admin |
| `admin/master hak akses.png` | `/admin/hak-akses` | Admin |
| `admin/pemantauan.png` | `/admin/pemantauan` | Admin |
| `admin/profil.png` | `/profil` | Shared |
| `spv/dashboard.png` | `/spv/dashboard` | SPV |
| `spv/laporan pemantauan.png` | `/spv/laporan` | SPV |
| `spv/profil.png` | `/profil` | Shared |
| `operator/form ritasi.png` | `/pegawai/ritasi/create` | Operator |
| `operator/form non ritasi.png` | `/pegawai/non-ritasi/create` | Operator |
| `operator/general.png` | `/pegawai` | Operator |

---

## 5. Offline-First & Sync Strategy

### 5.1 Extended Offline Forms

| Form | Sync Tag | Unique Constraint |
|------|----------|-------------------|
| Ritasi | `ritasi-sync` | (pegawai_id, tanggal, shift) |
| Non-Ritasi | `non-ritasi-sync` | (pegawai_id, tanggal, shift) |
| Pemantauan | `pemantauan-sync` | (spv_id, area_id, tanggal, shift) |
| Absensi (legacy) | `absensi-sync` | (pegawai_id, tanggal, shift) |

### 5.2 Sync Flow

1. **Offline**: Form → IndexedDB (serialized + base64 photos) → Toast "Tersimpan offline"
2. **Online**: `navigator.onLine` → `replayOutbox()` → GET `/csrf-token` → POST with `X-Offline-Replay: 1`
3. **Server**: Check unique constraint → If exists, return 200 with `replayed: true` → Delete from IndexedDB
4. **Success**: Toast "Data offline berhasil tersinkronisasi"

### 5.3 IndexedDB Schema

```javascript
{
  id: autoIncrement,
  url: "/pegawai/ritasi",
  method: "POST",
  payload: { area_id: 1, unit_id: 2, ... },
  files: [{ field: "foto", name: "photo.jpg", type: "image/jpeg", base64: "..." }],
  created_at: "2026-07-29T10:00:00Z",
  syncTag: "ritasi-sync"
}
```

---

## 6. API & Routes Design

### 6.1 New Routes

```php
// Admin
Route::resource('admin/material', AdminMaterialController::class);
Route::resource('admin/unit', AdminUnitController::class);
Route::resource('admin/unit-area', AdminUnitAreaController::class);
Route::get('admin/hak-akses', [AdminPermissionController::class, 'index']);
Route::put('admin/hak-akses/{role}', [AdminPermissionController::class, 'update']);
Route::get('admin/export', [AdminController::class, 'export']);

// SPV Laporan
Route::get('spv/laporan/harian', [SpvLaporanController::class, 'harian']);
Route::get('spv/laporan/mingguan', [SpvLaporanController::class, 'mingguan']);
Route::get('spv/laporan/bulanan', [SpvLaporanController::class, 'bulanan']);
Route::get('spv/laporan/unit-performance', [SpvLaporanController::class, 'unitPerformance']);
Route::post('spv/laporan/export/{type}', [SpvLaporanController::class, 'export']);

// Pegawai Ritasi
Route::get('pegawai/ritasi/create', [PegawaiRitasiController::class, 'create']);
Route::post('pegawai/ritasi', [PegawaiRitasiController::class, 'store']);
Route::get('pegawai/ritasi/riwayat', [PegawaiRitasiController::class, 'riwayat']);

// Pegawai Non-Ritasi
Route::get('pegawai/non-ritasi/create', [PegawaiNonRitasiController::class, 'create']);
Route::post('pegawai/non-ritasi', [PegawaiNonRitasiController::class, 'store']);
Route::get('pegawai/non-ritasi/riwayat', [PegawaiNonRitasiController::class, 'riwayat']);

// Public
Route::get('/', fn() => view('landing'))->name('landing');
```

### 6.2 Controllers to Create

| Controller | Methods |
|------------|---------|
| `AdminMaterialController` | index, create, store, edit, update, destroy |
| `AdminUnitController` | index, create, store, edit, update, destroy |
| `AdminUnitAreaController` | index, store, destroy (assign/unassign) |
| `AdminPermissionController` | index, update (matrix) |
| `SpvLaporanController` | harian, mingguan, bulanan, unitPerformance, export |
| `PegawaiRitasiController` | create, store, riwayat |
| `PegawaiNonRitasiController` | create, store, riwayat |

---

## 7. Seeder Strategy

### 7.1 New Seeders

| Seeder | Data | Dependencies |
|--------|------|--------------|
| `MaterialSeeder` | 30 materials (fuel, explosives, lubricants, parts) | - |
| `UnitSeeder` | 25 units (excavator, dump truck, dozer, grader) | - |
| `MaterialUnitSeeder` | 50 compatibility links | Material, Unit |
| `PermissionSeeder` | 40 permissions (5 groups × 8 actions) | - |
| `RolePermissionSeeder` | Admin=all, SPV=monitoring+report, Pegawai=forms | Permission |
| `UnitFuelLogSeeder` | 200 sample logs | Unit, Material(fuel) |
| `RitasiSeeder` | 200 sample hauling | Pegawai, Area, Unit, Material |
| `NonRitasiSeeder` | 150 sample non-hauling | Pegawai, Area, Unit |

### 7.2 DatabaseSeeder Order

```php
$this->call([
    // Existing
    AreaSeeder::class,
    AlatSeeder::class,      // Keep for legacy
    PegawaiSeeder::class,
    UserSeeder::class,
    
    // NEW
    MaterialSeeder::class,
    UnitSeeder::class,
    MaterialUnitSeeder::class,
    PermissionSeeder::class,
    RolePermissionSeeder::class,
    
    // Sample data (dev only)
    // UnitFuelLogSeeder::class,
    // RitasiSeeder::class,
    // NonRitasiSeeder::class,
]);
```

---

## 8. PRD Update (v2.0)

The PRD at `docs/PRD.md` will be updated with:

1. **Feature Specifications**: Detailed user stories per role
2. **Database Schema**: Complete ER diagram (Mermaid)
3. **API Documentation**: All endpoints with request/response examples
4. **Design System Guidelines**: Colors, typography, components
5. **Offline Architecture**: Sync flow, IndexedDB schema, conflict resolution
6. **Acceptance Criteria**: Checklist for each feature
7. **Migration Plan**: Phase-by-phase deployment

---

## 9. Implementation Phases

### Phase 1: Database & Core (Week 1)
- [ ] New migrations + seeders
- [ ] Models + relationships
- [ ] Permission system (middleware + gates)
- [ ] Test migrations on SQLite

### Phase 2: Admin Module (Week 2)
- [ ] Admin controllers + views
- [ ] Master Material/Unit/Area CRUD
- [ ] Master Hak Akses matrix
- [ ] Export functionality

### Phase 3: SPV Module (Week 3)
- [ ] SPV Dashboard with KPIs
- [ ] Pemantauan Lapangan CRUD
- [ ] Laporan (harian/mingguan/bulanan/unit)
- [ ] Export PDF/Excel

### Phase 4: Pegawai Module (Week 4)
- [ ] Dashboard
- [ ] Form Ritasi (offline)
- [ ] Form Non-Ritasi (offline)
- [ ] Riwayat with filters

### Phase 5: Public & Polish (Week 5)
- [ ] Landing page
- [ ] Login redesign
- [ ] Offline sync for new forms
- [ ] PWA testing
- [ ] E2E tests

### Phase 6: PRD & Documentation (Week 6)
- [ ] Update PRD.md
- [ ] API docs
- [ ] ER diagram
- [ ] Deployment guide

---

## 10. Risks & Mitigations

| Risk | Impact | Mitigation |
|------|--------|------------|
| Data migration from absensi_pegawais | High | Run migration script in Phase 1, verify with test data |
| Offline sync conflicts | Medium | Unique constraints + idempotent replay |
| PWA caching issues | Medium | Versioned service worker, cache-first strategy |
| Performance on mobile | Low | Lazy load components, optimize images |
| Permission matrix complexity | Medium | Start with 3 roles × 8 groups, expand later |

---

## 11. Approval

- [ ] **Database Architecture** - Approved?
- [ ] **Feature Architecture** - Approved?
- [ ] **UI/UX Approach** - Approved?
- [ ] **Offline Strategy** - Approved?
- [ ] **API/Routes** - Approved?
- [ ] **Seeder Strategy** - Approved?
- [ ] **PRD Update Plan** - Approved?

**Next Step**: Upon approval, invoke `writing-plans` skill to create detailed implementation plan.