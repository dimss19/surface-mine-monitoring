# PRD — Surface Mine Production (Sistem Absensi & Pemantauan Lapangan)

> Single Source of Truth untuk fitur ini. AI tidak boleh berasumsi di luar dokumen ini.
> Jika informasi tidak tersedia, AI harus menandai `TODO/ASUMSI` dan menuliskannya di Section 14, bukan menebak diam-diam.
>
> **Dokumen ini adalah REVISI TOTAL alur dari PRD sebelumnya** ("Web Absensi HM Alat Berat").
> Perubahan utama: dari 2 role (Guest, Admin) menjadi **3 role (Pegawai, SPV, Admin)**, dengan
> penambahan modul Pemantauan Lapangan oleh SPV.

---

## 0. AI Rules (Guardrail)

```
- Jangan membuat fitur/endpoint di luar scope dokumen ini.
- Jangan mengubah business rule, skema database, atau UI yang sudah ada tanpa instruksi eksplisit.
- Jangan rename field/variabel yang sudah ada di codebase.
- Gunakan komponen reusable yang sudah disebut di Section 8, jangan buat duplikat.
- Jangan membuat dummy logic/data untuk kode production (mock hanya jika diminta eksplisit).
- Jika ada informasi yang kurang untuk mengambil keputusan → tulis `// TODO: [pertanyaan]` di kode
  dan catat di Section 14 (Assumptions Log), JANGAN diam-diam menebak.
- Ikuti Tech Stack & Coding Convention di Section 2 secara ketat, jangan improvisasi library baru.
```

---

## 1. Ringkasan & Scope

```
Nama Fitur     : Surface Mine Production — Sistem Absensi & Pemantauan Lapangan
Tujuan Bisnis  : Mendigitalkan proses absensi HM alat berat di lapangan (tanpa login untuk
                 pegawai) sekaligus memberi SPV alat pantau area kerja (foto, kendala, progress),
                 dan memberi Admin visibilitas terpusat atas seluruh SPV, area, dan pegawai.
Prioritas      : Must-have

IN SCOPE       :
  - Landing page publik (tanpa login) untuk Pegawai mengisi absensi HM alat berat
  - Login SPV & Admin (session-based, password di-hash Argon2)
  - Dashboard SPV: pantau area yang di-assign, lihat rekap absensi pegawai di area tersebut,
    buat laporan Pemantauan Lapangan (foto, deskripsi, kendala, progress)
  - Dashboard Admin: daftar seluruh SPV, detail per-SPV (area, rekap absensi pegawai, laporan
    pemantauan), pembuatan akun SPV, CRUD master data (Pegawai, Alat/Unit, Area)
  - Upload foto (multiple, minimal 1) untuk laporan pemantauan lapangan
  - Export data ke .xlsx dari dashboard Admin
  - PWA: bisa di-install ke homescreen mobile

OUT OF SCOPE   :
  - Registrasi mandiri SPV/Admin (akun dibuat manual oleh Admin/seeder)
  - Edit/Delete data absensi pegawai oleh SPV/Admin (hanya View & Export di v1)
  - Offline submission / queue sync saat tidak ada internet
  - Notifikasi email/WhatsApp
  - Real-time tracking lokasi GPS pegawai/SPV
```

---

## 2. Tech Stack & Coding Convention

```
Framework         : Laravel 13 (PHP) — ⚠️ lihat Section 14 poin 1 (verifikasi versi saat setup)
Frontend          : Laravel Blade + Alpine.js/vanilla JS + Tailwind CSS
Database/ORM      : MySQL + Eloquent ORM
Auth              : Laravel bawaan (session-based, guard "web"), 2 role login: spv & admin.
                    Pegawai TIDAK memiliki akun/login — hanya mengisi form publik.
