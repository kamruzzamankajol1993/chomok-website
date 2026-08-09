<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Order;
use App\Models\Setting;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    private const DELIVERY_FEE = 70.0;
    public function index(Request $request): View|RedirectResponse
    {
        $cart = $request->session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('menu.index')->with('error', 'Your cart is empty.');
        }

        $client = Auth::guard('client')->user();
        $branches = Branch::query()->where('status', 'active')->where('accepting_orders', true)->orderBy('name')->get();
        $summary = $this->summary($cart);

        return view('website.checkout.checkout', compact('cart', 'client', 'branches', 'summary'));
    }

    public function processCheckout(Request $request, OrderService $orders): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('menu.index')->with('error', 'Your cart is empty.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'address' => ['required', 'string', 'max:2000'],
            'city' => ['nullable', 'string', 'max:255'],
            'postcode' => ['nullable', 'string', 'max:50'],
            'branch_id' => ['required', 'integer', Rule::exists('branches', 'id')->where(fn ($q) => $q->where('status', 'active')->where('accepting_orders', 1)->whereNull('deleted_at'))],
            'notes' => ['nullable', 'string', 'max:3000'],
            'payment' => ['required', Rule::in(['cod', 'bkash', 'nagad', 'card'])],
        ]);

        $client = Auth::guard('client')->user();
        $paymentType = match ($data['payment']) {
            'bkash', 'nagad' => 'mfs',
            'card' => 'bank',
            default => 'cash_on_delivery',
        };

        $payload = [
            'branch_id' => (int) $data['branch_id'],
            'client_id' => $client->id,
            'customer_name' => $data['name'],
            'customer_phone' => $data['phone'],
            'customer_email' => $client->email,
            'customer_address' => $data['address'],
            'order_type' => 'delivery',
            'delivery_address' => trim($data['address'].($data['city'] ? ', '.$data['city'] : '').($data['postcode'] ? ' - '.$data['postcode'] : '')),
            'delivery_charge' => self::DELIVERY_FEE,
            'discount_type' => 'fixed',
            'discount_value' => 0,
            'payment_type' => $paymentType,
            'payment_reference' => $paymentType === 'cash_on_delivery' ? null : 'Website checkout: '.strtoupper($data['payment']),
            'paid_amount' => 0,
            'status' => 'pending',
            'note' => $data['notes'] ?? null,
            'items' => collect($cart)->map(fn ($row) => [
                'menu_item_id' => (int) $row['menu_item_id'],
                'menu_item_price_id' => (int) $row['menu_item_price_id'],
                'quantity' => (int) $row['quantity'],
                'addon_ids' => array_values($row['addon_ids'] ?? []),
            ])->values()->all(),
        ];

        $order = $orders->save($payload, null, 'website');
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

    private function summary(array $cart): array
    {
        $setting = Setting::current();
        $subtotal = round(collect($cart)->sum(fn ($row) => (float) ($row['line_total'] ?? 0)), 2);
        $tax = round($subtotal * (max((float) $setting->tax_rate, 0) / 100), 2);
        $delivery = self::DELIVERY_FEE;
        $grand = (float) ceil(round($subtotal + $tax + $delivery, 2));

        return ['subtotal' => $subtotal, 'tax' => $tax, 'delivery' => $delivery, 'grand' => $grand, 'tax_label' => $setting->tax_label ?: 'VAT'];
    }
}
