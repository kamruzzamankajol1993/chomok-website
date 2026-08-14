<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\HomepageContent;
use App\Models\HomePromoCard;
use App\Models\HomeSlide;
use Illuminate\View\View;

class HomePageController extends Controller
{
    public function index(): View
    {
        $slides = HomeSlide::query()->where('is_active', true)->orderBy('sort_order')->orderBy('id')->get();
        $content = HomepageContent::current();
        $promoCards = HomePromoCard::query()
            ->where('is_active', true)
            ->whereBetween('banner_slot', [1, 5])
            ->orderBy('sort_order')
            ->orderBy('banner_slot')
            ->get();

        $loadActiveMenuItems = static function ($query): void {
            $query->where('is_active', true)
                ->with([
                    'prices.variationAddons',
                    'mainImage',
                    'addons' => fn ($q) => $q->where('is_active', true),
                ])
                ->orderBy('id', 'asc');
        };

        $categories = Category::query()
            ->where('is_active', true)
            ->with([
                // Home menu follows the design hierarchy:
                // Category -> Subcategory -> all active food items.
                'subcategories' => function ($query) use ($loadActiveMenuItems): void {
                    $query->where('is_active', true)
                        ->whereHas('menuItems', fn ($itemQuery) => $itemQuery->where('is_active', true))
                        ->with(['menuItems' => $loadActiveMenuItems])
                        ->orderBy('id', 'asc');
                },
                // Keep active items without a subcategory visible as a direct category grid.
                'menuItems' => function ($query) use ($loadActiveMenuItems): void {
                    $query->whereNull('subcategory_id');
                    $loadActiveMenuItems($query);
                },
            ])
            ->orderBy('id', 'asc')
            ->get()
            ->filter(fn (Category $category) => $category->menuItems->isNotEmpty() || $category->subcategories->isNotEmpty())
            ->values();

        return view('website.index', compact('slides', 'content', 'promoCards', 'categories'));
    }
}
