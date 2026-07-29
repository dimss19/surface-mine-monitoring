<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\RolePermission;
use Illuminate\Http\Request;

class AdminPermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::with('rolePermissions')->orderBy('group')->get();
        $roles = ['admin', 'spv', 'pegawai'];
        
        return view('admin.master-data.index', [
            'activeTab' => 'hak-akses',
            'permissions' => $permissions,
            'roles' => $roles,
        ]);
    }

    public function update(Request $request, string $role)
    {
        $validated = $request->validate([
            'permissions' => 'required|array',
        ]);

        foreach ($validated['permissions'] as $permissionId => $allowed) {
            RolePermission::updateOrCreate(
                ['role' => $role, 'permission_id' => $permissionId],
                ['allowed' => $allowed]
            );
        }

        return redirect()->route('admin.hak-akses.index')
            ->with('success', 'Hak akses berhasil diupdate');
    }
}