Hashing Password  : Argon2id (config/hashing.php driver = 'argon2id')
Upload Foto       : Local disk storage (storage/app/public/pemantauan), symlink public storage
Styling           : Tailwind CSS
State Management  : Alpine.js (local state form), tidak perlu state management global
Struktur folder   : Struktur default Laravel (app/Http/Controllers, app/Models, resources/views,
                    database/seeders, database/migrations)
Naming convention : PascalCase untuk Class/Model/Controller, camelCase untuk method/variable,
                    snake_case untuk nama kolom database & route
Library baru boleh ditambah? : Ya, terbatas untuk: maatwebsite/excel (export xlsx),
                    intervention/image (resize/compress foto sebelum simpan),
                    package PWA Laravel — selain itu tidak boleh tanpa konfirmasi eksplisit.
```

---

## 3. User Roles & Permission

```
3 Role: Pegawai (tanpa login, form publik), SPV (login), Admin (login)

| Aksi                                          | Pegawai | SPV | Admin |
|------------------------------------------------|---------|-----|-------|
| Isi absensi HM (landing page)                  |    ✓    |  -  |   -   |
| Lihat rekap absensi pegawai di area sendiri     |    -    |  ✓  |   ✓   |
| Lihat rekap absensi pegawai SEMUA area          |    -    |  -  |   ✓   |
| Buat laporan pemantauan lapangan (foto dst)     |    -    |  ✓  |   -   |
| Lihat laporan pemantauan lapangan SPV lain      |    -    |  -  |   ✓   |
| Buat akun SPV baru                              |    -    |  -  |   ✓   |
| Assign SPV ke Area (1 atau lebih)               |    -    |  -  |   ✓   |
| CRUD master data Pegawai                        |    -    |  -  |   ✓   |
| CRUD master data Alat/Unit                      |    -    |  -  |   ✓   |
| CRUD master data Area                           |    -    |  -  |   ✓   |
| Export data ke xlsx                             |    -    |  -  |   ✓   |
```

---

## 4. User Story & Skenario (Given-When-Then)

### 4.1 Pegawai — Isi Absensi (Landing Page, tanpa login)

```
Sebagai pegawai lapangan (tanpa akun)
Saya ingin mengisi data absensi & HM alat berat lewat HP
Supaya laporan tercatat otomatis tanpa proses manual

Skenario 1 (sukses):
  Given pegawai membuka landing page absensi di HP
  When pegawai mengisi Nama (pilih dari master pegawai), Area kerja, Shift, Tipe Pekerjaan,
       Alat/Unit yang dioperasikan, HM Awal, HM Akhir, lalu klik Submit
  Then data tersimpan ke database dan otomatis terkait ke Area (dan SPV yang memegang Area
       tersebut), tampil pesan "Data berhasil disimpan", form direset

Skenario 2 (gagal - validasi HM):
  Given pegawai mengisi HM Akhir <= HM Awal
  When pegawai submit
  Then tampil error inline "HM Akhir harus lebih besar dari HM Awal", data tidak tersimpan

Skenario 3 (edge - Area belum punya SPV):
  Given Area yang dipilih pegawai belum di-assign SPV oleh Admin
  When pegawai submit
  Then data absensi TETAP tersimpan (tidak boleh gagal karena hal ini), status "SPV belum
       ditugaskan" terlihat di dashboard Admin agar segera di-assign
```

### 4.2 SPV — Pantau Area & Buat Laporan Lapangan

```
Sebagai SPV
Saya ingin memantau area yang saya pegang dan melaporkan kondisi lapangan
Supaya progress & kendala terekam dan bisa dipantau Admin

Skenario 1 (sukses - lihat rekap absensi):
  Given SPV sudah login dan memegang 1 atau lebih Area
  When SPV membuka dashboard
  Then tampil daftar Area yang dipegang beserta rekap absensi pegawai HANYA untuk Area tersebut

