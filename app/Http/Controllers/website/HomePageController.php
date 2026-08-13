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

        $categories = Category::query()
            ->where('is_active', true)
            ->with(['menuItems' => function ($query): void {
                $query->where('is_active', true)
                    ->with(['prices.variationAddons', 'mainImage', 'addons' => fn ($q) => $q->where('is_active', true)])
                    //->orderBy('name', 'asc')
                    ->orderBy('id', 'asc');
            }])
            ->orderBy('id', 'asc')
            ->get()
            ->filter(fn (Category $category) => $category->menuItems->isNotEmpty())
            ->values();

        return view('website.index', compact('slides', 'content', 'promoCards', 'categories'));
    }
}
