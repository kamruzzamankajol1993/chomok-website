<?php

namespace App\Services;

use App\Models\BogoOffer;
use App\Models\Client;
use App\Models\Coupon;
use App\Models\MenuItemPrice;
use App\Models\Order;
use App\Models\PromotionSetting;
use App\Models\PromotionUsage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

/**
 * Resolves the active BOGO offer or coupon for a website checkout.
 * The service deliberately returns only one promotion at a time.
 */
class PromotionService
{
    public function quote(Client $client, array $cart, ?string $couponCode = null, bool $lockForUpdate = false, ?int $branchId = null): array
    {
        $lines = $this->priceCart($cart);
        $cartSubtotal = round(array_sum(array_map(fn (array $line): float => $line['line_total'], $lines)), 2);
        $displayCart = [];
        foreach ($lines as $line) {
            $displayCart[$line['cart_key']] = $line['display_row'];
        }
        $quote = [
            'cart_lines' => $displayCart,
            'cart_subtotal' => $cartSubtotal,
            'bogo_enabled' => false,
            'coupons_enabled' => false,
            'bogo_offer' => null,
            'free_cart_keys' => [],
            'free_quantity' => 0,
            'coupon' => null,
            'coupon_discount' => 0.0,
            'coupon_eligible_subtotal' => 0.0,
        ];

        if (! $this->promotionTablesAvailable()) {
            if (filled($couponCode)) {
                throw ValidationException::withMessages(['coupon_code' => 'Coupon service is not configured yet. Please contact the restaurant.']);
            }

            return $quote;
        }

        $settingsQuery = PromotionSetting::query()->whereKey(1);
        if ($lockForUpdate) {
            $settingsQuery->lockForUpdate();
        }
        $settings = $settingsQuery->first() ?? PromotionSetting::current();
        $quote['bogo_enabled'] = (bool) $settings->bogo_enabled;
        $quote['coupons_enabled'] = (bool) $settings->coupons_enabled;

        if (filled($couponCode)) {
            return $this->couponQuote($quote, $lines, $client, $settings, (string) $couponCode, $lockForUpdate, $branchId);
        }

        if (! $settings->bogo_enabled) {
            return $quote;
        }

        $now = now();
        $offersQuery = BogoOffer::query()
            ->with(['targets', 'branches'])
            ->where('is_active', true)
            ->where(fn ($query) => $query->whereNull('starts_at')->orWhere('starts_at', '<=', $now))
            ->where(fn ($query) => $query->whereNull('ends_at')->orWhere('ends_at', '>=', $now))
            ->where(function ($query) use ($branchId): void {
                $query->where('branch_scope_type', 'all_branches');
                if ($branchId !== null) {
                    $query->orWhere(function ($selected) use ($branchId): void {
                        $selected->where('branch_scope_type', 'selected_branches')
                            ->whereHas('branches', fn ($branches) => $branches->whereKey($branchId));
                    });
                }
            })
            ->orderByDesc('priority')
            ->orderBy('id');

        if ($lockForUpdate) {
            $offersQuery->lockForUpdate();
        }

        foreach ($offersQuery->get() as $offer) {
            if ($offer->eligibility_type === 'first_order'
                && Order::withTrashed()->where('client_id', $client->id)->exists()) {
                continue;
            }

            if ($cartSubtotal + 0.00001 < (float) $offer->minimum_order_amount) {
                continue;
            }

            if ($this->usageLimitReached($offer, 'bogo_offer', $client)) {
                continue;
            }

            $eligibleKeys = [];
            foreach ($lines as $line) {
                if ($this->matchesScope($offer, $line)) {
                    $eligibleKeys[] = (string) $line['cart_key'];
                }
            }

            if ($eligibleKeys === []) {
                continue;
            }

            // One free matching unit per eligible cart row. A row with quantity 2
            // therefore receives one free unit; separate eligible food rows each qualify.
            $freeKeys = $offer->cart_rule === 'first_food_only'
                ? [reset($eligibleKeys)]
                : $eligibleKeys;

            $quote['bogo_offer'] = $offer;
            $quote['free_cart_keys'] = array_values($freeKeys);
            $quote['free_quantity'] = count($freeKeys);
            return $quote;
        }

        return $quote;
    }

    public function clientKey(Client $client): string
    {
        $identifier = strtolower(trim((string) ($client->email ?: $client->phone)));

        return hash('sha256', $identifier !== '' ? $identifier : 'client:'.$client->id);
    }

