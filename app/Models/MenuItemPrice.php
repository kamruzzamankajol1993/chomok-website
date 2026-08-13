<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuItemPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_item_id', 'size_label', 'price', 'discount_price', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'discount_price' => 'decimal:2',
        ];
    }

    public function getEffectivePriceAttribute(): float
    {
        $discount = (float) ($this->discount_price ?? 0);
        $price = (float) $this->price;

        return $discount > 0 && $discount < $price ? $discount : $price;
    }

    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class);
    }

    public function variationAddons(): HasMany
    {
        return $this->hasMany(MenuItemPriceAddon::class)->orderBy('sort_order')->orderBy('id');
    }
}
