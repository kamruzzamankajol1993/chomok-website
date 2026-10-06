<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AddonController extends Controller
{
    public function index(Request $request): View
    {
        $addons = Addon::query()
            ->withCount('menuItems')
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = trim((string) $request->input('search'));
                $query->where('name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax()) {
            return view('admin.addon.partials.table', compact('addons'));
        }

        return view('admin.addon.index', compact('addons'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['is_active'] = $request->boolean('is_active');
        Addon::query()->create($data);

        return back()->with('success', 'Add-on created successfully.');
    }

    public function update(Request $request, Addon $addon): RedirectResponse
    {
        $data = $this->validated($request);
        $data['is_active'] = $request->boolean('is_active');
        $addon->update($data);

        return back()->with('success', 'Add-on updated successfully.');
    }

    public function destroy(Addon $addon): RedirectResponse
    {
        $this->deleteAddon($addon);

        return back()->with('success', 'Add-on deleted successfully.');
    }

    public function bulkAction(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:addons,id'],
            'bulk_action' => ['required', 'in:delete,active,inactive'],
        ]);

        $permission = $data['bulk_action'] === 'delete' ? 'addon.delete' : 'addon.edit';
        abort_unless($request->user()->can($permission), 403);

        if ($data['bulk_action'] !== 'delete') {
            Addon::query()->whereKey($data['ids'])->update(['is_active' => $data['bulk_action'] === 'active']);
            return back()->with('success', 'Selected add-ons updated successfully.');
        }

        $addons = Addon::query()->whereKey($data['ids'])->get();
        foreach ($addons as $addon) {
            $this->deleteAddon($addon);
        }

        return back()->with('success', $addons->count().' add-on(s) deleted successfully.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
        ]);
    }

    private function deleteAddon(Addon $addon): void
    {
        $addon->menuItems()->detach();
        $addon->forceDelete();
    }
}
