<div class="offcanvas-body" style="max-height:calc(100vh - 255px);overflow-y:auto;overscroll-behavior:contain;">
  @forelse($cart as $row)
    <div class="cart-item">
      @if(!empty($row['image']))
        <img src="{{ $adminAssetUrl($row['image']) }}" alt="{{ $row['name'] }}" class="cart-item-img">
      @else
        <img src="{{ asset('public/website/assets/images/food-placeholder.jpg') }}" alt="{{ $row['name'] }}" class="cart-item-img">
      @endif
      <div class="cart-item-info">
        <h6 class="cart-item-name">{{ $row['name'] }}</h6>
        <span class="cart-item-variant">{{ $row['size_label'] }}</span>
        @if(!empty($row['addons']))
          <div class="mt-1">
            @foreach($row['addons'] as $addon)
              <small class="d-block">
                <strong>{{ $addon['name'] ?? '' }}</strong>
                @if(filled($addon['description'] ?? null))
                  <span class="d-block text-muted">{{ $addon['description'] }}</span>
                @endif
              </small>
            @endforeach
          </div>
        @endif
        <span class="cart-item-price">TK {{ rtrim(rtrim(number_format((float)$row['line_total'], 2, '.', ''), '0'), '.') }}</span>
      </div>
      <div class="cart-item-qty">
        <button type="button" class="qty-btn" data-cart-qty data-key="{{ $row['key'] }}" data-quantity="{{ $row['quantity'] }}" data-delta="-1" aria-label="Decrease quantity">&minus;</button>
        <span class="qty-value">{{ $row['quantity'] }}</span>
        <button type="button" class="qty-btn" data-cart-qty data-key="{{ $row['key'] }}" data-quantity="{{ $row['quantity'] }}" data-delta="1" aria-label="Increase quantity">+</button>
        <button type="button" class="qty-btn" data-cart-remove data-key="{{ $row['key'] }}" aria-label="Remove item">&times;</button>
      </div>
    </div>
  @empty
    <div class="text-center py-5">
      <p class="mb-2">Your cart is empty.</p>
      <a href="{{ route('menu.index') }}" class="auth-link">Browse Menu</a>
    </div>
  @endforelse
</div>

<div class="cart-offcanvas-footer">
  <div class="cart-subtotal-row">
    <span>Subtotal</span>
    <span>TK {{ rtrim(rtrim(number_format((float)$subtotal, 2, '.', ''), '0'), '.') }}</span>
  </div>
  @if(!empty($cart))
    <button type="button" class="btn btn-outline-danger w-100 mb-2 fw-semibold" data-cart-clear>Clear Cart</button>
    <a href="{{ route('checkout.index') }}" class="btn-checkout">Proceed to Checkout</a>
  @endif
</div>
