<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'branch_id', 'created_by', 'code', 'name', 'email', 'phone', 'address',
        'delivery_city', 'delivery_postcode', 'notes', 'can_login', 'password', 'status',
    ];

    protected $hidden = ['password'];

    protected function casts(): array
    {
        return [
            'can_login' => 'boolean',
            'password' => 'hashed',
        ];
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function scopeVisibleTo(Builder $query, ?User $user): Builder
    {
        if (! $user || $user->canSeeAllBranches()) {
            return $query;
        }

        return $query->where('branch_id', $user->branch_id);
    }

    public function getTotalSpentAttribute(): float
    {
        return (float) $this->orders()->where('status', 'delivered')->sum('grand_total');
    }
}
