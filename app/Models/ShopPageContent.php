<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopPageContent extends Model
{
    protected $fillable = [
        'meta_title', 'meta_description', 'hero_eyebrow_text', 'hero_title', 'hero_image',
        'intro_badge_text', 'intro_heading', 'intro_text', 'map_link_text',
    ];

    public static function current(): self
    {
        return static::query()->firstOrCreate([], [
            'meta_title' => 'Shop',
            'meta_description' => 'Find Chomok branches, addresses and Google Maps locations.',
            'hero_eyebrow_text' => 'Dine In & Takeaway',
            'hero_title' => 'Visit Our Shops',
            'intro_badge_text' => 'Find Us',
            'intro_heading' => 'A Chomok Near You',
            'intro_text' => 'From our very first home kitchen in Halishahar to storefronts across Chittagong and now Rajshahi, every Chomok location serves the same fresh, home-style food we started with. Drop by, place a takeaway order, or just come say hello.',
            'map_link_text' => 'View on Google Maps',
        ]);
    }
}