    private function couponQuote(array $quote, array $lines, Client $client, PromotionSetting $settings, string $rawCode, bool $lockForUpdate, ?int $branchId): array
    {
        if (! $settings->coupons_enabled) {
            throw ValidationException::withMessages(['coupon_code' => 'Coupon codes are currently disabled.']);
        }

        $code = strtoupper(trim($rawCode));
        $now = now();
        $couponQuery = Coupon::query()
            ->with(['targets', 'branches'])
            ->where('code', $code)
            ->where('is_active', true)
            ->where(fn ($query) => $query->whereNull('starts_at')->orWhere('starts_at', '<=', $now))
            ->where(fn ($query) => $query->whereNull('ends_at')->orWhere('ends_at', '>=', $now))
            ->where(function ($query) use ($branchId): void {
                $query->where('branch_scope_type', 'all_branches');
                if ($branchId !== null) {
                    $query->orWhere(function ($selected) use ($branchId): void {
                        $selected->where('branch_scope_type', 'selected_branches')
                            ->whereHas('branches', fn ($branches) => $branches->whereKey($branchId));
                    });
                }
            });

        if ($lockForUpdate) {
            $couponQuery->lockForUpdate();
        }

        $coupon = $couponQuery->first();
        if (! $coupon) {
            throw ValidationException::withMessages(['coupon_code' => 'This coupon is invalid, inactive, outside its valid date range, or not available at the selected branch.']);
        }

        if ($this->usageLimitReached($coupon, 'coupon', $client)) {
            throw ValidationException::withMessages(['coupon_code' => 'This coupon has reached its usage limit for you or the campaign.']);
        }

        if ($quote['cart_subtotal'] + 0.00001 < (float) $coupon->minimum_order_amount) {
            throw ValidationException::withMessages([
                'coupon_code' => 'The order subtotal must be at least ৳'.number_format((float) $coupon->minimum_order_amount, 2).' to use this coupon.',
            ]);
        }

        $eligibleSubtotal = 0.0;
        foreach ($lines as $line) {
            // Product-level discount prices are excluded from coupon eligibility.
            // Coupon percentage/fixed discounts apply to eligible food prices, not add-ons.
            if (! $line['is_discounted'] && $this->matchesScope($coupon, $line)) {
                $eligibleSubtotal += $line['food_subtotal'];
            }
        }
        $eligibleSubtotal = round($eligibleSubtotal, 2);

        if ($eligibleSubtotal <= 0) {
            throw ValidationException::withMessages([
                'coupon_code' => 'This coupon does not apply to any eligible food in your cart. Foods already using a Discount Price are not coupon-eligible.',
            ]);
        }

        if ($coupon->discount_type === 'percentage') {
            // Match the existing order-calculation convention of rounding percentage
            // discounts upward to a whole currency unit before applying any cap.
            $discount = (float) ceil($eligibleSubtotal * ((float) $coupon->discount_value / 100));
        } else {
            $discount = (float) $coupon->discount_value;
        }

        $discount = min($discount, $eligibleSubtotal);
        if ($coupon->maximum_discount !== null && (float) $coupon->maximum_discount > 0) {
            $discount = min($discount, (float) $coupon->maximum_discount);
        }
        $discount = round(max($discount, 0), 2);

        if ($discount <= 0) {
            throw ValidationException::withMessages(['coupon_code' => 'This coupon does not provide a discount for the current cart.']);
        }

        $quote['coupon'] = $coupon;
        $quote['coupon_discount'] = $discount;
        $quote['coupon_eligible_subtotal'] = $eligibleSubtotal;
        // A coupon and BOGO are mutually exclusive: no BOGO offer/free line is returned.
        $quote['bogo_offer'] = null;
        $quote['free_cart_keys'] = [];
        $quote['free_quantity'] = 0;

        return $quote;
    }

    private function usageLimitReached(BogoOffer|Coupon $promotion, string $type, Client $client): bool
    {
        $base = PromotionUsage::query()
            ->where('promotion_type', $type)
            ->where('promotion_id', $promotion->id);

        if ($promotion->max_uses_total !== null && $base->count() >= (int) $promotion->max_uses_total) {
            return true;
        }

        if ($promotion->max_uses_per_user !== null) {
            $userCount = (clone $base)
                ->where(function ($query) use ($client): void {
                    $query->where('client_id', $client->id)
                        ->orWhere('client_key', $this->clientKey($client));
                })
                ->count();

            if ($userCount >= (int) $promotion->max_uses_per_user) {
                return true;
            }
        }

        return false;
    }

    private function matchesScope(BogoOffer|Coupon $promotion, array $line): bool
    {
        if ($promotion->scope_type === 'all_menu') {
            return true;
        }

        $targetType = match ($promotion->scope_type) {
            'categories' => 'category',
            'subcategories' => 'subcategory',
            'menu_items' => 'menu_item',
            default => null,
        };
        if (! $targetType) {
            return false;
        }

        $targetId = match ($targetType) {
            'category' => $line['category_id'],
            'subcategory' => $line['subcategory_id'],
            'menu_item' => $line['menu_item_id'],
        };
        if (! $targetId) {
            return false;
        }

        return $promotion->targets->contains(fn ($target): bool =>
            $target->target_type === $targetType && (int) $target->target_id === (int) $targetId
        );
    }

