<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageContent extends Model
{
    protected $fillable = [
        'about_badge_text', 'about_heading_line_1', 'about_heading_line_2',
        'about_pill_image', 'about_circle_image', 'about_paragraph_text',
        'about_button_text', 'about_trust_badge',
        'hungry_left_image', 'hungry_right_image', 'hungry_line_one',
        'hungry_line_two', 'hungry_subtext', 'hungry_button_text',
    ];

    public static function current(): self
    {
        return static::query()->firstOrCreate([], [
            'about_badge_text' => 'Taste Of World Class Food',
            'about_heading_line_1' => 'Experience',
            'about_heading_line_2' => 'Culinary Excellence',
            'about_paragraph_text' => 'A Good Restaurant Is Like A Vacation, It Transports You, And It Becomes A Lot More Than Just About The Food',
            'about_button_text' => 'Our Delicious Item',
            'about_trust_badge' => '10K+ Trusted by Families',
        ]);
    }
}