Skenario 2 (sukses - buat laporan pemantauan):
  Given SPV memilih salah satu Area yang dipegangnya, shift aktif belum ada laporan
  When SPV mengisi foto (minimal 1), deskripsi, kendala, progress (persentase & status),
       alat/unit terkait, lalu submit
  Then laporan tersimpan dan tampil di riwayat pemantauan Area tersebut

Skenario 3 (gagal - duplikat laporan per shift):
  Given SPV sudah membuat laporan untuk Area X, shift Siang, tanggal hari ini
  When SPV mencoba membuat laporan lagi untuk Area X, shift Siang, tanggal yang sama
  Then tampil peringatan "Laporan untuk area & shift ini sudah dibuat", tawarkan opsi
       edit laporan yang sudah ada (bukan create baru) — lihat Section 14 poin 5

Skenario 4 (gagal - belum login):
  Given user belum login
  When user mengakses /spv/dashboard
  Then redirect ke halaman login
```

### 4.3 Admin — Monitoring Terpusat & Master Data

```
Sebagai admin
Saya ingin melihat seluruh data SPV, area, absensi, dan mengelola master data
Supaya operasional lapangan terpantau dan data tetap konsisten

Skenario 1 (sukses - lihat daftar SPV):
  Given admin sudah login
  When admin membuka dashboard
  Then tampil daftar semua SPV beserta jumlah Area yang dipegang & jumlah laporan terbaru

Skenario 2 (sukses - detail SPV):
  Given admin klik salah satu SPV di daftar
  When halaman detail terbuka
  Then tampil semua Area yang dipegang SPV tersebut, rekap absensi pegawai di area-area itu,
       dan riwayat laporan pemantauan lapangan (foto, deskripsi, kendala, progress)

Skenario 3 (sukses - buat akun SPV):
  Given admin membuka halaman "Kelola SPV"
  When admin mengisi nama, email, password, dan memilih 1 atau lebih Area untuk di-assign
  Then akun SPV baru dibuat (password di-hash Argon2), SPV bisa login dan langsung
       melihat Area yang di-assign

Skenario 4 (sukses - tambah model alat):
  Given admin membuka halaman "Kelola Alat/Unit"
  When admin menambahkan kode & nama alat baru
  Then alat baru muncul di dropdown pilihan alat pada form Pegawai & form Pemantauan SPV

Skenario 5 (gagal - belum login):
  Given user belum login
  When user mengakses /admin/dashboard
  Then redirect ke halaman login
```

---

## 5. Functional Requirements & Field Validation

```
FR-1  : Landing page pegawai adalah 1 form publik (boleh multi-step) tanpa login.
FR-2  : Area yang dipilih pegawai menentukan SPV mana yang akan melihat data itu di dashboardnya
        (relasi Area -> SPV melalui tabel assignment, lihat Section 6).
FR-3  : SPV hanya bisa melihat & membuat laporan untuk Area yang di-assign ke dirinya oleh Admin.
FR-4  : 1 SPV bisa di-assign ke 1 atau lebih Area (many-to-many, fleksibel — sesuai keputusan user).
FR-5  : Laporan Pemantauan Lapangan bersifat terjadwal: maksimal 1 laporan per (SPV, Area, tanggal,
        shift). Jika SPV memegang beberapa Area, laporan dibuat terpisah per Area.
FR-6  : Laporan Pemantauan Lapangan wajib memiliki minimal 1 foto, boleh lebih dari 1.
FR-7  : Progress pada laporan pemantauan diinput sebagai KOMBINASI: persentase (0-100) DAN
        status kategori (Belum Mulai / Proses / Selesai).
FR-8  : Admin memiliki CRUD penuh untuk master data: Pegawai, Alat/Unit, Area.
FR-9  : Admin membuat akun SPV baru dan mengatur assignment Area untuk SPV tersebut (bisa diubah
        kapan saja, tidak permanen).
