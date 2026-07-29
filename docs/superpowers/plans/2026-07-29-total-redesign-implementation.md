# Surface Mine Production - Total Redesign Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Complete redesign of Surface Mine Production app with new database schema, features, and UI matching PNG mockups in `revisi/` folder.

**Architecture:** Laravel 13.x PWA with Blade + Alpine.js + Tailwind. Incremental migration from existing schema. Role-based: Admin, SPV, Pegawai (Operator).

**Tech Stack:** Laravel 13.x, Blade, Alpine.js, Tailwind CSS, Vite, Spatie MediaLibrary, Chart.js/ChartJS.

---

## Design System (Extracted from PNGs)

### Colors
```css
:root {
  --primary: #0f172a;        /* Dark navy sidebar/topbar */
  --primary-light: #1e293b;  /* Lighter navy */
  --accent: #f59e0b;         /* Orange buttons/active states */
  --accent-hover: #d97706;   /* Orange hover */
  --success: #22c55e;        /* Active/Validated badges */
  --warning: #f59e0b;        /* Low Stock/Maintenance badges */
  --danger: #ef4444;         /* Breakdown/Restricted badges */
  --info: #3b82f6;           /* Info badges */
  --bg: #f8fafc;             /* Page background */
  --card: #ffffff;           /* Card background */
  --border: #e2e8f0;        /* Borders */
  --text: #0f172a;           /* Primary text */
  --text-muted: #64748b;     /* Muted text */
}
```

### Typography
- **Headings:** Plus Jakarta Sans (700)
- **Body:** Inter (400, 500, 600)
- **Sizes:** H1: 2rem, H2: 1.5rem, H3: 1.125rem, Body: 0.875rem

