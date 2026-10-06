<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Order;
use App\Services\WebPushService;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


// Server-side website order push notification
// Runs without requiring the admin panel tab to stay open.
Schedule::call(function () {
    Order::query()
        ->where('source', 'website')
        ->whereNull('push_sent_at')
        ->orderBy('id')
        ->limit(20)
        ->get()
        ->each(function (Order $order) {
            try {
                app(WebPushService::class)->sendNewOrder($order);
                $order->update(['push_sent_at' => now()]);
            } catch (\Throwable $e) {
                report($e);
            }
        });
})->everyMinute();