FR-10 : Admin dashboard menyediakan filter & search untuk data absensi maupun laporan pemantauan.
FR-11 : Tombol "Export Excel" menghasilkan file .xlsx sesuai filter aktif.

### Form Absensi Pegawai (Landing Page)

| Field              | Tipe    | Wajib? | Validasi                                | Pesan Error                                  |
|---------------------|---------|--------|-------------------------------------------|-------------------------------------------------|
| pegawai_id          | select  | Y      | harus ada di master data pegawai          | "Pilih nama pegawai"                          |
| area_id             | select  | Y      | harus ada di master data area             | "Pilih area kerja"                            |
| shift               | enum    | Y      | Siang / Malam                             | "Pilih shift"                                 |
| tipe_pekerjaan      | enum    | Y      | Unit Non Ritasi / Unit Ritasi / Pekerjaan General | "Pilih tipe pekerjaan"                |
| alat_id             | select  | Y      | harus ada di master data alat             | "Pilih alat yang dioperasikan"                |
| hm_awal             | decimal | Y      | angka, >= 0                               | "HM Awal harus berupa angka"                  |
| hm_akhir            | decimal | Y      | angka, harus > hm_awal                    | "HM Akhir harus lebih besar dari HM Awal"     |
| deskripsi_pekerjaan | text    | Tidak  | maksimal 500 karakter                     | "Deskripsi maksimal 500 karakter"             |

### Form Pemantauan Lapangan (SPV)

| Field           | Tipe        | Wajib? | Validasi                                  | Pesan Error                             |
|------------------|-------------|--------|----------------------------------------------|--------------------------------------------|
| area_id          | select      | Y      | harus salah satu Area yang di-assign ke SPV | "Area tidak valid untuk akun ini"        |
| alat_id          | select      | Y      | harus ada di master data alat               | "Pilih alat/unit terkait"                |
| shift            | enum        | Y      | Siang / Malam                               | "Pilih shift"                            |
| foto             | file[]      | Y      | minimal 1 file, format jpg/png, maks 5MB/file | "Upload minimal 1 foto"                |
| deskripsi        | text        | Y      | maksimal 1000 karakter                      | "Deskripsi wajib diisi"                  |
| kendala          | text        | Tidak  | maksimal 1000 karakter                      | "Kendala maksimal 1000 karakter"         |
| progress_persen  | integer     | Y      | 0-100                                        | "Progress harus antara 0-100"            |
| progress_status  | enum        | Y      | Belum Mulai / Proses / Selesai              | "Pilih status progress"                  |
```

---

## 6. Data Model

```
User (tabel bawaan Laravel, dipakai untuk SPV & Admin)
- id: bigint (PK)
- name: string
- email: string (unique)
- password: string (hashed, Argon2id)
- role: enum('admin','spv')
- created_at, updated_at

Area
- id: bigint (PK)
- nama: string
- created_at, updated_at

AreaSpv (pivot, many-to-many User<->Area, khusus role spv)
- id: bigint (PK)
- area_id: bigint (FK -> areas.id)
- spv_id: bigint (FK -> users.id)
- created_at, updated_at
(unique constraint: area_id + spv_id, cegah duplikat assignment)

Pegawai
- id: bigint (PK)
- nama: string
- created_at, updated_at
(CRUD penuh oleh Admin — bukan lagi statis via seeder)

Alat (Unit)
- id: bigint (PK)
- kode: string (misal "EX021")
- nama: string (misal "PC320")
- created_at, updated_at
(CRUD oleh Admin — "penambahan model alat")

AbsensiPegawai
- id: bigint (PK)
- pegawai_id: bigint (FK -> pegawais.id)
- area_id: bigint (FK -> areas.id)
- alat_id: bigint (FK -> alats.id)
- tanggal: date
- shift: enum('siang','malam')
- tipe_pekerjaan: enum('unit_non_ritasi','unit_ritasi','pekerjaan_general')
- hm_awal: decimal(10,2)
- hm_akhir: decimal(10,2)
- hm_total: decimal(10,2) — dihitung otomatis (hm_akhir - hm_awal), bukan input user
- deskripsi_pekerjaan: text (nullable)
- created_at, updated_at

