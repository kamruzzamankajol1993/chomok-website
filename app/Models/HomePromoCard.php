<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomePromoCard extends Model
{
    protected $fillable = [
        'banner_slot', 'image', 'link', 'title', 'description', 'price', 'button_text',
        'color_theme', 'sort_order', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'banner_slot' => 'integer',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }
}
