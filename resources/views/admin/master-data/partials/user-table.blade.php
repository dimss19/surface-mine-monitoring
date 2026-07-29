<div class="card">
    {{-- Search and Filter --}}
    <div class="p-4 border-b border-[var(--border)] flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[var(--text-muted)]">
                    <span class="material-symbols-outlined text-lg">search</span>
                </span>
                <form method="GET" action="{{ route('admin.master-data.index') }}">
                    <input type="hidden" name="tab" value="user">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Cari data..."
                           class="pl-10 pr-4 py-2 border border-[var(--border)] rounded-lg w-64 text-sm focus:ring-2 focus:ring-[var(--accent)] focus:border-transparent">
                </form>
            </div>
        </div>
        <button class="btn-secondary flex items-center gap-2">
            <span class="material-symbols-outlined text-lg">filter_list</span>
            Filter
        </button>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">NO</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">NAMA LENGKAP</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">USERNAME</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">ROLE / HAK AKSES</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">AREA KERJA</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">STATUS</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">AKSI</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[var(--border)]">
                @forelse($users ?? [] as $index => $user)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 text-sm text-[var(--text)]">{{ $index + 1 }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-[var(--text)]">{{ $user->name }}</td>
                        <td class="px-4 py-3 text-sm text-[var(--text-muted)]">{{ $user->username }}</td>
                        <td class="px-4 py-3 text-sm text-[var(--text)]">{{ ucfirst($user->role) }}</td>
                        <td class="px-4 py-3 text-sm text-[var(--text-muted)]">
                            {{ $user->area->nama ?? '-' }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="badge {{ $user->is_active ? 'badge-active' : 'badge-inactive' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $user->is_active ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <button class="text-blue-600 hover:text-blue-700" onclick="editUser({{ $user->id }})">
                                    <span class="material-symbols-outlined text-lg">edit</span>
                                </button>
                                <button class="text-red-600 hover:text-red-700" onclick="deleteUser({{ $user->id }})">
                                    <span class="material-symbols-outlined text-lg">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-[var(--text-muted)]">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="p-4 border-t border-[var(--border)] flex items-center justify-between">
        <p class="text-sm text-[var(--text-muted)]">
            Showing {{ ($users ?? collect())->firstItem() ?? 0 }} to {{ ($users ?? collect())->lastItem() ?? 0 }} of {{ ($users ?? collect())->total() ?? 0 }} entries
        </p>
        @if(isset($users))
            {{ $users->withQueryString()->links() }}
        @endif
    </div>
</div>

{{-- Action buttons --}}
<div class="flex gap-3 mt-4">
    <button class="btn-secondary flex items-center gap-2">
        <span class="material-symbols-outlined text-lg">download</span>
        Export Excel
    </button>
    <button class="btn-primary flex items-center gap-2" onclick="openModal('addUser')">
        <span class="material-symbols-outlined text-lg">add</span>
        Tambah Data
    </button>
</div>

<script>
function editUser(id) {
    console.log('Edit user:', id);
}
function deleteUser(id) {
    if (confirm('Yakin ingin menghapus user ini?')) {
        fetch(`/admin/user/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        }).then(() => location.reload());
    }
}
</script>
