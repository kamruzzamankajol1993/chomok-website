<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuPageController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $categories = Category::query()->where('is_active', true)->orderBy('name')->get();
        $category = trim((string) $request->query('category', 'all'));

        $query = MenuItem::query()
            ->where('is_active', true)
            ->with(['category', 'prices', 'mainImage', 'addons' => fn ($q) => $q->where('is_active', true)]);

        if ($category !== '' && $category !== 'all') {
            $query->whereHas('category', fn ($q) => $q->where('slug', $category)->where('is_active', true));
        }

        $items = $query->orderBy('id')->paginate(12)->withQueryString();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('website.menu.partials.cards', ['items' => $items])->render(),
                'next_page' => $items->hasMorePages() ? $items->currentPage() + 1 : null,
            ]);
        }

        return view('website.menu.menu', compact('categories', 'items', 'category'));
    }

    public function show(MenuItem $menuItem): View
    {
        abort_unless($menuItem->is_active, 404);
        $menuItem->load(['category', 'subcategory', 'prices', 'images', 'addons' => fn ($q) => $q->where('is_active', true)]);
        $suggestions = MenuItem::query()
            ->where('is_active', true)
            ->where('id', '!=', $menuItem->id)
            ->where('category_id', $menuItem->category_id)
            ->with(['prices', 'mainImage', 'addons' => fn ($q) => $q->where('is_active', true)])
            ->limit(4)
            ->get();

        return view('website.menu.food-view', compact('menuItem', 'suggestions'));
    }

    public function configuration(MenuItem $menuItem): JsonResponse
    {
        abort_unless($menuItem->is_active, 404);
        $menuItem->load(['prices', 'addons' => fn ($q) => $q->where('is_active', true)]);

        return response()->json([
            'html' => view('website.menu.partials.configure', compact('menuItem'))->render(),
            'has_addons' => $menuItem->addons->isNotEmpty(),
        ]);
    }
}
