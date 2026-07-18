@extends('layouts.dashboard')

@section('title', '- Detail Laporan')
@section('page_title', 'Detail Laporan Pemantauan')

@section('content')
<div class="mb-6">
    <a href="{{ route('spv.pemantauan.index') }}" class="inline-flex items-center text-sm font-bold text-primary hover:text-primary-fixed transition-colors">
        <span class="material-symbols-outlined text-lg mr-1">arrow_back</span>
        Kembali ke Daftar Laporan
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-surface-container-high rounded-2xl border border-outline-variant overflow-hidden">
            <div class="px-6 py-5 border-b border-outline-variant flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-on-surface">Informasi Laporan</h3>
                    <p class="mt-1 text-sm text-on-surface-variant">Detail data pemantauan tanggal {{ \Carbon\Carbon::parse($pemantauan->tanggal)->format('d F Y') }}</p>
                </div>
                <div>
                    @if($pemantauan->progress_status == 'selesai')
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-bold rounded-full bg-[#1b5e20]/30 text-[#66bb6a] border border-[#2e7d32]">Selesai ({{ $pemantauan->progress_persen }}%)</span>
                    @elseif($pemantauan->progress_status == 'proses')
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-bold rounded-full bg-[#e65100]/30 text-[#ffb74d] border border-[#e65100]">Proses ({{ $pemantauan->progress_persen }}%)</span>
                    @else
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-bold rounded-full bg-surface-container text-on-surface-variant border border-outline-variant">Belum Mulai ({{ $pemantauan->progress_persen }}%)</span>
                    @endif
                </div>
            </div>
            <div class="px-6 py-5">
                <dl class="divide-y divide-outline-variant">
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-bold text-on-surface-variant">Area Lokasi</dt>
                        <dd class="mt-1 text-sm text-on-surface sm:mt-0 sm:col-span-2">{{ $pemantauan->area->nama ?? '-' }}</dd>
                    </div>
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-bold text-on-surface-variant">Alat Utama</dt>
                        <dd class="mt-1 text-sm text-on-surface sm:mt-0 sm:col-span-2">{{ $pemantauan->alat->nama ?? '-' }}</dd>
                    </div>
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-bold text-on-surface-variant">Shift</dt>
                        <dd class="mt-1 text-sm text-on-surface sm:mt-0 sm:col-span-2 capitalize">{{ $pemantauan->shift }}</dd>
                    </div>
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-bold text-on-surface-variant">Deskripsi Kondisi</dt>
                        <dd class="mt-1 text-sm text-on-surface sm:mt-0 sm:col-span-2 whitespace-pre-line">{{ $pemantauan->deskripsi }}</dd>
                    </div>
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-bold text-on-surface-variant">Kendala / Masalah</dt>
                        <dd class="mt-1 text-sm text-on-surface sm:mt-0 sm:col-span-2 whitespace-pre-line">
                            {{ $pemantauan->kendala ?: 'Tidak ada kendala tercatat.' }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <div class="bg-surface-container-high rounded-2xl border border-outline-variant overflow-hidden">
            <div class="px-6 py-5 border-b border-outline-variant">
                <h3 class="text-lg font-bold text-on-surface flex items-center">
                    <span class="material-symbols-outlined text-lg mr-2 text-primary">photo_library</span>
                    Foto Lapangan
                </h3>
            </div>
            <div class="p-6">
                @if($pemantauan->getMedia('pemantauan_fotos')->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($pemantauan->getMedia('pemantauan_fotos') as $media)
                            <a href="{{ $media->getUrl() }}" target="_blank" class="block rounded-xl overflow-hidden border border-outline-variant shadow-sm hover:opacity-80 transition-opacity">
                                <img src="{{ $media->getUrl() }}" alt="Foto Pemantauan" class="w-full h-40 object-cover">
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-on-surface-variant italic">Tidak ada foto yang diunggah.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-surface-container-high rounded-2xl border border-outline-variant overflow-hidden">
            <div class="px-6 py-5 border-b border-outline-variant">
                <h3 class="text-lg font-bold text-on-surface flex items-center">
                    <span class="material-symbols-outlined text-lg mr-2 text-primary">group</span>
                    Daftar Pegawai Hadir
                </h3>
                <p class="mt-1 text-sm text-on-surface-variant">Yang bertugas di area ini pada shift ini.</p>
            </div>
            <div class="p-0">
                <ul class="divide-y divide-outline-variant">
                    @forelse($absensi as $ab)
                        <li class="px-6 py-4 flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-surface-container flex items-center justify-center text-primary font-bold text-sm">
                                {{ substr($ab->pegawai->nama, 0, 2) }}
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-bold text-on-surface">{{ $ab->pegawai->nama }}</p>
                                <p class="text-xs text-on-surface-variant">Jam Masuk: {{ $ab->created_at->format('H:i') }}</p>
                            </div>
                        </li>
                    @empty
                        <li class="px-6 py-8 text-center text-sm text-on-surface-variant">
                            <span class="material-symbols-outlined text-3xl text-on-surface-variant mb-2 block">person_off</span>
                            Belum ada pegawai yang mengisi absen di area ini.
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
