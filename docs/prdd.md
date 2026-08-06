# PRD (Rancangan) — Sistem Manajemen Operasional Surface Mine

**Versi:** 0.9 (Draft — belum diimplementasikan)
**Tanggal:** 6 Agustus 2026
**Status:** Rancangan / Proposal
**Platform:** Web (mobile-first, offline-first PWA)

---

## 1. Ringkasan Eksekutif

Dokumen ini mengusulkan pembangunan aplikasi web untuk mendukung operasional
harian tambang permukaan (surface mine). Rancangan aplikasi dipakai oleh 3
jenis pengguna — **Admin**, **Supervisor (SPV)**, dan **Operator (Pegawai)** —
untuk mencatat aktivitas alat berat, memantau kondisi unit, menghitung
performa alat (PA/UA), dan membuat laporan rekapan.

Keunggulan utama yang diusulkan: **bisa dipakai di area tambang tanpa sinyal**.
Semua form input tetap bisa diisi saat offline, lalu otomatis terkirim ke
server saat koneksi kembali (offline-first). Tidak ada data yang hilang.

---

## 2. Latar Belakang & Masalah

Operasi tambang berjalan di area terpencil dengan jaringan tidak stabil.
Kondisi saat ini:

- Operator kesulitan melaporkan produksi (ritasi) dan kondisi unit saat tidak
  ada sinyal.
- Data HM (hour meter), konsumsi fuel, dan produksi tidak tercatat rapi.
- Tidak ada gambaran performa unit (berapa lama unit rusak vs bekerja).
- Rekap laporan dikerjakan manual, lambat, dan rawan salah.

**Usulan solusi:** satu aplikasi terpadu yang mencatat data di lapangan secara
offline, menghitung metrik PA/UA otomatis, dan menyediakan laporan + ekspor
(Excel/PDF).

---

## 3. Tujuan & Manfaat yang Diharapkan

| Tujuan | Manfaat |
|---|---|
| Pencatatan produksi ritasi & non-ritasi harian | Data produksi akurat dan terpusat |
| Pencatatan status unit (breakdown/servis/ready) | Perawatan & ketersediaan unit terpantau |
| Perhitungan PA/UA otomatis | Evaluasi performa unit tanpa hitung manual |
| Laporan rekapan pegawai | Pengawasan hasil kerja per operator |
| Tetap berfungsi tanpa internet | Produktivitas lapangan tidak terganggu |

---

## 4. Pengguna & Peran

| Peran | Deskripsi | Akses utama |
|---|---|---|
| **Admin** | Manajer sistem / kepala bagian | Master data, akun SPV & pegawai, semua laporan, target harian |
| **SPV** | Supervisor lapangan | Dashboard PA/UA, rekapan, utilization index |
| **Pegawai (Operator)** | Operator alat berat | Input ritasi, non-ritasi, general, utilization |

---

## 5. Fitur-Fitur (Ruang Lingkup V1)

### 5.1 Umum (Semua Pengguna)
1. **Halaman Landing** — halaman pembuka informasi perusahaan.
2. **Login** — masuk dengan *username* + *password*. Role ditentukan otomatis
   dari akun.
3. **Sidebar & topbar** — menu berbeda sesuai role; status online/offline
   ditampilkan lewat ikon (Material Symbols) agar operator tahu data sedang
   antre atau terkirim.
4. **Mode Offline (PWA)** — halaman yang sudah pernah dibuka tetap bisa
   diakses tanpa internet (disediakan oleh *service worker*).

### 5.2 Fitur Operator (Pegawai)
1. **Input Unit Ritasi** — form harian untuk alat angkut (dump truck):
   - Shift (Siang/Malam) & tanggal
   - Nomor unit, HM awal & HM akhir (durasi otomatis dihitung, batas 6–11 jam)
   - Konsumsi fuel (liter)
   - **Quantity produksi + satuan** (Ton/CBM/M3) — dipakai untuk menghitung tonase
   - Material, area, jumlah ritasi (trip), lokasi pekerjaan, deskripsi
2. **Riwayat Ritasi** — daftar input yang sudah dikirim, bisa difilter
   tanggal & shift.
3. **Input Unit Non-Ritasi** — untuk alat non-angkut: jam mulai/selesai,
   HM, fuel, lokasi, deskripsi. (Jika `jam_mulai` diisi, otomatis masuk
   kategori *Pekerjaan General*.)
4. **Riwayat Non-Ritasi**.
5. **Input Utilization (kondisi unit)** — memilih status unit:
   - **Breakdown** (rusak) — wajib isi tanggal/jam mulai + deskripsi kerusakan
   - **Servis** (perbaikan berjalan)
   - **Ready** (unit kembali operasional)
   - Ada petunjuk status unit saat ini saat memilih unit di form.
