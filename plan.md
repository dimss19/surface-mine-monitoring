PLAN: Role Pegawai + Auth Login + Offline-Sync PWA
Konteks untuk AI eksekutor
- Stack: Laravel 13, PHP 8.3, Filament, Spatie Media Library, maatwebsite/excel, Tailwind v4 (Vite), Alpine.js (CDN).
- Auth saat ini: custom App\Http\Controllers\AuthController (login/logout). TIDAK pakai Breeze. Pertahankan.
- users.role enum ['admin','spv'] (database/migrations/0001_01_01_000000_create_users_table.php:20).
- RoleMiddleware (app/Http/Middleware/RoleMiddleware.php:19) cek Auth::user()->role !== $role (single role).
- Route / & /absensi → AbsensiController@create TANPA auth (routes/web.php:13-15). View: resources/views/absensi/create.blade.php.
- welcome.blade.php = default Laravel page, tidak dipakai route manapun (orphan).
- PWA: public/sw.js + public/manifest.json ada, tapi SW belum diregistrasi (resources/js/app.js isinya //). sw.js hanya cache GET, tidak handle POST sync. offline.html di-referensi tapi tidak ada.
- Login redirect hanya admin/spv (AuthController.php:25-29).
- SPV pemantauan (SpvPemantauanController@store:44) simpan foto via Spatie addMedia()->toMediaCollection('pemantauan_fotos'). Tabel pemantauan_fotos ada tapi tidak dipakai langsung (Spatie pakai media table).
Keputusan terkunci (sudah dikonfirmasi user)
1. welcome.blade.php = landing publik + tombol Login.
2. Pegawai post-login → /pegawai (menu dashboard). Form absensi pindah ke /pegawai/absensi.
3. User pegawai di-link ke record Pegawai; pegawai_id auto-fill dari sesi login (dropdown nama dihapus).
4. Scope offline: absensi pegawai + pemantauan SPV + foto.
5. Tech: IndexedDB outbox + Background Sync API + fallback online event + sync-on-load. PWA installable.
Phase 1 — DB & Model: role pegawai + link User↔Pegawai
1.1 Migration ubah enum role (buat file baru database/migrations/2026_07_21_000001_add_pegawai_role_to_users.php):
DB::statement("ALTER TABLE users MODIFY role ENUM('admin','spv','pegawai') NOT NULL DEFAULT 'spv'");
Cek driver DB di .env. SQLite: MODIFY tidak didukung → pakai CHANGE atau drop+recreate kolom. Sesuaikan.
1.2 Migration tambah pegawai_id (2026_07_21_000002_add_pegawai_id_to_users.php):
Schema::table('users', function (Blueprint $t) {
    $t->foreignId('pegawai_id')->nullable()->after('role')->constrained('pegawais')->nullOnDelete();
});
1.3 app/Models/User.php: tambah pegawai_id ke #[Fillable([...])], relasi:
public function pegawai() { return $this->belongsTo(Pegawai::class); }
public function isPegawai(): bool { return $this->role === 'pegawai'; }
public function isSpv(): bool { return $this->role === 'spv'; }
public function isAdmin(): bool { return $this->role === 'admin'; }
1.4 app/Http/Middleware/RoleMiddleware.php: support multi-role (lazy):
$roles = explode(',', $role);
if (!Auth::check() || !in_array(Auth::user()->role, $roles, true)) abort(403);
1.5 Seeder: update database/seeders/UserSeeder.php — buat user pegawai untuk setiap record Pegawai (mis. email pegawai.{id}@mine.local, password password, role pegawai, pegawai_id = id). Tambah ke DatabaseSeeder jika perlu.
Phase 2 — Auth flow & routes
2.1 app/Http/controllers/AuthController.php:25-29 — tambah branch pegawai:
return match (Auth::user()->role) {
    'admin'   => redirect()->intended('/admin/dashboard'),
    'spv'     => redirect()->intended('/spv/dashboard'),
    'pegawai' => redirect()->intended('/pegawai'),
    default   => redirect('/'),
};
Logout (AuthController.php:43) → redirect ke / (landing) bukan /login.
2.2 routes/web.php — restructure:
Route::get('/', fn () => view('welcome'))->name('home');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/csrf-token', fn () => response()->json(['token' => csrf_token()]));

Route::middleware(['auth'])->group(function () {
    Route::middleware('role:pegawai')->prefix('pegawai')->name('pegawai.')->group(function () {
        Route::get('/', [PegawaiController::class, 'index'])->name('index');
        Route::get('/absensi', [PegawaiController::class, 'createAbsensi'])->name('absensi.create');
        Route::post('/absensi', [PegawaiController::class, 'storeAbsensi'])->name('absensi.store');
    });
    Route::middleware('role:spv')->prefix('spv')->name('spv.')->group(function () { /* existing */ });
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () { /* existing */ });
});

