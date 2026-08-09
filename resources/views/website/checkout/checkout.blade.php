@extends('website.master.master')
@section('title')
Checkout | Chomok Restaurant
@endsection

@section('css')
@endsection

@section('body')

<!-- Checkout -->
<section class="checkout-section">

  <div class="menu-section-head">
    <span class="badge-text">Almost There</span>
    <h1 class="menu-section-title">Checkout</h1>
  </div>

  <form class="contact-form checkout-form" action="#" method="post">
    <div class="checkout-layout">

      <div class="checkout-form-wrap">
        <h2 class="contact-form-title">Delivery Details</h2>

        <div class="form-row">
          <div class="form-group">
            <label for="checkout-name">Full Name</label>
            <input type="text" id="checkout-name" name="name" placeholder="Your name">
          </div>
          <div class="form-group">
            <label for="checkout-phone">Phone Number</label>
            <input type="tel" id="checkout-phone" name="phone" placeholder="+880 XXX-XXXXXX">
          </div>
        </div>

        <div class="form-group">
          <label for="checkout-address">Delivery Address</label>
          <input type="text" id="checkout-address" name="address" placeholder="House, Road, Area">
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="checkout-city">City</label>
            <input type="text" id="checkout-city" name="city" placeholder="Chittagong">
          </div>
          <div class="form-group">
            <label for="checkout-postcode">Postal Code</label>
            <input type="text" id="checkout-postcode" name="postcode" placeholder="4000">
          </div>
        </div>

        <div class="form-group">
          <label for="checkout-branch">Select Branch</label>
          <select id="checkout-branch" name="branch">
            <option>Head Office</option>
            <option>1st Outlet</option>
            <option>2nd Outlet</option>
            <option>Cloud Kitchen</option>
            <option>Chomok Rajshahi</option>
          </select>
        </div>

        <div class="form-group">
          <label for="checkout-notes">Delivery Notes (Optional)</label>
          <textarea id="checkout-notes" name="notes" rows="3" placeholder="E.g. Ring the bell, leave at the gate..."></textarea>
        </div>

        <h2 class="contact-form-title checkout-payment-title">Payment Method</h2>
        <div class="payment-methods">
          <label class="price-pill payment-pill">
            <input type="radio" name="payment" class="price-pill-input" checked>
            Cash on Delivery
          </label>
          <label class="price-pill payment-pill">
            <input type="radio" name="payment" class="price-pill-input">
            bKash
          </label>
          <label class="price-pill payment-pill">
            <input type="radio" name="payment" class="price-pill-input">
            Nagad
          </label>
          <label class="price-pill payment-pill">
            <input type="radio" name="payment" class="price-pill-input">
            Card
          </label>
        </div>
      </div>

      <div class="checkout-summary">
        <h2 class="contact-form-title">Order Summary</h2>

        <div class="checkout-item">
          <img src="assets/images/food_list/pizza/Margherita.png" alt="Margherita" class="checkout-item-img">
          <div class="checkout-item-info">
            <h4>Margherita</h4>
            <span>Regular &times; 1</span>
          </div>
          <span class="checkout-item-price">TK 269</span>
        </div>

        <div class="checkout-item">
          <img src="assets/images/food_list/burger/Classic-Burger.png" alt="Classic Burger" class="checkout-item-img">
          <div class="checkout-item-info">
            <h4>Classic Burger</h4>
            <span>Beef &times; 2</span>
          </div>
          <span class="checkout-item-price">TK 550</span>
        </div>

        <div class="checkout-item">
          <img src="assets/images/food_list/pasta/Naga-Pasta.png" alt="Naga Pasta" class="checkout-item-img">
          <div class="checkout-item-info">
            <h4>Naga Pasta</h4>
            <span>Regular &times; 1</span>
          </div>
          <span class="checkout-item-price">TK 329</span>
        </div>

        <div class="checkout-totals">
          <div class="checkout-total-row">
            <span>Subtotal</span>
            <span>TK 1,148</span>
          </div>
          <div class="checkout-total-row">
            <span>Delivery Fee</span>
            <span>TK 60</span>
          </div>
          <div class="checkout-total-row checkout-total-final">
            <span>Total</span>
            <span>TK 1,208</span>
          </div>
        </div>

        <button type="submit" class="btn-wc-hero checkout-place-order">Place Order</button>
      </div>

    </div>
  </form>
</section>

@endsection


@section('scripts')

@endsection