6. **Blokir input saat maintenance** — operator **tidak bisa** mengisi ritasi
   untuk unit yang sedang dalam maintenance aktif (lihat Aturan Bisnis 6.3).

### 5.3 Fitur SPV
1. **Dashboard PA/UA** (fitur utama):
   - Tab **Harian / Mingguan / Bulanan** (switch periode dengan filter tanggal/pekan/bulan)
   - **4 kartu KPI:** Fuel Consumption, Tonnage, Active Units, Maintenance Units
   - **Ringkasan performa:** PA %, UA %, dan jam SH/WH/BD
   - **Pie produksi:** Tonase Shift Siang (Day), Malam (Night), dan gabungan (Combined)
   - **Timeline unit harian:** batang berwarna per unit — merah (jam maintenance),
     biru (jam bekerja ≤ 12 jam), abu-abu (standby/12 − merah − biru)
   - **Tabel Hauling:** rincian ritasi di periode terpilih
2. **Ekspor Laporan:** tombol Export → unduh **Excel (.xls)**, atau **PDF**
   (tampilan cetak browser).
3. **Utilization Index** — daftar riwayat maintenance semua unit + kartu
   hitung breakdown/servis/ready + filter tanggal.
4. **Rekapan Pegawai** — tabel rekap per operator (jumlah ritasi, HM ritasi,
   non-ritasi, general) dengan filter tanggal/shift, pencarian nama, dan
   ekspor Excel.

### 5.4 Fitur Admin
Semua fitur SPV, ditambah:
1. **Master Data Unit** — CRUD unit alat (kode, tipe, kapasitas, konsumsi fuel,
   status, jadwal maintenance).
2. **Master Data Material** — CRUD material (satuan, stok, stok minimal,
   harga, **unit default** & **faktor konversi ke ton**).
3. **Master Data Area** — CRUD area kerja (kode, nama, status).
4. **Target Harian** — tetapkan target ritasi per material per tanggal;
   hapus jika salah.
5. **Manajemen SPV** — buat/ubah/hapus akun SPV + menugaskan area tanggung jawab.
6. **Manajemen Pegawai** — buat/ubah/hapus akun operator, hubungkan ke data
   pegawai.

---

## 6. Aturan Bisnis (Rancangan)

### 6.1 Pencegahan Duplikat
- **Ritasi / Non-Ritasi:** satu pegawai hanya boleh 1 entri per
  `(tanggal + shift)`. Pengiriman ulang (replay) otomatis dianggap sukses
  tanpa membuat data ganda.
- **Utilization:** entri yang sama persis (unit + status + waktu mulai +
  deskripsi) dianggap duplikat.

### 6.2 Lifecycle Utilization (Siklus Status Unit)
- Unit hanya boleh punya **satu status aktif** (belum ditutup).
- **Breakdown/Servis** → ditolak jika unit masih punya entri aktif
  ("Unit masih dalam maintenance aktif").
- **Ready** → hanya boleh jika ada entri aktif; dan saat Ready disimpan,
  entri aktif tersebut **otomatis ditutup** (diisi jam selesai).
- Semua penolakan tetap mengembalikan `replayed: true` untuk replay offline.

### 6.3 Pemblokiran Ritasi Saat Maintenance
- Jika unit punya utilization aktif (`ended_at` masih kosong), operator
  **tidak bisa** menginput ritasi/non-ritasi untuk unit itu, sampai status
  unit di-*ready*-kan.

### 6.4 Rumus PA/UA (Periode = Harian/Mingguan/Bulanan)
```
SH (Scheduled Hours) = jumlah unit aktif × 12 jam × jumlah hari
WH (Working Hours)   = jumlah hm_total ritasi, masing-masing dibatasi maksimal 12 jam
BD (Breakdown Hours) = total durasi breakdown + servis (jika ended_at kosong → dihitung sampai sekarang)
PA (Physical Availability) = (SH − BD) / SH × 100
UA (Utilization Availability) = WH / (SH − BD) × 100
```
- Jika SH ≤ BD, nilai PA/UA dibulatkan ke 0 (mencegah pembagian tidak valid).

### 6.5 Konversi Tonase
- Satuan `ton` → tonase = quantity langsung.
- Satuan lain (CBM/M3) → tonase = quantity × `material.to_ton_factor`.
- Default satuan mengikuti `material.unit_default` (fallback `ton`).

