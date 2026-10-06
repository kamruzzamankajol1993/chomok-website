<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutFeatureCard;
use App\Models\AboutPageContent;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class AboutPageContentController extends Controller
{
    use ImageUploadTrait;

    public function edit(): View
    {
        return view('admin.website.about.edit', [
            'content' => AboutPageContent::current(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $content = AboutPageContent::current();
        $data = $request->validate([
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:1000'],
            'story_heading_prefix' => ['nullable', 'string', 'max:255'],
            'story_heading_suffix' => ['nullable', 'string', 'max:255'],
            'story_heading_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:6144'],
            'story_left_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:6144'],
            'story_index' => ['nullable', 'string', 'max:20'],
            'story_button_text' => ['nullable', 'string', 'max:100'],
            'story_button_link' => ['nullable', 'string', 'max:500'],
            'story_description' => ['nullable', 'string', 'max:5000'],
            'story_avatar_1' => ['nullable', 'string', 'max:5'],
            'story_avatar_2' => ['nullable', 'string', 'max:5'],
            'story_avatar_3' => ['nullable', 'string', 'max:5'],
            'story_trust_label' => ['nullable', 'string', 'max:255'],
            'story_right_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:6144'],

            'mission_label' => ['nullable', 'string', 'max:100'],
            'mission_text' => ['nullable', 'string', 'max:5000'],
            'mission_main_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:6144'],
            'mission_secondary_image_1' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:6144'],
            'mission_secondary_image_2' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:6144'],
            'vision_label' => ['nullable', 'string', 'max:100'],
            'vision_text' => ['nullable', 'string', 'max:5000'],

            'services_heading_line_1' => ['nullable', 'string', 'max:255'],
            'services_heading_line_2' => ['nullable', 'string', 'max:255'],
            'services_description' => ['nullable', 'string', 'max:5000'],
            'services_button_text' => ['nullable', 'string', 'max:100'],
            'services_button_link' => ['nullable', 'string', 'max:500'],
            'service_1_title' => ['nullable', 'string', 'max:255'], 'service_1_text' => ['nullable', 'string', 'max:1000'],
            'service_2_title' => ['nullable', 'string', 'max:255'], 'service_2_text' => ['nullable', 'string', 'max:1000'],
            'service_3_title' => ['nullable', 'string', 'max:255'], 'service_3_text' => ['nullable', 'string', 'max:1000'],
            'service_4_title' => ['nullable', 'string', 'max:255'], 'service_4_text' => ['nullable', 'string', 'max:1000'],

            'reviews_eyebrow' => ['nullable', 'string', 'max:100'],
            'reviews_heading_line_1' => ['nullable', 'string', 'max:255'],
            'reviews_heading_line_2' => ['nullable', 'string', 'max:255'],
            'reviews_subtext' => ['nullable', 'string', 'max:3000'],
            'reviews_summary' => ['nullable', 'string', 'max:255'],
            'review_1_rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'review_1_quote' => ['nullable', 'string', 'max:3000'],
            'review_1_initial' => ['nullable', 'string', 'max:5'],
            'review_1_name' => ['nullable', 'string', 'max:255'],
            'review_1_role' => ['nullable', 'string', 'max:255'],
            'review_2_rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'review_2_quote' => ['nullable', 'string', 'max:3000'],
            'review_2_initial' => ['nullable', 'string', 'max:5'],
            'review_2_name' => ['nullable', 'string', 'max:255'],
            'review_2_role' => ['nullable', 'string', 'max:255'],
            'review_3_rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'review_3_quote' => ['nullable', 'string', 'max:3000'],
            'review_3_initial' => ['nullable', 'string', 'max:5'],
            'review_3_name' => ['nullable', 'string', 'max:255'],
            'review_3_role' => ['nullable', 'string', 'max:255'],
        ]);

        $imageFields = [
            'story_heading_image', 'story_left_image', 'story_right_image',
            'mission_main_image', 'mission_secondary_image_1', 'mission_secondary_image_2',
        ];

        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $this->handleImageUpdate($request, $content, $field, 'website/about');
            } else {
                unset($data[$field]);
            }
        }

        $content->update($data);

        return back()->with('success', 'About page content updated successfully.');
    }

    // Legacy feature-card actions are kept so older integrations/routes do not break.
    public function storeFeature(Request $request): RedirectResponse
    {
        $card = new AboutFeatureCard();
        $data = $this->featureData($request);
        if ($request->hasFile('image')) {
            $data['image'] = $this->handleImageUpload($request, $card, 'image', 'website/about/cards', 900, 900);
        }
        $data['is_active'] = $request->boolean('is_active');
        $card->fill($data)->save();

        return back()->with('success', 'Feature card added successfully.');
    }

    public function updateFeature(Request $request, AboutFeatureCard $feature): RedirectResponse
    {
        $data = $this->featureData($request);
        if ($request->hasFile('image')) {
            $data['image'] = $this->handleImageUpdate($request, $feature, 'image', 'website/about/cards', 900, 900);
        }
        $data['is_active'] = $request->boolean('is_active');
        $feature->update($data);

        return back()->with('success', 'Feature card updated successfully.');
    }

    public function destroyFeature(AboutFeatureCard $feature): RedirectResponse
    {
        if ($feature->image && File::exists(base_path($feature->image))) {
            File::delete(base_path($feature->image));
        }
        $feature->delete();

        return back()->with('success', 'Feature card deleted successfully.');
    }

    private function featureData(Request $request): array
    {
        return $request->validate([
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'title' => ['required', 'string', 'max:255'],
            'meta_tag_1' => ['nullable', 'string', 'max:100'],
            'meta_tag_2' => ['nullable', 'string', 'max:100'],
            'meta_tag_3' => ['nullable', 'string', 'max:100'],
            'button_style' => ['required', 'in:dark,light'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);
    }
}
