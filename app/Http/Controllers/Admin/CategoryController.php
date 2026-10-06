<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    use ImageUploadTrait;

    public function index(Request $request): View
    {
        $categories = Category::query()
            ->withCount(['subcategories', 'menuItems'])
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = trim((string) $request->input('search'));
                $query->where(function ($searchQuery) use ($search): void {
                    $searchQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax()) {
            return view('admin.category.partials.table', compact('categories'));
        }

        return view('admin.category.index', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($data['name']);
        $data['is_active'] = $request->boolean('is_active');

        $category = new Category();
        if ($request->hasFile('image')) {
            $data['image'] = $this->handleImageUpload($request, $category, 'image', 'categories', 700, 700);
        }

        try {
            Category::query()->create($data);
        } catch (\Throwable $exception) {
            $this->deleteImage($data['image'] ?? null);
            throw $exception;
        }

        return back()->with('success', 'Category created successfully.');
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($data['name'], $category->id);
        $data['is_active'] = $request->boolean('is_active');
        if ($request->hasFile('image')) {
            $data['image'] = $this->handleImageUpdate($request, $category, 'image', 'categories', 700, 700);
        }
        $category->update($data);

        return back()->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->subcategories()->exists() || $category->menuItems()->exists()) {
            return back()->with('error', 'Delete or move this category’s subcategories and menu items first.');
        }

        $this->deleteCategory($category);

        return back()->with('success', 'Category deleted successfully.');
    }

    public function bulkAction(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:categories,id'],
            'bulk_action' => ['required', 'in:delete,active,inactive'],
        ]);

        $this->authorizeBulkAction($request, $data['bulk_action']);
        $categories = Category::query()->whereKey($data['ids'])->get();

        if ($data['bulk_action'] !== 'delete') {
            Category::query()->whereKey($data['ids'])->update(['is_active' => $data['bulk_action'] === 'active']);
            return back()->with('success', 'Selected categories updated successfully.');
        }

        $deleted = 0;
        $skipped = 0;
        foreach ($categories as $category) {
            if ($category->subcategories()->exists() || $category->menuItems()->exists()) {
                $skipped++;
                continue;
            }
            $this->deleteCategory($category);
            $deleted++;
        }

        $message = "{$deleted} categor".($deleted === 1 ? 'y' : 'ies')." deleted.";
        if ($skipped > 0) {
            $message .= " {$skipped} skipped because related records exist.";
        }

        return back()->with($deleted > 0 ? 'success' : 'error', $message);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'category';
        $slug = $base;
        $counter = 2;

        while (Category::withTrashed()->where('slug', $slug)->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base.'-'.$counter++;
        }

        return $slug;
    }

    private function deleteCategory(Category $category): void
    {
        $this->deleteImage($category->image);
        $category->forceDelete();
    }

    private function deleteImage(?string $path): void
    {
        if ($path && File::exists(base_path($path))) {
            File::delete(base_path($path));
        }
    }

    private function authorizeBulkAction(Request $request, string $action): void
    {
        $permission = $action === 'delete' ? 'category.delete' : 'category.edit';
        abort_unless($request->user()->can($permission), 403);
    }
}
