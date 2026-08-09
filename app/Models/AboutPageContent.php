<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutPageContent extends Model
{
    protected $fillable = [
        'meta_title', 'meta_description',
        // Current website About page fields.
        'story_heading_prefix', 'story_heading_suffix', 'story_heading_image', 'story_left_image',
        'story_index', 'story_button_text', 'story_button_link', 'story_description',
        'story_avatar_1', 'story_avatar_2', 'story_avatar_3', 'story_trust_label', 'story_right_image',
        'mission_label', 'mission_text', 'mission_main_image', 'mission_secondary_image_1', 'mission_secondary_image_2',
        'vision_label', 'vision_text',
        'services_heading_line_1', 'services_heading_line_2', 'services_description',
        'services_button_text', 'services_button_link',
        'service_1_title', 'service_1_text', 'service_2_title', 'service_2_text',
        'service_3_title', 'service_3_text', 'service_4_title', 'service_4_text',
        'reviews_eyebrow', 'reviews_heading_line_1', 'reviews_heading_line_2', 'reviews_subtext', 'reviews_summary',
        'review_1_rating', 'review_1_quote', 'review_1_initial', 'review_1_name', 'review_1_role',
        'review_2_rating', 'review_2_quote', 'review_2_initial', 'review_2_name', 'review_2_role',
        'review_3_rating', 'review_3_quote', 'review_3_initial', 'review_3_name', 'review_3_role',

        // Legacy fields retained for backward compatibility with older integrations.
        'hero_title_line_1', 'hero_title_line_2', 'hero_button_text', 'hero_button_link', 'hero_image',
        'seat_heading', 'seat_list_item_1', 'seat_list_item_2', 'seat_list_item_3',
        'seat_button_text', 'seat_est_year', 'seat_image', 'support_heading',
        'diet_band_text', 'video_heading', 'video_link', 'video_image',
        'work_badge_text', 'work_heading',
        'step_1_icon', 'step_1_title', 'step_2_icon', 'step_2_title',
        'step_3_icon', 'step_3_title', 'step_4_icon', 'step_4_title',
        'testimonial_quote', 'testimonial_author', 'cta_heading', 'cta_button_text',
    ];

    protected function casts(): array
    {
        return [
            'review_1_rating' => 'integer',
            'review_2_rating' => 'integer',
            'review_3_rating' => 'integer',
        ];
    }

    public static function current(): self
    {
        return static::query()->firstOrCreate([], static::websiteDefaults());
    }

    public static function websiteDefaults(): array
    {
        return [
            'meta_title' => 'About',
            'meta_description' => 'Learn about Chomok, our story, mission, vision, service and customer experience.',
            'story_heading_prefix' => 'Homemade',
            'story_heading_suffix' => "And\nHearty Feasts",
            'story_heading_image' => 'public/uploads/website/about/defaults/naga-pasta.png',
            'story_left_image' => 'public/uploads/website/about/defaults/beef-maximus.png',
            'story_index' => '01',
            'story_button_text' => 'View Menu',
            'story_button_link' => 'menu.php',
            'story_description' => "Chomok started its journey on 27th October, 2017 in Chattogram, Bangladesh — when college student Mehedi opened his first online-based restaurant, delivering delicious pizzas, burgers and much more straight from his mother's kitchen.",
            'story_avatar_1' => 'M',
            'story_avatar_2' => 'F',
            'story_avatar_3' => 'T',
            'story_trust_label' => '10K+ Trusted Families',
            'story_right_image' => 'public/uploads/website/about/defaults/chatgaiya-burger.png',

            'mission_label' => 'Mission',
            'mission_text' => 'Our mission is simple — serve honest, home-style pizzas, burgers and comfort food that taste like they came straight from your own kitchen. We use fresh ingredients, keep our recipes true to real Bangladeshi flavors, and make sure every order reaches you fast, hot and full of care.',
            'mission_main_image' => 'public/uploads/website/about/defaults/interior-main.jpg',
            'mission_secondary_image_1' => 'public/uploads/website/about/defaults/interior-lights.jpg',
            'mission_secondary_image_2' => 'public/uploads/website/about/defaults/interior-window.jpg',
            'vision_label' => 'Vision',
            'vision_text' => "We're growing from a single home kitchen into a name every neighborhood in Bangladesh knows and trusts — one honest meal at a time.",

            'services_heading_line_1' => 'We Serve More Than',
            'services_heading_line_2' => 'Just Great Food',
            'services_description' => 'Chomok is a home-style kitchen delivering pizzas, burgers and comfort food across Chattogram — made fresh, delivered fast, and crafted with real care in every order.',
            'services_button_text' => 'Order Now',
            'services_button_link' => 'menu.php',
            'service_1_title' => 'Online Order',
            'service_1_text' => 'Order in a few taps through our website — no calls needed.',
            'service_2_title' => 'Free Delivery',
            'service_2_text' => 'Free delivery across Chattogram on every online order.',
            'service_3_title' => 'Easy To Order',
            'service_3_text' => 'Browse the menu, pick your favorites, and checkout in seconds.',
            'service_4_title' => 'Multiple Outlets',
            'service_4_text' => '5 branches across Chattogram & Rajshahi, always close to you.',

            'reviews_eyebrow' => 'Testimonials',
            'reviews_heading_line_1' => 'What Our',
            'reviews_heading_line_2' => 'Customers Say',
            'reviews_subtext' => 'Real feedback from real families across Chattogram & Rajshahi who order from Chomok every week.',
            'reviews_summary' => '4.9 average from 500+ orders',
            'review_1_rating' => 5,
            'review_1_quote' => "Chomok has been our family's go-to for pizza night since they opened their first outlet. You can genuinely taste the home-style care in every bite.",
            'review_1_initial' => 'F',
            'review_1_name' => 'Farhana Akter',
            'review_1_role' => 'Regular Customer, Chattogram',
            'review_2_rating' => 5,
            'review_2_quote' => 'Fast delivery, hot food, and the Chatgaiya Burger is unlike anything else in the city. Ordering through their website takes seconds.',
            'review_2_initial' => 'T',
            'review_2_name' => 'Tanvir Ahmed',
            'review_2_role' => 'Regular Customer, GEC Circle',
            'review_3_rating' => 5,
            'review_3_quote' => 'What started as a small home kitchen now feels like a proper neighborhood favorite. Consistent quality every single time.',
            'review_3_initial' => 'N',
            'review_3_name' => 'Nusrat Jahan',
            'review_3_role' => 'Regular Customer, Panchlaish',
        ];
    }
}
