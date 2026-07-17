# Workflow Eksekusi untuk AI Agent: [Nama Fitur]

> Dokumen ini dipasangkan dengan PRD (lihat: PRD_Template_Hybrid_Final.md).
> PRD menjawab "apa yang dibangun". Dokumen ini mengatur "urutan dan cara AI mengerjakannya"
> agar AI tidak overbuild, tidak melompat langkah, dan tidak menebak diam-diam saat stuck.

---

## Aturan Eksekusi (WAJIB dibaca AI sebelum mulai)

```
1. AI mengerjakan MAKSIMAL 1 fase per giliran, lalu BERHENTI.
   Jangan lanjut ke fase berikutnya tanpa konfirmasi dari user.

2. Jika di tengah fase menemukan informasi yang kurang atau ambigu:
   → BERHENTI, tulis pertanyaan spesifik ke user.
   → JANGAN lanjut mengerjakan dengan menebak.
   → Jika ambiguitas kecil dan sudah diizinkan di PRD Section 14 (Assumptions Log),
     boleh lanjut tapi WAJIB disebutkan asumsi yang dipakai.

3. Di akhir setiap fase, AI WAJIB melaporkan dalam format ini:
   - File yang dibuat/diubah: [daftar path file]
   - Checklist yang selesai: [nomor]
   - Checklist yang BELUM tercapai / butuh review: [nomor + alasan]
   - Pertanyaan terbuka (jika ada): [daftar]

4. AI tidak boleh mengubah file di luar scope fase yang sedang dikerjakan,
   kecuali file tersebut memang perlu diubah sesuai PRD (misal: menambah route baru
   di file routing utama).

5. Jika satu checklist item terasa terlalu besar untuk 1 langkah (misal "buat halaman
   dengan semua state"), AI boleh memecahnya jadi sub-langkah kecil di responsnya,
   tapi tetap dalam 1 fase yang sama — jangan diam-diam meluas ke fase lain.
```

---

## Fase 1: Persiapan & Fondasi
- [ ] 1.1 Buat struktur folder sesuai PRD Section 2
- [ ] 1.2 Definisikan tipe data (TypeScript interfaces/types) berdasarkan PRD Section 6 & 7
- [ ] 1.3 (Jika ada) Buat mock API handler atau service function

**Stop condition:** setelah Fase 1 selesai, laporkan tipe data yang dibuat sebelum lanjut —
ini fondasi yang paling murah untuk dikoreksi sekarang, paling mahal kalau dikoreksi di Fase 3+.

---

## Fase 2: Komponen Atomik & Molekul
- [ ] 2.1 Buat komponen baru yang reusable (sebutkan dari PRD Section 8)
- [ ] 2.2 Tulis unit test untuk komponen tersebut (PRD Section 12)

**Stop condition:** tunjukkan komponen dalam isolasi (props & states-nya) sebelum
diintegrasikan ke halaman penuh di Fase 3.

---

## Fase 3: Halaman & Integrasi State
- [ ] 3.1 Buat layout halaman utama (struktur & komponen, TANPA logic state dulu)
- [ ] 3.2 Tambahkan state management (local/global) sesuai PRD Section 8
- [ ] 3.3 Implementasikan 4 state wajib: loading, empty, error, success
- [ ] 3.4 Hubungkan komponen dengan API call (PRD Section 7)

**Stop condition:** setelah 3.1-3.2 selesai, boleh lapor dulu sebelum lanjut ke 3.3-3.4
jika halaman cukup kompleks (>1 komponen besar).

---

## Fase 4: Aturan Bisnis & Edge Cases
- [ ] 4.1 Implementasikan semua aturan IF-THEN (PRD Section 9)
- [ ] 4.2 Tangani semua edge case (PRD Section 10) satu per satu, jangan digabung generik

**Stop condition:** untuk setiap edge case yang perilakunya TIDAK disebutkan jelas di PRD,
berhenti dan tanya — jangan asumsikan "generic error handling" untuk semuanya.

---

## Fase 5: Testing & Finalisasi
- [ ] 5.1 Tulis integration test untuk halaman (PRD Section 12)
- [ ] 5.2 Lakukan self-review berdasarkan Definition of Done (PRD Section 13)
- [ ] 5.3 Selesaikan semua TODO/ASUMSI di Assumptions Log — laporkan satu per satu ke user,
       jangan tutup sendiri tanpa konfirmasi

**Stop condition:** laporkan hasil self-review DoD secara eksplisit item per item
(centang/tidak centang), jangan hanya bilang "sudah selesai".

---

## Format Laporan Akhir Setiap Fase (template siap pakai)

```
✅ Fase [n] selesai.

File dibuat/diubah:
- path/to/file1.tsx
- path/to/file2.ts

Checklist selesai: [n.1, n.2, ...]
Checklist belum/butuh review: [n.x — alasan]

Pertanyaan terbuka: [ada/tidak ada, sebutkan jika ada]

Lanjut ke Fase [n+1]? (tunggu konfirmasi)
```
