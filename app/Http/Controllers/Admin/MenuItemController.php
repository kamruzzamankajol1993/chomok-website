<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use App\Models\Branch;
use App\Models\Category;
use App\Models\MenuItem;
use App\Models\MenuItemImage;
use App\Models\Subcategory;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class MenuItemController extends Controller
{
    use ImageUploadTrait;

    public function index(Request $request): View
    {
        $query = MenuItem::query()
            ->with(['category', 'subcategory', 'mainImage', 'prices']);

        if (! $request->user()->canSeeAllBranches()) {
            $query->whereHas('branches', fn ($branchQuery) => $branchQuery->whereKey($request->user()->branch_id));
        }

        $menuItems = $query
            ->when($request->filled('search'), function ($itemQuery) use ($request): void {
                $search = trim((string) $request->input('search'));
                $itemQuery->where(function ($searchQuery) use ($search): void {
                    $searchQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhereHas('category', fn ($categoryQuery) => $categoryQuery->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('subcategory', fn ($subcategoryQuery) => $subcategoryQuery->where('name', 'like', "%{$search}%"));
                });
            })
            ->when($request->filled('category_id'), fn ($itemQuery) => $itemQuery->where('category_id', $request->integer('category_id')))
            ->when($request->filled('status'), fn ($itemQuery) => $itemQuery->where('is_active', $request->input('status') === 'active'))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax()) {
            return view('admin.menu-item.partials.table', compact('menuItems'));
        }

        $categories = Category::query()->orderBy('name')->get();
        $categoryCount = Category::query()->count();
        $itemCount = MenuItem::query()->count();

        return view('admin.menu-item.index', compact('menuItems', 'categories', 'categoryCount', 'itemCount'));
    }

    public function create(Request $request): View
    {
        return view('admin.menu-item.create', $this->formData($request));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $uploadedPaths = [];

        try {
            $menuItem = DB::transaction(function () use ($request, $data, &$uploadedPaths): MenuItem {
                $menuItem = MenuItem::query()->create([
                    'category_id' => $data['category_id'],
                    'subcategory_id' => $data['subcategory_id'] ?? null,
                    'name' => $data['name'],
                    'slug' => $this->uniqueSlug($data['name']),
                    'description' => $data['description'] ?? null,
                    'is_active' => $request->boolean('is_active'),
                ]);

                $this->syncPrices($menuItem, $data['prices']);
                $menuItem->addons()->sync($data['addon_ids'] ?? []);
                $menuItem->branches()->sync($this->branchIdsFor($request, $data['branch_ids'] ?? []));
                $newImages = $this->uploadImages($request, $menuItem, $uploadedPaths);
                $this->setMainImage($menuItem, (string) ($data['main_image_value'] ?? ''), $newImages);

                return $menuItem;
            });
        } catch (\Throwable $exception) {
            foreach ($uploadedPaths as $path) {
                $this->deleteFile($path);
            }
            throw $exception;
        }

        return redirect()->route('admin.menu-items.show', $menuItem)->with('success', 'Menu item created successfully.');
    }

    public function show(Request $request, MenuItem $menuItem): View
    {
        $this->ensureVisible($request, $menuItem);
        $menuItem->load(['category', 'subcategory', 'images', 'prices.variationAddons', 'addons', 'branches']);

        return view('admin.menu-item.show', compact('menuItem'));
    }

    public function edit(Request $request, MenuItem $menuItem): View
    {
        $this->ensureVisible($request, $menuItem);
        $menuItem->load(['images', 'prices.variationAddons', 'addons', 'branches']);

        return view('admin.menu-item.edit', array_merge($this->formData($request), compact('menuItem')));
    }

    public function update(Request $request, MenuItem $menuItem): RedirectResponse
    {
        $this->ensureVisible($request, $menuItem);
        $data = $this->validated($request, $menuItem);
        $removeIds = collect($data['remove_image_ids'] ?? [])->map(fn ($id) => (int) $id)->unique();
        $removableImages = $menuItem->images()->whereKey($removeIds)->get();
        $remainingCount = $menuItem->images()->count() - $removableImages->count() + count($request->file('images', []));

        if ($remainingCount < 1) {
            throw ValidationException::withMessages(['images' => 'At least one menu item image is required.']);
        }
        if ($remainingCount > 8) {
            throw ValidationException::withMessages(['images' => 'A menu item can have a maximum of 8 images.']);
        }

        $uploadedPaths = [];
        $removedPaths = $removableImages->pluck('image')->filter()->all();

        try {
            DB::transaction(function () use ($request, $data, $menuItem, $removableImages, &$uploadedPaths): void {
                $menuItem->update([
                    'category_id' => $data['category_id'],
                    'subcategory_id' => $data['subcategory_id'] ?? null,
                    'name' => $data['name'],
                    'slug' => $this->uniqueSlug($data['name'], $menuItem->id),
                    'description' => $data['description'] ?? null,
                    'is_active' => $request->boolean('is_active'),
                ]);

                $this->syncPrices($menuItem, $data['prices']);
                $menuItem->addons()->sync($data['addon_ids'] ?? []);
                $menuItem->branches()->sync($this->branchIdsFor($request, $data['branch_ids'] ?? []));

                if ($removableImages->isNotEmpty()) {
                    $menuItem->images()->whereKey($removableImages->modelKeys())->delete();
                }

                $newImages = $this->uploadImages($request, $menuItem, $uploadedPaths);
                $this->setMainImage($menuItem, (string) ($data['main_image_value'] ?? ''), $newImages);
            });
        } catch (\Throwable $exception) {
            foreach ($uploadedPaths as $path) {
                $this->deleteFile($path);
            }
            throw $exception;
        }

        foreach ($removedPaths as $path) {
            $this->deleteFile($path);
        }

        return redirect()->route('admin.menu-items.show', $menuItem)->with('success', 'Menu item updated successfully.');
    }

    public function destroy(Request $request, MenuItem $menuItem): RedirectResponse
    {
        $this->ensureVisible($request, $menuItem);
        $this->deleteMenuItem($menuItem);

        return redirect()->route('admin.menu-items.index')->with('success', 'Menu item deleted successfully.');
    }

    public function bulkAction(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:menu_items,id'],
            'bulk_action' => ['required', 'in:delete,active,inactive'],
        ]);

        $permission = $data['bulk_action'] === 'delete' ? 'menu-item.delete' : 'menu-item.edit';
        abort_unless($request->user()->can($permission), 403);

        $query = MenuItem::query()->whereKey($data['ids']);
        if (! $request->user()->canSeeAllBranches()) {
            $query->whereHas('branches', fn ($branchQuery) => $branchQuery->whereKey($request->user()->branch_id));
        }

        if ($data['bulk_action'] !== 'delete') {
            $count = (clone $query)->update(['is_active' => $data['bulk_action'] === 'active']);
            return back()->with('success', "{$count} menu item(s) updated successfully.");
        }

        $items = $query->get();
        foreach ($items as $item) {
            $this->deleteMenuItem($item);
        }

        return back()->with('success', $items->count().' menu item(s) deleted successfully.');
    }

    private function validated(Request $request, ?MenuItem $menuItem = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'subcategory_id' => [
                'nullable',
                Rule::exists('subcategories', 'id')->where(fn ($query) => $query->where('category_id', $request->integer('category_id'))),
            ],
            'description' => ['nullable', 'string', 'max:5000'],
            'prices' => ['required', 'array', 'min:1', 'max:20'],
            'prices.*.size_label' => ['nullable', 'string', 'max:100'],
            'prices.*.price' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'prices.*.discount_price' => ['nullable', 'numeric', 'min:0', 'max:9999999999.99'],
            'prices.*.addons' => ['nullable', 'array', 'max:30'],
            'prices.*.addons.*.name' => ['required', 'string', 'max:255'],
            'prices.*.addons.*.description' => ['nullable', 'string', 'max:1000'],
            'prices.*.addons.*.price' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'addon_ids' => ['nullable', 'array'],
            'addon_ids.*' => ['integer', 'exists:addons,id'],
            'branch_ids' => ['required', 'array', 'min:1'],
            'branch_ids.*' => ['integer', 'exists:branches,id'],
            'images' => [$menuItem ? 'nullable' : 'required', 'array', $menuItem ? 'max:8' : 'min:1', 'max:8'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'main_image_value' => ['nullable', 'string', 'max:50'],
            'remove_image_ids' => ['nullable', 'array'],
            'remove_image_ids.*' => ['integer'],
        ]);

        foreach ($data['prices'] as $index => $price) {
            if (filled($price['discount_price'] ?? null) && (float) $price['discount_price'] > (float) $price['price']) {
                throw ValidationException::withMessages([
                    "prices.{$index}.discount_price" => 'Discount price cannot be greater than the regular price.',
                ]);
            }
        }

        return $data;
    }

    private function formData(Request $request): array
    {
        return [
            'categories' => Category::query()->orderBy('name')->get(),
            'subcategories' => Subcategory::query()->orderBy('name')->get(),
            'addons' => Addon::query()->orderBy('name')->get(),
            'branches' => Branch::query()->visibleTo($request->user())->orderBy('name')->get(),
        ];
    }

    private function syncPrices(MenuItem $menuItem, array $prices): void
    {
        $menuItem->prices()->delete();

        foreach (array_values($prices) as $index => $price) {
            $menuItemPrice = $menuItem->prices()->create([
                'size_label' => filled($price['size_label'] ?? null) ? trim((string) $price['size_label']) : null,
                'price' => $price['price'],
                'discount_price' => filled($price['discount_price'] ?? null) ? $price['discount_price'] : null,
                'sort_order' => $index,
            ]);

            $variationAddons = collect($price['addons'] ?? [])
                ->values()
                ->map(fn (array $addon, int $addonIndex): array => [
                    'name' => trim((string) $addon['name']),
                    'description' => filled($addon['description'] ?? null) ? trim((string) $addon['description']) : null,
                    'price' => $addon['price'],
                    'sort_order' => $addonIndex,
                ])
                ->filter(fn (array $addon): bool => $addon['name'] !== '')
                ->all();

            if ($variationAddons) {
                $menuItemPrice->variationAddons()->createMany($variationAddons);
            }
        }
    }

    /**
     * @return array<int, MenuItemImage>
     */
    private function uploadImages(Request $request, MenuItem $menuItem, array &$uploadedPaths): array
    {
        $created = [];
        $startOrder = (int) $menuItem->images()->max('sort_order') + 1;

        foreach (array_values($request->file('images', [])) as $index => $file) {
            $singleRequest = Request::create('/', 'POST', [], [], ['image' => $file]);
            $imageModel = new MenuItemImage();
            $path = $this->handleImageUpload($singleRequest, $imageModel, 'image', 'menu-items');
            if (! $path) {
                continue;
            }

            $uploadedPaths[] = $path;
            $created[$index] = $menuItem->images()->create([
                'image' => $path,
                'is_main' => false,
                'sort_order' => $startOrder + $index,
            ]);
        }

        return $created;
    }

    /**
     * @param array<int, MenuItemImage> $newImages
     */
    private function setMainImage(MenuItem $menuItem, string $selection, array $newImages): void
    {
        $candidate = null;

        if (str_starts_with($selection, 'existing:')) {
            $id = (int) Str::after($selection, 'existing:');
            $candidate = $menuItem->images()->whereKey($id)->first();
        } elseif (str_starts_with($selection, 'new:')) {
            $index = (int) Str::after($selection, 'new:');
            $candidate = $newImages[$index] ?? null;
        }

        $candidate ??= $menuItem->images()->orderBy('sort_order')->first();
        $menuItem->images()->update(['is_main' => false]);
        $candidate?->update(['is_main' => true]);
    }

    private function branchIdsFor(Request $request, array $requestedIds): array
    {
        if (! $request->user()->canSeeAllBranches()) {
            return array_filter([$request->user()->branch_id]);
        }

        return Branch::query()->whereKey($requestedIds)->pluck('id')->all();
    }

    private function ensureVisible(Request $request, MenuItem $menuItem): void
    {
        if ($request->user()->canSeeAllBranches()) {
            return;
        }

        abort_unless($menuItem->branches()->whereKey($request->user()->branch_id)->exists(), 403);
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'menu-item';
        $slug = $base;
        $counter = 2;

        while (MenuItem::withTrashed()->where('slug', $slug)->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base.'-'.$counter++;
        }

        return $slug;
    }

    private function deleteMenuItem(MenuItem $menuItem): void
    {
        $paths = $menuItem->images()->pluck('image')->filter()->all();

        DB::transaction(function () use ($menuItem): void {
            $menuItem->addons()->detach();
            $menuItem->branches()->detach();
            $menuItem->prices()->delete();
            $menuItem->images()->delete();
            $menuItem->forceDelete();
        });

        foreach ($paths as $path) {
            $this->deleteFile($path);
        }
    }

    private function deleteFile(?string $path): void
    {
        if ($path && File::exists(base_path($path))) {
            File::delete(base_path($path));
        }
    }
}
