<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-[var(--text)]">Manajemen Pegawai</h1>
                <a href="{{ route('admin.pegawai.create') }}" class="btn-primary flex items-center gap-2">
                    <span class="material-symbols-outlined text-lg">add</span>
                    Tambah Pegawai
                </a>
            </div>

            <div class="card">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">NO</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">NAMA</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">UNIT</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">AREA</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">SHIFT</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[var(--border)]">
                            @forelse($pegawais as $index => $pegawai)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-3 text-sm text-[var(--text)]">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3 text-sm font-medium text-[var(--text)]">{{ $pegawai->nama }}</td>
                                    <td class="px-4 py-3 text-sm text-[var(--text-muted)]">{{ $pegawai->unit->kode ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-[var(--text-muted)]">{{ $pegawai->area->nama ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-[var(--text-muted)]">{{ ucfirst($pegawai->shift ?? '-') }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('admin.pegawai.edit', $pegawai) }}" class="text-blue-600 hover:text-blue-700">
                                                <span class="material-symbols-outlined text-lg">edit</span>
                                            </a>
                                            <button class="text-red-600 hover:text-red-700" onclick="deletePegawai({{ $pegawai->id }})">
                                                <span class="material-symbols-outlined text-lg">delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-[var(--text-muted)]">Tidak ada data pegawai</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-[var(--border)]">
                    {{ $pegawais->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
    function deletePegawai(id) {
        if (confirm('Yakin ingin menghapus pegawai ini?')) {
            fetch(`/admin/pegawai/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            }).then(() => location.reload());
        }
    }
    </script>
</x-app-layout>
