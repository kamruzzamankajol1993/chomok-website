<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\AboutFeatureCard;
use App\Models\AboutPageContent;
use App\Models\ContactPageContent;
use App\Models\HomepageContent;
use App\Models\HomePromoCard;
use App\Models\HomeSlide;
use Illuminate\Http\JsonResponse;

class WebsiteContentController extends Controller
{
    public function home(): JsonResponse
    {
        $contentModel = HomepageContent::current();
        $content = $contentModel->toArray();
        $content['about_pill_image_url'] = $this->assetUrl($content['about_pill_image'] ?? null);
        $content['about_circle_image_url'] = $this->assetUrl($content['about_circle_image'] ?? null);
        $content['hungry_left_image_url'] = $this->assetUrl($content['hungry_left_image'] ?? null);
        $content['hungry_right_image_url'] = $this->assetUrl($content['hungry_right_image'] ?? null);

        $slides = HomeSlide::query()->where('is_active', true)->orderBy('sort_order')->get()
            ->map(fn (HomeSlide $slide): array => $slide->toArray() + ['image_url' => $this->assetUrl($slide->image)]);

        $lowerBanners = HomePromoCard::query()
            ->whereBetween('banner_slot', [1, 5])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('banner_slot')
            ->get()
            ->map(function (HomePromoCard $banner): array {
                [$width, $height] = match ((int) $banner->banner_slot) {
                    1 => [606, 258],
                    2 => [857, 258],
                    default => [478, 262],
                };

                return $banner->toArray() + [
                    'image_url' => $this->assetUrl($banner->image),
                    'layout_row' => $banner->banner_slot <= 2 ? 1 : 2,
                    'expected_width' => $width,
                    'expected_height' => $height,
                ];
            });

        $hungrySection = [
            'left_image' => $contentModel->hungry_left_image,
            'left_image_url' => $this->assetUrl($contentModel->hungry_left_image),
            'right_image' => $contentModel->hungry_right_image,
            'right_image_url' => $this->assetUrl($contentModel->hungry_right_image),
            'line_one' => $contentModel->hungry_line_one,
            'line_two' => $contentModel->hungry_line_two,
            'subtext' => $contentModel->hungry_subtext,
            'button_text' => $contentModel->hungry_button_text,
        ];

        return response()->json([
            'content' => $content,
            'slides' => $slides,
            // Keep the old key for existing frontend compatibility.
            'promo_cards' => $lowerBanners,
            'lower_banners' => $lowerBanners,
            'hungry_section' => $hungrySection,
        ]);
    }

    public function about(): JsonResponse
    {
        $content = AboutPageContent::current()->toArray();
        foreach ([
            'hero_image', 'seat_image', 'video_image',
            'story_heading_image', 'story_left_image', 'story_right_image',
            'mission_main_image', 'mission_secondary_image_1', 'mission_secondary_image_2',
        ] as $field) {
            $content[$field.'_url'] = $this->assetUrl($content[$field] ?? null);
        }

        $cards = AboutFeatureCard::query()->where('is_active', true)->orderBy('sort_order')->get()
            ->map(fn (AboutFeatureCard $card): array => $card->toArray() + ['image_url' => $this->assetUrl($card->image)]);

        return response()->json(['content' => $content, 'feature_cards' => $cards]);
    }

    public function contact(): JsonResponse
    {
        $content = ContactPageContent::current()->toArray();
        $content['hero_image_url'] = $this->assetUrl($content['hero_image'] ?? null);

        return response()->json(['content' => $content]);
    }

    private function assetUrl(?string $path): ?string
    {
        return $path ? asset($path) : null;
    }
}
