<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BogoOfferTarget extends Model
{
    protected $fillable = ['bogo_offer_id', 'target_type', 'target_id'];

    public function offer(): BelongsTo
    {
        return $this->belongsTo(BogoOffer::class, 'bogo_offer_id');
    }
}
