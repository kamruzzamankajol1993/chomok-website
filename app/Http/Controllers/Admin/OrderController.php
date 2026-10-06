<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Client;
use App\Models\MenuItem;
use App\Models\Order;
use App\Services\WebPushService;
use App\Models\Setting;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderController extends Controller
{
    public function __construct(private readonly OrderService $orderService)
    {
    }

    public function index(Request $request): View
    {
        $baseQuery = Order::query()->visibleTo($request->user());
        $orders = (clone $baseQuery)
            ->with(['branch', 'client', 'items'])
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = trim((string) $request->input('search'));
                $query->where(function ($inner) use ($search): void {
                    $inner->where('order_number', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%")
                        ->orWhere('customer_phone', 'like', "%{$search}%")
                        ->orWhereHas('items', fn ($itemQuery) => $itemQuery->where('item_name', 'like', "%{$search}%"));
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->input('status')))
            ->when($request->filled('source'), fn ($query) => $query->where('source', $request->input('source')))
            ->when($request->filled('branch_id') && $request->user()->canSeeAllOrders(), fn ($query) => $query->where('branch_id', $request->integer('branch_id')))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax()) {
            return view('admin.order.partials.table', compact('orders'));
        }

        $stats = [
            'pending' => (clone $baseQuery)->where('status', 'pending')->count(),
            'processing' => (clone $baseQuery)->whereIn('status', ['confirmed', 'processing'])->count(),
            'delivered' => (clone $baseQuery)->where('status', 'delivered')->count(),
            'cancelled' => (clone $baseQuery)->where('status', 'cancelled')->count(),
        ];
        $branches = Branch::query()
            ->when(! $request->user()->canSeeAllOrders(), fn ($query) => $query->whereKey($request->user()->branch_id))
            ->orderBy('name')
            ->get();

        return view('admin.order.index', compact('orders', 'stats', 'branches'));
    }

    public function create(Request $request): View
    {
        return view('admin.order.create', $this->formData($request));
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $data = $this->validated($request);
        $data['branch_id'] = $this->resolveBranchId($request, $data);
        $order = $this->orderService->save($data, $request->user(), 'admin');

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Order created successfully.',
                'redirect' => route('admin.orders.show', $order),
                'order_id' => $order->id,
            ]);
        }

        return redirect()->route('admin.orders.show', $order)->with('success', 'Order created successfully.');
    }

    public function show(Request $request, Order $order): View
    {
        $this->ensureVisible($request, $order);
        if ($order->source === 'website' && ! $order->notification_seen_at) {
            $order->update(['notification_seen_at' => now()]);
        }
        $order->load(['branch', 'client', 'creator', 'items.addons.addon', 'items.addons.menuItemPriceAddon']);

        return view('admin.order.show', compact('order'));
    }

    public function edit(Request $request, Order $order): View
    {
        $this->ensureVisible($request, $order);
        $order->load(['items.addons.addon', 'items.addons.menuItemPriceAddon', 'branch', 'client']);

        return view('admin.order.edit', array_merge($this->formData($request, $order), compact('order')));
    }

    public function update(Request $request, Order $order): JsonResponse|RedirectResponse
    {
        $this->ensureVisible($request, $order);
        $data = $this->validated($request, true);
        $data['branch_id'] = $this->resolveBranchId($request, $data, $order);
        $order = $this->orderService->save($data, $request->user(), $order->source, $order);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Order updated successfully.',
                'redirect' => route('admin.orders.show', $order),
            ]);
        }

        return redirect()->route('admin.orders.show', $order)->with('success', 'Order updated successfully.');
    }

    public function destroy(Request $request, Order $order): RedirectResponse
    {
        $this->ensureVisible($request, $order);
        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully.');
    }

    public function updateStatus(Request $request, Order $order): JsonResponse
    {
        $this->ensureVisible($request, $order);
        $data = $request->validate(['status' => ['required', Rule::in(['pending', 'confirmed', 'processing', 'delivered', 'cancelled'])]]);
        $order->update([
            'status' => $data['status'],
            'confirmed_at' => in_array($data['status'], ['confirmed', 'processing', 'delivered'], true)
                ? ($order->confirmed_at ?? now())
                : null,
        ]);

        return response()->json(['message' => 'Order status updated.', 'status' => $order->status]);
    }

    public function confirm(Request $request, Order $order): JsonResponse|RedirectResponse
    {
        $this->ensureVisible($request, $order);
        $order->update([
            'status' => 'confirmed',
            'confirmed_at' => $order->confirmed_at ?? now(),
            'notification_seen_at' => $order->notification_seen_at ?? now(),
        ]);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Order confirmed successfully.']);
        }

        return back()->with('success', 'Order confirmed successfully.');
    }

    public function menuItems(Request $request): JsonResponse
    {
        $branchId = $request->user()->canSeeAllOrders()
            ? $request->integer('branch_id')
            : (int) $request->user()->branch_id;

        abort_unless($branchId, 422, 'Select a branch first.');

        $items = MenuItem::query()
            ->where('is_active', true)
            ->whereHas('branches', fn ($query) => $query->whereKey($branchId))
            ->with(['category', 'subcategory', 'mainImage', 'prices.variationAddons', 'addons' => fn ($query) => $query->where('is_active', true)])
            ->when($request->filled('search'), fn ($query) => $query->where('name', 'like', '%'.trim((string) $request->input('search')).'%'))
            ->when($request->filled('category_id'), fn ($query) => $query->where('category_id', $request->integer('category_id')))
            ->orderBy('name')
            ->limit(80)
            ->get()
            ->map(fn (MenuItem $item) => [
                'id' => $item->id,
                'name' => $item->name,
                'category' => $item->category?->name,
                'subcategory' => $item->subcategory?->name,
                'image' => $item->mainImage?->image ? asset($item->mainImage->image) : null,
                'prices' => $item->prices->map(fn ($price) => [
                    'id' => $price->id,
                    'size_label' => $price->size_label ?: 'Regular',
                    'price' => (float) $price->price,
                    'discount_price' => $price->discount_price !== null ? (float) $price->discount_price : null,
                    'effective_price' => $price->effective_price,
                    'variation_addons' => $price->variationAddons->map(fn ($addon) => [
                        'id' => $addon->id,
                        'name' => $addon->name,
                        'price' => (float) $addon->price,
                    ])->values(),
                ])->values(),
                'addons' => $item->addons->map(fn ($addon) => [
                    'id' => $addon->id,
                    'name' => $addon->name,
                    'price' => (float) $addon->price,
                ])->values(),
            ]);

        return response()->json(['items' => $items]);
    }

    public function clientSearch(Request $request): JsonResponse
    {
        $branchId = $request->user()->canSeeAllOrders()
            ? $request->integer('branch_id')
            : (int) $request->user()->branch_id;

        abort_unless($branchId, 422, 'Select a branch first.');

        $clients = Client::query()
            ->where('status', 'active')
            ->where('branch_id', $branchId)
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = trim((string) $request->input('search'));
                $query->where(fn ($inner) => $inner->where('name', 'like', "%{$search}%")->orWhere('phone', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
            })
            ->orderBy('name')
            ->limit(30)
            ->get(['id', 'branch_id', 'code', 'name', 'email', 'phone', 'address']);

        return response()->json(['clients' => $clients]);
    }

    public function pendingNotifications(Request $request): JsonResponse
    {
        $order = Order::query()
            ->visibleTo($request->user())
            ->where('source', 'website')
            ->whereNull('notification_seen_at')
            ->whereNull('notification_dismissed_at')
            ->with(['branch', 'items'])
            ->oldest()
            ->first();

        // Send browser push only once for each website order.
        if ($order && is_null($order->push_sent_at)) {
            try {
                app(WebPushService::class)->sendNewOrder($order);
                $order->update(['push_sent_at' => now()]);
            } catch (\Throwable $e) {
                // Do not break order notification polling if push service fails.
            }
        }

        $count = Order::query()
            ->visibleTo($request->user())
            ->where('source', 'website')
            ->whereNull('notification_seen_at')
            ->whereNull('notification_dismissed_at')
            ->count();

        return response()->json([
            'count' => $count,
            'order' => $order ? [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'customer_name' => $order->customer_name,
                'customer_phone' => $order->customer_phone,
                'branch' => $order->branch?->name,
                'total' => (float) $order->grand_total,
                'items' => $order->items->pluck('item_name')->take(3)->implode(', '),
                'view_url' => route('admin.orders.show', $order),
                'seen_url' => route('admin.orders.notification-seen', $order),
                'dismiss_url' => route('admin.orders.notification-dismiss', $order),
            ] : null,
        ]);
    }

    public function markNotificationSeen(Request $request, Order $order): JsonResponse
    {
        $this->ensureVisible($request, $order);
        $order->update(['notification_seen_at' => now()]);

        return response()->json(['message' => 'Notification marked as viewed.']);
    }

    public function dismissNotification(Request $request, Order $order): JsonResponse
    {
        $this->ensureVisible($request, $order);
        $order->update(['notification_dismissed_at' => now()]);

        return response()->json(['message' => 'Notification dismissed.']);
    }

    public function invoiceA4(Request $request, Order $order): Response
    {
        $this->ensureVisible($request, $order);
        $order->load(['branch', 'items.addons.addon', 'items.addons.menuItemPriceAddon']);

        return $this->renderInvoicePdf($order, 'admin.order.invoice-a4', 'A4', 'a4');
    }

    public function invoice80mm(Request $request, Order $order): Response
    {
        $this->ensureVisible($request, $order);
        $order->load(['branch', 'items.addons.addon', 'items.addons.menuItemPriceAddon']);

        $addonCount = $order->items->sum(fn ($item) => $item->addons->count());
        $pageHeight = max(140, min(500, 110 + ($order->items->count() * 14) + ($addonCount * 4)));

        return $this->renderInvoicePdf($order, 'admin.order.invoice-80mm', [80, $pageHeight], '80mm');
    }

    public function export(Request $request): StreamedResponse
    {
        $orders = Order::query()->visibleTo($request->user())->with('branch')->latest()->get();

        return response()->streamDownload(function () use ($orders): void {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Order', 'Customer', 'Phone', 'Total', 'Payment', 'Branch', 'Source', 'Type', 'Status', 'Date']);
            foreach ($orders as $order) {
                fputcsv($handle, [
                    $order->order_number,
                    $order->customer_name,
                    $order->customer_phone,
                    $order->grand_total,
                    $order->payment_label,
                    $order->branch?->name,
                    ucfirst($order->source),
                    $order->order_type_label,
                    ucfirst($order->status),
                    $order->created_at?->format('d/m/Y H:i'),
                ]);
            }
            fclose($handle);
        }, 'orders-'.now()->format('Y-m-d').'.csv', ['Content-Type' => 'text/csv']);
    }

    private function formData(Request $request, ?Order $order = null): array
    {
        $branches = Branch::query()
            ->where('status', 'active')
            ->when(! $request->user()->canSeeAllOrders(), fn ($query) => $query->whereKey($request->user()->branch_id))
            ->orderBy('name')
            ->get();
        $selectedBranchId = $order?->branch_id ?? ($request->user()->canSeeAllOrders() ? $branches->first()?->id : $request->user()->branch_id);
        $clients = Client::query()
            ->where('status', 'active')
            ->when(
                $selectedBranchId,
                fn ($query) => $query->where('branch_id', $selectedBranchId),
                fn ($query) => $query->whereRaw('1 = 0'),
            )
            ->orderBy('name')
            ->limit(100)
            ->get();
        $categories = Category::query()->where('is_active', true)->orderBy('name')->get();
        $setting = Setting::current();

        return compact('branches', 'clients', 'categories', 'setting', 'selectedBranchId');
    }

    private function validated(Request $request, bool $editing = false): array
    {
        return $request->validate([
            'branch_id' => ['nullable', 'integer', 'exists:branches,id'],
            'client_id' => ['nullable', 'integer', 'exists:clients,id'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:50'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'customer_address' => ['nullable', 'string', 'max:3000'],
            'order_type' => ['required', Rule::in(['dine_in', 'delivery'])],
            'delivery_address' => ['required_if:order_type,delivery', 'nullable', 'string', 'max:3000'],
            'delivery_charge' => ['nullable', 'numeric', 'min:0', 'max:9999999.99'],
            'discount_type' => ['nullable', Rule::in(['fixed', 'percentage'])],
            'discount_value' => ['nullable', 'numeric', 'min:0', 'max:9999999.99'],
            'discount' => ['nullable', 'numeric', 'min:0', 'max:9999999.99'],
            'payment_type' => ['required', Rule::in(['cash', 'cash_on_delivery', 'mfs', 'bank', 'split'])],
            'paid_amount' => ['nullable', 'numeric', 'min:0', 'max:9999999.99'],
            'payment_reference' => ['required_if:payment_type,mfs,bank', 'nullable', 'string', 'max:255'],
            'split_cash' => ['required_if:payment_type,split', 'nullable', 'numeric', 'min:0'],
            'split_mfs' => ['required_if:payment_type,split', 'nullable', 'numeric', 'min:0'],
            'split_bank' => ['required_if:payment_type,split', 'nullable', 'numeric', 'min:0'],
            'split_mfs_reference' => ['nullable', 'string', 'max:255'],
            'split_bank_reference' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['pending', 'confirmed', 'processing', 'delivered', 'cancelled'])],
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
    }

    private function renderInvoicePdf(Order $order, string $view, string|array $format, string $suffix): Response
    {
        $tempDir = storage_path('app/mpdf');
        if (! is_dir($tempDir)) {
            mkdir($tempDir, 0775, true);
        }

        $setting = Setting::current();
        $invoiceLogo = null;
        if ($setting->show_logo_invoice && $setting->logo) {
            $logoPath = public_path(ltrim($setting->logo, '/'));
            if (is_file($logoPath)) {
                $invoiceLogo = $logoPath;
            }
        }

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => $format,
            'margin_left' => $suffix === '80mm' ? 3 : 12,
            'margin_right' => $suffix === '80mm' ? 3 : 12,
            'margin_top' => $suffix === '80mm' ? 3 : 12,
            'margin_bottom' => $suffix === '80mm' ? 3 : 12,
            'tempDir' => $tempDir,
        ]);
        $mpdf->SetTitle('Invoice '.$order->order_number);
        $mpdf->SetAuthor($setting->restaurant_name ?: 'Restaurant');
        $mpdf->WriteHTML(view($view, [
            'order' => $order,
            'restaurantSetting' => $setting,
            'invoiceLogo' => $invoiceLogo,
        ])->render());

        $filename = 'invoice-'.$order->order_number.'-'.$suffix.'.pdf';
        $pdf = $mpdf->Output($filename, Destination::STRING_RETURN);

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$filename.'"',
            'Cache-Control' => 'private, max-age=0, must-revalidate',
        ]);
    }

    private function resolveBranchId(Request $request, array $data, ?Order $order = null): int
    {
        if (! $request->user()->canSeeAllOrders()) {
            abort_unless($request->user()->branch_id, 422, 'Your account is not assigned to a branch.');

            return (int) $request->user()->branch_id;
        }

        $branchId = (int) ($data['branch_id'] ?? $order?->branch_id ?? 0);
        abort_unless($branchId, 422, 'Select a branch.');

        return $branchId;
    }

    private function ensureVisible(Request $request, Order $order): void
    {
        abort_unless(
            $request->user()->canSeeAllOrders()
                || ($request->user()->branch_id && (int) $order->branch_id === (int) $request->user()->branch_id),
            403,
        );
    }
}
