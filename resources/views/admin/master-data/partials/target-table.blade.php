<div class="card">
    {{-- Search --}}
    <div class="p-4 border-b border-[var(--border)] flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[var(--text-muted)]">
                    <span class="material-symbols-outlined text-lg">search</span>
                </span>
                <form method="GET" action="{{ route('admin.master-data.index') }}">
                    <input type="hidden" name="tab" value="target">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Cari material..."
                           class="form-input pl-10 w-64">
                </form>
            </div>
        </div>
        <button class="btn-primary flex items-center gap-2" onclick="openModal('targetModal')">
            <span class="material-symbols-outlined text-lg">add</span>
            Tambah Target
        </button>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">NO</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">MATERIAL</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">TANGGAL</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">TARGET RITASI</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">AKSI</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[var(--border)]">
                @forelse($targets as $index => $target)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 text-sm text-[var(--text)]">{{ str_pad($targets->firstItem() + $index, 2, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-[var(--text)]">{{ $target->material->nama }}</td>
                        <td class="px-4 py-3 text-sm text-[var(--text-muted)]">{{ \Carbon\Carbon::parse($target->tanggal)->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-sm text-[var(--text)]">{{ $target->target_ritasi }}</td>
                        <td class="px-4 py-3">
                            <button class="text-red-600 hover:text-red-700" onclick="deleteTarget({{ $target->id }})">
                                <span class="material-symbols-outlined text-lg">delete</span>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-[var(--text-muted)]">Tidak ada data target harian</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="p-4 border-t border-[var(--border)] flex items-center justify-between">
        <p class="text-sm text-[var(--text-muted)]">
            Showing {{ $targets->firstItem() }} to {{ $targets->lastItem() }} of {{ $targets->total() }} entries
        </p>
        {{ $targets->withQueryString()->links() }}
    </div>
</div>

{{-- Modal Add Target --}}
<div id="targetModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl w-full max-w-lg mx-4">
        <div class="p-4 border-b border-[var(--border)] flex items-center justify-between">
            <h3 class="font-heading font-bold text-[var(--primary)]">Tambah Target Harian</h3>
            <button onclick="closeModal('targetModal')" class="text-slate-400 hover:text-slate-600">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form action="{{ route('admin.target.store') }}" method="POST" class="p-4 space-y-4">
            @csrf
            <div>
                <label class="form-label">Material</label>
                <select name="material_id" class="form-input" required>
                    <option value="">Pilih Material</option>
                    @foreach($materials as $material)
                        <option value="{{ $material->id }}">{{ $material->nama }} ({{ $material->kode }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-input" value="{{ date('Y-m-d') }}" required>
            </div>
            <div>
                <label class="form-label">Target Ritasi</label>
                <input type="number" name="target_ritasi" class="form-input" min="0" required placeholder="Masukkan jumlah target ritasi">
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-[var(--border)]">
                <button type="button" onclick="closeModal('targetModal')" class="btn-secondary">Batal</button>
                <button type="submit" class="btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
}
function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
}
function deleteTarget(id) {
    if (confirm('Yakin ingin menghapus target ini?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/target/${id}`;
        form.innerHTML = `
            @csrf
            <input type="hidden" name="_method" value="DELETE">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
