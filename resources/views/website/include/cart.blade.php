<div class="offcanvas offcanvas-end cart-offcanvas" tabindex="-1" id="cartOffcanvas" aria-labelledby="cartOffcanvasLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="cartOffcanvasLabel">Your Cart</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">

    <div class="cart-item">
      <img src="assets/images/food_list/pizza/Margherita.png" alt="Margherita" class="cart-item-img">
      <div class="cart-item-info">
        <h6 class="cart-item-name">Margherita</h6>
        <span class="cart-item-variant">Regular</span>
        <span class="cart-item-price">TK 269</span>
      </div>
      <div class="cart-item-qty">
        <button type="button" class="qty-btn" aria-label="Decrease quantity">&minus;</button>
        <span class="qty-value">1</span>
        <button type="button" class="qty-btn" aria-label="Increase quantity">+</button>
      </div>
    </div>

    <div class="cart-item">
      <img src="assets/images/food_list/burger/Classic-Burger.png" alt="Classic Burger" class="cart-item-img">
      <div class="cart-item-info">
        <h6 class="cart-item-name">Classic Burger</h6>
        <span class="cart-item-variant">Beef</span>
        <span class="cart-item-price">TK 275</span>
      </div>
      <div class="cart-item-qty">
        <button type="button" class="qty-btn" aria-label="Decrease quantity">&minus;</button>
        <span class="qty-value">2</span>
        <button type="button" class="qty-btn" aria-label="Increase quantity">+</button>
      </div>
    </div>

    <div class="cart-item">
      <img src="assets/images/food_list/pasta/Naga-Pasta.png" alt="Naga Pasta" class="cart-item-img">
      <div class="cart-item-info">
        <h6 class="cart-item-name">Naga Pasta</h6>
        <span class="cart-item-variant">Regular</span>
        <span class="cart-item-price">TK 329</span>
      </div>
      <div class="cart-item-qty">
        <button type="button" class="qty-btn" aria-label="Decrease quantity">&minus;</button>
        <span class="qty-value">1</span>
        <button type="button" class="qty-btn" aria-label="Increase quantity">+</button>
      </div>
    </div>

  </div>

  <div class="cart-offcanvas-footer">
    <div class="cart-subtotal-row">
      <span>Subtotal</span>
      <span>TK 1,148</span>
    </div>
    <a href="{{ route('checkout.index') }}" class="btn-checkout">Proceed to Checkout</a>
  </div>
</div>