### Layout Structure
- **Sidebar:** 256px fixed, dark navy (#0f172a), white text
- **Topbar:** Full width, white background, dark text
- **Content:** Padded, scrollable
- **Cards:** White, rounded-lg, shadow-sm, border

---

## File Structure

### New Files to Create

| Path | Purpose |
|------|---------|
| `resources/css/design-system.css` | CSS variables, base styles |
| `resources/views/layouts/public.blade.php` | Public layout (landing/login) |
| `resources/views/layouts/admin.blade.php` | Admin dashboard layout |
| `resources/views/layouts/operator.blade.php` | Operator dashboard layout |
| `resources/views/landing.blade.php` | Landing page |
| `resources/views/auth/login.blade.php` | Login page (redesign) |
| `resources/views/admin/dashboard.blade.php` | Admin dashboard |
| `resources/views/admin/master-data/index.blade.php` | Master Data tabs |
| `resources/views/admin/master-data/partials/unit-table.blade.php` | Unit tab |
| `resources/views/admin/master-data/partials/material-table.blade.php` | Material tab |
| `resources/views/admin/master-data/partials/area-table.blade.php` | Area tab |
| `resources/views/admin/master-data/partials/user-table.blade.php` | User tab |
| `resources/views/admin/master-data/partials/hak-akses.blade.php` | Permission matrix |
| `resources/views/admin/laporan/index.blade.php` | Admin Laporan Pemantauan |
| `resources/views/admin/profil/show.blade.php` | Admin Profile |
| `resources/views/operator/dashboard.blade.php` | Operator dashboard |
| `resources/views/operator/ritasi/create.blade.php` | Form Ritasi |
| `resources/views/operator/non-ritasi/create.blade.php` | Form Non-Ritasi |
| `resources/views/operator/general/create.blade.php` | Form Pekerjaan General |
| `resources/views/spv/dashboard.blade.php` | SPV Dashboard |
| `resources/views/spv/laporan/index.blade.php` | SPV Laporan Pemantauan |
| `resources/views/spv/profil/show.blade.php` | SPV Profile |
| `resources/views/components/sidebar.blade.php` | Reusable sidebar |
| `resources/views/components/topbar.blade.php` | Reusable topbar |
| `resources/views/components/kpi-card.blade.php` | KPI card component |
| `resources/views/components/status-badge.blade.php` | Status badge |
| `resources/views/components/data-table.blade.php` | Reusable data table |
| `resources/js/charts.js` | Chart.js configurations |

---

## Task 1: Design System & Base Styles

**Files:**
- Modify: `resources/css/app.css`
- Create: `resources/views/layouts/public.blade.php`
- Modify: `tailwind.config.js`

- [ ] **Step 1: Add CSS variables to app.css**

```css
/* resources/css/app.css - Add at top */
:root {
  --primary: #0f172a;
  --primary-light: #1e293b;
  --accent: #f59e0b;
  --accent-hover: #d97706;
  --success: #22c55e;
  --warning: #f59e0b;
  --danger: #ef4444;
  --info: #3b82f6;
  --bg: #f8fafc;
  --card: #ffffff;
  --border: #e2e8f0;
  --text: #0f172a;
  --text-muted: #64748b;
}

/* Sidebar */
.sidebar {
  @apply fixed left-0 top-0 h-full w-64 bg-[#0f172a] text-white z-40 transition-transform duration-300;
}

.sidebar-nav-item {
  @apply flex items-center gap-3 px-6 py-3 text-sm text-slate-300 hover:bg-white/10 hover:text-white transition-colors;
}

.sidebar-nav-item.active {
  @apply bg-[var(--accent)] text-white font-semibold border-l-4 border-white;
}

/* Topbar */
.topbar {
  @apply fixed top-0 left-64 right-0 h-16 bg-white border-b z-30 flex items-center justify-between px-6;
}

/* Cards */
.card {
  @apply bg-white rounded-lg border shadow-sm;
}

/* Status Badges */
.badge {
  @apply inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium;
}

.badge-active { @apply bg-green-100 text-green-700; }
.badge-maintenance { @apply bg-yellow-100 text-yellow-700; }
.badge-breakdown { @apply bg-red-100 text-red-700; }
.badge-standby { @apply bg-gray-100 text-gray-600; }
.badge-low-stock { @apply bg-orange-100 text-orange-700; }
.badge-inactive { @apply bg-gray-100 text-gray-500; }
.badge-restricted { @apply bg-red-100 text-red-600; }
.badge-validated { @apply bg-green-100 text-green-700; }
.badge-pending { @apply bg-yellow-100 text-yellow-700; }
.badge-in-progress { @apply bg-blue-100 text-blue-700; }

/* Form Elements */
.form-input {
  @apply w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[var(--accent)] focus:border-transparent;
}

.form-label {
  @apply block text-sm font-medium text-gray-700 mb-1;
}

.btn-primary {
  @apply bg-[var(--accent)] hover:bg-[var(--accent-hover)] text-white font-semibold px-4 py-2 rounded-lg transition-colors;
}

.btn-secondary {
  @apply bg-white border hover:bg-gray-50 text-gray-700 font-medium px-4 py-2 rounded-lg transition-colors;
}
```

- [ ] **Step 2: Create public layout**

```blade
{{-- resources/views/layouts/public.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Surface Mine Production')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
</head>
<body class="bg-[#0f172a] min-h-screen font-['Inter',sans-serif]">
    @yield('content')
</body>
</html>
```

- [ ] **Step 3: Update tailwind.config.js**

```javascript
// tailwind.config.js
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
    ],
    theme: {
        extend: {
            fontFamily: {
                heading: ['"Plus Jakarta Sans"', 'sans-serif'],
                body: ['Inter', 'sans-serif'],
            },
            colors: {
                primary: { DEFAULT: '#0f172a', light: '#1e293b' },
                accent: { DEFAULT: '#f59e0b', hover: '#d97706' },
            }
        },
    },
    plugins: [],
}
```

- [ ] **Step 4: Run build**

```bash
npm run build
```

---

## Task 2: Database Migrations

**Files:**
- Create: `database/migrations/2026_07_29_000001_create_materials_table.php`
- Create: `database/migrations/2026_07_29_000002_create_units_table.php`
- Create: `database/migrations/2026_07_29_000003_create_unit_area_table.php`
- Create: `database/migrations/2026_07_29_000004_create_material_unit_table.php`
- Create: `database/migrations/2026_07_29_000005_create_material_movements_table.php`
- Create: `database/migrations/2026_07_29_000006_create_ritasis_table.php`
- Create: `database/migrations/2026_07_29_000007_create_non_ritasis_table.php`
- Create: `database/migrations/2026_07_29_000008_create_unit_fuel_logs_table.php`
- Create: `database/migrations/2026_07_29_000009_create_permissions_table.php`
- Create: `database/migrations/2026_07_29_000010_create_role_permissions_table.php`

- [ ] **Step 1: Create materials migration**

```php
<?php
// database/migrations/2026_07_29_000001_create_materials_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->string('nama');
            $table->string('satuan'); // Tonnes, Kilograms, Liters, Meters
            $table->enum('kategori', ['ore', 'waste', 'fuel', 'lubricant', 'explosive', 'spare_part', 'other'])->default('other');
            $table->decimal('stok', 12, 2)->default(0);
            $table->decimal('stok_minimal', 12, 2)->default(0);
            $table->decimal('harga_satuan', 15, 2)->nullable();
            $table->enum('status', ['active', 'low_stock', 'inactive', 'restricted'])->default('active');
            $table->text('keterangan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('materials');
    }
};
```

- [ ] **Step 2: Create units migration**

```php
<?php
// database/migrations/2026_07_29_000002_create_units_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique(); // EXC-001, DT-104, BDZ-022
            $table->string('nama'); // Excavator PC2000-8
            $table->string('tipe'); // excavator, dump_truck, bulldozer, motor_grader
            $table->string('merk')->nullable();
            $table->string('model')->nullable();
            $table->integer('tahun')->nullable();
            $table->decimal('kapasitas', 10, 2)->nullable();
            $table->decimal('fuel_consumption_rate', 8, 2)->nullable();
            $table->enum('status', ['active', 'maintenance', 'breakdown', 'standby'])->default('active');
            $table->date('last_maintenance')->nullable();
            $table->date('next_maintenance')->nullable();
            $table->text('keterangan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('units');
    }
};
```

- [ ] **Step 3: Create pivot tables**

```php
<?php
// database/migrations/2026_07_29_000003_create_unit_area_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('unit_area', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('area_id')->constrained()->cascadeOnDelete();
            $table->timestamp('assigned_at')->nullable();
            $table->timestamps();
            $table->unique(['unit_id', 'area_id']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('unit_area');
    }
};
```

```php
<?php
// database/migrations/2026_07_29_000004_create_material_unit_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('material_unit', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->constrained()->cascadeOnDelete();
            $table->foreignId('unit_id')->constrained()->cascadeOnDelete();
            $table->decimal('consumption_rate', 10, 4)->nullable();
            $table->timestamps();
            $table->unique(['material_id', 'unit_id']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('material_unit');
    }
};
```

- [ ] **Step 4: Create material_movements**

```php
<?php
// database/migrations/2026_07_29_000005_create_material_movements_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('material_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->constrained()->cascadeOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('area_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('tipe', ['masuk', 'keluar', 'transfer', 'opname', 'koreksi']);
            $table->decimal('jumlah', 12, 2);
            $table->decimal('stok_sebelum', 12, 2);
            $table->decimal('stok_sesudah', 12, 2);
            $table->string('referensi')->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('tanggal');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('material_movements');
    }
};
```

- [ ] **Step 5: Create ritasis and non_ritasis**

```php
<?php
// database/migrations/2026_07_29_000006_create_ritasis_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('ritasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawais')->cascadeOnDelete();
            $table->foreignId('unit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('area_id')->constrained()->cascadeOnDelete();
            $table->foreignId('material_id')->constrained()->cascadeOnDelete();
            $table->date('tanggal');
            $table->enum('shift', ['siang', 'malam']);
            $table->decimal('hm_awal', 10, 2);
            $table->decimal('hm_akhir', 10, 2);
            $table->decimal('hm_total', 10, 2);
            $table->integer('jumlah_ritasi')->default(0);
            $table->string('lokasi_pekerjaan')->nullable();
            $table->text('deskripsi_pekerjaan')->nullable();
            $table->text('kendala')->nullable();
            $table->string('status', 20)->default('pending'); // pending, validated, in_progress
            $table->foreignId('validated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('validated_at')->nullable();
            $table->timestamps();
            
            $table->unique(['pegawai_id', 'tanggal', 'shift'], 'ritasi_unique');
        });
    }

    public function down(): void {
        Schema::dropIfExists('ritasis');
    }
};
```

```php
<?php
// database/migrations/2026_07_29_000007_create_non_ritasis_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('non_ritasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawais')->cascadeOnDelete();
            $table->foreignId('unit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('area_id')->constrained()->cascadeOnDelete();
            $table->date('tanggal');
            $table->enum('shift', ['siang', 'malam']);
            $table->decimal('hm_awal', 10, 2);
            $table->decimal('hm_akhir', 10, 2);
            $table->decimal('hm_total', 10, 2);
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();
            $table->boolean('is_overtime')->default(false);
            $table->string('lokasi_pekerjaan')->nullable();
            $table->text('deskripsi_pekerjaan')->nullable();
            $table->text('kendala')->nullable();
            $table->string('status', 20)->default('pending');
            $table->foreignId('validated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('validated_at')->nullable();
            $table->timestamps();
            
            $table->unique(['pegawai_id', 'tanggal', 'shift'], 'non_ritasi_unique');
        });
    }

    public function down(): void {
        Schema::dropIfExists('non_ritasis');
    }
};
```

- [ ] **Step 6: Create permissions tables**

```php
<?php
// database/migrations/2026_07_29_000009_create_permissions_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('label');
            $table->string('group'); // unit, material, area, pegawai, pemantauan, laporan, hak_akses
            $table->timestamps();
        });

        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id();
            $table->enum('role', ['admin', 'spv', 'pegawai']);
            $table->foreignId('permission_id')->constrained()->cascadeOnDelete();
            $table->boolean('allowed')->default(false);
            $table->timestamps();
            $table->unique(['role', 'permission_id']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('permissions');
    }
};
```

- [ ] **Step 7: Run migrations**

```bash
php artisan migrate
```

---

## Task 3: Models & Relationships

**Files:**
- Create: `app/Models/Unit.php`
- Create: `app/Models/Material.php`
- Create: `app/Models/UnitArea.php`
- Create: `app/Models/MaterialUnit.php`
- Create: `app/Models/MaterialMovement.php`
- Create: `app/Models/Ritasi.php`
- Create: `app/Models/NonRitasi.php`
- Create: `app/Models/Permission.php`
- Create: `app/Models/RolePermission.php`
- Modify: `app/Models/Pegawai.php`
- Modify: `app/Models/Area.php`
- Modify: `app/Models/User.php`

- [ ] **Step 1: Create Unit model**

```php
<?php
// app/Models/Unit.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Unit extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'kapasitas' => 'decimal:2',
        'fuel_consumption_rate' => 'decimal:2',
        'tahun' => 'integer',
        'is_active' => 'boolean',
        'last_maintenance' => 'date',
        'next_maintenance' => 'date',
    ];

    public function areas()
    {
        return $this->belongsToMany(Area::class, 'unit_area')->withTimestamps();
    }

    public function materials()
    {
        return $this->belongsToMany(Material::class, 'material_unit')->withPivot('consumption_rate');
    }

    public function ritasis()
    {
        return $this->hasMany(Ritasi::class);
    }

    public function nonRitasis()
    {
        return $this->hasMany(NonRitasi::class);
    }

    public function fuelLogs()
    {
        return $this->hasMany(UnitFuelLog::class);
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'active' => 'badge-active',
            'maintenance' => 'badge-maintenance',
            'breakdown' => 'badge-breakdown',
            'standby' => 'badge-standby',
            default => 'badge-standby',
        };
    }
}
```

- [ ] **Step 2: Create Material model**

```php
<?php
// app/Models/Material.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Material extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'stok' => 'decimal:2',
        'stok_minimal' => 'decimal:2',
        'harga_satuan' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function units()
    {
        return $this->belongsToMany(Unit::class, 'material_unit')->withPivot('consumption_rate');
    }

    public function movements()
    {
        return $this->hasMany(MaterialMovement::class);
    }

    public function ritasis()
    {
        return $this->hasMany(Ritasi::class);
    }

    public function getStatusBadgeAttribute()
    {
        if ($this->stok <= $this->stok_minimal) return 'badge-low-stock';
        return match($this->status) {
            'active' => 'badge-active',
            'inactive' => 'badge-inactive',
            'restricted' => 'badge-restricted',
            default => 'badge-active',
        };
    }

    public function isLowStock()
    {
        return $this->stok <= $this->stok_minimal;
    }
}
```

- [ ] **Step 3: Create Ritasi and NonRitasi models**

```php
<?php
// app/Models/Ritasi.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ritasi extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'tanggal' => 'date',
        'hm_awal' => 'decimal:2',
        'hm_akhir' => 'decimal:2',
        'hm_total' => 'decimal:2',
        'jumlah_ritasi' => 'integer',
        'validated_at' => 'datetime',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'validated' => 'badge-validated',
            'pending' => 'badge-pending',
            'in_progress' => 'badge-in-progress',
            default => 'badge-pending',
        };
    }

    public function getShiftLabelAttribute()
    {
        return $this->shift === 'siang' ? 'Day' : 'Night';
    }
}
```

```php
<?php
// app/Models/NonRitasi.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NonRitasi extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'tanggal' => 'date',
        'hm_awal' => 'decimal:2',
        'hm_akhir' => 'decimal:2',
        'hm_total' => 'decimal:2',
        'jam_mulai' => 'datetime',
        'jam_selesai' => 'datetime',
        'is_overtime' => 'boolean',
        'validated_at' => 'datetime',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'validated' => 'badge-validated',
            'pending' => 'badge-pending',
            'in_progress' => 'badge-in-progress',
            default => 'badge-pending',
        };
    }

    public function getShiftLabelAttribute()
    {
        return $this->shift === 'siang' ? 'Day' : 'Night';
    }
}
```

- [ ] **Step 4: Create Permission model**

```php
<?php
// app/Models/Permission.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $guarded = [];

    public function rolePermissions()
    {
        return $this->hasMany(RolePermission::class);
    }

    public function isAllowedFor(string $role): bool
    {
        return $this->rolePermissions()
            ->where('role', $role)
            ->where('allowed', true)
            ->exists();
    }
}
```

```php
<?php
// app/Models/RolePermission.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $guarded = [];

    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
}
```

- [ ] **Step 5: Update existing models**

```php
<?php
// app/Models/Pegawai.php - Add relationships
public function ritasis()
{
    return $this->hasMany(Ritasi::class);
}

public function nonRitasis()
{
    return $this->hasMany(NonRitasi::class);
}

public function user()
{
    return $this->hasOne(User::class);
}
```

```php
<?php
// app/Models/User.php - Add relationships
public function pegawai()
{
    return $this->belongsTo(Pegawai::class);
}

public function unit()
{
    return $this->belongsTo(Unit::class);
}

public function area()
{
    return $this->belongsTo(Area::class);
}

public function areas()
{
    return $this->belongsToMany(Area::class, 'area_spv');
}

public function hasPermission(string $permission): bool
{
    if ($this->role === 'admin') return true;
    
    return Permission::where('name', $permission)
        ->where('rolePermissions.role', $this->role)
        ->where('rolePermissions.allowed', true)
        ->exists();
}
```

- [ ] **Step 6: Verify models work**

```bash
php artisan tinker
```
```php
\Unit::count();
\Material::count();
\Ritasi::count();
```

---

## Task 4: Seeders

**Files:**
- Create: `database/seeders/MaterialSeeder.php`
- Create: `database/seeders/UnitSeeder.php`
- Create: `database/seeders/PermissionSeeder.php`
- Create: `database/seeders/RolePermissionSeeder.php`
- Create: `database/seeders/RitasiSeeder.php`
- Create: `database/seeders/NonRitasiSeeder.php`
- Modify: `database/seeders/DatabaseSeeder.php`

- [ ] **Step 1: Create MaterialSeeder**

```php
<?php
// database/seeders/MaterialSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Material;

class MaterialSeeder extends Seeder
{
    public function run(): void
    {
        $materials = [
            ['kode' => 'MAT-BX-001', 'nama' => 'Bauxite Ore (Raw)', 'satuan' => 'Tonnes (t)', 'kategori' => 'ore', 'stok' => 15000, 'stok_minimal' => 5000, 'status' => 'active'],
            ['kode' => 'MAT-AL-045', 'nama' => 'Processed Alumina', 'satuan' => 'Kilograms (kg)', 'kategori' => 'ore', 'stok' => 8000, 'stok_minimal' => 2000, 'status' => 'active'],
            ['kode' => 'CNS-LB-112', 'nama' => 'Industrial Lubricant (Heavy)', 'satuan' => 'Liters (L)', 'kategori' => 'lubricant', 'stok' => 150, 'stok_minimal' => 200, 'status' => 'low_stock'],
            ['kode' => 'PRT-CB-099', 'nama' => 'Obsolete Conveyor Belt (Type B)', 'satuan' => 'Meters (m)', 'kategori' => 'spare_part', 'stok' => 0, 'stok_minimal' => 50, 'status' => 'inactive'],
            ['kode' => 'EXP-DC-002', 'nama' => 'Detonator Cord (Standard)', 'satuan' => 'Meters (m)', 'kategori' => 'explosive', 'stok' => 500, 'stok_minimal' => 100, 'status' => 'restricted'],
            ['kode' => 'FUEL-DS-001', 'nama' => 'Diesel Fuel', 'satuan' => 'Liters (L)', 'kategori' => 'fuel', 'stok' => 50000, 'stok_minimal' => 10000, 'status' => 'active'],
            ['kode' => 'MAT-WA-001', 'nama' => 'Waste', 'satuan' => 'Tonnes (t)', 'kategori' => 'waste', 'stok' => 99999, 'stok_minimal' => 0, 'status' => 'active'],
            ['kode' => 'MAT-MU-001', 'nama' => 'Mud - Lumpur', 'satuan' => 'Tonnes (t)', 'kategori' => 'waste', 'stok' => 99999, 'stok_minimal' => 0, 'status' => 'active'],
            ['kode' => 'MAT-PA-001', 'nama' => 'Pasir Hitam', 'satuan' => 'Tonnes (t)', 'kategori' => 'ore', 'stok' => 99999, 'stok_minimal' => 0, 'status' => 'active'],
            ['kode' => 'MAT-MT-001', 'nama' => 'Mining Tuff', 'satuan' => 'Tonnes (t)', 'kategori' => 'ore', 'stok' => 99999, 'stok_minimal' => 0, 'status' => 'active'],
            ['kode' => 'MAT-BP-001', 'nama' => 'Batu Pica (5/15)', 'satuan' => 'Tonnes (t)', 'kategori' => 'ore', 'stok' => 99999, 'stok_minimal' => 0, 'status' => 'active'],
            ['kode' => 'MAT-TO-001', 'nama' => 'Tuff Off', 'satuan' => 'Tonnes (t)', 'kategori' => 'ore', 'stok' => 99999, 'stok_minimal' => 0, 'status' => 'active'],
            ['kode' => 'MAT-KC-001', 'nama' => 'KCN', 'satuan' => 'Tonnes (t)', 'kategori' => 'ore', 'stok' => 99999, 'stok_minimal' => 0, 'status' => 'active'],
            ['kode' => 'MAT-CG-001', 'nama' => 'Cake', 'satuan' => 'Tonnes (t)', 'kategori' => 'ore', 'stok' => 99999, 'stok_minimal' => 0, 'status' => 'active'],
            ['kode' => 'MAT-DS-001', 'nama' => 'DSTuff', 'satuan' => 'Tonnes (t)', 'kategori' => 'ore', 'stok' => 99999, 'stok_minimal' => 0, 'status' => 'active'],
        ];

        foreach ($materials as $material) {
            Material::create($material);
        }
    }
}
```

- [ ] **Step 2: Create UnitSeeder**

```php
<?php
// database/seeders/UnitSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            ['kode' => 'EXC-001', 'nama' => 'Excavator PC2000-8', 'tipe' => 'excavator', 'merk' => 'Komatsu', 'model' => 'PC2000-8', 'tahun' => 2021, 'status' => 'active'],
            ['kode' => 'DT-104', 'nama' => 'Dump Truck HD785-7', 'tipe' => 'dump_truck', 'merk' => 'Komatsu', 'model' => 'HD785-7', 'tahun' => 2020, 'status' => 'active'],
            ['kode' => 'BDZ-022', 'nama' => 'Bulldozer D375A-6', 'tipe' => 'bulldozer', 'merk' => 'Komatsu', 'model' => 'D375A-6', 'tahun' => 2018, 'status' => 'maintenance'],
            ['kode' => 'EXC-005', 'nama' => 'Excavator R220LC-9', 'tipe' => 'excavator', 'merk' => 'Hyundai', 'model' => 'R220LC-9', 'tahun' => 2019, 'status' => 'breakdown'],
            ['kode' => 'MG-011', 'nama' => 'Motor Grader GD825A-2', 'tipe' => 'motor_grader', 'merk' => 'Komatsu', 'model' => 'GD825A-2', 'tahun' => 2022, 'status' => 'standby'],
            ['kode' => 'EXC-022', 'nama' => 'Excavator Long Arm', 'tipe' => 'excavator', 'merk' => 'Komatsu', 'model' => 'PC1250-8', 'tahun' => 2020, 'status' => 'active'],
            ['kode' => 'EXC-024', 'nama' => 'Excavator PC320', 'tipe' => 'excavator', 'merk' => 'Komatsu', 'model' => 'PC320-8', 'tahun' => 2021, 'status' => 'active'],
            ['kode' => 'EXC-025', 'nama' => 'Excavator PC320', 'tipe' => 'excavator', 'merk' => 'Komatsu', 'model' => 'PC320-8', 'tahun' => 2021, 'status' => 'active'],
            ['kode' => 'EXC-027', 'nama' => 'Excavator PC340', 'tipe' => 'excavator', 'merk' => 'Komatsu', 'model' => 'PC340-8', 'tahun' => 2022, 'status' => 'active'],
            ['kode' => 'EXC-028', 'nama' => 'Excavator PC320', 'tipe' => 'excavator', 'merk' => 'Komatsu', 'model' => 'PC320-8', 'tahun' => 2020, 'status' => 'active'],
            ['kode' => 'EXC-029', 'nama' => 'Excavator SY215', 'tipe' => 'excavator', 'merk' => 'Sany', 'model' => 'SY215C', 'tahun' => 2022, 'status' => 'active'],
            ['kode' => 'EXC-032', 'nama' => 'Excavator SY215', 'tipe' => 'excavator', 'merk' => 'Sany', 'model' => 'SY215C', 'tahun' => 2023, 'status' => 'active'],
            ['kode' => 'EXC-033', 'nama' => 'Excavator SY215', 'tipe' => 'excavator', 'merk' => 'Sany', 'model' => 'SY215C', 'tahun' => 2023, 'status' => 'active'],
            ['kode' => 'EXC-034', 'nama' => 'Excavator SY215', 'tipe' => 'excavator', 'merk' => 'Sany', 'model' => 'SY215C', 'tahun' => 2023, 'status' => 'active'],
            ['kode' => 'DT-1042', 'nama' => 'Dump Truck DT-1042', 'tipe' => 'dump_truck', 'merk' => 'Komatsu', 'model' => 'HD785-7', 'tahun' => 2020, 'status' => 'active'],
            ['kode' => 'DT-1055', 'nama' => 'Dump Truck DT-1055', 'tipe' => 'dump_truck', 'merk' => 'Komatsu', 'model' => 'HD785-7', 'tahun' => 2021, 'status' => 'active'],
            ['kode' => 'EX-2015', 'nama' => 'Excavator EX-2015', 'tipe' => 'excavator', 'merk' => 'Komatsu', 'model' => 'PC200-8', 'tahun' => 2019, 'status' => 'active'],
            ['kode' => 'LV-008', 'nama' => 'Leviathan LV-008', 'tipe' => 'loader', 'merk' => 'Caterpillar', 'model' => '966M', 'tahun' => 2020, 'status' => 'active'],
        ];

        foreach ($units as $unit) {
            Unit::create($unit);
        }
    }
}
```

- [ ] **Step 3: Create PermissionSeeder**

```php
<?php
// database/seeders/PermissionSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Unit
            ['name' => 'unit.view', 'label' => 'Lihat Unit', 'group' => 'unit'],
            ['name' => 'unit.create', 'label' => 'Tambah Unit', 'group' => 'unit'],
            ['name' => 'unit.edit', 'label' => 'Edit Unit', 'group' => 'unit'],
            ['name' => 'unit.delete', 'label' => 'Hapus Unit', 'group' => 'unit'],
            ['name' => 'unit.export', 'label' => 'Export Unit', 'group' => 'unit'],
            
            // Material
            ['name' => 'material.view', 'label' => 'Lihat Material', 'group' => 'material'],
            ['name' => 'material.create', 'label' => 'Tambah Material', 'group' => 'material'],
            ['name' => 'material.edit', 'label' => 'Edit Material', 'group' => 'material'],
            ['name' => 'material.delete', 'label' => 'Hapus Material', 'group' => 'material'],
            ['name' => 'material.export', 'label' => 'Export Material', 'group' => 'material'],
            
            // Area
            ['name' => 'area.view', 'label' => 'Lihat Area', 'group' => 'area'],
            ['name' => 'area.create', 'label' => 'Tambah Area', 'group' => 'area'],
            ['name' => 'area.edit', 'label' => 'Edit Area', 'group' => 'area'],
            ['name' => 'area.delete', 'label' => 'Hapus Area', 'group' => 'area'],
            
            // Pegawai
            ['name' => 'pegawai.view', 'label' => 'Lihat Pegawai', 'group' => 'pegawai'],
            ['name' => 'pegawai.create', 'label' => 'Tambah Pegawai', 'group' => 'pegawai'],
            ['name' => 'pegawai.edit', 'label' => 'Edit Pegawai', 'group' => 'pegawai'],
            ['name' => 'pegawai.delete', 'label' => 'Hapus Pegawai', 'group' => 'pegawai'],
            
            // Ritasi
            ['name' => 'ritasi.view', 'label' => 'Lihat Ritasi', 'group' => 'ritasi'],
            ['name' => 'ritasi.create', 'label' => 'Input Ritasi', 'group' => 'ritasi'],
            ['name' => 'ritasi.validate', 'label' => 'Validasi Ritasi', 'group' => 'ritasi'],
            
            // Non-Ritasi
            ['name' => 'non-ritasi.view', 'label' => 'Lihat Non-Ritasi', 'group' => 'non-ritasi'],
            ['name' => 'non-ritasi.create', 'label' => 'Input Non-Ritasi', 'group' => 'non-ritasi'],
            ['name' => 'non-ritasi.validate', 'label' => 'Validasi Non-Ritasi', 'group' => 'non-ritasi'],
            
            // Pemantauan
            ['name' => 'pemantauan.view', 'label' => 'Lihat Pemantauan', 'group' => 'pemantauan'],
            ['name' => 'pemantauan.create', 'label' => 'Buat Pemantauan', 'group' => 'pemantauan'],
            
            // Laporan
            ['name' => 'laporan.harian', 'label' => 'Laporan Harian', 'group' => 'laporan'],
            ['name' => 'laporan.mingguan', 'label' => 'Laporan Mingguan', 'group' => 'laporan'],
            ['name' => 'laporan.bulanan', 'label' => 'Laporan Bulanan', 'group' => 'laporan'],
            ['name' => 'laporan.export', 'label' => 'Export Laporan', 'group' => 'laporan'],
            
            // Hak Akses
            ['name' => 'hak-akses.view', 'label' => 'Lihat Hak Akses', 'group' => 'hak-akses'],
            ['name' => 'hak-akses.edit', 'label' => 'Edit Hak Akses', 'group' => 'hak-akses'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}
```

- [ ] **Step 4: Create RolePermissionSeeder**

```php
<?php
// database/seeders/RolePermissionSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\RolePermission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = Permission::all();
        
        foreach ($permissions as $permission) {
            // Admin gets all permissions
            RolePermission::create([
                'role' => 'admin',
                'permission_id' => $permission->id,
                'allowed' => true,
            ]);
            
            // SPV gets limited permissions
            $spvAllowed = in_array($permission->group, ['pemantauan', 'laporan']) ||
                          in_array($permission->name, ['unit.view', 'material.view', 'area.view', 'pegawai.view', 'ritasi.view', 'non-ritasi.view']);
            
            RolePermission::create([
                'role' => 'spv',
                'permission_id' => $permission->id,
                'allowed' => $spvAllowed,
            ]);
            
            // Pegawai gets form permissions only
            $pegawaiAllowed = in_array($permission->name, ['ritasi.create', 'non-ritasi.create']);
            
            RolePermission::create([
                'role' => 'pegawai',
                'permission_id' => $permission->id,
                'allowed' => $pegawaiAllowed,
            ]);
        }
    }
}
```

- [ ] **Step 5: Create sample data seeders**

```php
<?php
// database/seeders/RitasiSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ritasi;
use App\Models\Pegawai;
use App\Models\Unit;
use App\Models\Area;
use App\Models\Material;

class RitasiSeeder extends Seeder
{
    public function run(): void
    {
        $pegawai = Pegawai::take(10)->get();
        $units = Unit::where('tipe', 'dump_truck')->take(5)->get();
        $areas = Area::take(5)->get();
        $materials = Material::whereIn('kategori', ['ore', 'waste'])->take(5)->get();

        if ($pegawai->isEmpty() || $units->isEmpty() || $areas->isEmpty() || $materials->isEmpty()) {
            return;
        }

        for ($i = 0; $i < 50; $i++) {
            $hmAwal = rand(10000, 15000) + (rand(0, 99) / 100);
            $hmTotal = rand(6, 11) + (rand(0, 99) / 100);
            
            Ritasi::create([
                'pegawai_id' => $pegawai->random()->id,
                'unit_id' => $units->random()->id,
                'area_id' => $areas->random()->id,
                'material_id' => $materials->random()->id,
                'tanggal' => now()->subDays(rand(0, 30)),
                'shift' => collect(['siang', 'malam'])->random(),
                'hm_awal' => $hmAwal,
                'hm_akhir' => $hmAwal + $hmTotal,
                'hm_total' => $hmTotal,
                'jumlah_ritasi' => rand(8, 20),
                'lokasi_pekerjaan' => collect(['Pit 1 North', 'Pit 2 South', 'East Dump', 'Haul Road A', 'South Pit'])->random(),
                'deskripsi_pekerjaan' => 'Hauling ' . collect(['ore', 'waste', 'overburden'])->random(),
                'status' => collect(['pending', 'validated', 'in_progress'])->random(),
            ]);
        }
    }
}
```

```php
<?php
// database/seeders/NonRitasiSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NonRitasi;
use App\Models\Pegawai;
use App\Models\Unit;
use App\Models\Area;

class NonRitasiSeeder extends Seeder
{
    public function run(): void
    {
        $pegawai = Pegawai::take(10)->get();
        $units = Unit::whereIn('tipe', ['excavator', 'bulldozer', 'motor_grader'])->take(5)->get();
        $areas = Area::take(5)->get();

        if ($pegawai->isEmpty() || $units->isEmpty() || $areas->isEmpty()) {
            return;
        }

        for ($i = 0; $i < 30; $i++) {
            $hmAwal = rand(5000, 12000) + (rand(0, 99) / 100);
            $hmTotal = rand(6, 11) + (rand(0, 99) / 100);
            
            NonRitasi::create([
                'pegawai_id' => $pegawai->random()->id,
                'unit_id' => $units->random()->id,
                'area_id' => $areas->random()->id,
                'tanggal' => now()->subDays(rand(0, 30)),
                'shift' => collect(['siang', 'malam'])->random(),
                'hm_awal' => $hmAwal,
                'hm_akhir' => $hmAwal + $hmTotal,
                'hm_total' => $hmTotal,
                'jam_mulai' => collect(['06:00', '07:00', '18:00', '19:00'])->random(),
                'jam_selesai' => collect(['17:00', '18:00', '05:00', '06:00'])->random(),
                'lokasi_pekerjaan' => collect(['Pit 1 North', 'Workshop', 'Office Area', 'Fuel Station'])->random(),
                'deskripsi_pekerjaan' => collect(['Dozing', 'Grading', 'Drilling', 'Blasting preparation'])->random(),
                'status' => collect(['pending', 'validated'])->random(),
            ]);
        }
    }
}
```

- [ ] **Step 6: Update DatabaseSeeder**

```php
<?php
// database/seeders/DatabaseSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            AreaSeeder::class,
            AlatSeeder::class,
            PegawaiSeeder::class,
            UserSeeder::class,
            MaterialSeeder::class,
            UnitSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
            RitasiSeeder::class,
            NonRitasiSeeder::class,
        ]);
    }
}
```

- [ ] **Step 7: Run seeders**

```bash
php artisan migrate:fresh --seed
```

---

## Task 5: Controllers

**Files:**
- Create: `app/Http/Controllers/LandingController.php`
- Create: `app/Http/Controllers/AdminUnitController.php`
- Create: `app/Http/Controllers/AdminMaterialController.php`
- Create: `app/Http/Controllers/AdminAreaController.php`
- Create: `app/Http/Controllers/AdminPermissionController.php`
- Create: `app/Http/Controllers/AdminLaporanController.php`
- Create: `app/Http/Controllers/PegawaiRitasiController.php`
- Create: `app/Http/Controllers/PegawaiNonRitasiController.php`
- Create: `app/Http/Controllers/PegawaiGeneralController.php`
- Create: `app/Http/Controllers/SpvLaporanController.php`
- Modify: `app/Http/Controllers/AuthController.php`
- Modify: `app/Http/Controllers/ProfileController.php`

- [ ] **Step 1: Create LandingController**

```php
<?php
// app/Http/Controllers/LandingController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        return view('landing');
    }
}
```

- [ ] **Step 2: Create AdminUnitController**

```php
<?php
// app/Http/Controllers/AdminUnitController.php
namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Area;
use Illuminate\Http\Request;

class AdminUnitController extends Controller
{
    public function index(Request $request)
    {
        $query = Unit::query();
        
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('kode', 'like', "%{$request->search}%")
                  ->orWhere('nama', 'like', "%{$request->search}%")
                  ->orWhere('model', 'like', "%{$request->search}%");
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $units = $query->orderBy('kode')->paginate(10);
        
        return view('admin.master-data.index', [
            'activeTab' => 'unit',
            'units' => $units,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|unique:units,kode',
            'nama' => 'required|string',
            'tipe' => 'required|in:excavator,dump_truck,bulldozer,motor_grader,loader,other',
            'merk' => 'nullable|string',
            'model' => 'nullable|string',
            'tahun' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'kapasitas' => 'nullable|numeric|min:0',
            'fuel_consumption_rate' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,maintenance,breakdown,standby',
            'keterangan' => 'nullable|string',
        ]);

        Unit::create($validated);

        return redirect()->route('admin.master-data.index', ['tab' => 'unit'])
            ->with('success', 'Unit berhasil ditambahkan');
    }

    public function update(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'kode' => 'required|string|unique:units,kode,' . $unit->id,
            'nama' => 'required|string',
            'tipe' => 'required|in:excavator,dump_truck,bulldozer,motor_grader,loader,other',
            'merk' => 'nullable|string',
            'model' => 'nullable|string',
            'tahun' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'kapasitas' => 'nullable|numeric|min:0',
            'fuel_consumption_rate' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,maintenance,breakdown,standby',
            'keterangan' => 'nullable|string',
        ]);

        $unit->update($validated);

        return redirect()->route('admin.master-data.index', ['tab' => 'unit'])
            ->with('success', 'Unit berhasil diupdate');
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();
        return redirect()->route('admin.master-data.index', ['tab' => 'unit'])
            ->with('success', 'Unit berhasil dihapus');
    }

    public function export()
    {
        // Export to Excel
        return response()->download('units.xlsx');
    }
}
```

- [ ] **Step 3: Create AdminMaterialController**

```php
<?php
// app/Http/Controllers/AdminMaterialController.php
namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;

class AdminMaterialController extends Controller
{
    public function index(Request $request)
    {
        $query = Material::query();
        
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('kode', 'like', "%{$request->search}%")
                  ->orWhere('nama', 'like', "%{$request->search}%");
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $materials = $query->orderBy('kode')->paginate(10);
        
        return view('admin.master-data.index', [
            'activeTab' => 'material',
            'materials' => $materials,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|unique:materials,kode',
            'nama' => 'required|string',
            'satuan' => 'required|string',
            'kategori' => 'required|in:ore,waste,fuel,lubricant,explosive,spare_part,other',
            'stok' => 'required|numeric|min:0',
            'stok_minimal' => 'required|numeric|min:0',
            'harga_satuan' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,low_stock,inactive,restricted',
            'keterangan' => 'nullable|string',
        ]);

        Material::create($validated);

        return redirect()->route('admin.master-data.index', ['tab' => 'material'])
            ->with('success', 'Material berhasil ditambahkan');
    }

    public function update(Request $request, Material $material)
    {
        $validated = $request->validate([
            'kode' => 'required|string|unique:materials,kode,' . $material->id,
            'nama' => 'required|string',
            'satuan' => 'required|string',
            'kategori' => 'required|in:ore,waste,fuel,lubricant,explosive,spare_part,other',
            'stok' => 'required|numeric|min:0',
            'stok_minimal' => 'required|numeric|min:0',
            'harga_satuan' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,low_stock,inactive,restricted',
            'keterangan' => 'nullable|string',
        ]);

        $material->update($validated);

        return redirect()->route('admin.master-data.index', ['tab' => 'material'])
            ->with('success', 'Material berhasil diupdate');
    }

    public function destroy(Material $material)
    {
        $material->delete();
        return redirect()->route('admin.master-data.index', ['tab' => 'material'])
            ->with('success', 'Material berhasil dihapus');
    }
}
```

- [ ] **Step 4: Create PegawaiRitasiController**

```php
<?php
// app/Http/Controllers/PegawaiRitasiController.php
namespace App\Http\Controllers;

use App\Models\Ritasi;
use App\Models\Unit;
use App\Models\Area;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PegawaiRitasiController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        $pegawai = $user->pegawai;
        
        $units = Unit::where('is_active', true)->orderBy('kode')->pluck('kode', 'id')->toArray();
        $areas = Area::orderBy('nama')->pluck('nama', 'id')->toArray();
        $materials = Material::where('is_active', true)->where('status', 'active')->orderBy('nama')->pluck('nama', 'id')->toArray();

        return view('operator.ritasi.create', compact('pegawai', 'units', 'areas', 'materials'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'area_id' => 'required|exists:areas,id',
            'material_id' => 'required|exists:materials,id',
            'shift' => 'required|in:siang,malam',
            'tanggal' => 'required|date',
            'hm_awal' => 'required|numeric|min:0',
            'hm_akhir' => 'required|numeric|min:0|gte:hm_awal',
            'jumlah_ritasi' => 'required|integer|min:0',
            'lokasi_pekerjaan' => 'nullable|string',
            'deskripsi_pekerjaan' => 'nullable|string',
            'kendala' => 'nullable|string',
        ]);

        $pegawaiId = Auth::user()->pegawai_id;

        // Check duplicate
        $exists = Ritasi::where('pegawai_id', $pegawaiId)
            ->where('tanggal', $validated['tanggal'])
            ->where('shift', $validated['shift'])
            ->exists();

        if ($exists) {
            if ($request->header('X-Offline-Replay') === '1') {
                return response()->json(['success' => true, 'replayed' => true], 200);
            }
            return back()->with('error', 'Anda sudah melakukan input ritasi pada shift dan tanggal tersebut.');
        }

        $validated['pegawai_id'] = $pegawaiId;
        $validated['hm_total'] = $validated['hm_akhir'] - $validated['hm_awal'];

        Ritasi::create($validated);

        if ($request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Data ritasi berhasil disimpan!');
    }

    public function riwayat(Request $request)
    {
        $pegawaiId = Auth::user()->pegawai_id;
        
        $query = Ritasi::with(['unit', 'area', 'material'])
            ->where('pegawai_id', $pegawaiId)
            ->orderBy('tanggal', 'desc');

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        if ($request->filled('shift')) {
            $query->where('shift', $request->shift);
        }

        $ritasis = $query->paginate(15);

        return view('operator.ritasi.index', compact('ritasis'));
    }
}
```

- [ ] **Step 5: Create routes**

```php
<?php
// routes/web.php - Updated
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SpvController;
use App\Http\Controllers\AdminUnitController;
use App\Http\Controllers\AdminMaterialController;
use App\Http\Controllers\AdminAreaController;
use App\Http\Controllers\AdminPermissionController;
use App\Http\Controllers\PegawaiRitasiController;
use App\Http\Controllers\PegawaiNonRitasiController;
use App\Http\Controllers\PegawaiGeneralController;
use App\Http\Controllers\SpvLaporanController;
use App\Http\Controllers\ProfileController;

// Public
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Auth
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/csrf-token', fn () => response()->json(['token' => csrf_token()]));

Route::middleware(['auth'])->group(function () {
    // Profile
    Route::prefix('profil')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password');
        Route::post('/photo', [ProfileController::class, 'updatePhoto'])->name('photo');
        Route::delete('/photo', [ProfileController::class, 'removePhoto'])->name('photo.remove');
    });

    // Pegawai (Operator)
    Route::middleware('role:pegawai')->prefix('pegawai')->name('pegawai.')->group(function () {
        Route::get('/', [PegawaiController::class, 'dashboard'])->name('dashboard');
        
        // Ritasi
        Route::get('ritasi/create', [PegawaiRitasiController::class, 'create'])->name('ritasi.create');
        Route::post('ritasi', [PegawaiRitasiController::class, 'store'])->name('ritasi.store');
        Route::get('ritasi/riwayat', [PegawaiRitasiController::class, 'riwayat'])->name('ritasi.riwayat');
        
        // Non-Ritasi
        Route::get('non-ritasi/create', [PegawaiNonRitasiController::class, 'create'])->name('non-ritasi.create');
        Route::post('non-ritasi', [PegawaiNonRitasiController::class, 'store'])->name('non-ritasi.store');
        Route::get('non-ritasi/riwayat', [PegawaiNonRitasiController::class, 'riwayat'])->name('non-ritasi.riwayat');
        
        // General
        Route::get('general/create', [PegawaiGeneralController::class, 'create'])->name('general.create');
        Route::post('general', [PegawaiGeneralController::class, 'store'])->name('general.store');
        
        // Legacy
        Route::get('rekapan/create', [PegawaiController::class, 'createRekapan'])->name('rekapan.create');
        Route::post('rekapan', [PegawaiController::class, 'storeRekapan'])->name('rekapan.store');
        Route::get('riwayat', [PegawaiController::class, 'riwayat'])->name('riwayat');
    });

    // SPV
    Route::middleware('role:spv')->prefix('spv')->name('spv.')->group(function () {
        Route::get('/dashboard', [SpvController::class, 'dashboard'])->name('dashboard');
        
        // Laporan
        Route::prefix('laporan')->name('laporan.')->group(function () {
            Route::get('/', [SpvLaporanController::class, 'index'])->name('index');
            Route::get('harian', [SpvLaporanController::class, 'harian'])->name('harian');
            Route::get('mingguan', [SpvLaporanController::class, 'mingguan'])->name('mingguan');
            Route::get('bulanan', [SpvLaporanController::class, 'bulanan'])->name('bulanan');
            Route::post('export/{type}', [SpvLaporanController::class, 'export'])->name('export');
        });
    });

    // Admin
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // Master Data
        Route::get('master-data', [AdminUnitController::class, 'index'])->name('master-data.index');
        Route::post('unit', [AdminUnitController::class, 'store'])->name('unit.store');
        Route::put('unit/{unit}', [AdminUnitController::class, 'update'])->name('unit.update');
        Route::delete('unit/{unit}', [AdminUnitController::class, 'destroy'])->name('unit.destroy');
        
        Route::post('material', [AdminMaterialController::class, 'store'])->name('material.store');
        Route::put('material/{material}', [AdminMaterialController::class, 'update'])->name('material.update');
        Route::delete('material/{material}', [AdminMaterialController::class, 'destroy'])->name('material.destroy');
        
        Route::post('area', [AdminAreaController::class, 'store'])->name('area.store');
        Route::put('area/{area}', [AdminAreaController::class, 'update'])->name('area.update');
        Route::delete('area/{area}', [AdminAreaController::class, 'destroy'])->name('area.destroy');
        
        // Hak Akses
        Route::get('hak-akses', [AdminPermissionController::class, 'index'])->name('hak-akses.index');
        Route::put('hak-akses/{role}', [AdminPermissionController::class, 'update'])->name('hak-akses.update');
        
        // Laporan
        Route::get('laporan', [AdminController::class, 'laporan'])->name('laporan.index');
        Route::post('laporan/export', [AdminController::class, 'exportLaporan'])->name('laporan.export');
        
        // Export
        Route::get('export', [AdminController::class, 'export'])->name('export');
    });
});

// Legacy redirects
Route::get('/absensi', fn () => redirect()->route('pegawai.ritasi.create'));
Route::get('/rekapan', fn () => redirect()->route('pegawai.ritasi.create'));
```

---

## Task 6: Views - Landing & Login

**Files:**
- Modify: `resources/views/landing.blade.php`
- Modify: `resources/views/auth/login.blade.php`

- [ ] **Step 1: Create landing page**

```blade
{{-- resources/views/landing.blade.php --}}
@extends('layouts.public')

@section('title', 'Surface Mine Production')

@section('content')
<div class="min-h-screen flex items-center justify-center relative overflow-hidden">
    {{-- Background gradient --}}
    <div class="absolute inset-0 bg-gradient-to-br from-[#0a0f1a] via-[#0f172a] to-[#1a2332]"></div>
    
    {{-- Content --}}
    <div class="relative z-10 max-w-6xl mx-auto px-6 py-12 flex items-center gap-12">
        {{-- Left side - Text --}}
        <div class="flex-1 text-white">
            <h1 class="text-4xl lg:text-5xl font-heading font-bold leading-tight mb-6">
                Surface Mine<br>
                Production Operational<br>
                Record
            </h1>
            <p class="text-lg text-slate-300 mb-8 max-w-lg">
                A centralized dashboard to monitor daily operational activities across Civil Departments, providing real-time insights
            </p>
            <a href="{{ route('login') }}" class="inline-block bg-white text-[#0f172a] font-bold px-8 py-3 rounded-lg hover:bg-slate-100 transition-colors">
                LOGIN
            </a>
        </div>
        
        {{-- Right side - Image --}}
        <div class="flex-1 hidden lg:flex justify-center">
            <img src="{{ asset('images/worker-hero.png') }}" alt="Mining Worker" class="max-w-md w-full h-auto">
        </div>
    </div>
</div>

<style>
    body { margin: 0; padding: 0; overflow-x: hidden; }
</style>
@endsection
```

**Image needed:** `public/images/worker-hero.png` - Mining worker in orange uniform with hard hat

- [ ] **Step 2: Create login page**

```blade
{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.public')

@section('title', 'Login - Surface Mine')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-[#0f172a]">
    <div class="w-full max-w-md px-6">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <img src="{{ asset('images/company-logo.png') }}" alt="Company Logo" class="w-32 h-32 mx-auto mb-4 rounded-full border-2 border-amber-500">
        </div>
        
        {{-- Title --}}
        <div class="text-center mb-8">
            <h1 class="text-3xl font-heading font-bold text-white mb-2">SURFACE MINE OPERATIONALS</h1>
            <p class="text-slate-300 text-lg">Welcome To Civil Departement</p>
        </div>
        
        {{-- Form --}}
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            
            {{-- Username --}}
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Username</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                        <span class="material-symbols-outlined">person</span>
                    </span>
                    <input type="text" 
                           name="username" 
                           value="{{ old('username') }}"
                           placeholder="Enter username"
                           class="w-full pl-10 pr-4 py-3 bg-white border-0 rounded-lg text-gray-900 placeholder-slate-400 focus:ring-2 focus:ring-amber-500"
                           required>
                </div>
                @error('username')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>
            
            {{-- Password / ID Pekerja --}}
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">ID Pekerja</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                        <span class="material-symbols-outlined">badge</span>
                    </span>
                    <input type="password" 
                           name="password" 
                           placeholder="Enter ID Pekerja"
                           class="w-full pl-10 pr-4 py-3 bg-white border-0 rounded-lg text-gray-900 placeholder-slate-400 focus:ring-2 focus:ring-amber-500"
                           required>
                </div>
                @error('password')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>
            
            {{-- Error message --}}
            @if(session('error'))
                <div class="bg-red-500/20 border border-red-500 text-red-300 px-4 py-3 rounded-lg text-sm">
                    {{ session('error') }}
                </div>
            @endif
            
            {{-- Button --}}
            <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 rounded-lg transition-colors flex items-center justify-center gap-2">
                Masuk
                <span class="material-symbols-outlined">arrow_forward</span>
            </button>
        </form>
    </div>
</div>
@endsection
```

**Image needed:** `public/images/company-logo.png` - Company logo (PT NUSA HALMAHERA MINERALS)

---

## Task 7: Views - Admin Layout & Dashboard

**Files:**
- Create: `resources/views/layouts/admin.blade.php`
- Modify: `resources/views/admin/dashboard.blade.php`
- Create: `resources/views/components/sidebar.blade.php`
- Create: `resources/views/components/topbar.blade.php`
- Create: `resources/views/components/kpi-card.blade.php`

- [ ] **Step 1: Create admin layout**

```blade
{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Surface Mine</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('styles')
</head>
<body class="bg-[var(--bg)] font-['Inter',sans-serif]">
    @include('components.sidebar', ['role' => 'admin'])
    @include('components.topbar', ['role' => 'admin'])
    
    <main class="ml-64 pt-16 min-h-screen p-6">
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4">
                {{ session('error') }}
            </div>
        @endif
        
        @yield('content')
    </main>
    
    @stack('scripts')
</body>
</html>
```

- [ ] **Step 2: Create sidebar component**

```blade
{{-- resources/views/components/sidebar.blade.php --}}
@props(['role' => 'admin'])

<aside class="sidebar">
    {{-- Logo --}}
    <div class="p-6 border-b border-white/10">
        <a href="{{ route("$role.dashboard") }}" class="flex items-center gap-3">
            <img src="{{ asset('images/mining-logo.png') }}" alt="Logo" class="w-8 h-8">
            <span class="text-lg font-heading font-bold">Surface Mine</span>
        </a>
    </div>
    
    {{-- Navigation --}}
    <nav class="py-4">
        @if($role === 'admin')
            <a href="{{ route('admin.dashboard') }}" 
               class="sidebar-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="material-symbols-outlined">dashboard</span>
                Dashboard Pemantauan
            </a>
            <a href="{{ route('admin.master-data.index') }}" 
               class="sidebar-nav-item {{ request()->routeIs('admin.master-data.*') ? 'active' : '' }}">
                <span class="material-symbols-outlined">storage</span>
                Master Data
            </a>
            <a href="{{ route('admin.hak-akses.index') }}" 
               class="sidebar-nav-item {{ request()->routeIs('admin.hak-akses.*') ? 'active' : '' }}">
                <span class="material-symbols-outlined">admin_panel_settings</span>
                Hak Akses
            </a>
            <a href="{{ route('admin.laporan.index') }}" 
               class="sidebar-nav-item {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                <span class="material-symbols-outlined">monitoring</span>
                Laporan Pemantauan
            </a>
        @elseif($role === 'spv')
            <a href="{{ route('spv.dashboard') }}" 
               class="sidebar-nav-item {{ request()->routeIs('spv.dashboard') ? 'active' : '' }}">
                <span class="material-symbols-outlined">dashboard</span>
                Dashboard Pemantauan
            </a>
            <a href="{{ route('spv.laporan.index') }}" 
               class="sidebar-nav-item {{ request()->routeIs('spv.laporan.*') ? 'active' : '' }}">
                <span class="material-symbols-outlined">monitoring</span>
                Laporan Pemantauan
            </a>
        @elseif($role === 'pegawai')
            <a href="{{ route('pegawai.dashboard') }}" 
               class="sidebar-nav-item {{ request()->routeIs('pegawai.dashboard') ? 'active' : '' }}">
                <span class="material-symbols-outlined">dashboard</span>
                Dashboard
            </a>
            <a href="{{ route('pegawai.ritasi.create') }}" 
               class="sidebar-nav-item {{ request()->routeIs('pegawai.ritasi.*') ? 'active' : '' }}">
                <span class="material-symbols-outlined">local_shipping</span>
                Unit Ritasi
            </a>
            <a href="{{ route('pegawai.non-ritasi.create') }}" 
               class="sidebar-nav-item {{ request()->routeIs('pegawai.non-ritasi.*') ? 'active' : '' }}">
                <span class="material-symbols-outlined">construction</span>
                Unit Non Ritasi
            </a>
            <a href="{{ route('pegawai.general.create') }}" 
               class="sidebar-nav-item {{ request()->routeIs('pegawai.general.*') ? 'active' : '' }}">
                <span class="material-symbols-outlined">engineering</span>
                Pekerjaan General
            </a>
        @endif
        
        <div class="border-t border-white/10 my-4"></div>
        
        <a href="{{ route('profile.show') }}" 
           class="sidebar-nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">person</span>
            Profil
        </a>
    </nav>
</aside>
```

**Image needed:** `public/images/mining-logo.png` - Mining equipment icon/logo

- [ ] **Step 3: Create topbar component**

```blade
{{-- resources/views/components/topbar.blade.php --}}
@props(['role' => 'admin'])

<header class="topbar">
    <h1 class="text-xl font-heading font-bold text-[var(--primary)]">Mining Oprationals Civil Departement</h1>
    
    <div class="flex items-center gap-4">
        <span class="text-sm font-medium">{{ ucfirst($role) }}</span>
        
        <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button type="submit" class="flex items-center gap-1 text-sm text-red-600 hover:text-red-700">
                <span class="material-symbols-outlined text-lg">logout</span>
                Logout
            </button>
        </form>
        
        <button class="relative">
            <span class="material-symbols-outlined text-slate-600">notifications</span>
            <span class="absolute -top-1 -right-1 w-2 h-2 bg-red-500 rounded-full"></span>
        </button>
        
        <button>
            <span class="material-symbols-outlined text-slate-600">settings</span>
        </button>
    </div>
</header>
```

- [ ] **Step 4: Create KPI card component**

```blade
{{-- resources/views/components/kpi-card.blade.php --}}
@props(['title', 'value', 'icon', 'color' => 'blue'])

@php
    $colorClasses = match($color) {
        'blue' => 'bg-blue-50 text-blue-600',
        'green' => 'bg-green-50 text-green-600',
        'orange' => 'bg-orange-50 text-orange-600',
        'purple' => 'bg-purple-50 text-purple-600',
        default => 'bg-blue-50 text-blue-600',
    };
@endphp

<div class="card p-4">
    <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-lg {{ $colorClasses }} flex items-center justify-center">
            <span class="material-symbols-outlined">{{ $icon }}</span>
        </div>
        <div>
            <p class="text-sm text-slate-500">{{ $title }}</p>
            <p class="text-2xl font-bold text-[var(--primary)]">{{ $value }}</p>
        </div>
    </div>
</div>
```

- [ ] **Step 5: Create admin dashboard view**

```blade
{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-heading font-bold text-[var(--primary)]">Monitoring Dashboard</h1>
    <p class="text-slate-500">Live operational telemetry and site metrics.</p>
</div>

{{-- Action buttons --}}
<div class="flex gap-3 mb-6">
    <a href="{{ route('admin.export') }}" class="btn-secondary flex items-center gap-2">
        <span class="material-symbols-outlined text-lg">download</span>
        Export
    </a>
    <button class="bg-green-500 hover:bg-green-600 text-white font-semibold px-4 py-2 rounded-lg flex items-center gap-2">
        <span class="material-symbols-outlined text-lg">sync</span>
        Sync Data
    </button>
</div>

{{-- KPI Cards --}}
<div class="grid grid-cols-4 gap-4 mb-6">
    <x-kpi-card title="Total Ritasi" value="{{ number_format($metrics['total_ritasi'] ?? 1284) }}" icon="local_shipping" color="blue" />
    <x-kpi-card title="Total Unit Aktif" value="{{ $metrics['unit_aktif'] ?? '42 / 50' }}" icon="precision_manufacturing" color="orange" />
    <x-kpi-card title="Total Jam Kerja" value="{{ $metrics['jam_kerja'] ?? '342h' }}" icon="schedule" color="green" />
    <x-kpi-card title="Pekerjaan General" value="{{ $metrics['general_tasks'] ?? '18 Tasks' }}" icon="assignment" color="purple" />
</div>

{{-- Daily Breakdown Activity --}}
<div class="card p-6 mb-6">
    <h2 class="text-lg font-heading font-bold mb-4">Daily Breakdown Activity</h2>
    <div class="grid grid-cols-3 gap-6">
        {{-- Bar Chart --}}
        <div>
            <div class="bg-slate-100 rounded-lg p-4 mb-4">
                <span class="text-sm text-slate-500">Daily All Hauling</span>
                <p class="text-2xl font-bold text-[var(--accent)]">4,278</p>
            </div>
            <canvas id="dailyChart" height="200"></canvas>
        </div>
        
        {{-- Day Shift --}}
        <div>
            <h3 class="text-center font-semibold mb-4">Day Shift</h3>
            <div class="space-y-3" id="dayShiftBars">
                {{-- Bars will be rendered here --}}
            </div>
        </div>
        
        {{-- Night Shift --}}
        <div>
            <h3 class="text-center font-semibold mb-4">Night Shift</h3>
            <div class="space-y-3" id="nightShiftBars">
                {{-- Bars will be rendered here --}}
            </div>
        </div>
    </div>
</div>

{{-- Grafik All Hauling (WTD) --}}
<div class="card p-6 mb-6">
    <h2 class="text-lg font-heading font-bold mb-4">Grafik All Hauling (WTD)</h2>
    <div class="grid grid-cols-2 gap-6">
        <div>
            <div class="bg-slate-100 rounded-lg p-4 mb-4">
                <span class="text-sm text-slate-500">Weekly All Hauling</span>
                <p class="text-2xl font-bold text-[var(--accent)]">8,568</p>
            </div>
            <canvas id="weeklyChart" height="200"></canvas>
        </div>
        
        <div>
            {{-- Availability --}}
            <div class="mb-6">
                <h3 class="font-semibold mb-4">Availability</h3>
                <div class="grid grid-cols-4 gap-4">
                    @foreach(['Exc' => '45.5%', 'Sany' => '33.3%', 'ADT' => '66.7%', 'Dozer' => '31.3%'] as $name => $value)
                        <div class="text-center">
                            <canvas id="avail-{{ $name }}" width="80" height="80"></canvas>
                            <p class="text-xs mt-1">{{ $name }}</p>
                            <p class="text-sm font-bold">{{ $value }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
            
            {{-- UoA --}}
            <div>
                <h3 class="font-semibold mb-4">UoA</h3>
                <div class="grid grid-cols-4 gap-4">
                    @foreach(['Exc' => '49.1%', 'Sany' => '41.8%', 'ADT' => '74.4%', 'Dozer' => '38.2%'] as $name => $value)
                        <div class="text-center">
                            <canvas id="uoa-{{ $name }}" width="80" height="80"></canvas>
                            <p class="text-xs mt-1">{{ $name }}</p>
                            <p class="text-sm font-bold">{{ $value }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Monthly MTD Report --}}
<div class="card p-6">
    <h2 class="text-lg font-heading font-bold mb-4">Monthly - MTD Report</h2>
    <div class="bg-slate-100 rounded-lg p-4 mb-4">
        <span class="text-sm text-slate-500">All Materials Hauling</span>
        <p class="text-2xl font-bold text-[var(--accent)]">75,709</p>
    </div>
    <canvas id="monthlyChart" height="150"></canvas>
</div>
@endsection

@push('scripts')
<script>
// Initialize charts
document.addEventListener('DOMContentLoaded', function() {
    // Daily Chart
    new Chart(document.getElementById('dailyChart'), {
        type: 'bar',
        data: {
            labels: ['Tuff Paste', 'KCN', 'CakeDST', 'Tuff Off', 'Batu Pica', 'Mining Tuff', 'Pasir Hitam', 'Mud', 'Lumpur', 'Waste'],
            datasets: [{
                data: [1227, 700, 260, 175, 120, 90, 40, 26, 0, 0],
                backgroundColor: '#0f172a',
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
    
    // Monthly Chart
    new Chart(document.getElementById('monthlyChart'), {
        type: 'bar',
        data: {
            labels: ['1-May', '4-May', '7-May', '10-May', '13-May', '16-May', '19-May', '22-May', '25-May', '28-May', '31-May'],
            datasets: [
                { type: 'bar', label: 'Ore', data: [40, 35, 45, 50, 42, 48, 55, 52, 58, 54, 60], backgroundColor: '#0f172a' },
                { type: 'bar', label: 'Others', data: [30, 28, 32, 35, 30, 34, 38, 36, 40, 38, 42], backgroundColor: '#93c5fd' },
                { type: 'line', label: 'Cumulative', data: [70, 63, 77, 85, 72, 82, 93, 88, 98, 92, 102], borderColor: '#f59e0b', fill: false }
            ]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true } }
        }
    });
});
</script>
@endpush
```

---

## Task 8: Views - Master Data

**Files:**
- Create: `resources/views/admin/master-data/index.blade.php`
- Create: `resources/views/admin/master-data/partials/unit-table.blade.php`
- Create: `resources/views/admin/master-data/partials/material-table.blade.php`
- Create: `resources/views/admin/master-data/partials/area-table.blade.php`
- Create: `resources/views/admin/master-data/partials/user-table.blade.php`
- Create: `resources/views/admin/master-data/partials/hak-akses.blade.php`

- [ ] **Step 1: Create master data index with tabs**

```blade
{{-- resources/views/admin/master-data/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Master Data')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-heading font-bold text-[var(--primary)]">Mining Oprationals Civil Departement</h1>
</div>

{{-- Tabs --}}
<div class="border-b mb-6">
    <nav class="flex gap-8">
        @php $tabs = ['user' => 'User', 'area' => 'Area', 'unit' => 'Unit', 'material' => 'Material', 'hak-akses' => 'Hak Akses']; @endphp
        @foreach($tabs as $key => $label)
            <a href="?tab={{ $key }}" 
               class="pb-3 px-1 text-sm font-medium border-b-2 transition-colors
                      {{ ($activeTab ?? 'unit') === $key ? 'border-[var(--accent)] text-[var(--accent)]' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                {{ $label }}
            </a>
        @endforeach
    </nav>
</div>

{{-- Tab Content --}}
@if(($activeTab ?? 'unit') === 'unit')
    @include('admin.master-data.partials.unit-table')
@elseif($activeTab === 'material')
    @include('admin.master-data.partials.material-table')
@elseif($activeTab === 'area')
    @include('admin.master-data.partials.area-table')
@elseif($activeTab === 'user')
    @include('admin.master-data.partials.user-table')
@elseif($activeTab === 'hak-akses')
    @include('admin.master-data.partials.hak-akses')
@endif
@endsection
```

- [ ] **Step 2: Create unit table partial**

```blade
{{-- resources/views/admin/master-data/partials/unit-table.blade.php --}}
<div class="card">
    {{-- Search and Filter --}}
    <div class="p-4 border-b flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                    <span class="material-symbols-outlined text-lg">search</span>
                </span>
                <input type="text" 
                       placeholder="Cari data..." 
                       class="pl-10 pr-4 py-2 border rounded-lg w-64 text-sm"
                       id="searchInput">
            </div>
        </div>
        <button class="btn-secondary flex items-center gap-2">
            <span class="material-symbols-outlined text-lg">filter_list</span>
            Filter
        </button>
    </div>
    
    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600">NO</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600">UNIT ID</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600">MODEL/TIPE</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600">TAHUN</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600">STATUS</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600">AKSI</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($units as $index => $unit)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 text-sm">{{ $index + 1 }}</td>
                        <td class="px-4 py-3 text-sm font-medium">{{ $unit->kode }}</td>
                        <td class="px-4 py-3 text-sm">{{ $unit->nama }}</td>
                        <td class="px-4 py-3 text-sm">{{ $unit->tahun ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <span class="badge {{ $unit->status_badge }}">
                                {{ ucfirst($unit->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <button class="text-blue-600 hover:text-blue-700" onclick="editUnit({{ $unit->id }})">
                                    <span class="material-symbols-outlined text-lg">edit</span>
                                </button>
                                <button class="text-red-600 hover:text-red-700" onclick="deleteUnit({{ $unit->id }})">
                                    <span class="material-symbols-outlined text-lg">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-slate-500">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{-- Pagination --}}
    <div class="p-4 border-t flex items-center justify-between">
        <p class="text-sm text-slate-500">Showing {{ $units->firstItem() ?? 0 }} to {{ $units->lastItem() ?? 0 }} of {{ $units->total() }} entries</p>
        {{ $units->withQueryString()->links() }}
    </div>
</div>

{{-- Action buttons --}}
<div class="flex gap-3 mt-4">
    <button class="btn-secondary flex items-center gap-2">
        <span class="material-symbols-outlined text-lg">download</span>
        Export Excel
    </button>
    <button class="btn-primary flex items-center gap-2" onclick="openModal('addUnit')">
        <span class="material-symbols-outlined text-lg">add</span>
        Tambah Data
    </button>
</div>

{{-- Modal for Add/Edit --}}
<div id="unitModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl w-full max-w-lg mx-4">
        <div class="p-4 border-b flex items-center justify-between">
            <h3 class="font-heading font-bold">Tambah Unit</h3>
            <button onclick="closeModal('unitModal')" class="text-slate-400 hover:text-slate-600">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form action="{{ route('admin.unit.store') }}" method="POST" class="p-4 space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Kode Unit</label>
                    <input type="text" name="kode" class="form-input" placeholder="EXC-001" required>
                </div>
                <div>
                    <label class="form-label">Tipe</label>
                    <select name="tipe" class="form-input" required>
                        <option value="excavator">Excavator</option>
                        <option value="dump_truck">Dump Truck</option>
                        <option value="bulldozer">Bulldozer</option>
                        <option value="motor_grader">Motor Grader</option>
                        <option value="loader">Loader</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="form-label">Nama/Model</label>
                <input type="text" name="nama" class="form-input" placeholder="Excavator PC2000-8" required>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="form-label">Merk</label>
                    <input type="text" name="merk" class="form-input" placeholder="Komatsu">
                </div>
                <div>
                    <label class="form-label">Model</label>
                    <input type="text" name="model" class="form-input" placeholder="PC2000-8">
                </div>
                <div>
                    <label class="form-label">Tahun</label>
                    <input type="number" name="tahun" class="form-input" placeholder="2021">
                </div>
            </div>
            <div>
                <label class="form-label">Status</label>
                <select name="status" class="form-input" required>
                    <option value="active">Active</option>
                    <option value="maintenance">Maintenance</option>
                    <option value="breakdown">Breakdown</option>
                    <option value="standby">Standby</option>
                </select>
            </div>
            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="closeModal('unitModal')" class="btn-secondary">Batal</button>
                <button type="submit" class="btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(type) {
    document.getElementById(type + 'Modal').classList.remove('hidden');
}
function closeModal(type) {
    document.getElementById(type + 'Modal').classList.add('hidden');
}
function editUnit(id) {
    // TODO: Open edit modal
    console.log('Edit unit:', id);
}
function deleteUnit(id) {
    if (confirm('Yakin ingin menghapus unit ini?')) {
        fetch(`/admin/unit/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        }).then(() => location.reload());
    }
}
</script>
```

- [ ] **Step 3: Create material table partial**

```blade
{{-- resources/views/admin/master-data/partials/material-table.blade.php --}}
<div class="card">
    {{-- Search and Filter --}}
    <div class="p-4 border-b flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                    <span class="material-symbols-outlined text-lg">search</span>
                </span>
                <input type="text" placeholder="Cari data..." class="pl-10 pr-4 py-2 border rounded-lg w-64 text-sm">
            </div>
        </div>
        <button class="btn-secondary flex items-center gap-2">
            <span class="material-symbols-outlined text-lg">filter_list</span>
            Filter
        </button>
    </div>
    
    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600">NO</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600">NAMA MATERIAL</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600">KODE MATERIAL</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600">SATUAN</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600">STATUS</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600">AKSI</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($materials as $index => $material)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 text-sm">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-4 py-3 text-sm font-medium">{{ $material->nama }}</td>
                        <td class="px-4 py-3 text-sm font-mono">{{ $material->kode }}</td>
                        <td class="px-4 py-3 text-sm">{{ $material->satuan }}</td>
                        <td class="px-4 py-3">
                            <span class="badge {{ $material->status_badge }}">
                                {{ ucfirst(str_replace('_', ' ', $material->status)) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <button class="text-blue-600 hover:text-blue-700">
                                    <span class="material-symbols-outlined text-lg">edit</span>
                                </button>
                                <button class="text-red-600 hover:text-red-700">
                                    <span class="material-symbols-outlined text-lg">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-slate-500">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{-- Pagination --}}
    <div class="p-4 border-t flex items-center justify-between">
        <p class="text-sm text-slate-500">Showing {{ $materials->firstItem() ?? 0 }} to {{ $materials->lastItem() ?? 0 }} of {{ $materials->total() }} entries</p>
        {{ $materials->withQueryString()->links() }}
    </div>
</div>

{{-- Action buttons --}}
<div class="flex gap-3 mt-4">
    <button class="btn-secondary flex items-center gap-2">
        <span class="material-symbols-outlined text-lg">download</span>
        Export Excel
    </button>
    <button class="btn-primary flex items-center gap-2">
        <span class="material-symbols-outlined text-lg">add</span>
        Tambah Data
    </button>
</div>
```

---

## Task 9: Views - Operator Forms

**Files:**
- Create: `resources/views/layouts/operator.blade.php`
- Modify: `resources/views/operator/dashboard.blade.php`
- Modify: `resources/views/operator/ritasi/create.blade.php`
- Modify: `resources/views/operator/non-ritasi/create.blade.php`
- Create: `resources/views/operator/general/create.blade.php`

- [ ] **Step 1: Create operator layout**

```blade
{{-- resources/views/layouts/operator.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Operator Dashboard') - Surface Mine</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
</head>
<body class="bg-[var(--bg)] font-['Inter',sans-serif]">
    @include('components.sidebar', ['role' => 'pegawai'])
    
    {{-- Operator Topbar --}}
    <header class="topbar">
        <h1 class="text-xl font-heading font-bold text-[var(--primary)]">@yield('page-title', 'Form Input')</h1>
        <div class="flex items-center gap-4">
            <button class="text-slate-600 hover:text-slate-800">
                <span class="material-symbols-outlined">help</span>
            </button>
            <button class="relative">
                <span class="material-symbols-outlined text-slate-600">notifications</span>
            </button>
        </div>
    </header>
    
    <main class="ml-64 pt-16 min-h-screen p-6">
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4">
                {{ session('error') }}
            </div>
        @endif
        
        @yield('content')
    </main>
</body>
</html>
```

- [ ] **Step 2: Create Form Ritasi view**

```blade
{{-- resources/views/operator/ritasi/create.blade.php --}}
@extends('layouts.operator')

@section('title', 'Form Input Unit Ritasi')
@section('page-title', 'Form Input Unit Ritasi')

@section('content')
{{-- Session Info --}}
<div class="bg-[var(--primary)] text-white rounded-lg p-4 mb-6">
    <div class="flex items-center gap-3">
        <span class="material-symbols-outlined">info</span>
        <div>
            <p class="font-semibold">Sesi Aktif</p>
            <p class="text-sm text-slate-300">Silakan isi data ritasi operasional harian. Pastikan durasi HM sesuai (6 - 11 Jam).</p>
        </div>
    </div>
</div>

<form action="{{ route('pegawai.ritasi.store') }}" method="POST" data-offline-form data-sync-tag="ritasi-sync">
    @csrf
    
    <div class="card p-6">
        {{-- Data Dasar --}}
        <h2 class="text-lg font-heading font-bold text-[var(--primary)] mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined">description</span>
            Data Dasar
        </h2>
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <label class="form-label">Shift</label>
                <select name="shift" class="form-input" required>
                    <option value="">Pilih Shift</option>
                    <option value="siang">Siang</option>
                    <option value="malam">Malam</option>
                </select>
            </div>
            <div>
                <label class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-input" value="{{ date('Y-m-d') }}" required>
            </div>
            <div>
                <label class="form-label">Nama Operator</label>
                <input type="text" class="form-input bg-slate-50" value="{{ $pegawai->nama ?? Auth::user()->name }}" readonly>
            </div>
            <div>
                <label class="form-label">Nomor Unit (Dump Truck)</label>
                <select name="unit_id" class="form-input" required>
                    <option value="">Pilih Unit</option>
                    @foreach($units as $id => $kode)
                        <option value="{{ $id }}">{{ $kode }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        
        {{-- Hour Meter --}}
        <h2 class="text-lg font-heading font-bold text-[var(--primary)] mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined">timer</span>
            Hour Meter (HM)
        </h2>
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div>
                <label class="form-label">HM Awal</label>
                <input type="number" name="hm_awal" class="form-input" step="0.1" min="0" value="0.0" required id="hmAwal">
            </div>
            <div>
                <label class="form-label">HM Akhir</label>
                <input type="number" name="hm_akhir" class="form-input" step="0.1" min="0" value="0.0" required id="hmAkhir">
            </div>
            <div class="bg-slate-100 rounded-lg p-4 flex items-center justify-between">
                <span class="text-sm font-medium">Total Durasi HM:</span>
                <span class="text-xl font-bold text-[var(--primary)]" id="hmTotal">0.0 Jam</span>
            </div>
        </div>
        
        {{-- Detail Pekerjaan --}}
        <h2 class="text-lg font-heading font-bold text-[var(--primary)] mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined">work</span>
            Detail Pekerjaan
        </h2>
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <label class="form-label">Jenis Material</label>
                <select name="material_id" class="form-input" required>
                    <option value="">Pilih Material</option>
                    @foreach($materials as $id => $nama)
                        <option value="{{ $id }}">{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Jumlah Ritasi (Trip)</label>
                <input type="number" name="jumlah_ritasi" class="form-input" min="0" value="0" required>
            </div>
            <div>
                <label class="form-label">Lokasi Pekerjaan (Pit / Disposal)</label>
                <input type="text" name="lokasi_pekerjaan" class="form-input" placeholder="Contoh: Pit 1 North">
            </div>
            <div class="col-span-2">
                <label class="form-label">Deskripsi Pekerjaan / Kendala (Opsional)</label>
                <textarea name="deskripsi_pekerjaan" class="form-input" rows="3" placeholder="Tambahkan catatan khusus bila ada kendala operasional..."></textarea>
            </div>
        </div>
        
        <input type="hidden" name="area_id" value="{{ $areas[array_key_first($areas)] ?? 1 }}">
        
        {{-- Buttons --}}
        <div class="flex justify-end gap-3 pt-4 border-t">
            <button type="reset" class="btn-secondary">Reset</button>
            <button type="submit" class="btn-primary flex items-center gap-2">
                <span class="material-symbols-outlined">save</span>
                Simpan Data Ritasi
            </button>
        </div>
    </div>
</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const hmAwal = document.getElementById('hmAwal');
    const hmAkhir = document.getElementById('hmAkhir');
    const hmTotal = document.getElementById('hmTotal');
    
    function updateTotal() {
        const awal = parseFloat(hmAwal.value) || 0;
        const akhir = parseFloat(hmAkhir.value) || 0;
        const total = akhir - awal;
        hmTotal.textContent = total.toFixed(1) + ' Jam';
    }
    
    hmAwal.addEventListener('input', updateTotal);
    hmAkhir.addEventListener('input', updateTotal);
});
</script>
@endpush
@endsection
```

- [ ] **Step 3: Create Form Non-Ritasi view**

```blade
{{-- resources/views/operator/non-ritasi/create.blade.php --}}
@extends('layouts.operator')

@section('title', 'Form Input Unit Non Ritasi')
@section('page-title', 'Form Input Unit Non Ritasi')

@section('content')
{{-- Session Info --}}
<div class="bg-[var(--primary)] text-white rounded-lg p-4 mb-6">
    <div class="flex items-center gap-3">
        <span class="material-symbols-outlined">info</span>
        <div>
            <p class="font-semibold">Sesi Aktif</p>
            <p class="text-sm text-slate-300">Silakan isi data ritasi operasional harian. Pastikan durasi HM sesuai (6 - 11 Jam).</p>
        </div>
    </div>
</div>

<form action="{{ route('pegawai.non-ritasi.store') }}" method="POST" data-offline-form data-sync-tag="non-ritasi-sync">
    @csrf
    
    <div class="card p-6">
        {{-- Data Dasar --}}
        <h2 class="text-lg font-heading font-bold text-[var(--primary)] mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined">description</span>
            Data Dasar
        </h2>
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <label class="form-label">Shift</label>
                <select name="shift" class="form-input" required>
                    <option value="">Pilih Shift</option>
                    <option value="siang">Siang</option>
                    <option value="malam">Malam</option>
                </select>
            </div>
            <div>
                <label class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-input" value="{{ date('Y-m-d') }}" required>
            </div>
            <div>
                <label class="form-label">Nama Operator</label>
                <input type="text" class="form-input bg-slate-50" value="{{ $pegawai->nama ?? Auth::user()->name }}" readonly>
            </div>
            <div>
                <label class="form-label">Nomor Unit (Dump Truck)</label>
                <select name="unit_id" class="form-input" required>
                    <option value="">Pilih Unit</option>
                    @foreach($units as $id => $kode)
                        <option value="{{ $id }}">{{ $kode }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        
        {{-- Hour Meter --}}
        <h2 class="text-lg font-heading font-bold text-[var(--primary)] mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined">timer</span>
            Hour Meter (HM)
        </h2>
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div>
                <label class="form-label">HM Awal</label>
                <input type="number" name="hm_awal" class="form-input" step="0.1" min="0" value="0.0" required id="hmAwal">
            </div>
            <div>
                <label class="form-label">HM Akhir</label>
                <input type="number" name="hm_akhir" class="form-input" step="0.1" min="0" value="0.0" required id="hmAkhir">
            </div>
            <div class="bg-slate-100 rounded-lg p-4 flex items-center justify-between">
                <span class="text-sm font-medium">Total Durasi HM:</span>
                <span class="text-xl font-bold text-[var(--primary)]" id="hmTotal">0.0 Jam</span>
            </div>
        </div>
        
        {{-- Detail Pekerjaan --}}
        <h2 class="text-lg font-heading font-bold text-[var(--primary)] mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined">work</span>
            Detail Pekerjaan
        </h2>
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <label class="form-label">Lokasi Pekerjaan (Pit / Disposal)</label>
                <input type="text" name="lokasi_pekerjaan" class="form-input" placeholder="Contoh: Pit 1 North">
            </div>
            <div class="col-span-2">
                <label class="form-label">Deskripsi Pekerjaan / Kendala (Opsional)</label>
                <textarea name="deskripsi_pekerjaan" class="form-input" rows="3" placeholder="Tambahkan catatan khusus bila ada kendala operasional..."></textarea>
            </div>
        </div>
        
        <input type="hidden" name="area_id" value="{{ $areas[array_key_first($areas)] ?? 1 }}">
        
        {{-- Buttons --}}
        <div class="flex justify-end gap-3 pt-4 border-t">
            <button type="reset" class="btn-secondary">Reset</button>
            <button type="submit" class="btn-primary flex items-center gap-2">
                <span class="material-symbols-outlined">save</span>
                Simpan Data Ritasi
            </button>
        </div>
    </div>
</form>
@endsection
```

---

## Task 10: Views - SPV & Admin Laporan

**Files:**
- Modify: `resources/views/spv/dashboard.blade.php`
- Create: `resources/views/spv/laporan/index.blade.php`
- Modify: `resources/views/admin/laporan/index.blade.php`

- [ ] **Step 1: Create SPV Laporan view**

```blade
{{-- resources/views/spv/laporan/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Laporan Pemantauan')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-heading font-bold text-[var(--primary)]">Mining Oprationals Civil Departement</h1>
</div>

{{-- Export Button --}}
<div class="mb-4">
    <button class="bg-amber-500 hover:bg-amber-600 text-white font-semibold px-4 py-2 rounded-lg flex items-center gap-2">
        <span class="material-symbols-outlined text-lg">download</span>
        Export to Excel
    </button>
</div>

{{-- Filters --}}
<div class="card p-4 mb-6">
    <div class="flex items-center gap-4">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-slate-400">calendar_today</span>
            <input type="date" class="form-input w-40">
            <span class="text-slate-400">-</span>
            <input type="date" class="form-input w-40">
        </div>
        <select class="form-input w-40">
            <option>All Shifts</option>
            <option>Day</option>
            <option>Night</option>
        </select>
        <select class="form-input w-44">
            <option>All Unit Types</option>
            <option>Ritasi</option>
            <option>Non-Ritasi</option>
            <option>General</option>
        </select>
        <div class="relative flex-1">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                <span class="material-symbols-outlined text-lg">search</span>
            </span>
            <input type="text" placeholder="Search Operator or Unit ID..." class="pl-10 pr-4 py-2 border rounded-lg w-full text-sm">
        </div>
    </div>
</div>

{{-- Table --}}
<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600">TANGGAL</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600">SHIFT</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600">NAMA OPERATOR</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600">UNIT ID</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600">TIPE</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600">HM AWAL</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600">HM AKHIR</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600">TOTAL / RIT</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600">LOKASI</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600">STATUS</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600">ACTION</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($laporans as $laporan)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 text-sm">{{ $laporan->tanggal->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-sm">{{ $laporan->shift_label }}</td>
                        <td class="px-4 py-3 text-sm font-medium">{{ $laporan->pegawai->nama }}</td>
                        <td class="px-4 py-3 text-sm font-mono">{{ $laporan->unit->kode }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">
                                    {{ $laporan instanceof \App\Models\Ritasi ? 'local_shipping' : 'construction' }}
                                </span>
                                <span class="text-sm">{{ class_basename($laporan) }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm">{{ number_format($laporan->hm_awal, 1) }}</td>
                        <td class="px-4 py-3 text-sm">{{ number_format($laporan->hm_akhir, 1) }}</td>
                        <td class="px-4 py-3 text-sm">
                            {{ number_format($laporan->hm_total, 1) }}
                            @if($laporan instanceof \App\Models\Ritasi)
                                / {{ $laporan->jumlah_ritasi }}
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm">{{ $laporan->area->nama ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <span class="badge {{ $laporan->status_badge }}">{{ ucfirst($laporan->status) }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <button class="text-blue-600 hover:text-blue-700">
                                <span class="material-symbols-outlined text-lg">visibility</span>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="px-4 py-8 text-center text-slate-500">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{-- Pagination --}}
    <div class="p-4 border-t flex items-center justify-between">
        <p class="text-sm text-slate-500">Showing 1 to 10 of {{ $laporans->total() }} entries</p>
        {{ $laporans->withQueryString()->links() }}
    </div>
</div>
@endsection
```

---

## Image Assets Needed

Place these files in `public/images/`:

| Filename | Description | Source |
|----------|-------------|--------|
| `company-logo.png` | Company logo (PT NUSA HALMAHERA MINERALS GOSOWONG GOLD MINE) | Extract from `revisi/login.png` |
| `mining-logo.png` | Mining equipment icon for sidebar | Extract from `revisi/admin/dashboard.png` |
| `worker-hero.png` | Mining worker in orange uniform | Extract from `revisi/landing.png` |
| `worker-avatar.png` | Default user avatar | Use placeholder or extract from `revisi/admin/profil.png` |

---

## Implementation Order

1. **Task 1**: Design System & Base Styles
2. **Task 2**: Database Migrations
3. **Task 3**: Models & Relationships
4. **Task 4**: Seeders
5. **Task 5**: Controllers & Routes
6. **Task 6**: Landing & Login Views
7. **Task 7**: Admin Dashboard
8. **Task 8**: Master Data Views
9. **Task 9**: Operator Forms
10. **Task 10**: SPV & Admin Laporan

---

## Verification

After each task:
```bash
php artisan migrate:fresh --seed  # Verify migrations and seeders
php artisan route:list            # Verify routes
php artisan test                  # Run tests
npm run build                     # Verify frontend builds
```
