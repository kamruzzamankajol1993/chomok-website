@extends('website.master.master')
@section('title', 'Checkout | '.($siteSetting?->restaurant_name ?? 'Chomok Restaurant'))
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
          <label for="checkout-branch">Select Branch <span aria-hidden="true">*</span></label>
          <p class="form-help" style="margin:0 0 .55rem;"><strong>NB:</strong> Please select your branch carefully. Branch selection is mandatory.</p>
          <select id="checkout-branch" name="branch_id" required aria-required="true">
            <option value="" disabled @selected(blank(old('branch_id')))>Choose a branch</option>
            @foreach($branches as $branch)<option value="{{ $branch->id }}" @selected((string)old('branch_id')===(string)$branch->id)>{{ $branch->name }}{{ $branch->address ? ' — '.$branch->address : '' }}</option>@endforeach
          </select>
        </div>

        <h2 class="contact-form-title checkout-payment-title">Payment Method</h2>
        <div class="payment-methods">
          <label class="price-pill payment-pill checkout-cod-only"><input type="radio" name="payment_display" value="cod" class="price-pill-input" checked disabled>Cash on Delivery</label>
        </div>
      </div>

      <div class="checkout-summary">
        <h2 class="contact-form-title">Order Summary</h2>
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
            </div>
          </div>
        @endforeach
        <div class="checkout-totals">
          <div class="checkout-total-row"><span>Subtotal</span><span>TK {{ $formatCheckoutMoney($summary['subtotal']) }}</span></div>
          @if($summary['tax'] > 0)<div class="checkout-total-row"><span>{{ $summary['tax_label'] }}</span><span>TK {{ $formatCheckoutMoney($summary['tax']) }}</span></div>@endif
          <div class="checkout-total-row"><span>Delivery Fee</span><span>TK {{ $formatCheckoutMoney($summary['delivery']) }}</span></div>
          <div class="checkout-total-row checkout-total-final"><span>Total</span><span>TK {{ $formatCheckoutMoney($summary['grand']) }}</span></div>
        </div>
        <button type="submit" class="btn-wc-hero checkout-place-order">Place Order</button>
      </div>
    </div>
  </form>
</section>
@endsection
