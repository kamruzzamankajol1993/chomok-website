<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'menu_item_id', 'menu_item_price_id', 'item_name', 'size_label',
        'unit_price', 'quantity', 'addon_total', 'line_total', 'note',
    ];

    protected function casts(): array
    {
        return [
            'unit_price' => 'decimal:2',
            'addon_total' => 'decimal:2',
            'line_total' => 'decimal:2',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class);
    }

    public function price(): BelongsTo
    {
        return $this->belongsTo(MenuItemPrice::class, 'menu_item_price_id');
    }

    public function addons(): HasMany
    {
        return $this->hasMany(OrderItemAddon::class);
    }
}
