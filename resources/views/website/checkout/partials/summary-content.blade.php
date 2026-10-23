@php
  $formatCheckoutMoney = static function ($value) {
      return rtrim(rtrim(number_format((float) $value, 2, '.', ''), '0'), '.');
  };
@endphp
<h2 class="contact-form-title">Order Summary</h2>
@if(!empty($promotionQuote['bogo_offer']))
  <div class="first-order-offer" id="checkoutBogoSummary">
    <strong>{{ $promotionQuote['bogo_offer']->name }}</strong>
    <span>{{ $promotionQuote['free_quantity'] }} free matching item(s) will be added for the selected branch.</span>
  </div>
@elseif(empty($selectedBranchId) && ($promotionQuote['bogo_enabled'] ?? false))
  <div class="first-order-offer" id="checkoutBogoSummary"><strong>BOGO offers</strong><span>Select a branch to check branch-specific offers. The final offer is validated when you place the order.</span></div>
@endif
@foreach($cart as $cartKey => $row)
  @php
    $unitPrice = (float) ($row['unit_price'] ?? 0);
    $addonTotal = (float) ($row['addon_total'] ?? collect($row['addons'] ?? [])->sum(fn ($addon) => (float) ($addon['price'] ?? 0)));
    $combinedUnitPrice = $unitPrice + $addonTotal;
    $quantity = max(1, (int) ($row['quantity'] ?? 1));
  @endphp
  <div class="checkout-item" data-cart-key="{{ (string) $cartKey }}">
    @if(!empty($row['image']))<img src="{{ $adminAssetUrl($row['image']) }}" alt="{{ $row['name'] }}" class="checkout-item-img">@endif
    <div class="checkout-item-info">
      <h4>{{ $row['name'] }}</h4>
      <span class="checkout-base-price">{{ $row['size_label'] ?? 'Regular' }} - TK {{ $formatCheckoutMoney($unitPrice) }}</span>
      @if(!empty($row['addons']))
        <div class="checkout-addon-prices mt-1">
          @foreach($row['addons'] as $addon)
            <small class="d-block" style="line-height:1.45;overflow-wrap:anywhere;">
              {{ $addon['name'] ?? '' }}@if(filled($addon['description'] ?? null))/{{ $addon['description'] }}@endif
              - TK {{ $formatCheckoutMoney($addon['price'] ?? 0) }}
            </small>
          @endforeach
        </div>
      @endif
      <strong class="checkout-combined-price">TK {{ $formatCheckoutMoney($combinedUnitPrice) }}</strong>
      @if($quantity > 1)
        <small class="checkout-line-total">Total ({{ $quantity }} items) - TK {{ $formatCheckoutMoney($row['line_total'] ?? ($combinedUnitPrice * $quantity)) }}</small>
      @endif
      @if(!empty($promotionQuote['bogo_offer']) && in_array((string) $cartKey, $promotionQuote['free_cart_keys'] ?? [], true))
        <span class="checkout-free-line">🎁 1 FREE matching item for this food</span>
      @endif
    </div>
  </div>
@endforeach
<div class="checkout-totals">
  <div class="checkout-total-row"><span>Subtotal</span><span id="checkoutSubtotal">TK {{ $formatCheckoutMoney($summary['subtotal']) }}</span></div>
  <div class="checkout-total-row" id="checkoutCouponDiscountRow" @if(($summary['discount'] ?? 0) <= 0) hidden @endif><span>Coupon Discount</span><span id="checkoutCouponDiscount">- TK {{ $formatCheckoutMoney($summary['discount'] ?? 0) }}</span></div>
  @if(($summary['tax_rate'] ?? 0) > 0)
    <div class="checkout-total-row"><span>{{ $summary['tax_label'] }} ({{ $formatCheckoutMoney($summary['tax_rate']) }}%)</span><span id="checkoutTax">TK {{ $formatCheckoutMoney($summary['tax']) }}</span></div>
  @endif
  @if($deliveryConfig['enabled'])
    <div class="checkout-total-row" id="checkoutDeliveryFeeRow"><span>Delivery Fee</span><span id="checkoutDeliveryFee">TK {{ $formatCheckoutMoney($summary['delivery']) }}</span></div>
  @endif
  <div class="checkout-total-row checkout-total-final"><span>Total</span><span id="checkoutGrandTotal">TK {{ $formatCheckoutMoney($summary['grand']) }}</span></div>
</div>
<button type="submit" class="btn-wc-hero checkout-place-order" id="checkoutPlaceOrder" disabled>Choose a branch to order</button>