    private function priceCart(array $cart): array
    {
        $lines = [];

        foreach ($cart as $cartKey => $row) {
            $quantity = max(1, (int) ($row['quantity'] ?? 1));
            $price = MenuItemPrice::query()
                ->with(['menuItem.category', 'menuItem.subcategory', 'menuItem.addons', 'variationAddons'])
                ->whereKey((int) ($row['menu_item_price_id'] ?? 0))
                ->where('menu_item_id', (int) ($row['menu_item_id'] ?? 0))
                ->first();

            if (! $price || ! $price->menuItem) {
                $fallbackUnit = max((float) ($row['unit_price'] ?? 0), 0);
                $fallbackAddon = max((float) ($row['addon_total'] ?? 0), 0);
                $fallbackLine = round(($fallbackUnit + $fallbackAddon) * $quantity, 2);
                $display = $row;
                $display['quantity'] = $quantity;
                $display['line_total'] = $fallbackLine;
                $lines[] = [
                    'cart_key' => (string) $cartKey,
                    'menu_item_id' => (int) ($row['menu_item_id'] ?? 0),
                    'category_id' => null,
                    'subcategory_id' => null,
                    'quantity' => $quantity,
                    'food_subtotal' => round($fallbackUnit * $quantity, 2),
                    'line_total' => $fallbackLine,
                    'is_discounted' => false,
                    'display_row' => $display,
                ];
                continue;
            }

            $addonIds = collect($row['addon_ids'] ?? [])->map(fn ($id) => (int) $id)->unique()->values();
            $priceAddonIds = collect($row['price_addon_ids'] ?? [])->map(fn ($id) => (int) $id)->unique()->values();
            $globalAddons = $price->menuItem->addons->where('is_active', true)->whereIn('id', $addonIds);
            $variationAddons = $price->variationAddons->whereIn('id', $priceAddonIds);

            $unitPrice = (float) $price->effective_price;
            $addonTotal = (float) $globalAddons->sum(fn ($addon) => (float) $addon->price)
                + (float) $variationAddons->sum(fn ($addon) => (float) $addon->price);
            $foodSubtotal = round($unitPrice * $quantity, 2);
            $lineTotal = round(($unitPrice + $addonTotal) * $quantity, 2);
            $discountPrice = (float) ($price->discount_price ?? 0);
            $isDiscounted = $discountPrice > 0 && $discountPrice < (float) $price->price;

            $addonRows = $globalAddons->map(fn ($addon): array => [
                'id' => $addon->id,
                'price_addon_id' => null,
                'source' => 'global',
                'name' => $addon->name,
                'description' => $addon->description ?? null,
                'price' => (float) $addon->price,
            ])->concat($variationAddons->map(fn ($addon): array => [
                'id' => null,
                'price_addon_id' => $addon->id,
                'source' => 'variation',
                'name' => $addon->name,
                'description' => $addon->description ?? null,
                'price' => (float) $addon->price,
            ]))->values()->all();

            $display = array_merge($row, [
                'name' => $price->menuItem->name,
                'size_label' => $price->size_label ?: 'Regular',
                'unit_price' => $unitPrice,
                'quantity' => $quantity,
                'addon_total' => $addonTotal,
                'addons' => $addonRows,
                'line_total' => $lineTotal,
                'is_discounted' => $isDiscounted,
            ]);

            $lines[] = [
                'cart_key' => (string) $cartKey,
                'menu_item_id' => (int) $price->menu_item_id,
                'category_id' => $price->menuItem->category_id ? (int) $price->menuItem->category_id : null,
                'subcategory_id' => $price->menuItem->subcategory_id ? (int) $price->menuItem->subcategory_id : null,
                'quantity' => $quantity,
                'food_subtotal' => $foodSubtotal,
                'line_total' => $lineTotal,
                'is_discounted' => $isDiscounted,
                'display_row' => $display,
            ];
        }

        return $lines;
    }

    private function promotionTablesAvailable(): bool
    {
        foreach (['promotion_settings', 'bogo_offers', 'bogo_offer_targets', 'coupons', 'coupon_targets', 'promotion_usages', 'bogo_offer_branches', 'coupon_branches'] as $table) {
            if (! Schema::hasTable($table)) {
                return false;
            }
        }

        return Schema::hasColumn('bogo_offers', 'branch_scope_type')
            && Schema::hasColumn('coupons', 'branch_scope_type');
    }
}
