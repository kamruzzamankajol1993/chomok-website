<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutFeatureCard extends Model
{
    protected $fillable = [
        'image', 'title', 'meta_tag_1', 'meta_tag_2', 'meta_tag_3',
        'button_style', 'sort_order', 'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'sort_order' => 'integer'];
    }
}