PemantauanLapangan
- id: bigint (PK)
- spv_id: bigint (FK -> users.id)
- area_id: bigint (FK -> areas.id)
- alat_id: bigint (FK -> alats.id)
- tanggal: date
- shift: enum('siang','malam')
- deskripsi: text
- kendala: text (nullable)
- progress_persen: unsignedTinyInteger (0-100)
- progress_status: enum('belum_mulai','proses','selesai')
- created_at, updated_at
(unique constraint: spv_id + area_id + tanggal + shift, cegah duplikat laporan)

PemantauanFoto
- id: bigint (PK)
- pemantauan_id: bigint (FK -> pemantauan_lapangans.id)
- path: string (path file di storage)
- created_at, updated_at

Relasi:
- Area belongsToMany User (role spv) melalui AreaSpv
- AbsensiPegawai belongsTo Pegawai, Area, Alat
- PemantauanLapangan belongsTo User (spv), Area, Alat
- PemantauanLapangan hasMany PemantauanFoto
```

---

## 7. API Contract (Route Contract — Laravel Monolith)

```
=== Pegawai (Publik, tanpa login) ===
Route         : GET /absensi
Deskripsi     : Landing page form absensi pegawai
Auth          : Tidak perlu

Route         : POST /absensi
Request body  : { pegawai_id, area_id, shift, tipe_pekerjaan, alat_id, hm_awal, hm_akhir,
                  deskripsi_pekerjaan }
Response 200  : Redirect / tampil pesan sukses (Blade, bukan JSON)
Response 422  : Redirect back dengan validation errors
Auth          : Tidak perlu
Rate limit    : throttle 10 request/menit per IP

=== SPV (Login) ===
Route         : GET /login , POST /login , POST /logout   (shared dengan Admin, beda redirect by role)
Route         : GET /spv/dashboard
Deskripsi     : Daftar Area yang dipegang + rekap absensi pegawai per Area
Auth          : middleware auth + role:spv

Route         : GET /spv/pemantauan/create
Deskripsi     : Form buat laporan pemantauan lapangan (pilih Area dari yang di-assign)
Auth          : middleware auth + role:spv

Route         : POST /spv/pemantauan
Request body  : { area_id, alat_id, shift, foto[] (multipart), deskripsi, kendala,
                  progress_persen, progress_status }
Response 422  : jika sudah ada laporan untuk Area+shift+tanggal yang sama (lihat FR-5)
Auth          : middleware auth + role:spv

Route         : GET /spv/pemantauan
Deskripsi     : Riwayat laporan pemantauan milik SPV yang login
Auth          : middleware auth + role:spv

=== Admin (Login) ===
Route         : GET /admin/dashboard
Deskripsi     : Daftar semua SPV + ringkasan (jumlah Area, laporan terbaru)
Auth          : middleware auth + role:admin

Route         : GET /admin/spv/{id}
Deskripsi     : Detail SPV — Area yang dipegang, rekap absensi pegawai di area itu,
                riwayat laporan pemantauan (foto, deskripsi, kendala, progress)
Auth          : middleware auth + role:admin

Route         : GET /admin/spv/create , POST /admin/spv
Deskripsi     : Form & proses pembuatan akun SPV baru + assign Area
Auth          : middleware auth + role:admin

Route         : GET|POST|PUT|DELETE /admin/pegawai{/id}
Deskripsi     : CRUD master data Pegawai
Auth          : middleware auth + role:admin

Route         : GET|POST|PUT|DELETE /admin/alat{/id}
Deskripsi     : CRUD master data Alat/Unit
Auth          : middleware auth + role:admin

