<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteContent extends Model
{
    protected $fillable = ['terms_and_conditions', 'privacy_policy', 'refund_policy', 'delivery_info'];

    public static function current(): self
    {
        return static::query()->firstOrCreate([], [
            'terms_and_conditions' => null,
            'privacy_policy' => null,
            'refund_policy' => null,
            'delivery_info' => null,
        ]);
    }
}
