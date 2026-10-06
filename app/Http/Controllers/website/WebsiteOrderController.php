<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WebsiteOrderController extends Controller
{
    public function __construct(private readonly OrderService $orderService)
    {
    }

    public function store(Request $request): JsonResponse
    {
        $configuredKey = (string) config('services.website_order.key', '');
        if ($configuredKey !== '' && ! hash_equals($configuredKey, (string) $request->header('X-Website-Order-Key'))) {
            abort(403, 'Invalid website order key.');
        }

        $data = $request->validate([
            'branch_id' => ['required', 'integer', Rule::exists('branches', 'id')->where(fn ($query) => $query->where('status', 'active')->where('accepting_orders', true))],
            'client_id' => ['nullable', 'integer', 'exists:clients,id'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:50'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'customer_address' => ['nullable', 'string', 'max:3000'],
            'order_type' => ['required', Rule::in(['dine_in', 'delivery'])],
            'delivery_address' => ['required_if:order_type,delivery', 'nullable', 'string', 'max:3000'],
            'delivery_charge' => ['nullable', 'numeric', 'min:0', 'max:9999999.99'],
            'discount' => ['nullable', 'numeric', 'min:0', 'max:9999999.99'],
            'payment_type' => ['required', Rule::in(['cash', 'cash_on_delivery', 'mfs', 'bank', 'split'])],
            'payment_reference' => ['required_if:payment_type,mfs,bank', 'nullable', 'string', 'max:255'],
            'split_cash' => ['required_if:payment_type,split', 'nullable', 'numeric', 'min:0'],
            'split_mfs' => ['required_if:payment_type,split', 'nullable', 'numeric', 'min:0'],
            'split_bank' => ['required_if:payment_type,split', 'nullable', 'numeric', 'min:0'],
            'split_mfs_reference' => ['nullable', 'string', 'max:255'],
            'split_bank_reference' => ['nullable', 'string', 'max:255'],
            'note' => ['nullable', 'string', 'max:5000'],
            'items' => ['required', 'array', 'min:1', 'max:100'],
            'items.*.menu_item_id' => ['required', 'integer', 'exists:menu_items,id'],
            'items.*.menu_item_price_id' => ['required', 'integer', 'exists:menu_item_prices,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:999'],
            'items.*.addon_ids' => ['nullable', 'array'],
            'items.*.addon_ids.*' => ['integer', 'exists:addons,id'],
            'items.*.price_addon_ids' => ['nullable', 'array'],
            'items.*.price_addon_ids.*' => ['integer', 'exists:menu_item_price_addons,id'],
            'items.*.note' => ['nullable', 'string', 'max:1000'],
        ]);

        abort_unless(Branch::query()->whereKey($data['branch_id'])->where('status', 'active')->where('accepting_orders', true)->exists(), 422, 'The selected branch is not accepting orders.');

        $data['status'] = 'pending';
        $order = $this->orderService->save($data, null, 'website');


        return response()->json([
            'message' => 'Order placed successfully.',
            'order_number' => $order->order_number,
            'total' => (float) $order->grand_total,
        ], 201);
    }
}