Route         : GET|POST|PUT|DELETE /admin/area{/id}
Deskripsi     : CRUD master data Area
Auth          : middleware auth + role:admin

Route         : GET /admin/export/absensi
Route         : GET /admin/export/pemantauan
Deskripsi     : Download file .xlsx sesuai filter aktif
Auth          : middleware auth + role:admin
```

---

## 8. UI, State & Reusable Components

```
Loading state  : Tombol submit berubah jadi spinner + teks "Memproses...", disabled
Empty state    : "Belum ada data" di setiap tabel/riwayat yang masih kosong
Error state    : Text merah di bawah field (validasi), toast/alert untuk server error
Success state  : Pegawai: "Data berhasil disimpan, terima kasih" + tombol "Isi lagi"
                 SPV: toast sukses setelah submit laporan pemantauan
Responsive     : Mobile-first untuk landing page Pegawai & form SPV lapangan; dashboard
                 Admin/SPV tetap rapi di desktop

Komponen baru yang harus reusable :
  <SelectDropdown />        — Pegawai/Area/Alat/SPV
  <FormInput />
  <PhotoUploader />         — multi-file upload dengan preview, min 1 foto
  <ProgressInput />         — kombinasi slider persentase + pilihan status
  <DataTable />             — dengan filter, search, pagination (dipakai Admin & SPV)
  <SpvCard />                — kartu ringkasan SPV di dashboard Admin (nama, jumlah area, laporan terakhir)

PWA:
  - manifest.json (nama app, icon, theme_color)
  - Service worker untuk cache asset statis
  - TIDAK termasuk offline data submission
```

---

## 9. Business Rules (IF-THEN)

```
Rule 1:
IF hm_akhir <= hm_awal THEN tolak submit AbsensiPegawai, tampilkan error validasi

Rule 2:
IF data AbsensiPegawai berhasil disimpan THEN hitung hm_total = hm_akhir - hm_awal otomatis

Rule 3:
IF pegawai memilih Area THEN data absensinya otomatis terlihat oleh SEMUA SPV yang di-assign
   ke Area tersebut (bukan pegawai yang memilih SPV secara langsung)

Rule 4:
IF SPV sudah membuat laporan Pemantauan untuk (Area, tanggal, shift) yang sama THEN tolak
   pembuatan laporan baru, arahkan ke laporan yang sudah ada (lihat Section 14 poin 5)

Rule 5:
IF SPV membuat laporan pemantauan THEN Area yang boleh dipilih HANYA Area yang di-assign
   ke SPV tersebut (validasi server-side, bukan hanya UI)

Rule 6:
IF Admin menghapus assignment SPV<->Area THEN laporan pemantauan LAMA milik SPV tersebut untuk
   Area itu TETAP tersimpan (histori tidak dihapus), hanya akses baru yang dicabut

Rule 7:
IF Admin menerapkan filter di dashboard THEN "Export Excel" mengambil data SESUAI filter aktif

Rule 8:
IF session SPV/Admin expired THEN redirect ke /login dengan pesan "Sesi habis, silakan login kembali"
```

---

## 10. Edge Cases & Error Handling

```
- Double submit / double click            : tombol disabled setelah klik pertama (JS) +
                                              validasi server tetap jalan sebagai lapisan kedua
- Network timeout / server error           : pesan error generik, tombol aktif kembali, data
                                              form pegawai TIDAK hilang
