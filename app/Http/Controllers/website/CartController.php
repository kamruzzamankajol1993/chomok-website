<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use App\Models\MenuItemPrice;
use App\Models\MenuItemPriceAddon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return $this->response($request);
    }

    public function addToCart(Request $request): JsonResponse
    {
        $data = $request->validate([
            'menu_item_id' => ['required', 'integer', 'exists:menu_items,id'],
            'menu_item_price_id' => ['required', 'integer', 'exists:menu_item_prices,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:99'],
            'addon_ids' => ['nullable', 'array'],
            'addon_ids.*' => ['integer', 'exists:addons,id'],
            'price_addon_ids' => ['nullable', 'array'],
            'price_addon_ids.*' => ['integer', 'exists:menu_item_price_addons,id'],
        ]);

        $price = MenuItemPrice::query()
            ->with(['menuItem.mainImage', 'menuItem.addons', 'variationAddons'])
            ->whereKey($data['menu_item_price_id'])
            ->where('menu_item_id', $data['menu_item_id'])
            ->whereHas('menuItem', fn ($q) => $q->where('is_active', true))
            ->firstOrFail();

        $selectedIds = collect($data['addon_ids'] ?? [])->map(fn ($id) => (int) $id)->unique()->sort()->values();
        $selectedPriceAddonIds = collect($data['price_addon_ids'] ?? [])->map(fn ($id) => (int) $id)->unique()->sort()->values();
        $addons = $price->menuItem->addons->where('is_active', true)->whereIn('id', $selectedIds);
        $priceAddons = $price->variationAddons->whereIn('id', $selectedPriceAddonIds);
        if ($addons->count() !== $selectedIds->count()) {
            throw ValidationException::withMessages(['addon_ids' => 'One or more selected global add-ons are unavailable.']);
        }
        if ($priceAddons->count() !== $selectedPriceAddonIds->count()) {
            throw ValidationException::withMessages(['price_addon_ids' => 'One or more selected variation add-ons are unavailable for this price/size.']);
        }

        $quantity = (int) ($data['quantity'] ?? 1);
        $unitPrice = (float) $price->effective_price;
        $globalAddonTotal = (float) $addons->sum(fn (Addon $addon) => (float) $addon->price);
        $variationAddonTotal = (float) $priceAddons->sum(fn ($addon) => (float) $addon->price);
        $addonTotal = $globalAddonTotal + $variationAddonTotal;
        $key = sha1($price->menu_item_id.'|'.$price->id.'|g:'.$selectedIds->implode(',').'|v:'.$selectedPriceAddonIds->implode(','));
        $cart = $request->session()->get('cart', []);

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] = min(99, (int) $cart[$key]['quantity'] + $quantity);
            $cart[$key]['line_total'] = round(($unitPrice + $addonTotal) * $cart[$key]['quantity'], 2);
        } else {
            $globalAddonRows = $addons->map(fn (Addon $addon) => [
                'id' => $addon->id,
                'price_addon_id' => null,
                'source' => 'global',
                'name' => $addon->name,
                'description' => $addon->description,
                'price' => (float) $addon->price,
            ]);
            $variationAddonRows = $priceAddons->map(fn ($addon) => [
                'id' => null,
                'price_addon_id' => $addon->id,
                'source' => 'variation',
                'name' => $addon->name,
                'description' => $addon->description,
                'price' => (float) $addon->price,
            ]);

            $cart[$key] = [
                'key' => $key,
                'menu_item_id' => $price->menu_item_id,
                'menu_item_price_id' => $price->id,
                'name' => $price->menuItem->name,
                'size_label' => $price->size_label ?: 'Regular',
                'unit_price' => $unitPrice,
                'quantity' => $quantity,
                'addon_ids' => $selectedIds->all(),
                'price_addon_ids' => $selectedPriceAddonIds->all(),
                'addons' => $globalAddonRows->concat($variationAddonRows)->values()->all(),
                'addon_total' => $addonTotal,
                'line_total' => round(($unitPrice + $addonTotal) * $quantity, 2),
                'image' => $price->menuItem->mainImage?->image,
            ];
        }

        $request->session()->put('cart', $cart);

        return $this->response($request, 'Item added to cart.');
    }

    public function updateCart(Request $request): JsonResponse
    {
        $data = $request->validate([
            'key' => ['required', 'string'],
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);
        $cart = $request->session()->get('cart', []);
        if (! isset($cart[$data['key']])) {
            return response()->json(['message' => 'Cart item not found.'], 404);
        }
        $cart[$data['key']]['quantity'] = (int) $data['quantity'];
        $cart[$data['key']]['line_total'] = round(
            ((float) $cart[$data['key']]['unit_price'] + (float) $cart[$data['key']]['addon_total']) * (int) $data['quantity'],
            2
        );
        $request->session()->put('cart', $cart);

        return $this->response($request, 'Cart updated.');
    }

    public function removeFromCart(Request $request): JsonResponse
    {
        $data = $request->validate(['key' => ['required', 'string']]);
        $cart = $request->session()->get('cart', []);
        unset($cart[$data['key']]);
        $request->session()->put('cart', $cart);

        return $this->response($request, 'Item removed.');
    }

    public function clearCart(Request $request): JsonResponse
    {
        $request->session()->forget('cart');
        return $this->response($request, 'Cart cleared.');
    }


    private function hydrateAddonDescriptions(array $cart): array
    {
        $globalIds = collect($cart)
            ->flatMap(fn ($row) => collect($row['addons'] ?? [])->where('source', 'global')->pluck('id'))
            ->filter()->map(fn ($id) => (int) $id)->unique()->values();
        $variationIds = collect($cart)
            ->flatMap(fn ($row) => collect($row['addons'] ?? [])->where('source', 'variation')->pluck('price_addon_id'))
            ->filter()->map(fn ($id) => (int) $id)->unique()->values();

        $globalDescriptions = $globalIds->isEmpty()
            ? collect()
            : Addon::query()->withTrashed()->whereIn('id', $globalIds)->pluck('description', 'id');
        $variationDescriptions = $variationIds->isEmpty()
            ? collect()
            : MenuItemPriceAddon::query()->whereIn('id', $variationIds)->pluck('description', 'id');

        foreach ($cart as &$row) {
            if (empty($row['addons']) || ! is_array($row['addons'])) {
                continue;
            }
            foreach ($row['addons'] as &$addon) {
                if (filled($addon['description'] ?? null)) {
                    continue;
                }
                $addon['description'] = ($addon['source'] ?? null) === 'variation'
                    ? $variationDescriptions->get((int) ($addon['price_addon_id'] ?? 0))
                    : $globalDescriptions->get((int) ($addon['id'] ?? 0));
            }
            unset($addon);
        }
        unset($row);

        return $cart;
    }

    private function response(Request $request, ?string $message = null): JsonResponse
    {
        $cart = $this->hydrateAddonDescriptions($request->session()->get('cart', []));
        $request->session()->put('cart', $cart);
        $subtotal = round(collect($cart)->sum(fn ($row) => (float) ($row['line_total'] ?? 0)), 2);
        $count = collect($cart)->sum(fn ($row) => (int) ($row['quantity'] ?? 0));

        return response()->json([
            'message' => $message,
            'count' => $count,
            'subtotal' => $subtotal,
            'html' => view('website.include.cart-content', compact('cart', 'subtotal'))->render(),
        ]);
    }
}
