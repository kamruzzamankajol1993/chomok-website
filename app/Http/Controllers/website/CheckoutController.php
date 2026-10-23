<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Client;
use App\Models\Order;
use App\Models\PromotionUsage;
use App\Models\Setting;
use App\Services\BranchOrderHours;
use App\Services\OrderService;
use App\Services\PromotionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function index(Request $request, PromotionService $promotions): View|RedirectResponse
    {
        $sessionCart = $request->session()->get('cart', []);
        if (empty($sessionCart)) {
            return redirect()->route('menu.index')->with('error', 'Your cart is empty.');
        }

        $client = Auth::guard('client')->user();
        $branches = Branch::query()->where('status', 'active')->where('accepting_orders', true)->orderBy('name')->get();
        $setting = Setting::current();
        $selectedBranchId = old('branch_id', $request->session()->get('checkout_branch_id'));
        $selectedBranchId = is_numeric($selectedBranchId) ? (int) $selectedBranchId : null;
        $couponCode = $request->session()->get('checkout_coupon_code');

        try {
            $promotionQuote = $promotions->quote($client, $sessionCart, $couponCode, false, $selectedBranchId);
        } catch (ValidationException $exception) {
            $request->session()->forget('checkout_coupon_code');
            return redirect()->route('checkout.index')->withErrors($exception->errors());
        }

        $cart = $promotionQuote['cart_lines'];
        $summary = $this->summary(
            $cart,
            $setting,
            'within_1km',
            old('order_type', 'delivery'),
            (float) $promotionQuote['coupon_discount'],
            (float) $promotionQuote['cart_subtotal']
        );
        $deliveryConfig = $this->deliveryConfig($setting);

        return view('website.checkout.checkout', compact(
            'cart', 'client', 'branches', 'summary', 'deliveryConfig', 'promotionQuote', 'couponCode', 'selectedBranchId'
        ));
    }

    public function applyCoupon(Request $request, PromotionService $promotions): RedirectResponse
    {
        $data = $request->validate([
            'coupon_code' => ['required', 'string', 'max:80'],
            'branch_id' => ['required', 'integer', Rule::exists('branches', 'id')->where(fn ($query) => $query->where('status', 'active')->where('accepting_orders', true)->whereNull('deleted_at'))],
        ]);
        $cart = $request->session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('menu.index')->with('error', 'Your cart is empty.');
        }

        $client = Auth::guard('client')->user();
        $code = strtoupper(trim((string) $data['coupon_code']));
        $branchId = (int) $data['branch_id'];
        // Validate before replacing a currently applied code.
        $quote = $promotions->quote($client, $cart, $code, false, $branchId);
        $request->session()->put('checkout_coupon_code', $quote['coupon']->code);
        $request->session()->put('checkout_branch_id', $branchId);

        return redirect()->route('checkout.index')->with(
            'success',
            'Coupon '.$quote['coupon']->code.' applied. BOGO will not be applied while this coupon is active.'
        );
    }

    public function removeCoupon(Request $request): RedirectResponse
    {
        $request->session()->forget('checkout_coupon_code');
        return redirect()->route('checkout.index')->with('success', 'Coupon removed. Any eligible BOGO offer can now apply.');
    }

    public function processCheckout(
        Request $request,
        OrderService $orders,
        BranchOrderHours $hours,
        PromotionService $promotions
    ): RedirectResponse {
        $cart = $request->session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('menu.index')->with('error', 'Your cart is empty.');
        }

        $data = $request->validate([
            'phone' => ['required', 'string', 'max:50'],
            'order_type' => ['required', Rule::in(['delivery', 'takeaway', 'pickup'])],
            'address' => ['required_if:order_type,delivery', 'nullable', 'string', 'max:2000'],
            'branch_id' => ['required', 'integer', Rule::exists('branches', 'id')->where(fn ($query) => $query->where('status', 'active')->where('accepting_orders', 1)->whereNull('deleted_at'))],
        ]);

        $branchId = (int) $data['branch_id'];
        $request->session()->put('checkout_branch_id', $branchId);
        $paymentType = 'cash_on_delivery';
        $setting = Setting::current();
        $deliveryConfig = $this->deliveryConfig($setting);
        $deliveryCharge = $data['order_type'] === 'delivery' && $deliveryConfig['enabled'] ? $deliveryConfig['within'] : 0.0;
        $couponCode = $request->session()->get('checkout_coupon_code');

        $order = DB::transaction(function () use (
            $request, $orders, $hours, $promotions, $cart, $data, $paymentType, $deliveryCharge, $couponCode, $branchId
        ): Order {
            $branch = Branch::query()->whereKey($branchId)->lockForUpdate()->firstOrFail();
            $availability = $hours->status($branch);
            if (! $availability['can_order']) {
                throw ValidationException::withMessages(['branch_id' => $availability['message']]);
            }

            $client = Client::query()->whereKey(Auth::guard('client')->id())->lockForUpdate()->firstOrFail();
            $quote = $promotions->quote($client, $cart, $couponCode, true, $branchId);
            $freeCartKeys = $quote['free_cart_keys'];
            $offer = $quote['bogo_offer'];
            $coupon = $quote['coupon'];

            $items = [];
            foreach ($cart as $cartKey => $row) {
                $paidItem = [
                    'menu_item_id' => (int) $row['menu_item_id'],
                    'menu_item_price_id' => (int) $row['menu_item_price_id'],
                    'quantity' => max(1, (int) ($row['quantity'] ?? 1)),
                    'addon_ids' => array_values($row['addon_ids'] ?? []),
                    'price_addon_ids' => array_values($row['price_addon_ids'] ?? []),
                    'note' => null,
                ];
                $items[] = $paidItem;

                if ($offer && in_array((string) $cartKey, $freeCartKeys, true)) {
                    $freeItem = $paidItem;
                    $freeItem['quantity'] = 1;
                    $freeItem['note'] = 'BOGO FREE: Offer #'.$offer->id.' '.$offer->name.'; one free matching unit for this eligible cart food.';
                    $items[] = $freeItem;
                }
            }

            $orderNote = null;
            if ($coupon) {
                $orderNote = 'COUPON APPLIED: '.$coupon->code.'. Discount amount: ৳'.number_format((float) $quote['coupon_discount'], 2).'.';
            } elseif ($offer) {
                $orderNote = 'BOGO OFFER APPLIED: '.$offer->name.'. Free units: '.(int) $quote['free_quantity'].'.';
            }

            $payload = [
                'branch_id' => $branchId,
                'client_id' => $client->id,
                'customer_name' => $client->name,
                'customer_phone' => trim($data['phone']),
                'customer_email' => $client->email,
                'customer_address' => $data['order_type'] === 'delivery' ? trim((string) $data['address']) : null,
                'order_type' => $data['order_type'],
                'delivery_address' => $data['order_type'] === 'delivery' ? trim((string) $data['address']) : null,
                'delivery_charge' => $deliveryCharge,
                'delivery_charge_option' => null,
                'discount_type' => 'fixed',
                'discount_value' => $coupon ? (float) $quote['coupon_discount'] : 0,
                'discount_amount_override' => $coupon ? (float) $quote['coupon_discount'] : null,
                'payment_type' => $paymentType,
                'payment_reference' => null,
                'paid_amount' => 0,
                'status' => 'pending',
                'note' => $orderNote,
                'items' => $items,
            ];

            $order = $orders->save($payload, null, 'website');

            if ($coupon) {
                PromotionUsage::query()->create([
                    'promotion_type' => 'coupon',
                    'promotion_id' => $coupon->id,
                    'order_id' => $order->id,
                    'client_id' => $client->id,
                    'client_key' => $promotions->clientKey($client),
                    'discount_amount' => (float) $quote['coupon_discount'],
                    'free_quantity' => 0,
                    'metadata' => [
                        'coupon_code' => $coupon->code,
                        'coupon_discount_type' => $coupon->discount_type,
                        'coupon_discount_value' => (float) $coupon->discount_value,
                        'eligible_subtotal' => (float) $quote['coupon_eligible_subtotal'],
                        'branch_id' => $branchId,
                    ],
                ]);
            } elseif ($offer) {
                PromotionUsage::query()->create([
                    'promotion_type' => 'bogo_offer',
                    'promotion_id' => $offer->id,
                    'order_id' => $order->id,
                    'client_id' => $client->id,
                    'client_key' => $promotions->clientKey($client),
                    'discount_amount' => 0,
                    'free_quantity' => (int) $quote['free_quantity'],
                    'metadata' => [
                        'offer_name' => $offer->name,
                        'free_cart_keys' => $freeCartKeys,
                        'branch_id' => $branchId,
                    ],
                ]);
            }

            return $order;
        });

        $request->session()->forget(['cart', 'checkout_coupon_code', 'checkout_branch_id']);
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
        return [
            'enabled' => (bool) ($setting->delivery_charge_enabled ?? true),
            'within' => 60.0,
            'outside_base' => 60.0,
            'per_km' => 10.0,
        ];
    }

    private function summary(
        array $cart,
        Setting $setting,
        string $deliveryOption,
        string $orderType = 'delivery',
        float $discount = 0.0,
        ?float $subtotalOverride = null
    ): array {
        $subtotal = $subtotalOverride !== null
            ? max(round($subtotalOverride, 2), 0)
            : round(collect($cart)->sum(fn ($row) => (float) ($row['line_total'] ?? 0)), 2);
        $discount = min(max(round($discount, 2), 0), $subtotal);
        $netSubtotal = max(round($subtotal - $discount, 2), 0);
        $taxRate = max((float) $setting->tax_rate, 0);
        $tax = $taxRate > 0 ? (float) ceil($netSubtotal * ($taxRate / 100)) : 0.0;
        $deliveryConfig = $this->deliveryConfig($setting);
        $delivery = $deliveryConfig['enabled'] && $orderType === 'delivery'
            ? ($deliveryOption === 'outside_1km' ? $deliveryConfig['outside_base'] : $deliveryConfig['within'])
            : 0.0;
        $beforeDelivery = round($netSubtotal + $tax, 2);
        $grand = (float) ceil(round($beforeDelivery + $delivery, 2));

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'net_subtotal' => $netSubtotal,
            'tax' => $tax,
            'delivery' => $delivery,
            'before_delivery' => $beforeDelivery,
            'grand' => $grand,
            'tax_label' => $setting->tax_label ?: 'VAT',
            'tax_rate' => $taxRate,
        ];
    }
}