### 6.6 Lainnya
- Nama operator diambil dari akun yang login (tidak bisa diubah lewat form).
- Upload file maksimal 5 MB (dikonversi ke base64 saat offline).

---

## 7. Teknologi yang Diusulkan

| Lapisan | Teknologi |
|---|---|
| Bahasa backend | PHP 8.3 |
| Framework backend | Laravel 13 (Eloquent ORM, Blade, Artisan) |
| Basis data | PostgreSQL |
| Frontend | Blade + Alpine.js + Tailwind CSS + Vite |
| Offline | Service Worker (`public/sw.js`) + IndexedDB (`offline-sync.js`) |
| File | Spatie MediaLibrary (tabel `media`) |
| Keamanan | Hash Argon2id, enkripsi session, CSRF token |
| Alat bantu | Carbon (tanggal), Composer, npm, Git |

### 7.1 Rancangan cara kerja mode offline
1. Operator mengisi form → saat **offline**, data disimpan di **IndexedDB**
   (file foto/upload dikonversi jadi base64).
2. Saat **online**, aplikasi mengambil token CSRF baru (`GET /csrf-token`),
   lalu mengirim ulang antrean satu per satu dengan header `X-Offline-Replay: 1`.
3. Server mencegah duplikat (lihat Aturan Bisnis) dan membalas `replayed: true`.
4. Indikator online/offline di topbar memberi tahu status pengiriman.

---

## 8. Struktur Data & Relasi (Rancangan Skema)

### 8.1 Daftar Tabel yang Diusulkan

| Tabel | Isi | Catatan |
|---|---|---|
| `users` | Akun login (admin/spv/operator) | `pegawai_id` menghubungkan operator ke tabel pegawai |
| `pegawais` | Data pegawai (nama) | |
| `areas` | Area kerja | |
| `units` | Alat berat (kode, tipe, kapasitas, status) | |
| `materials` | Material (satuan, stok, faktor konversi ton) | |
| `ritasis` | Catatan produksi ritasi harian | Unik `(pegawai_id, tanggal, shift)` |
| `non_ritasis` | Catatan non-ritasi & pekerjaan general | Unik `(pegawai_id, tanggal, shift)` |
| `unit_utilizations` | Riwayat status unit (breakdown/servis/ready) | |
| `daily_targets` | Target ritasi per material per tanggal | Unik `(material_id, tanggal)` |
| `material_unit` | Pivot: material yang boleh dipakai unit + konsumsi | |
| `unit_area` | Pivot: unit yang ditempatkan di area | |
| `area_spv` | Pivot: area tanggung jawab SPV | `spv_id` mengacu ke `users.id` |
| `media` | File upload (Spatie) | |
| `material_movements`, `unit_fuel_logs` | Riwayat pergerakan material & fuel | Cadangan/perluasan |
| `cache`, `jobs` | Infrastruktur Laravel (cache & antrean) | |

### 8.2 Relasi Antar Tabel (Rancangan)

```
users (1)──(0..1)──pegawais          # akun operator milik 1 pegawai
pegawais (1)───────(0..*) ritasis     # satu pegawai banyak ritasi
pegawais (1)───────(0..*) non_ritasis
units (1)──────────(0..*) ritasis
units (1)──────────(0..*) non_ritasis
units (1)──────────(0..*) unit_utilizations   # riwayat status unit
units (1)──────────(0..*) unit_area   (pivot) → (0..*) areas
units (1)──────────(0..*) material_unit (pivot) → (0..*) materials
materials (1)──────(0..*) ritasis
materials (1)──────(0..*) daily_targets
materials (1)──────(0..*) material_movements
areas (1)──────────(0..*) area_spv (pivot) → (0..*) users (SPV)
users (1)──────────(0..*) unit_utilizations   # siapa yang input status
ritasis (0..*)──────(1) users (validated_by)  # validator laporan
non_ritasis (0..*)──(1) users (validated_by)
```

### 8.3 Kolom penting

- **ritasis:** `pegawai_id, unit_id, area_id, material_id, shift (siang/malam),
  tanggal, hm_awal, hm_akhir, hm_total, jumlah_ritasi, quantity, quantity_unit,
  fuel_consumption, status, validated_by`
- **unit_utilizations:** `unit_id, status (breakdown|servis|ready), started_at,
  ended_at, deskripsi, user_id` — entri dianggap **aktif** jika `ended_at` kosong.

---

## 9. Arsitektur Aplikasi (Rancangan)

