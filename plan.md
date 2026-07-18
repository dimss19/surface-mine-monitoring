# Audit Project - Surface Mine Production

Bertindak sebagai **Senior Software Engineer, System Analyst, QA Engineer, dan UI/UX Reviewer**.

Website ini sudah selesai dibuat menggunakan **Laravel 13**, **MySQL**, dan **Argon2**. Jangan membuat PRD atau fitur baru. Tugasmu hanya **mengaudit project yang sudah ada** dan memastikan implementasinya sesuai kebutuhan berikut.

## Role

### Pegawai
- Tidak memiliki akun dan tidak perlu login.
- Hanya mengakses Landing Page untuk melakukan absensi.
- Mengisi absensi menggunakan data pegawai yang telah didaftarkan Admin.

### Supervisor (SPV)
- Login ke dashboard.
- Melihat rekap absensi pegawai.
- Membuat laporan pemantauan lapangan yang berisi:
  - Foto
  - Deskripsi
  - Kendala
  - Progress
- Mengelola laporan yang dibuatnya sendiri.

### Admin
- Mengelola akun Supervisor.
- Mengelola data Pegawai.
- Mengelola model alat dan data master lainnya.
- Melihat seluruh data Supervisor.
- Saat memilih Supervisor, dapat melihat:
  - Rekap absensi pegawai
  - Laporan pemantauan
  - Progress
  - Kendala
  - Dokumentasi foto

## Tugas Audit

Periksa dan evaluasi:

- Struktur project Laravel
- Struktur database dan relasi
- Kesesuaian fitur dengan kebutuhan
- UI/UX
- Keamanan (Argon2, Middleware, Validation, Authorization)
- Performa (Query, Pagination, Caching)
- Clean Code dan Best Practice Laravel

## Output

Berikan hasil dalam format:

### ✅ Fitur yang sudah sesuai

### ❌ Fitur yang belum ada

### ⚠️ Fitur yang perlu diperbaiki

### 🐞 Bug atau potensi masalah

### 💡 Rekomendasi perbaikan

> Jangan langsung mengubah kode. Lakukan audit terlebih dahulu, jelaskan setiap temuan secara singkat, dan sertakan prioritas perbaikannya (High, Medium, Low).