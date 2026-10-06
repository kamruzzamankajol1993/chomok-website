<?php

namespace App\Models\Concerns;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

trait BranchScoped
{
    public function scopeVisibleTo(Builder $query, ?User $viewer): Builder
    {
        if (! $viewer || $viewer->canSeeAllBranches()) {
            return $query;
        }

        return $query->where($query->qualifyColumn('branch_id'), $viewer->branch_id);
    }
}
