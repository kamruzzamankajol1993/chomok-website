<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShopPageContent;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopPageContentController extends Controller
{
    use ImageUploadTrait;

    public function edit(): View
    {
        return view('admin.website.shop.edit', ['content' => ShopPageContent::current()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $content = ShopPageContent::current();
        $data = $request->validate([
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:1000'],
            'hero_eyebrow_text' => ['nullable', 'string', 'max:255'],
            'hero_title' => ['nullable', 'string', 'max:255'],
            'hero_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:6144'],
            'intro_badge_text' => ['nullable', 'string', 'max:100'],
            'intro_heading' => ['nullable', 'string', 'max:255'],
            'intro_text' => ['nullable', 'string', 'max:5000'],
            'map_link_text' => ['nullable', 'string', 'max:100'],
        ]);

        if ($request->hasFile('hero_image')) {
            $data['hero_image'] = $this->handleImageUpdate($request, $content, 'hero_image', 'website/shop', 1368, 766);
        } else {
            unset($data['hero_image']);
        }

        $content->update($data);

        return back()->with('success', 'Shop page content updated successfully.');
    }
}
