<?php

namespace App\Services;

use App\Models\PushSubscription;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;

class WebPushService
{
    public function sendNewOrder($order): void
    {
        $webPush = new WebPush([
            'VAPID' => [
                'subject' => env('VAPID_SUBJECT'),
                'publicKey' => env('VAPID_PUBLIC_KEY'),
                'privateKey' => env('VAPID_PRIVATE_KEY'),
            ],
        ]);

        $payload = json_encode([
            'title' => 'New Order Received',
            'body' => 'Order #'.$order->order_number.' received',
            'url' => route('admin.orders.show', $order),
        ]);

        PushSubscription::query()->each(function ($item) use ($webPush, $payload) {
            $webPush->queueNotification(
                Subscription::create([
                    'endpoint' => $item->endpoint,
                    'publicKey' => $item->public_key,
                    'authToken' => $item->auth_token,
                ]),
                $payload
            );
        });

        foreach ($webPush->flush() as $report) {
        }
    }
}
