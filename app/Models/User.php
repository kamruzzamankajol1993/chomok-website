<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable;

    protected $fillable = [
        'name', 'image', 'phone', 'email', 'password', 'branch_id',
        'all_branch_access', 'status',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'all_branch_access' => 'boolean',
        ];
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function canSeeAllBranches(): bool
    {
        return (bool) $this->all_branch_access || $this->hasRole('Super Admin');
    }

    public function canSeeAllOrders(): bool
    {
        return $this->canSeeAllBranches();
    }

    public function scopeVisibleTo(Builder $query, ?self $viewer): Builder
    {
        if (! $viewer || $viewer->canSeeAllBranches()) {
            return $query;
        }

        return $query->where('branch_id', $viewer->branch_id);
    }
}
