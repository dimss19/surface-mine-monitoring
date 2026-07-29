@php
    $groupedPermissions = $permissions->groupBy('group');
    $groupLabels = [
        'unit' => 'Unit',
        'material' => 'Material',
        'area' => 'Area',
        'pegawai' => 'Pegawai',
        'ritasi' => 'Ritasi',
        'non-ritasi' => 'Non-Ritasi',
        'pemantauan' => 'Pemantauan',
        'laporan' => 'Laporan',
        'hak-akses' => 'Hak Akses',
    ];
@endphp

<div class="card">
    <div class="p-4 border-b border-[var(--border)]">
        <h2 class="text-lg font-heading font-bold text-[var(--primary)]">Hak Akses Per Role</h2>
        <p class="text-sm text-[var(--text-muted)] mt-1">Atur permission untuk setiap role</p>
    </div>

    <form action="{{ route('admin.hak-akses.update', 'admin') }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="role" value="admin">

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">GRUP</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">NAMA PERMISSION</th>
                        @foreach($roles as $role)
                            <th class="px-4 py-3 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                {{ ucfirst($role) }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border)]">
                    @forelse($groupedPermissions as $group => $groupPermissions)
                        @foreach($groupPermissions as $index => $permission)
                            <tr class="hover:bg-slate-50">
                                @if($index === 0)
                                    <td class="px-4 py-3 text-sm font-medium text-[var(--text)]" rowspan="{{ $groupPermissions->count() }}">
                                        {{ $groupLabels[$group] ?? ucfirst($group) }}
                                    </td>
                                @endif
                                <td class="px-4 py-3 text-sm text-[var(--text-muted)]">{{ $permission->label }}</td>
                                @foreach($roles as $role)
                                    @php
                                        $rolePermission = $permission->rolePermissions->where('role', $role)->first();
                                        $isAllowed = $rolePermission?->allowed ?? false;
                                    @endphp
                                    <td class="px-4 py-3 text-center">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox"
                                                   name="permissions[{{ $permission->id }}][{{ $role }}]"
                                                   value="1"
                                                   {{ $isAllowed ? 'checked' : '' }}
                                                   class="sr-only peer"
                                                   onchange="togglePermission(this, '{{ $role }}', {{ $permission->id }})">
                                            <div class="w-9 h-5 bg-gray-200 peer-focus:ring-2 peer-focus:ring-[var(--accent)] rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[var(--accent)]"></div>
                                        </label>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="{{ count($roles) + 2 }}" class="px-4 py-8 text-center text-[var(--text-muted)]">Tidak ada permission</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-[var(--border)] flex justify-end">
            <button type="submit" class="btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>

<script>
function togglePermission(checkbox, role, permissionId) {
    const allowed = checkbox.checked ? 1 : 0;
    // Permission is saved via the form submission
}
</script>
