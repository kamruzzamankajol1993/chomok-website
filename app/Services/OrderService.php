<?php

namespace App\Services;

use App\Models\Addon;
use App\Models\Branch;
use App\Models\Client;
use App\Models\MenuItemPrice;
use App\Models\Order;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function save(array $payload, ?User $creator, string $source = 'admin', ?Order $order = null): Order
    {
        $branchId = (int) $payload['branch_id'];
        $client = ! empty($payload['client_id'])
            ? Client::query()
                ->whereKey($payload['client_id'])
                ->when($source !== 'website', fn ($query) => $query->where('branch_id', $branchId))
                ->first()
            : null;

        if (! empty($payload['client_id']) && ! $client) {
            throw ValidationException::withMessages(['client_id' => 'The selected client is not available.']);
        }

        [$itemRows, $subtotal] = $this->prepareItems($payload['items'], $branchId);
        $setting = Setting::current();
        $orderType = $payload['order_type'];
        $discountTypeInput = $payload['discount_type'] ?? 'fixed';
        $discountType = in_array($discountTypeInput, ['fixed', 'percentage'], true)
            ? $discountTypeInput
            : 'fixed';
        $discountValue = max((float) ($payload['discount_value'] ?? $payload['discount'] ?? 0), 0);
        if ($discountType === 'percentage') {
            $discountValue = min($discountValue, 100);
            $discount = round($subtotal * ($discountValue / 100), 2);
        } else {
            $discount = min($discountValue, $subtotal);
        }
        $netSubtotal = max($subtotal - $discount, 0);
        $serviceRate = $orderType === 'dine_in' ? max((float) $setting->service_charge, 0) : 0;
        $serviceAmount = round($netSubtotal * ($serviceRate / 100), 2);
        $taxRate = max((float) $setting->tax_rate, 0);
        $taxAmount = round(($netSubtotal + $serviceAmount) * ($taxRate / 100), 2);
        $deliveryCharge = $orderType === 'delivery' ? max((float) ($payload['delivery_charge'] ?? 0), 0) : 0;
        $grandTotal = (float) ceil(round($netSubtotal + $serviceAmount + $taxAmount + $deliveryCharge, 2));
        $payment = $this->paymentData($payload, $grandTotal);
        $status = $payload['status'] ?? 'pending';

        return DB::transaction(function () use (
            $payload, $creator, $source, $order, $branchId, $client, $itemRows,
            $subtotal, $discountType, $discountValue, $discount, $serviceRate, $serviceAmount, $taxRate,
            $taxAmount, $deliveryCharge, $grandTotal, $payment, $setting, $status
        ): Order {
            $attributes = [
                'branch_id' => $branchId,
                'client_id' => $client?->id,
                'created_by' => $order?->created_by ?? $creator?->id,
                'source' => $order?->source ?? $source,
                'order_type' => $payload['order_type'],
                'status' => $status,
                'payment_type' => $payload['payment_type'],
                'payment_reference' => $payment['payment_reference'],
                'split_cash' => $payment['split_cash'],
                'split_mfs' => $payment['split_mfs'],
                'split_bank' => $payment['split_bank'],
                'split_mfs_reference' => $payment['split_mfs_reference'],
                'split_bank_reference' => $payment['split_bank_reference'],
                'customer_name' => $payload['customer_name'],
                'customer_phone' => $payload['customer_phone'],
                'customer_email' => $payload['customer_email'] ?? null,
                'customer_address' => $payload['customer_address'] ?? null,
                'delivery_address' => $payload['order_type'] === 'delivery' ? $payload['delivery_address'] : null,
                'subtotal' => $subtotal,
                'discount_type' => $discountType,
                'discount_value' => $discountValue,
                'discount' => $discount,
                'service_charge_rate' => $serviceRate,
                'service_charge_amount' => $serviceAmount,
                'tax_label' => $setting->tax_label ?: 'VAT',
                'tax_rate' => $taxRate,
                'tax_amount' => $taxAmount,
                'delivery_charge' => $deliveryCharge,
                'grand_total' => $grandTotal,
                'paid_amount' => $payment['paid_amount'],
                'due_amount' => max(round($grandTotal - $payment['paid_amount'], 2), 0),
                'note' => $payload['note'] ?? null,
                'confirmed_at' => in_array($status, ['confirmed', 'processing', 'delivered'], true)
                    ? ($order?->confirmed_at ?? now())
                    : null,
            ];

            if ($order) {
                $branchChanged = (int) $order->branch_id !== $branchId;
                $order->update($attributes);

                if ($branchChanged) {
                    $this->assignBranchInvoiceNumber($order, $setting);
                }

                $order->items()->delete();
            } else {
                $order = Order::query()->create(array_merge($attributes, ['order_number' => 'TEMP-'.uniqid()]));
                $this->assignBranchInvoiceNumber($order, $setting);
            }

            foreach ($itemRows as $itemRow) {
                $addons = $itemRow['addons'];
                unset($itemRow['addons']);
                $orderItem = $order->items()->create($itemRow);
                foreach ($addons as $addon) {
                    $orderItem->addons()->create($addon);
                }
            }

            return $order->fresh(['branch', 'client', 'creator', 'items.addons']);
        });
    }

    private function prepareItems(array $items, int $branchId): array
    {
        $rows = [];
        $subtotal = 0.0;

        foreach ($items as $index => $item) {
            $price = MenuItemPrice::query()
                ->with(['menuItem.addons'])
                ->whereKey($item['menu_item_price_id'])
                ->where('menu_item_id', $item['menu_item_id'])
                ->whereHas('menuItem', function ($query) use ($branchId): void {
                    $query->where('is_active', true)
                        ->whereHas('branches', fn ($branchQuery) => $branchQuery->whereKey($branchId));
                })
                ->first();

            if (! $price) {
                throw ValidationException::withMessages([
                    "items.{$index}.menu_item_price_id" => 'The selected menu item price is not available at this branch.',
                ]);
            }

            $quantity = max((int) ($item['quantity'] ?? 1), 1);
            $selectedAddonIds = collect($item['addon_ids'] ?? [])->map(fn ($id) => (int) $id)->unique()->values();
            $availableAddons = $price->menuItem->addons
                ->where('is_active', true)
                ->whereIn('id', $selectedAddonIds);

            if ($availableAddons->count() !== $selectedAddonIds->count()) {
                throw ValidationException::withMessages([
                    "items.{$index}.addon_ids" => 'One or more selected add-ons are unavailable.',
                ]);
            }

            $unitPrice = $price->effective_price;
            $addonTotal = (float) $availableAddons->sum(fn (Addon $addon) => (float) $addon->price);
            $lineTotal = round(($unitPrice + $addonTotal) * $quantity, 2);
            $subtotal += $lineTotal;

            $rows[] = [
                'menu_item_id' => $price->menu_item_id,
                'menu_item_price_id' => $price->id,
                'item_name' => $price->menuItem->name,
                'size_label' => $price->size_label,
                'unit_price' => $unitPrice,
                'quantity' => $quantity,
                'addon_total' => $addonTotal,
                'line_total' => $lineTotal,
                'note' => $item['note'] ?? null,
                'addons' => $availableAddons->map(fn (Addon $addon) => [
                    'addon_id' => $addon->id,
                    'addon_name' => $addon->name,
                    'price' => (float) $addon->price,
                    'quantity' => $quantity,
                    'line_total' => round((float) $addon->price * $quantity, 2),
                ])->values()->all(),
            ];
        }

        return [$rows, round($subtotal, 2)];
    }

    private function paymentData(array $payload, float $grandTotal): array
    {
        $type = $payload['payment_type'];
        $splitCash = $type === 'split' ? max((float) ($payload['split_cash'] ?? 0), 0) : 0;
        $splitMfs = $type === 'split' ? max((float) ($payload['split_mfs'] ?? 0), 0) : 0;
        $splitBank = $type === 'split' ? max((float) ($payload['split_bank'] ?? 0), 0) : 0;

        if ($type === 'split' && $splitMfs > 0 && blank($payload['split_mfs_reference'] ?? null)) {
            throw ValidationException::withMessages(['split_mfs_reference' => 'MFS reference number is required.']);
        }
        if ($type === 'split' && $splitBank > 0 && blank($payload['split_bank_reference'] ?? null)) {
            throw ValidationException::withMessages(['split_bank_reference' => 'Bank reference number is required.']);
        }

        $requestedPaidAmount = max((float) ($payload['paid_amount'] ?? ($type === 'cash_on_delivery' ? 0 : $grandTotal)), 0);
        $paidAmount = match ($type) {
            'split' => min(round($splitCash + $splitMfs + $splitBank, 2), $grandTotal),
            default => min(round($requestedPaidAmount, 2), $grandTotal),
        };

        return [
            'payment_reference' => in_array($type, ['mfs', 'bank'], true) ? ($payload['payment_reference'] ?? null) : null,
            'split_cash' => $splitCash,
            'split_mfs' => $splitMfs,
            'split_bank' => $splitBank,
            'split_mfs_reference' => $type === 'split' ? ($payload['split_mfs_reference'] ?? null) : null,
            'split_bank_reference' => $type === 'split' ? ($payload['split_bank_reference'] ?? null) : null,
            'paid_amount' => $paidAmount,
        ];
    }

    private function assignBranchInvoiceNumber(Order $order, Setting $setting): void
    {
        // Lock the branch row so two simultaneous orders from the same branch
        // cannot receive the same branch-specific invoice sequence.
        $branch = Branch::query()
            ->whereKey($order->branch_id)
            ->lockForUpdate()
            ->firstOrFail();

        $startingNumber = max((int) $setting->invoice_starting_number, 1);
        $lastSequence = (int) Order::withTrashed()
            ->where('branch_id', $branch->id)
            ->whereNotNull('invoice_sequence')
            ->max('invoice_sequence');
        $sequence = max($startingNumber, $lastSequence + 1);

        $prefix = trim((string) $setting->invoice_prefix) ?: 'INV';
        $prefix = rtrim($prefix, "-_/ \t\n\r\0\x0B");

        $branchCode = strtoupper((string) preg_replace('/[^A-Z0-9]+/i', '', (string) $branch->code));
        if ($branchCode === '') {
            $branchCode = 'BR'.$branch->id;
        }

        $order->update([
            'invoice_sequence' => $sequence,
            'order_number' => $prefix.'-'.$branchCode.'-'.str_pad((string) $sequence, 4, '0', STR_PAD_LEFT),
        ]);
    }
}