```
Browser (PWA: Service Worker + IndexedDB)
   │  HTTP (Blade/JSON) + CSRF
   ▼
Laravel 13 (routes/web.php → middleware role → Controller)
   │
   ├─ DashboardController ──► DashboardReportService (rumus PA/UA)
   ├─ UtilizationController (lifecycle + blokir ritasi)
   ├─ PegawaiRitasiController / PegawaiNonRitasiController / PegawaiGeneralController
   ├─ RekapanController
   └─ Admin*Controller (unit, material, area, target, spv, pegawai)
   │
   ▼
PostgreSQL (Eloquent ORM)  ·  Queue (jobs) untuk proses latar
```

---

## 10. Keamanan (Rancangan)

- Password di-hash **Argon2id**; tidak pernah disimpan plain text.
- **Session terenkripsi** (`SESSION_ENCRYPT=true`).
- Semua form dilindungi **CSRF token**; replay offline me-refresh token CSRF
  sebelum kirim ulang.
- Route dilindungi middleware `role:admin|spv|pegawai` — pengguna tidak bisa
  mengakses menu role lain.
- Upload file dibatasi ukuran & jenis.

---

## 11. Kebutuhan Non-Fungsional

- **Offline-first:** form utama tetap berfungsi tanpa internet.
- **Responsif:** tampilan dioptimalkan untuk HP (mobile-first) tapi tetap
  nyaman di desktop.
- **Performa:** data tambang skala kecil–menengah; agregasi laporan memakai
  satu-dua query terpusat (no N+1 berat).
- **Keandalan:** antrean offline diproses dengan pencegahan duplikat —
  pengiriman ulang aman.
- **Pengujian:** tidak memakai test framework otomatis; verifikasi dilakukan
  dengan `migrate:fresh --seed`, `view:cache`, pemeriksaan route, tinker, dan
  smoke test HTTP manual.

---

## 12. Rencana Implementasi (Roadmap)

| Fase | Isi | Output |
|---|---|---|
| 1. Fondasi | Setup Laravel + PostgreSQL, auth (3 role), middleware, skema DB & seeder akun demo | Aplikasi bisa login 3 role |
| 2. Master data | CRUD unit, material, area, target harian, akun SPV & pegawai | Admin bisa kelola data dasar |
| 3. Input offline | Form ritasi/non-ritasi/general + antrean IndexedDB + replay CSRF | Operator bisa input tanpa sinyal |
| 4. Utilization | Lifecycle breakdown/servis/ready + blokir input saat maintenance | Status unit terpantau |
| 5. Dashboard | Dashboard PA/UA harian/mingguan/bulanan + KPI + pie + timeline | SPV bisa pantau performa |
| 6. Laporan | Rekapan pegawai + ekspor Excel/PDF | Laporan siap dibagikan |
| 7. UAT & rilis | Pengujian end-to-end, perbaikan, penyerahan | Produksi |

### Akun demo (dibuat saat seeding)

| Username | Role |
|---|---|
| `admin` | Admin |
| `spv` | SPV |
| `operator` | Pegawai (Operator) |

Semua password demo: `password`.

---

## 13. Kriteria Penerimaan (Acceptance Criteria)

Sistem dianggap selesai bila:

1. Operator dapat menginput ritasi, non-ritasi, dan status unit **dalam
   keadaan offline**, dan data terkirim otomatis saat online tanpa duplikat.
2. Unit yang sedang breakdown/servis aktif **tidak bisa** diinput ritasi;
   setelah di-*ready*-kan, input kembali normal.
3. Dashboard PA/UA menampilkan angka yang sesuai rumus (SH/WH/BD → PA/UA)
   dan bisa diekspor ke Excel/PDF.
4. Admin dapat membuat akun SPV/pegawai dan mengelola seluruh master data.
5. Rekapan pegawai menampilkan agregasi ritasi + non-ritasi + general sesuai
   filter, dan bisa diekspor.

---

## 14. Risiko & Mitigasi

| Risiko | Mitigasi |
|---|---|
| Duplikat data karena pengiriman ulang offline | Kunci unik `(pegawai, tanggal, shift)` + cek duplikat utilization |
| Unit terkunci selamanya karena lupa status ready | Ready otomatis menutup entri aktif |
| Jaringan tidak stabil di lapangan | Arsitektur offline-first (IndexedDB + service worker) |
| Kesalahan input shift/tanggal | Validasi form + pencegahan duplikat di sisi server |

---

## 15. Backlog (Di Luar V1)

- Validasi laporan oleh SPV (tombol approve di rekapan).
- Dashboard per-SPV yang hanya melihat area tanggung jawabnya.
- Notifikasi push saat antrean offline kosong.
- Automasi peringatan saat unit breakdown lebih dari X jam.
- Grafik tren PA/UA antar periode.
