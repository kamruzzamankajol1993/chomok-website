<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Role;
use App\Models\User;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    use ImageUploadTrait;

    public function index(Request $request): View
    {
        $users = User::query()
            ->visibleTo($request->user())
            ->with(['branch', 'roles'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = trim((string) $request->input('search'));
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->input('status')))
            ->when($request->filled('branch_id') && $request->user()->canSeeAllBranches(), fn ($query) => $query->where('branch_id', $request->integer('branch_id')))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax()) {
            return view('admin.user.partials.table', compact('users'));
        }

        $branches = Branch::query()->visibleTo($request->user())->orderBy('name')->get();
        return view('admin.user.index', compact('users', 'branches'));
    }

    public function create(Request $request): View
    {
        return view('admin.user.create', $this->formData($request));
    }

    public function show(Request $request, User $user): View
    {
        $this->ensureVisible($request, $user);
        $user->load(['branch', 'roles.permissions']);

        return view('admin.user.show', compact('user'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $roleName = $data['role'];
        $this->ensureRoleAssignable($request, $roleName);
        $role = Role::query()
            ->where('name', $roleName)
            ->where('guard_name', 'web')
            ->firstOrFail();
        unset($data['role']);

        $imageModel = new User();
        if ($request->hasFile('image')) {
            $data['image'] = $this->handleImageUpload($request, $imageModel, 'image', 'users', 500, 500);
        }

        $this->normalizeBranchAccess($request, $data);

        try {
            DB::transaction(function () use ($data, $role): void {
                $user = User::query()->create($data);
                $user->syncRoles([$role]);
            });
        } catch (\Throwable $exception) {
            $this->deleteImage($data['image'] ?? null);
            throw $exception;
        }

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(Request $request, User $user): View
    {
        $this->ensureVisible($request, $user);
        return view('admin.user.edit', array_merge($this->formData($request), compact('user')));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->ensureVisible($request, $user);
        $data = $this->validated($request, $user);
        $roleName = $data['role'];
        $this->ensureRoleAssignable($request, $roleName);
        $role = Role::query()
            ->where('name', $roleName)
            ->where('guard_name', 'web')
            ->firstOrFail();
        unset($data['role']);

        if (! filled($data['password'] ?? null)) {
            unset($data['password']);
        }

        $this->normalizeBranchAccess($request, $data);

        if ($request->user()->is($user) && ($data['status'] ?? 'active') !== 'active') {
            return back()->withInput()->with('error', 'You cannot deactivate your own account.');
        }

        if ($user->hasRole('Super Admin') && $roleName !== 'Super Admin' && User::role('Super Admin')->count() <= 1) {
            return back()->withInput()->with('error', 'At least one Super Admin account must remain.');
        }

        if ($request->hasFile('image')) {
            $data['image'] = $this->handleImageUpdate($request, $user, 'image', 'users', 500, 500);
        }

        DB::transaction(function () use ($user, $data, $role): void {
            $user->update($data);
            $user->syncRoles([$role]);
        });

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        $this->ensureVisible($request, $user);

        if ($request->user()->is($user)) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        if ($user->hasRole('Super Admin') && ! $request->user()->hasRole('Super Admin')) {
            abort(403);
        }

        if ($user->hasRole('Super Admin') && User::role('Super Admin')->count() <= 1) {
            return back()->with('error', 'The last Super Admin account cannot be deleted.');
        }

        $oldImage = $user->image;
        DB::transaction(function () use ($user): void {
            $user->syncPermissions([]);
            $user->syncRoles([]);
            $user->delete();
        });
        $this->deleteImage($oldImage);

        return back()->with('success', 'User deleted successfully.');
    }

    private function validated(Request $request, ?User $user = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user?->id)],
            'password' => [$user ? 'nullable' : 'required', 'string', 'min:8', 'confirmed'],
            'branch_id' => ['nullable', 'exists:branches,id'],
            'role' => [
                'required',
                Rule::exists('roles', 'name')->where(fn ($query) => $query->where('guard_name', 'web')),
            ],
            'status' => ['required', 'in:active,inactive'],
        ]);
    }

    private function normalizeBranchAccess(Request $request, array &$data): void
    {
        $viewer = $request->user();

        if (! $viewer->canSeeAllBranches()) {
            $data['branch_id'] = $viewer->branch_id;
            $data['all_branch_access'] = false;
            return;
        }

        $data['all_branch_access'] = $request->boolean('all_branch_access');
        if (! $data['all_branch_access'] && empty($data['branch_id'])) {
            $data['branch_id'] = $viewer->branch_id;
        }
    }

    private function formData(Request $request): array
    {
        $viewer = $request->user();
        $roleNames = $this->assignedRoleNames($viewer);
        $isSuperAdmin = in_array('Super Admin', $roleNames, true);
        $canSeeAllBranches = (bool) $viewer->all_branch_access
            || $isSuperAdmin;

        $roles = Role::query()
            ->where('guard_name', 'web')
            ->when(! $isSuperAdmin, fn ($query) => $query->where('name', '!=', 'Super Admin'))
            ->orderBy('name')
            ->get();

        $branches = Branch::query()
            ->when(! $canSeeAllBranches, fn ($query) => $query->whereKey($viewer->branch_id))
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return compact('branches', 'roles', 'canSeeAllBranches');
    }

    /**
     * Read assigned role names without forcing Spatie to resolve the configured
     * Role model while the create/edit form is being prepared.
     */
    private function assignedRoleNames(User $user): array
    {
        $tableNames = config('permission.table_names', []);
        $columnNames = config('permission.column_names', []);

        $rolesTable = $tableNames['roles'] ?? 'roles';
        $modelHasRolesTable = $tableNames['model_has_roles'] ?? 'model_has_roles';
        $rolePivotKey = $columnNames['role_pivot_key'] ?? 'role_id';
        $modelMorphKey = $columnNames['model_morph_key'] ?? 'model_id';

        return DB::table($modelHasRolesTable)
            ->join($rolesTable, $rolesTable.'.id', '=', $modelHasRolesTable.'.'.$rolePivotKey)
            ->where($modelHasRolesTable.'.'.$modelMorphKey, $user->getKey())
            ->where($modelHasRolesTable.'.model_type', $user->getMorphClass())
            ->where($rolesTable.'.guard_name', 'web')
            ->pluck($rolesTable.'.name')
            ->all();
    }

    private function ensureRoleAssignable(Request $request, string $role): void
    {
        if ($role === 'Super Admin' && ! $request->user()->hasRole('Super Admin')) {
            abort(403);
        }
    }

    private function ensureVisible(Request $request, User $user): void
    {
        if ($user->hasRole('Super Admin') && ! $request->user()->hasRole('Super Admin')) {
            abort(403);
        }

        if (! $request->user()->canSeeAllBranches() && $request->user()->branch_id !== $user->branch_id) {
            abort(403);
        }
    }

    private function deleteImage(?string $path): void
    {
        if ($path && File::exists(base_path($path))) {
            File::delete(base_path($path));
        }
    }
}
