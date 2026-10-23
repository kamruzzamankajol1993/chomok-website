<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class BogoOffer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'description', 'is_active', 'branch_scope_type', 'eligibility_type', 'cart_rule', 'scope_type',
        'minimum_order_amount', 'max_uses_total', 'max_uses_per_user', 'starts_at', 'ends_at',
        'priority', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'minimum_order_amount' => 'decimal:2',
            'max_uses_total' => 'integer',
            'max_uses_per_user' => 'integer',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'priority' => 'integer',
        ];
    }

    public function branches(): BelongsToMany
    {
        return $this->belongsToMany(Branch::class, 'bogo_offer_branches')->withTimestamps();
    }

    public function targets(): HasMany
    {
        return $this->hasMany(BogoOfferTarget::class);
    }

    public function usages(): HasMany
    {
        return $this->hasMany(PromotionUsage::class, 'promotion_id')
            ->where('promotion_type', 'bogo_offer');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function getBranchScopeLabelAttribute(): string
    {
        return $this->branch_scope_type === 'selected_branches' ? 'Selected branches' : 'All branches';
    }

    public function getEligibilityLabelAttribute(): string
    {
        return $this->eligibility_type === 'first_order' ? 'First order only' : 'Any order during offer';
    }

    public function getCartRuleLabelAttribute(): string
    {
        return $this->cart_rule === 'first_food_only' ? 'First eligible food only' : 'Every eligible food';
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
