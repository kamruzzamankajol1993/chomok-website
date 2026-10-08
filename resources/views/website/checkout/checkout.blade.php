@extends('website.master.master')
@section('title', 'Checkout | '.($siteSetting?->restaurant_name ?? 'Chomok Restaurant'))
@section('css')
<style>
  .checkout-choice-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}
  .checkout-choice{position:relative;display:block;margin:0;cursor:pointer}
  .checkout-choice input{position:absolute;opacity:0;pointer-events:none}
  .checkout-choice-card{height:100%;border:1px solid #dedede;border-radius:14px;padding:14px 15px;background:#fff;transition:.2s ease;display:flex;gap:11px;align-items:flex-start}
  .checkout-choice-dot{width:20px;height:20px;border:2px solid #aaa;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;flex:0 0 auto;margin-top:2px}
  .checkout-choice-dot:after{content:"";width:10px;height:10px;border-radius:50%;background:currentColor;transform:scale(0);transition:.18s ease}
  .checkout-choice input:checked + .checkout-choice-card{border-color:#111;box-shadow:0 0 0 1px #111}
  .checkout-choice input:checked + .checkout-choice-card .checkout-choice-dot{border-color:#111;color:#111}
  .checkout-choice input:checked + .checkout-choice-card .checkout-choice-dot:after{transform:scale(1)}
  .checkout-choice-copy{min-width:0}.checkout-choice-copy strong{display:block;font-size:15px}.checkout-choice-copy small{display:block;margin-top:3px;color:#6c757d;line-height:1.4}
  .delivery-charge-info{position:relative;overflow:hidden;border:1px solid #e6e1d6;border-radius:16px;padding:16px 18px;background:linear-gradient(135deg,#fffdf7 0%,#f8f5ec 100%);box-shadow:0 7px 20px rgba(0,0,0,.045)}
  .delivery-charge-info-title{display:flex;align-items:center;gap:8px;margin-bottom:10px;font-size:16px;font-weight:800;color:#171717}
  .delivery-charge-info-title::before{content:"";width:8px;height:8px;border-radius:50%;background:#171717;flex:0 0 auto}
  .delivery-charge-info-row{display:flex;align-items:center;justify-content:space-between;gap:16px;padding:8px 0;border-top:1px dashed #ddd6c6;color:#3c3c3c}
  .delivery-charge-info-row:first-of-type{border-top:0}
  .delivery-charge-info-row strong{color:#111;white-space:nowrap}
  .delivery-charge-info-note{display:block;margin-top:9px;color:#737373;font-size:12px;line-height:1.45}
  .first-order-offer{border:1px solid #111;border-radius:16px;padding:14px 16px;margin:0 0 18px;background:#fff;box-shadow:0 8px 22px rgba(0,0,0,.05)}
  .first-order-offer strong{display:block;font-size:17px;margin-bottom:3px}
  .first-order-offer span{display:block;color:#555;line-height:1.45}
  .checkout-free-line{display:inline-flex;align-items:center;align-self:flex-start;width:fit-content;max-width:100%;gap:6px;margin-top:8px;padding:7px 11px;border:1px solid #d5b84b;border-radius:10px;background:#fff4c2;color:#171717!important;opacity:1!important;font-size:13px;font-weight:800;line-height:1.35;overflow-wrap:anywhere}
  @media(max-width:767.98px){.checkout-choice-grid{grid-template-columns:1fr}}
</style>
@endsection
@section('body')
@php
  $formatCheckoutMoney = static function ($value) {
      return rtrim(rtrim(number_format((float) $value, 2, '.', ''), '0'), '.');
  };
@endphp
<section class="checkout-section">
  <div class="menu-section-head"><span class="badge-text">Almost There</span><h1 class="menu-section-title">Checkout</h1></div>

  <form class="contact-form checkout-form" action="{{ route('checkout.process') }}" method="post">
    @csrf
    <div class="checkout-layout">
      <div class="checkout-form-wrap">
        <h2 class="contact-form-title">Delivery Details</h2>

        <div class="form-group">
          <label for="checkout-phone">Phone Number</label>
          <input type="tel" id="checkout-phone" name="phone" value="{{ old('phone', $client->phone) }}" placeholder="Phone Number" autocomplete="tel" required>
        </div>

        <div class="form-group">
          <label for="checkout-address">Delivery Address</label>
          <input type="text" id="checkout-address" name="address" value="{{ old('address', $client->address) }}" placeholder="House, Road, Area" required>
        </div>

        <div class="form-group">
          <label>Select Branch <span aria-hidden="true">*</span></label>
          <p class="form-help" style="margin:0 0 .55rem;"><strong>NB:</strong> Please select your branch carefully. Branch selection is mandatory.</p>
          <div class="checkout-choice-grid" role="radiogroup" aria-label="Select branch">
            @foreach($branches as $branch)
              <label class="checkout-choice">
                <input type="radio" name="branch_id" value="{{ $branch->id }}" @checked((string)old('branch_id') === (string)$branch->id) required>
                <span class="checkout-choice-card">
                  <span class="checkout-choice-dot"></span>
                  <span class="checkout-choice-copy"><strong>{{ $branch->name }}</strong>@if($branch->address)<small>{{ $branch->address }}</small>@endif</span>
                </span>
              </label>
            @endforeach
          </div>
        </div>

        @if($deliveryConfig['enabled'])
          <div class="form-group">
            <div class="delivery-charge-info" aria-label="Delivery charge information">
              <div class="delivery-charge-info-title">Delivery Charge</div>
              <div class="delivery-charge-info-row">
                <span>Within 1 km</span>
                <strong>TK {{ $formatCheckoutMoney($deliveryConfig['within']) }}</strong>
              </div>
              <div class="delivery-charge-info-row">
                <span>Beyond 1 km</span>
                <strong>TK {{ $formatCheckoutMoney($deliveryConfig['outside_base']) }} + TK {{ $formatCheckoutMoney($deliveryConfig['per_km']) }} / km</strong>
              </div>
              <small class="delivery-charge-info-note">For deliveries beyond 1 km, TK {{ $formatCheckoutMoney($deliveryConfig['per_km']) }} will be added for each additional kilometer.</small>
            </div>
          </div>
        @endif

        <h2 class="contact-form-title checkout-payment-title">Payment Method</h2>
        <div class="payment-methods">
          <label class="price-pill payment-pill checkout-cod-only"><input type="radio" name="payment_display" value="cod" class="price-pill-input" checked disabled>Cash on Delivery</label>
        </div>
      </div>

      <div class="checkout-summary">
        <h2 class="contact-form-title">Order Summary</h2>
        @if($firstOrderOfferEligible)
          <div class="first-order-offer">
            <strong>First Order Offer — Buy 1 Get 1 Free</strong>
            <span>Every food in this order gets the same quantity free. The free item is an exact copy of the paid item — same size/variation, same add-ons and same selected options.</span>
          </div>
        @endif
        @foreach($cart as $row)
          @php
            $unitPrice = (float) ($row['unit_price'] ?? 0);
            $addonTotal = (float) ($row['addon_total'] ?? collect($row['addons'] ?? [])->sum(fn ($addon) => (float) ($addon['price'] ?? 0)));
            $combinedUnitPrice = $unitPrice + $addonTotal;
            $quantity = max(1, (int) ($row['quantity'] ?? 1));
          @endphp
          <div class="checkout-item">
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
              @if($firstOrderOfferEligible)
                <span class="checkout-free-line">🎁 FREE ×{{ $quantity }} — Same size, add-ons &amp; options</span>
              @endif
            </div>
          </div>
        @endforeach
        <div class="checkout-totals">
          <div class="checkout-total-row"><span>Subtotal</span><span>TK {{ $formatCheckoutMoney($summary['subtotal']) }}</span></div>
          @if(($summary['tax_rate'] ?? 0) > 0)
            <div class="checkout-total-row"><span>{{ $summary['tax_label'] }} ({{ $formatCheckoutMoney($summary['tax_rate']) }}%)</span><span>TK {{ $formatCheckoutMoney($summary['tax']) }}</span></div>
          @endif
          @if($deliveryConfig['enabled'])
            <div class="checkout-total-row"><span>Delivery Fee</span><span id="checkoutDeliveryFee">TK {{ $formatCheckoutMoney($summary['delivery']) }}</span></div>
          @endif
          <div class="checkout-total-row checkout-total-final"><span>Total</span><span id="checkoutGrandTotal">TK {{ $formatCheckoutMoney($summary['grand']) }}</span></div>
        </div>
        <button type="submit" class="btn-wc-hero checkout-place-order">Place Order</button>
      </div>
    </div>
  </form>
</section>
@endsection
