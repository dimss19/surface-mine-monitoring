# PA/UA Dashboard Design

## Overview
Add a PA/UA dashboard (Daily/Weekly/Monthly tabs) for admin+spv, following the structure of the company report `rencanaupdate/Civil Daily Report Activity 260517_page-0001.jpg`, backed by the app's own data. Includes lifecycle utilization (breakdown/servis/ready), quantity entry, and unit-maintenance blocking on operator forms.

## Context
- Laravel 13.x, PostgreSQL, Blade + Alpine + Tailwind, offline-first PWA.
- Branch: `cleanup/dashboard`; current admin/spv pages: Rekapan Pegawai + Utilization.
- Data sources: `ritasis`, `non_ritasis`, `unit_utilizations`, `materials`, `units`, `daily_targets`, `pegawais`.
- `unit_utilizations` currently: `tipe` enum (breakdown/servis), `tanggal`, `deskripsi`, `user_id`.

## Goals
- New `Dashboard` menu for admin+spv (spv sees all areas, same as admin), placed before Rekapan Pegawai in sidebar.
- Tabbed UI: Daily, Weekly, Monthly.
- PA/UA formulas:
  - `PA = (SH - BD) / SH * 100`
  - `UA = WH / (SH - BD) * 100`
  - `SH = active units * 12 hours * selected shifts/days` (12h per shift per unit)
  - `WH = sum(hm_total)`, clamped to max 12 per unit per shift
  - `BD = breakdown + servis duration`; if `ended_at` is null, ends at `now()`
- Daily unit timeline: red = breakdown+servis hours in shift, green = working hours (HM, max 12), white = standby (12 - red - green).
- Default dashboard display = ton; `tonnage = (quantity_unit === 'ton') ? quantity : quantity * material.to_ton_factor`.
- Block ritasi/non-ritasi submit for a unit with active (not-ended) utilization; block invalid utilization transitions.
- Operator name always from auth user.
- No automated test suite — verify via migrate:fresh --seed, view:cache, route checks, tinker, and HTTP smoke (ccomposer run dev; users admin/spv/operator all password "password").

## Design Decisions

### 1. Routing & Menu
- Admin: `GET /admin/dashboard?tab=` → `admin.dashboard.index`; `GET /admin/dashboard/export/{period}` → `admin.dashboard.export`
- Spv: `spv.dashboard.index`, `spv.dashboard.export`
- `tab` query param ∈ {daily, weekly, monthly} (default daily)
- Sidebar: Dashboard item first for admin & spv (icon `dashboard`)
- Logo link: admin/spv → `route("$role.dashboard.index")`; pegawai → `route("pegawai.dashboard")`
- Post-login + guest redirect to role dashboard (AuthenticatedSessionController + GuestRedirect middleware pattern)

### 2. Data Model Changes
- `materials`: add `unit_default` string (default 'ton'); `to_ton_factor` decimal(8,4) default 1
- `ritasis`: add `quantity` decimal(12,2) nullable; `quantity_unit` string (default 'ton')
- `unit_utilizations`: replace `tipe` enum with `status` enum('breakdown','servis','ready') default 'breakdown'; drop `tanggal`; add `started_at` timestamp nullable, `ended_at` timestamp nullable. Keep `unit_id`, `deskripsi`, `user_id`.

### 3. Utilization Lifecycle
- Flow: `breakdown aktif` → optional `servis aktif` → `selesai/ready`.
- Both breakdown and servis count as red/maintenance hours (BD).
- Active = entries where `ended_at` is null.
- At most ONE active entry per unit (block create if unit already active).
- `servis` allowed only if a `breakdown` is the latest active entry (no servis-first, no servis when already ready-ready without an active breakdown).
- `ready` allowed only if an active entry exists; sets `ended_at`.
- Offline forms: `data-offline-form data-sync-tag="utilization-sync"`; store returns `['success'=>true,'replayed'=>true]` on duplicate/duplicate-block for offline replay.

### 4. Validation & Blocking Rules
- `StoreUtilizationRequest`: `unit_id` required|exists; `status` required|in:breakdown,servis,ready; `started_at` required_if:status,breakdown|servis, date; `ended_at` nullable|date|after_or_equal:started_at; `deskripsi` nullable|string|max:500.
- Ritasi/non-ritasi store blocks unit with active utilization → back with error (same offline-replay pattern).
- Ritasi form: `quantity` nullable|numeric|min:0; `quantity_unit` in:ton,m3,bcm (default 'ton').

### 5. Report Service (DashboardReportService) Method Signatures
Consumed by controller + views; signatures must match exactly.
- `dailyData(Request): array` → keys: `kpi`{fuel,float; tonnage,float; active_units,int; maintenance_units,int}, `hauling`[ {name,ton} ], `timeline`{day:[{name,segs[{t,h}]}], night:[...]}, `pies`{day,night,combined each {green,red,white}}, `units`
  - `segs` types: 'running' (green), 'maintenance' (red), 'standby' (white)
- `weeklyData(Request): array` → keys: `hauling`[ {name,ton} ], `paGauges`[ {type,pa} ], `uaGauges`[ {type,ua} ], `top5`{hm:[ {name,value}], ore:[...], equip:[...]}
- `monthlyData(Request): array` → keys: `stacked`[ {day,ore,others} ], `cumulativeLine`[ {day,total} ], `top5`{hm,ore,equip}
- `exportData(Request, string $period): array` → `{rows: array of arrays, period: string}`
- Helpers: `activeUnitCount()`, `maintenanceUnitCount()`, `fuelConsumption()`
- PA/UA uses unit `tipe` prefix grouping: Exc*, Sany*, ADT*/DT*, Dozer* (matches existing availability logic)

### 6. Daily Tab View
KPI cards: Fuel Consumption, Total Hauling (ton), Active Units, Maintenance Units.
Hauling bar per material. Unit timeline day/night with colored 12h segments. 3 pie charts (day/night/combined) with red/green/white hour segments.
Exports honor the active date filter.

### 7. Weekly Tab View
Weekly hauling per material (Ore, Tuff, Cake). PA + UA gauges per unit type. Top 5 tables: running hours, ore hauling (ton), equipment trips used.

### 8. Monthly Tab View
MTD stacked per day: Ore vs Others. Cumulative ton line. Top 5 tables (HM, ore, equipment).

### 9. Export
Excel (.xls) and PDF views. Export uses same filters/formulas as visible dashboard. Filenames `Dashboard_<period>_<YYYY-mm-dd_His>.xls` / `.pdf`.

### 10. Architecture
- `app/Services/DashboardReportService.php` — pure calculation (no view, no auth)
- `app/Http/Controllers/DashboardController.php` — index (tab dispatch) + export
- `resources/views/dashboard/index.blade.php`, `dashboard/partials/{daily,weekly,monthly}.blade.php`, `dashboard/export/{excel,pdf}.blade.php`

### 11. Verification
- `php artisan migrate:fresh --seed`
- `php artisan view:cache`
- `php artisan route:list --name=dashboard` → expect 4
- tinker: `DashboardController::index` returns `dashboard.index` view name; `DashboardReportService::dailyData` returns arrays with numeric PA/UA.
- HTTP smoke (admin/spv login → 200 on each tab; export download; operator form has quantity fields; maintenance unit blocks ritasi submit).

## Out of Scope
- Redesigning Rekapan Pegawai.
- Changing `units.status` enum values.
- Touching `offline-sync.js` / `sw.js`.
- A report-snapshot/audit table.
- Multi-unit display toggle (future): default display is ton now.
