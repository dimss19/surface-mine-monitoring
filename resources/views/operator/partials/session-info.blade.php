@props(['description' => 'Silakan isi data operasional harian.'])
<div class="bg-[var(--primary)] text-white rounded-lg p-4 mb-6">
    <div class="flex items-center gap-3">
        <span class="material-symbols-outlined">info</span>
        <div>
            <p class="font-semibold">Sesi Aktif</p>
            <p class="text-sm text-slate-300">{{ $description }}</p>
        </div>
    </div>
</div>