- HM Akhir <= HM Awal                      : validasi inline, submit ditolak
- Area belum punya SPV saat pegawai absen   : absensi TETAP tersimpan (lihat Skenario 4.1.3)
- SPV coba laporan untuk Area yang bukan miliknya : ditolak 403, meski memanipulasi request
- Duplikat laporan pemantauan (area+shift+tanggal sama) : ditolak dengan pesan jelas
- Upload foto gagal (ukuran/format tidak valid) : error per-file, file lain tetap bisa diupload
- Export dengan hasil filter kosong         : tetap hasilkan file xlsx berisi header saja
```

---

## 11. Non-Functional (Security & Performance)

```
Security     : - Password SPV & Admin di-hash Argon2id (config/hashing.php)
               - CSRF protection aktif di semua form
               - Rate limiting (throttle) di route POST /absensi untuk cegah spam
               - Sanitize semua input, gunakan Eloquent/Query Builder
               - Validasi server-side WAJIB untuk Area assignment SPV (jangan percaya input client)
               - File foto divalidasi tipe MIME & ukuran sebelum disimpan
               - Tidak ada data sensitif (password) yang di-log
Performance  : - Dashboard Admin & SPV pakai pagination (25-50 baris/halaman)
               - Index database di kolom yang sering difilter: area_id, tanggal, shift, spv_id
               - Foto dikompres/resize (intervention/image) sebelum disimpan agar tidak berat
               - Asset PWA di-cache oleh service worker
```

---

## 12. Testing Requirements

```
Jenis test    : Feature test (PHPUnit)
Skenario wajib ditest :
  - Submit absensi pegawai sukses (hm_total terhitung benar, area & pegawai tersimpan benar)
  - Submit absensi gagal karena hm_akhir <= hm_awal
  - SPV hanya bisa lihat & submit pemantauan untuk Area miliknya (403 jika bukan miliknya)
  - Duplikat laporan pemantauan (area+shift+tanggal sama) ditolak
  - Laporan pemantauan gagal jika tidak ada foto sama sekali
  - Admin bisa lihat detail SPV lengkap (area, rekap absensi, laporan pemantauan)
  - Admin CRUD Pegawai/Alat/Area berjalan benar
  - SPV/Admin tidak bisa akses dashboard tanpa login
  - Export xlsx menghasilkan file dengan jumlah baris sesuai filter
```

---

## 13. Definition of Done

```
- [ ] Landing page pegawai berfungsi, bisa diakses & diisi dari mobile
- [ ] Data absensi tersimpan dengan relasi Pegawai/Area/Alat yang benar, hm_total otomatis
- [ ] SPV bisa login, hanya melihat & mengelola Area yang di-assign ke dirinya
- [ ] SPV bisa membuat laporan pemantauan (foto multi, deskripsi, kendala, progress ganda)
- [ ] Validasi 1 laporan per Area+shift+tanggal berjalan
- [ ] Admin bisa login, melihat daftar semua SPV, dan detail lengkap per-SPV
- [ ] Admin bisa membuat akun SPV baru + assign Area (fleksibel, 1 atau lebih)
- [ ] Admin punya CRUD penuh untuk Pegawai, Alat/Unit, Area
- [ ] Export xlsx berfungsi untuk data absensi maupun pemantauan, mengikuti filter aktif
- [ ] Password SPV & Admin menggunakan Argon2id
- [ ] Web bisa di-install sebagai PWA di HP
- [ ] Tidak ada TODO/ASUMSI tersisa yang belum dikonfirmasi user
```

---

## 14. Assumptions Log / Open Questions

```
1. Versi "Laravel 13" disebutkan user secara eksplisit. Per pengetahuan terakhir, versi stable
   Laravel yang umum beredar belum mencapai 13 — TODO: konfirmasi/cek versi Laravel terbaru yang
   benar-benar tersedia saat instalasi (composer create-project), gunakan versi itu jika 13 belum
   rilis stable. Ini tidak mengubah struktur PRD, hanya versi paket saat `composer install`.

2. Foto pemantauan lapangan: user menjawab "minimal 1, boleh lebih" tapi lokasi penyimpanan belum
   eksplisit dikonfirmasi. ASUMSI: disimpan di local disk storage (storage/app/public), BUKAN
   cloud (S3/dst) untuk v1 — lebih sederhana & tidak butuh biaya cloud tambahan. TODO: konfirmasi
   jika ke depan butuh cloud storage (misal karena volume foto besar).

