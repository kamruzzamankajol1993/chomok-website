<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromotionSetting extends Model
{
    protected $fillable = ['bogo_enabled', 'coupons_enabled', 'updated_by'];

    protected function casts(): array
    {
        return [
            'bogo_enabled' => 'boolean',
            'coupons_enabled' => 'boolean',
        ];
    }

    public static function current(): self
    {
        return static::query()->firstOrCreate(
            ['id' => 1],
            ['bogo_enabled' => true, 'coupons_enabled' => false]
        );
    }
}
