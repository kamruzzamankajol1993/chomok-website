<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactQuery extends Model
{
    protected $fillable = ['name', 'email', 'phone', 'subject', 'message', 'status', 'read_at'];

    protected function casts(): array
    {
        return ['read_at' => 'datetime'];
    }
}