Route::get('/absensi', fn () => redirect()->route('pegawai.absensi.create')); // back-compat
2.3 Controller baru app/Http/Controllers/PegawaiController.php:
- index() → view pegawai.index (menu dashboard).
- createAbsensi() → kirim $areaOptions, $alatOptions saja (pegawai dari Auth::user()->pegawai). View pegawai.absensi.create.
- storeAbsensi(Request): validasi sama seperti AbsensiController@store tapi pegawai_id dipaksa dari Auth::user()->pegawai_id (abaikan input). Duplikat-check (tanggal+shift+pegawai_id) tetap. Untuk idempotensi replay: jika duplikat dan request header X-Offline-Replay: 1, return 200 JSON (bukan error). Return JSON {success:true} jika Accept: application/json atau header X-Requested-With: XMLHttpRequest, else redirect biasa.
2.4 Hapus app/Http/Controllers/AbsensiController.php + resources/views/absensi/create.blade.php (logic sudah pindah ke PegawaiController). Model AbsensiPegawai dipertahankan.
Phase 3 — Views
3.1 resources/views/welcome.blade.php — rewrite total jadi landing publik mobile-first: brand "Surface Mine Production" + tombol "Masuk" → route('login'). Standalone HTML (tidak extend layout, atau extend layout minimal tanpa navbar). Sertakan meta PWA.
3.2 resources/views/pegawai/index.blade.php (baru) — menu dashboard pegawai: kartu "Isi Absensi" (→ route('pegawai.absensi.create')), "Riwayat" (placeholder), badge outbox offline, tombol Logout. Extend layouts.app.
3.3 resources/views/pegawai/absensi/create.blade.php (baru, adaptasi dari absensi/create.blade.php) — hapus field "Nama Pegawai" (dropdown), ganti input hidden pegawai_id = Auth::user()->pegawai_id. Tambah: indikator status online/offline + badge jumlah item antrian offline. Form id="absensiForm". Tambah @vite(['resources/js/offline-sync.js']) atau load via layout.
3.4 resources/views/auth/login.blade.php — tidak perlu ubah fungsional (sudah role-agnostic). Opsional: ubah teks jadi "Login Pegawai / SPV / Admin".
3.5 resources/views/layouts/app.blade.php:31 — ubah kondisi hide navbar: sembunyikan hanya di route home (welcome landing) dan login. Tampilkan di route pegawai/spv/admin. Label tombol guest (:55) ubah dari "Login SPV/Admin" → "Masuk". Pastikan @vite include resources/js/offline-sync.js (atau import di app.js).
3.6 resources/views/layouts/dashboard.blade.php — tambahkan badge outbox offline global (Alpine component baca dari IndexedDB) untuk SPV.
Phase 4 — Offline-sync pegawai absensi
4.1 resources/js/app.js — registrasi SW:
import './offline-sync.js';
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => navigator.serviceWorker.register('/sw.js'));
}
4.2 public/sw.js — rewrite:
- install: precache app shell ['/', '/login', '/pegawai', '/pegawai/absensi', '/offline.html', '/manifest.json'] + asset build (baca dari build/manifest.json kalau bisa, atau cache saat fetch).
- fetch: network-first untuk navigasi (fallback cache → /offline.html), cache-first untuk /build/ & aset gambar.
- sync event: dengar tag 'absensi-sync' & 'pemantauan-sync' → baca outbox dari IndexedDB, POST tiap item, hapus saat sukses. Komunikasi via clients.matchAll() + postMessage untuk update badge.
4.3 public/offline.html (baru) — halaman sederhana "Anda sedang offline. Data tersimpan lokal & akan dikirim otomatis saat tersambung."
4.4 resources/js/offline-sync.js (baru, entry Vite):
- Buka IndexedDB surface-mine, object store outbox {id autoinc, url, method, payload, blobs:[{name,type,base64}], formEnctype, csrf, created_at, status}.
- Helper isOnline() = navigator.onLine.
- Generic interceptor: cari semua form [data-offline-form]. Override submit:
- Jika online → coba fetch normal dulu; jika gagal/network error → tangkap ke outbox.
- Jika offline → simpan ke outbox langsung, navigator.serviceWorker.ready.then(reg => reg.sync.register(form.dataset.syncTag)), tampilkan toast "Tersimpan offline", reset form, update badge.
- replayOutbox(tag?): ambil semua pending, fetch fresh CSRF via GET /csrf-token, POST dengan FormData (rekonstruksi File dari blobs untuk foto), hapus item sukses, update badge, postMessage ke SW.
- Listener: window.addEventListener('online', () => replayOutbox()) + DOMContentLoaded → jika online & outbox tidak kosong → replay.
- Badge Alpine: store global window.outboxCount, refresh di setiap perubahan.
4.5 PegawaiController@storeAbsensi — return JSON {success, redirect?} saat Accept: application/json / X-Requested-With: XMLHttpRequest. Saat duplikat + X-Offline-Replay: 1 → return response()->json(['success'=>true,'replayed'=>true], 200).
4.6 manifest.json — pastikan start_url: /login, display: standalone, scope: /. Verifikasi public/icons/icon-192x192.png & icon-512x512.png ada; jika tidak, generate placeholder (bisa pakai php artisan atau letakkan file manual).
Phase 5 — Offline-sync SPV pemantauan + foto
5.1 SpvPemantauanController@store (app/Http/Controllers/SpvPemantauanController.php:44):
- Saat duplikat ($exists true) + header X-Offline-Replay: 1 → return 200 JSON (idempotensi).
- Saat Accept: application/json → return JSON {success:true, id:...} (bukan redirect) supaya JS bisa konfirmasi.
- Foto: tetap via Spatie addMedia($file) — JS replay kirim foto[] sebagai File multipart, controller terima sama.
5.2 resources/views/spv/pemantauan/create.blade.php:15 — tambah atribut form data-offline-form data-sync-tag="pemantauan-sync". Tambah badge outbox + indikator offline. Pastikan resources/js/offline-sync.js ter-load di layout dashboard.
5.3 offline-sync.js harus generic handle enctype="multipart/form-data": untuk <input type=file multiple>, baca tiap File jadi blob (simpan name/type + base64/ArrayBuffer di IndexedDB), saat replay rekonstruksi new File([blob], name, {type}).
5.4 resources/views/spv/dashboard.blade.php + spv/pemantauan/index.blade.php — tampilkan badge jumlah antrian offline.
Phase 6 — Verifikasi & test
6.1 Jalankan:
php artisan migrate:fresh --seed
npm run build
php artisan test
6.2 Checklist manual:
- Login admin → /admin/dashboard; spv → /spv/dashboard; pegawai → /pegawai.
- / menampilkan landing + tombol Masuk.
- Pegawai isi absensi (tidak ada field nama) → sukses.
- Pegawai offline (DevTools → Offline) → isi absensi → toast "Tersimpan offline" → cek IndexedDB ada item → online → data muncul di DB, badge 0.
- SPV offline → buat pemantauan + 2 foto → online → foto muncul di media collection.
- Replay duplikat → tidak ada double insert (return 200).
- Install PWA di Chrome Android → buka dari home screen.
- php artisan migrate:status clean.
Risiko & catatan
- iOS Safari: Background Sync API tidak didukung. Fallback online event + sync-on-load hanya jalan saat tab terbuka. True background sync iOS butuh native app — di luar scope. Terima sesuai pilihan user.
- CSRF untuk replay: token form basi saat replay. Solusi: route GET /csrf-token (Phase 2.2) untuk ambil token fresh sebelum tiap replay POST.
- Enum role: raw ALTER TABLE ... MODIFY — cek driver DB (MySQL OK, SQLite perlu pendekatan beda).
- Ukuran foto di IndexedDB: sudah ada validasi max:5120 KB (SpvPemantauanController.php:60). Aman.
- Idempotensi: duplikat-check existing (tanggal+shift+pegawai_id untuk absensi; +area_id untuk pemantauan) sudah cukup. Return 200 saat replay-duplikat agar JS hapus item outbox.
- Back-compat: /absensi redirect → /pegawai/absensi.