<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id', 'subcategory_id', 'name', 'slug', 'description', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(MenuItemImage::class)->orderByDesc('is_main')->orderBy('sort_order');
    }

    public function mainImage(): HasOne
    {
        return $this->hasOne(MenuItemImage::class)->where('is_main', true);
    }

    public function prices(): HasMany
    {
        return $this->hasMany(MenuItemPrice::class)->orderBy('sort_order');
    }

    public function addons(): BelongsToMany
    {
        return $this->belongsToMany(Addon::class, 'addon_menu_item')->withTimestamps();
    }

    public function branches(): BelongsToMany
    {
        return $this->belongsToMany(Branch::class, 'branch_menu_item')->withTimestamps();
    }

    public function getPriceDisplayAttribute(): string
    {
        $prices = $this->relationLoaded('prices') ? $this->prices : $this->prices()->get();

        if ($prices->isEmpty()) {
            return 'TK 0';
        }

        $effectivePrices = $prices->map(fn ($price) => $price->effective_price);
        $minimum = (float) $effectivePrices->min();
        $maximum = (float) $effectivePrices->max();
        $format = static fn (float $value): string => number_format($value, $value == floor($value) ? 0 : 2);

        return $minimum === $maximum
            ? 'TK '.$format($minimum)
            : 'TK '.$format($minimum).' – '.$format($maximum);
    }
}
