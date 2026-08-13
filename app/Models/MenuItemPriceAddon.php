<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuItemPriceAddon extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_item_price_id', 'name', 'description', 'price', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }

    public function menuItemPrice(): BelongsTo
    {
        return $this->belongsTo(MenuItemPrice::class);
    }

    public function orderItemAddons(): HasMany
    {
        return $this->hasMany(OrderItemAddon::class);
    }
}