3. Relasi SPV <-> Area: user menjawab "1 SPV bisa pegang 1 atau lebih Area, fleksibel". ASUMSI:
   dibuat many-to-many (tabel pivot area_spv) agar SATU Area juga bisa di-assign ke lebih dari
   1 SPV jika suatu saat dibutuhkan (misal shift bergantian). TODO: konfirmasi apakah 1 Area
   memang boleh dipegang lebih dari 1 SPV sekaligus, atau harus strictly 1 Area = 1 SPV aktif.

4. Progress pemantauan menggunakan KOMBINASI persentase (0-100) + status kategori (Belum Mulai/
   Proses/Selesai), keduanya diinput manual oleh SPV secara independen (tidak auto-sinkron).
   TODO: konfirmasi apakah perlu validasi silang, misal progress_persen=100 otomatis mengunci
   progress_status="Selesai", atau dibiarkan bebas.

5. Skenario 4.2.3 (duplikat laporan per shift): ASUMSI sistem menolak pembuatan laporan baru dan
   mengarahkan ke laporan yang sudah ada, TAPI edit laporan pemantauan yang sudah dibuat DIANGGAP
   OUT OF SCOPE di Section 1 (hanya View & Export). TODO: konfirmasi — apakah SPV boleh EDIT
   laporan pemantauan miliknya sendiri di hari/shift yang sama (misal update foto tambahan),
   atau benar-benar strict 1x submit tanpa edit sama sekali.

6. Absensi Pegawai tetap mengikuti struktur PRD lama: 1x isi per shift (check-in saja, tanpa
   check-out), termasuk field alat/unit + HM Awal/HM Akhir — sesuai jawaban eksplisit user.

7. Field "tipe_pekerjaan" (Unit Non Ritasi/Unit Ritasi/Pekerjaan General) dari PRD lama
   DIPERTAHANKAN di form Absensi Pegawai. TODO: konfirmasi apakah field ini masih relevan di
   alur baru, atau bisa dihapus karena sudah ada Area sebagai konteks lokasi kerja.

8. Master data Area, meskipun tidak disebut eksplisit sebagai "di-assign ke SPV" secara CRUD
   terpisah oleh user, ASUMSI tetap dikelola (CRUD) oleh Admin — karena Area adalah data pivot
   yang menghubungkan Pegawai, SPV, dan Alat. TODO: konfirmasi jika Area sebenarnya harus statis
   (seed manual) seperti Operator/Unit/Lokasi di PRD lama.

9. Akun SPV dibuat HANYA oleh Admin (tidak ada self-registration), sesuai instruksi user.

10. Master data Pegawai & Alat/Unit sekarang FULL CRUD oleh Admin (berbeda dari PRD lama yang
    statis via seeder) — sesuai jawaban eksplisit user.

11. Export xlsx mencakup 2 jenis laporan terpisah: data Absensi Pegawai dan data Pemantauan
    Lapangan SPV (termasuk foto sebagai link/nama file, bukan gambar ter-embed di Excel).
    TODO: konfirmasi jika foto justru perlu di-embed langsung di file Excel (lebih kompleks).
```

---

## Lampiran — Master Data Awal (Referensi dari PRD Sebelumnya)

```
Master data Pegawai (66 nama), Alat/Unit (45 unit), dan Area (31 lokasi) yang sudah pernah
diberikan pada PRD sebelumnya TETAP BISA dipakai sebagai data seed awal, dengan catatan:
- "Operator" pada PRD lama = "Pegawai" pada PRD ini
- "Unit" pada PRD lama = "Alat" pada PRD ini
- "Lokasi" pada PRD lama = "Area" pada PRD ini
Namun karena ketiganya sekarang FULL CRUD oleh Admin (Section 14 poin 10), data ini hanya
dipakai sebagai SEED AWAL (starting point), bukan data statis permanen.
```
