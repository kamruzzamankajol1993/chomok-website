@php
  $formatCartMoney = static function ($value) {
      return rtrim(rtrim(number_format((float) $value, 2, '.', ''), '0'), '.');
  };
@endphp

<div style="height:calc(100vh - 72px);display:flex;flex-direction:column;min-height:0;">
  <div class="offcanvas-body" style="flex:1 1 auto;min-height:0;overflow-y:auto;overscroll-behavior:contain;padding-bottom:1rem;">
    @forelse($cart as $row)
      <div class="cart-item" style="align-items:flex-start;">
        @if(!empty($row['image']))
          <img src="{{ $adminAssetUrl($row['image']) }}" alt="{{ $row['name'] }}" class="cart-item-img">
        @else
          <img src="{{ asset('public/website/assets/images/food-placeholder.jpg') }}" alt="{{ $row['name'] }}" class="cart-item-img">
        @endif

        <div class="cart-item-info" style="min-width:0;flex:1;">
          <h6 class="cart-item-name mb-1">{{ $row['name'] }}</h6>

          @if(filled($row['size_label'] ?? null))
            <span class="cart-item-variant d-block mb-1">{{ $row['size_label'] }}</span>
          @endif

          @if(!empty($row['addons']))
            <div class="mb-1">
              @foreach($row['addons'] as $addon)
                <small class="d-block" style="line-height:1.45;overflow-wrap:anywhere;">
                  <span>
                    {{ $addon['name'] ?? '' }}@if(filled($addon['description'] ?? null))/{{ $addon['description'] }}@endif
                    - TK {{ $formatCartMoney($addon['price'] ?? 0) }}
                  </span>
                </small>
              @endforeach
            </div>
          @endif

          <span class="cart-item-price d-block mb-2">TK {{ $formatCartMoney($row['line_total'] ?? 0) }}</span>

          <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap">
            <button type="button"
                    class="btn btn-sm btn-outline-danger"
                    data-cart-remove
                    data-key="{{ $row['key'] }}"
                    aria-label="Remove {{ $row['name'] }} from cart">
               <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M3 6h18" />
                <path d="M8 6V4h8v2" />
                <path d="M19 6l-1 14H6L5 6" />
                <path d="M10 11v5" />
                <path d="M14 11v5" />
              </svg>
            </button>

            <div class="cart-item-qty" style="margin-left:auto;">
              <button type="button" class="qty-btn" data-cart-qty data-key="{{ $row['key'] }}" data-quantity="{{ $row['quantity'] }}" data-delta="-1" aria-label="Decrease quantity">&minus;</button>
              <span class="qty-value">{{ $row['quantity'] }}</span>
              <button type="button" class="qty-btn" data-cart-qty data-key="{{ $row['key'] }}" data-quantity="{{ $row['quantity'] }}" data-delta="1" aria-label="Increase quantity">+</button>
            </div>
          </div>
        </div>
      </div>
    @empty
      <div class="text-center py-5">
        <p class="mb-2">Your cart is empty.</p>
        <a href="{{ route('menu.index') }}" class="auth-link">Browse Menu</a>
      </div>
    @endforelse
  </div>

  <div class="cart-offcanvas-footer" style="flex:0 0 auto;margin-top:auto;position:sticky;bottom:0;z-index:5;background:#fff;">
    <div class="cart-subtotal-row">
      <span>Subtotal</span>
      <span>TK {{ $formatCartMoney($subtotal) }}</span>
    </div>
    @if(!empty($cart))
      <button type="button" class="btn btn-outline-danger w-100 mb-2 fw-semibold" data-cart-clear>Clear Cart</button>
      <a href="{{ route('checkout.index') }}" class="btn-checkout">Proceed to Checkout</a>
    @endif
  </div>
</div>
