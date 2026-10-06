<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageContent;
use App\Models\HomePromoCard;
use App\Models\HomeSlide;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class HomepageContentController extends Controller
{
    use ImageUploadTrait;

    public function index(): View
    {
        return view('admin.website.home.index', [
            'content' => HomepageContent::current(),
            'slides' => HomeSlide::query()->orderBy('sort_order')->orderBy('id')->get(),
            'promoCards' => HomePromoCard::query()
                ->whereBetween('banner_slot', [1, 5])
                ->orderBy('sort_order')
                ->orderBy('banner_slot')
                ->get(),
        ]);
    }

    public function updateAbout(Request $request): RedirectResponse
    {
        $content = HomepageContent::current();
        $data = $request->validate([
            'about_badge_text' => ['nullable', 'string', 'max:255'],
            'about_heading_line_1' => ['nullable', 'string', 'max:255'],
            'about_heading_line_2' => ['nullable', 'string', 'max:255'],
            'about_pill_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'about_circle_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'about_paragraph_text' => ['nullable', 'string', 'max:3000'],
            'about_button_text' => ['nullable', 'string', 'max:255'],
            'about_trust_badge' => ['nullable', 'string', 'max:255'],
        ]);

        if ($request->hasFile('about_pill_image')) {
            $data['about_pill_image'] = $this->handleImageUpdate($request, $content, 'about_pill_image', 'website/home', 1366, 768);
        }
        if ($request->hasFile('about_circle_image')) {
            $data['about_circle_image'] = $this->handleImageUpdate($request, $content, 'about_circle_image', 'website/home', 1366, 768);
        }

        $content->update($data);

        return redirect()->to(route('admin.website.home.index').'#about-pane')->with('success', 'Homepage about section updated successfully.');
    }

    public function updateHungry(Request $request): RedirectResponse
    {
        $content = HomepageContent::current();
        $data = $request->validate([
            'hungry_left_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:6144'],
            'hungry_right_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:6144'],
            'hungry_line_one' => ['nullable', 'string', 'max:255'],
            'hungry_line_two' => ['nullable', 'string', 'max:255'],
            'hungry_subtext' => ['nullable', 'string', 'max:3000'],
            'hungry_button_text' => ['nullable', 'string', 'max:100'],
        ]);

        if ($request->hasFile('hungry_left_image')) {
            $data['hungry_left_image'] = $this->handleImageUpdate($request, $content, 'hungry_left_image', 'website/home/hungry');
        }
        if ($request->hasFile('hungry_right_image')) {
            $data['hungry_right_image'] = $this->handleImageUpdate($request, $content, 'hungry_right_image', 'website/home/hungry');
        }

        $content->update($data);

        return redirect()->to(route('admin.website.home.index').'#hungry-pane')->with('success', 'Hungry section updated successfully.');
    }

    public function storeSlide(Request $request): RedirectResponse
    {
        $slide = new HomeSlide();
        $data = $this->slideData($request);
        if ($request->hasFile('image')) {
            $data['image'] = $this->handleImageUpload($request, $slide, 'image', 'website/home/slides', 1366, 768);
        }
        $data['is_active'] = $request->boolean('is_active');
        $slide->fill($data)->save();

        return back()->with('success', 'Homepage slide added successfully.');
    }

    public function updateSlide(Request $request, HomeSlide $slide): RedirectResponse
    {
        $data = $this->slideData($request);
        if ($request->hasFile('image')) {
            $data['image'] = $this->handleImageUpdate($request, $slide, 'image', 'website/home/slides', 1366, 768);
        }
        $data['is_active'] = $request->boolean('is_active');
        $slide->update($data);

        return back()->with('success', 'Homepage slide updated successfully.');
    }

    public function destroySlide(HomeSlide $slide): RedirectResponse
    {
        $this->deleteImage($slide->image);
        $slide->delete();

        return back()->with('success', 'Homepage slide deleted successfully.');
    }

    public function updatePromo(Request $request, HomePromoCard $promo): RedirectResponse
    {
        abort_unless(in_array((int) $promo->banner_slot, [1, 2, 3, 4, 5], true), 404);

        $data = $request->validate([
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'link' => ['nullable', 'string', 'max:1000'],
            'sort_order' => ['required', 'integer', 'min:1', 'max:9999'],
        ]);

        if ($request->hasFile('image')) {
            [$width, $height] = match ((int) $promo->banner_slot) {
                1 => [606, 258],
                2 => [857, 258],
                default => [478, 262],
            };

            $data['image'] = $this->handleImageUpdate(
                $request,
                $promo,
                'image',
                'website/home/lower-banners',
                $width,
                $height
            );
        }

        $data['is_active'] = $request->boolean('is_active');
        $promo->update($data);

        return redirect()->to(route('admin.website.home.index').'#banner-pane')->with('success', 'Lower banner updated successfully.');
    }

    private function slideData(Request $request): array
    {
        return $request->validate([
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:6144'],
            'eyebrow_text' => ['nullable', 'string', 'max:255'],
            'title_line_1' => ['required', 'string', 'max:255'],
            'title_line_2' => ['nullable', 'string', 'max:255'],
            'subtext' => ['nullable', 'string', 'max:3000'],
            'button_1_text' => ['nullable', 'string', 'max:100'],
            'button_1_link' => ['nullable', 'string', 'max:500'],
            'button_2_text' => ['nullable', 'string', 'max:100'],
            'button_2_link' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);
    }

    private function deleteImage(?string $path): void
    {
        if ($path && File::exists(base_path($path))) {
            File::delete(base_path($path));
        }
    }
}
