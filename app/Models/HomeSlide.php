<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeSlide extends Model
{
    protected $fillable = [
        'image', 'eyebrow_text', 'title_line_1', 'title_line_2', 'subtext',
        'button_1_text', 'button_1_link', 'button_2_text', 'button_2_link',
        'sort_order', 'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'sort_order' => 'integer'];
    }
}
