<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SubcategoryController extends Controller
{
    public function index(Request $request): View
    {
        $subcategories = Subcategory::query()
            ->with('category')
            ->withCount('menuItems')
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = trim((string) $request->input('search'));
                $query->where(function ($searchQuery) use ($search): void {
                    $searchQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%")
                        ->orWhereHas('category', fn ($categoryQuery) => $categoryQuery->where('name', 'like', "%{$search}%"));
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax()) {
            return view('admin.subcategory.partials.table', compact('subcategories'));
        }

        $categories = Category::query()->where('is_active', true)->orderBy('name')->get();

        return view('admin.subcategory.index', compact('subcategories', 'categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($data['name']);
        $data['is_active'] = $request->boolean('is_active');
        Subcategory::query()->create($data);

        return back()->with('success', 'Subcategory created successfully.');
    }

    public function update(Request $request, Subcategory $subcategory): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($data['name'], $subcategory->id);
        $data['is_active'] = $request->boolean('is_active');
        $subcategory->update($data);

        return back()->with('success', 'Subcategory updated successfully.');
    }

    public function destroy(Subcategory $subcategory): RedirectResponse
    {
        if ($subcategory->menuItems()->exists()) {
            return back()->with('error', 'Delete or move this subcategory’s menu items first.');
        }

        $subcategory->forceDelete();

        return back()->with('success', 'Subcategory deleted successfully.');
    }

    public function bulkAction(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:subcategories,id'],
            'bulk_action' => ['required', 'in:delete,active,inactive'],
        ]);

        $permission = $data['bulk_action'] === 'delete' ? 'subcategory.delete' : 'subcategory.edit';
        abort_unless($request->user()->can($permission), 403);

        if ($data['bulk_action'] !== 'delete') {
            Subcategory::query()->whereKey($data['ids'])->update(['is_active' => $data['bulk_action'] === 'active']);
            return back()->with('success', 'Selected subcategories updated successfully.');
        }

        $deleted = 0;
        $skipped = 0;
        foreach (Subcategory::query()->whereKey($data['ids'])->get() as $subcategory) {
            if ($subcategory->menuItems()->exists()) {
                $skipped++;
                continue;
            }
            $subcategory->forceDelete();
            $deleted++;
        }

        $message = "{$deleted} subcategor".($deleted === 1 ? 'y' : 'ies')." deleted.";
        if ($skipped > 0) {
            $message .= " {$skipped} skipped because menu items exist.";
        }

        return back()->with($deleted > 0 ? 'success' : 'error', $message);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
        ]);
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'subcategory';
        $slug = $base;
        $counter = 2;

        while (Subcategory::withTrashed()->where('slug', $slug)->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base.'-'.$counter++;
        }

        return $slug;
    }
}
