<?php

namespace App\Events;

use App\Models\Order;

/**
 * Legacy compatibility event. Website order notifications now use
 * database-backed AJAX polling only and this class is not dispatched.
 */
class WebsiteOrderCreated
{
    public function __construct(public Order $order)
    {
    }
}
