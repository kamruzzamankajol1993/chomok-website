<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Client;
use App\Models\Order;
use App\Models\Setting;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $cart = $request->session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('menu.index')->with('error', 'Your cart is empty.');
        }

        $client = Auth::guard('client')->user();
        $branches = Branch::query()->where('status', 'active')->where('accepting_orders', true)->orderBy('name')->get();
        $setting = Setting::current();
        $summary = $this->summary($cart, $setting, 'within_1km', old('order_type', 'delivery'));
        $deliveryConfig = $this->deliveryConfig($setting);
        $firstOrderOfferEligible = ! Order::withTrashed()
            ->where('client_id', $client->id)
            ->where('source', 'website')
            ->exists();

        return view('website.checkout.checkout', compact(
            'cart', 'client', 'branches', 'summary', 'deliveryConfig', 'firstOrderOfferEligible'
        ));
    }

    public function processCheckout(Request $request, OrderService $orders): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('menu.index')->with('error', 'Your cart is empty.');
        }

        $data = $request->validate([
            'phone' => ['required', 'string', 'max:50'],
            'order_type' => ['required', Rule::in(['delivery', 'takeaway', 'pickup'])],
            'address' => ['required_if:order_type,delivery', 'nullable', 'string', 'max:2000'],
            'branch_id' => ['required', 'integer', Rule::exists('branches', 'id')->where(fn ($q) => $q->where('status', 'active')->where('accepting_orders', 1)->whereNull('deleted_at'))],
        ]);

        $paymentType = 'cash_on_delivery';
        $setting = Setting::current();
        $deliveryConfig = $this->deliveryConfig($setting);
        $deliveryCharge = $data['order_type'] === 'delivery' && $deliveryConfig['enabled'] ? $deliveryConfig['within'] : 0.0;

        // Lock the client row while checking/creating the first website order.
        // This prevents two near-simultaneous checkout requests from both receiving
        // the one-time Buy 1 Get 1 Free offer.
        $order = DB::transaction(function () use ($request, $orders, $cart, $data, $paymentType, $deliveryCharge): Order {
            $client = Client::query()
                ->whereKey(Auth::guard('client')->id())
                ->lockForUpdate()
                ->firstOrFail();

            $firstOrderOfferEligible = ! Order::withTrashed()
                ->where('client_id', $client->id)
                ->where('source', 'website')
                ->exists();

            $items = collect($cart)->flatMap(function ($row) use ($firstOrderOfferEligible): array {
                $quantity = max((int) ($row['quantity'] ?? 1), 1);
                $paidItem = [
                    'menu_item_id' => (int) $row['menu_item_id'],
                    'menu_item_price_id' => (int) $row['menu_item_price_id'],
                    'quantity' => $quantity,
                    'addon_ids' => array_values($row['addon_ids'] ?? []),
                    'price_addon_ids' => array_values($row['price_addon_ids'] ?? []),
                    'note' => null,
                ];

                if (! $firstOrderOfferEligible) {
                    return [$paidItem];
                }

                // The free BOGO line is a true second order item with exactly the same
                // food, size/variation, quantity and selected add-ons/options. OrderService
                // recognises the BOGO FREE marker and stores this copied line at zero price.
                $freeItem = $paidItem;
                $freeItem['note'] = 'BOGO FREE: First Order Buy 1 Get 1 Free — identical food, variation/size, add-ons and options.';

                return [$paidItem, $freeItem];
            })->values()->all();

            $payload = [
                'branch_id' => (int) $data['branch_id'],
                'client_id' => $client->id,
                'customer_name' => $client->name,
                'customer_phone' => trim($data['phone']),
                'customer_email' => $client->email,
                'customer_address' => $data['order_type'] === 'delivery' ? trim($data['address']) : null,
                'order_type' => $data['order_type'],
                'delivery_address' => $data['order_type'] === 'delivery' ? trim($data['address']) : null,
                'delivery_charge' => $deliveryCharge,
                'delivery_charge_option' => null,
                'discount_type' => 'fixed',
                'discount_value' => 0,
                'payment_type' => $paymentType,
                'payment_reference' => null,
                'paid_amount' => 0,
                'status' => 'pending',
                'note' => $firstOrderOfferEligible
                    ? 'FIRST ORDER OFFER APPLIED: Buy 1 Get 1 Free on each ordered food. The free copy is identical to the paid item, including the same variation/size, selected add-ons and options.'
                    : null,
                'items' => $items,
            ];

            return $orders->save($payload, null, 'website');
        });

        $request->session()->forget('cart');
        $request->session()->put('last_website_order_id', $order->id);

        return redirect()->route('checkout.success');
    }

    public function checkoutSuccess(Request $request): View|RedirectResponse
    {
        $id = $request->session()->get('last_website_order_id');
        if (! $id) {
            return redirect()->route('home.index');
        }
        $client = Auth::guard('client')->user();
        $order = Order::query()->where('client_id', $client->id)->with('branch')->findOrFail($id);

        return view('website.checkout.success', compact('order'));
    }

    private function deliveryConfig(Setting $setting): array
    {
        // Website delivery information is fixed for now. The customer does not
        // choose a distance option at checkout; the order starts with the
        // within-1-km charge and the admin can adjust the final delivery fee.
        return [
            'enabled' => (bool) ($setting->delivery_charge_enabled ?? true),
            'within' => 60.0,
            'outside_base' => 60.0,
            'per_km' => 10.0,
        ];
    }

    private function summary(array $cart, Setting $setting, string $deliveryOption, string $orderType = 'delivery'): array
    {
        $subtotal = round(collect($cart)->sum(fn ($row) => (float) ($row['line_total'] ?? 0)), 2);
        $taxRate = max((float) $setting->tax_rate, 0);
        $tax = $taxRate > 0 ? (float) ceil($subtotal * ($taxRate / 100)) : 0.0;
        $deliveryConfig = $this->deliveryConfig($setting);
        $delivery = $deliveryConfig['enabled'] && $orderType === 'delivery'
            ? ($deliveryOption === 'outside_1km' ? $deliveryConfig['outside_base'] : $deliveryConfig['within'])
            : 0.0;
        $beforeDelivery = round($subtotal + $tax, 2);
        $grand = (float) ceil(round($beforeDelivery + $delivery, 2));

        return [
            'subtotal' => $subtotal,
            'tax' => $tax,
            'delivery' => $delivery,
            'before_delivery' => $beforeDelivery,
            'grand' => $grand,
            'tax_label' => $setting->tax_label ?: 'VAT',
            'tax_rate' => $taxRate,
        ];
    }
}
