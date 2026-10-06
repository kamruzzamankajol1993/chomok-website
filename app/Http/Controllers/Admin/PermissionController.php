<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PermissionController extends Controller
{
    public function index(Request $request): View
    {
        $permissions = Permission::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = trim((string) $request->input('search'));
                $query->where(fn ($subQuery) => $subQuery->where('name', 'like', "%{$search}%")->orWhere('group', 'like', "%{$search}%"));
            })
            ->orderBy('group')
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        if ($request->ajax()) {
            return view('admin.permission.partials.table', compact('permissions'));
        }

        return view('admin.permission.index', compact('permissions'));
    }

    public function create(): View
    {
        return view('admin.permission.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'group' => ['required', 'string', 'max:100', 'not_regex:/[\\\/]/'],
            'permission_names' => ['required', 'array', 'min:1'],
            'permission_names.*' => ['required', 'string', 'max:150'],
        ]);

        $names = $this->normalizeNames($data['permission_names']);
        if (count($names) !== count($data['permission_names'])) {
            throw ValidationException::withMessages(['permission_names' => 'Permission names must be unique.']);
        }
        $this->ensureUniqueNames($names);

        DB::transaction(function () use ($data, $names): void {
            foreach ($names as $name) {
                Permission::query()->create([
                    'name' => $name,
                    'guard_name' => 'web',
                    'group' => trim($data['group']),
                ]);
            }
        });

        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        return redirect()->route('admin.permissions.index')->with('success', 'Permission group created successfully.');
    }

    public function editGroup(string $group): View
    {
        $group = urldecode($group);
        $permissions = Permission::query()->where('group', $group)->orderBy('name')->get();
        abort_if($permissions->isEmpty(), 404);

        return view('admin.permission.edit', compact('group', 'permissions'));
    }

    public function updateGroup(Request $request, string $group): RedirectResponse
    {
        $group = urldecode($group);
        $data = $request->validate([
            'group' => ['required', 'string', 'max:100', 'not_regex:/[\\\/]/'],
            'permission_ids' => ['nullable', 'array'],
            'permission_ids.*' => ['nullable', 'integer'],
            'permission_names' => ['required', 'array', 'min:1'],
            'permission_names.*' => ['required', 'string', 'max:150'],
        ]);

        $existing = Permission::query()->where('group', $group)->get()->keyBy('id');
        abort_if($existing->isEmpty(), 404);

        $names = $this->normalizeNames($data['permission_names']);
        if (count($names) !== count($data['permission_names'])) {
            throw ValidationException::withMessages(['permission_names' => 'Permission names must be unique.']);
        }

        $ids = array_map(fn ($id) => $id ? (int) $id : null, $data['permission_ids'] ?? []);
        $submittedExistingIds = collect($ids)->filter()->values();
        $removed = $existing->keys()->diff($submittedExistingIds);

        foreach ($removed as $permissionId) {
            $attached = DB::table('role_has_permissions')->where('permission_id', $permissionId)->exists()
                || DB::table('model_has_permissions')->where('permission_id', $permissionId)->exists();
            if ($attached) {
                throw ValidationException::withMessages([
                    'permission_names' => 'An assigned permission cannot be removed. Unassign it from roles first.',
                ]);
            }
        }

        foreach ($names as $index => $name) {
            $ignoreId = $ids[$index] ?? null;
            $duplicate = Permission::query()->where('name', $name)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists();
            if ($duplicate) {
                throw ValidationException::withMessages(['permission_names' => "The permission '{$name}' already exists."]);
            }
        }

        DB::transaction(function () use ($data, $names, $ids, $existing, $removed): void {
            foreach ($removed as $permissionId) {
                $existing[$permissionId]->delete();
            }

            foreach ($names as $index => $name) {
                $permissionId = $ids[$index] ?? null;
                if ($permissionId && $existing->has($permissionId)) {
                    $existing[$permissionId]->update(['name' => $name, 'group' => trim($data['group'])]);
                } else {
                    Permission::query()->create([
                        'name' => $name,
                        'guard_name' => 'web',
                        'group' => trim($data['group']),
                    ]);
                }
            }
        });

        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        return redirect()->route('admin.permissions.index')->with('success', 'Permission group updated successfully.');
    }

    public function destroy(Permission $permission): RedirectResponse
    {
        $attached = DB::table('role_has_permissions')->where('permission_id', $permission->id)->exists()
            || DB::table('model_has_permissions')->where('permission_id', $permission->id)->exists();

        if ($attached) {
            return back()->with('error', 'This permission is assigned and cannot be deleted.');
        }

        $permission->delete();
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        return back()->with('success', 'Permission deleted successfully.');
    }

    private function normalizeNames(array $names): array
    {
        return array_values(array_unique(array_filter(array_map(fn ($name) => trim((string) $name), $names))));
    }

    private function ensureUniqueNames(array $names): void
    {
        if (Permission::query()->whereIn('name', $names)->exists()) {
            throw ValidationException::withMessages(['permission_names' => 'One or more permission names already exist.']);
        }
    }
}
