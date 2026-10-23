<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code', 'description', 'is_active', 'branch_scope_type', 'discount_type', 'discount_value', 'maximum_discount',
        'minimum_order_amount', 'scope_type', 'max_uses_total', 'max_uses_per_user', 'starts_at',
        'ends_at', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'discount_value' => 'decimal:2',
            'maximum_discount' => 'decimal:2',
            'minimum_order_amount' => 'decimal:2',
            'max_uses_total' => 'integer',
            'max_uses_per_user' => 'integer',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    public function branches(): BelongsToMany
    {
        return $this->belongsToMany(Branch::class, 'coupon_branches')->withTimestamps();
    }

    public function targets(): HasMany
    {
        return $this->hasMany(CouponTarget::class);
    }

    public function usages(): HasMany
    {
        return $this->hasMany(PromotionUsage::class, 'promotion_id')
            ->where('promotion_type', 'coupon');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function getBranchScopeLabelAttribute(): string
    {
        return $this->branch_scope_type === 'selected_branches' ? 'Selected branches' : 'All branches';
    }

    public function getDiscountLabelAttribute(): string
    {
        return $this->discount_type === 'percentage'
            ? rtrim(rtrim(number_format((float) $this->discount_value, 2), '0'), '.').'%'
            : '৳'.number_format((float) $this->discount_value, 2);
    }

    public function getScopeLabelAttribute(): string
    {
        return match ($this->scope_type) {
            'categories' => 'Selected categories',
            'subcategories' => 'Selected subcategories',
            'menu_items' => 'Selected foods',
            default => 'Full menu',
        };
    }
}
