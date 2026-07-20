    # Surface Mine Production Operational Record

    ---

    # 1. Topik / Judul Project

    Surface Mine Production Operational Record

    ---

    # 2. Tujuan Project

    Surface Mine Production Operational Record merupakan sistem informasi berbasis web yang digunakan untuk mendukung proses absensi pegawai, monitoring operasional, serta pelaporan kegiatan lapangan.

    Sistem terdiri dari tiga jenis pengguna:

    - **Pegawai** — Tidak perlu login. Cukup membuka halaman absensi untuk mengisi data kehadiran (HM alat, shift, area, dan tipe pekerjaan).
    - **Supervisor (SPV)** — Login ke dashboard untuk membuat laporan pemantauan lapangan (dilengkapi foto, deskripsi, kendala, dan progress) serta melihat daftar pegawai yang hadir di area tugasnya.
    - **Admin** — Login untuk mengelola akun SPV, data pegawai, data alat, serta melihat seluruh laporan pemantauan dan melakukan export Excel.

    Seluruh data tersimpan secara terpusat sehingga proses monitoring dan pelaporan menjadi lebih cepat, akurat, dan efisien.

    ---

    # 3. Alur Sistem

    ```mermaid
    flowchart TD
        A[Pegawai] -->|Buka halaman absensi| B[Isi Form Absensi]
        B -->|Nama, Area, Alat, Shift, Tipe Pekerjaan, HM Awal/Akhir| C[Submit Absensi]
        C --> D[Data tersimpan di database]
        
        E[Supervisor SPV] -->|Login| F[Dashboard SPV]
        F -->|Buat Laporan Baru| G[Form Pemantauan Lapangan]
        G -->|Foto, Deskripsi, Kendala, Progress| H[Submit Laporan]
        H --> I[Laporan tersimpan]
        F -->|Lihat Laporan| J[Daftar Laporan Saya]
        J -->|Klik Detail| K[Detail Laporan + Daftar Pegawai Hadir]
        
        L[Admin] -->|Login| M[Dashboard Admin]
        M -->|Lihat Ringkasan| N[Metrics: Total SPV, Area, Laporan]
        M -->|Kelola SPV| O[CRUD Akun SPV + Assign Area]
        M -->|Kelola Pegawai| P[CRUD Data Pegawai]
        M -->|Kelola Alat| Q[CRUD Master Alat]
        M -->|Lihat Laporan| R[Semua Laporan + Filter]
        R -->|Export| S[Download Excel]
    ```

    ---

    # 4. Fitur Berdasarkan Role

    ## 4.1 Pegawai (Tanpa Login)

    | No | Fitur | Deskripsi |
    |----|-------|-----------|
    | 1 | Isi Absensi | Mengisi form absensi dengan data: nama pegawai, area, alat, shift, tipe pekerjaan, HM awal & HM akhir, deskripsi pekerjaan |
    | 2 | Cegah Duplikasi | Sistem otomatis mencegah absensi ganda pada shift & tanggal yang sama |

    **Catatan:** Tidak ada fitur check-in/check-out terpisah. Absensi bersifat sekali submit per shift per hari. Tidak ada upload foto selfie — foto hanya untuk laporan pemantauan oleh SPV.

    ## 4.2 Supervisor (SPV)

    | No | Fitur | Deskripsi |
    |----|-------|-----------|
    | 1 | Login | Login ke dashboard menggunakan email & password |
    | 2 | Dashboard | Melihat jumlah area ditugaskan dan jumlah laporan bulan ini |
    | 3 | Buat Laporan | Membuat laporan pemantauan lapangan: foto (via galeri atau kamera langsung), deskripsi, kendala, progress slider |
    | 4 | Lihat Laporan | Daftar laporan yang pernah dibuat (dengan filter tanggal & area) |
    | 5 | Detail Laporan | Melihat detail laporan + daftar pegawai yang hadir di area/shift tersebut |
    | 6 | Cegah Duplikasi | Sistem mencegah laporan ganda untuk area & shift yang sama pada tanggal yang sama |

    ## 4.3 Admin

    | No | Fitur | Deskripsi |
    |----|-------|-----------|
    | 1 | Login | Login menggunakan email & password |
    | 2 | Dashboard | Ringkasan: total SPV, total area kerja, laporan hari ini |
    | 3 | Kelola SPV | CRUD akun supervisor + assign area tugas |
    | 4 | Kelola Pegawai | CRUD data pegawai (nama saja) |
    | 5 | Kelola Alat | CRUD master data alat (nama + jenis: excavator, dump_truck, dozer, grader, loader, lainnya) |
    | 6 | Lihat Laporan | Semua laporan pemantauan dari seluruh SPV (filter tanggal & SPV) |
    | 7 | Export Excel | Download seluruh laporan ke format Excel |

    ---

    # 5. Struktur Database

    ## 5.1 Tabel Utama

    | Tabel | Keterangan |
    |-------|------------|
    | `users` | Akun SPV & Admin (role: admin/spv) |
    | `pegawais` | Data pegawai (id, nama) |
    | `areas` | Data area kerja |
    | `alats` | Data alat (nama, jenis) |
    | `area_spv` | Pivot relasi area dengan SPV |
    | `pemantauan_lapangans` | Laporan pemantauan lapangan (spv_id, area_id, alat_id, tanggal, shift, deskripsi, kendala, progress_persen, progress_status) |
    | `media` | File foto laporan (Spatie Media Library) |
    | `absensi_pegawais` | Absensi pegawai (pegawai_id, area_id, alat_id, tanggal, shift, tipe_pekerjaan, hm_awal, hm_akhir, hm_total) |

    ---

    # 6. Hak Akses

    | Fitur | Pegawai | Supervisor | Admin |
    |-------|---------|------------|-------|
    | Isi Absensi | ✓ | - | - |
    | Dashboard | - | ✓ | ✓ |
    | Buat Laporan Pemantauan | - | ✓ | - |
    | Lihat Laporan Sendiri | - | ✓ | - |
    | Detail Laporan + Pegawai Hadir | - | ✓ | - |
    | Kelola SPV | - | - | ✓ |
    | Kelola Pegawai | - | - | ✓ |
    | Kelola Alat | - | - | ✓ |
    | Lihat Semua Laporan | - | - | ✓ |
    | Export Excel | - | - | ✓ |

    ---

    # 7. Teknologi

    | Komponen | Teknologi |
    |----------|-----------|
    | Backend | Laravel 11 |
    | Database | MySQL |
    | Authentication | Session-based dengan role middleware |
    | Password Hashing | Argon2id |
    | File Upload | Spatie Media Library |
    | Frontend | Tailwind CSS, Alpine.js, Material Symbols |
    | Build Tool | Vite |
