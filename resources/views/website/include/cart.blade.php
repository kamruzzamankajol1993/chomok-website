<div class="offcanvas offcanvas-end cart-offcanvas" tabindex="-1" id="cartOffcanvas" aria-labelledby="cartOffcanvasLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="cartOffcanvasLabel">Your Cart</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div id="cartContentWrap">
    @php($initialCart = session('cart', []))
    @include('website.include.cart-content', ['cart' => $initialCart, 'subtotal' => collect($initialCart)->sum(fn($row) => (float)($row['line_total'] ?? 0))])
  </div>
</div>
