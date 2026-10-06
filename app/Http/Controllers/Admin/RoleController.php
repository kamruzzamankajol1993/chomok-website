<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function index(Request $request): View
    {
        $roles = Role::query()
            ->withCount(['permissions', 'users'])
            ->when($request->filled('search'), fn ($query) => $query->where('name', 'like', '%'.trim((string) $request->input('search')).'%'))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax()) {
            return view('admin.role.partials.table', compact('roles'));
        }

        return view('admin.role.index', compact('roles'));
    }

    public function create(): View
    {
        return view('admin.role.create', ['permissionGroups' => $this->permissionGroups()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:roles,name'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['integer', 'exists:permissions,id'],
        ]);

        DB::transaction(function () use ($data): void {
            $role = Role::query()->create(['name' => $data['name'], 'guard_name' => 'web']);
            $permissions = Permission::query()
                ->whereIn('id', $data['permissions'] ?? [])
                ->where('guard_name', 'web')
                ->get();

            $role->syncPermissions($permissions);
        });

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role): View
    {
        return view('admin.role.edit', [
            'role' => $role,
            'permissionGroups' => $this->permissionGroups(),
            'selectedPermissions' => $role->permissions()->pluck('id')->all(),
        ]);
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('roles', 'name')->ignore($role->id)],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['integer', 'exists:permissions,id'],
        ]);

        if ($role->name === 'Super Admin' && $data['name'] !== 'Super Admin') {
            return back()->with('error', 'The Super Admin role name cannot be changed.');
        }

        DB::transaction(function () use ($role, $data): void {
            $role->update(['name' => $data['name']]);
            $permissions = Permission::query()
                ->whereIn('id', $data['permissions'] ?? [])
                ->where('guard_name', $role->guard_name)
                ->get();

            $role->syncPermissions($permissions);
        });

        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        if ($role->name === 'Super Admin') {
            return back()->with('error', 'The Super Admin role cannot be deleted.');
        }

        if ($role->users()->exists()) {
            return back()->with('error', 'This role is assigned to users and cannot be deleted.');
        }

        $role->delete();
        return back()->with('success', 'Role deleted successfully.');
    }

    private function permissionGroups()
    {
        return Permission::query()->orderBy('group')->orderBy('name')->get()->groupBy('group');
    }
}
