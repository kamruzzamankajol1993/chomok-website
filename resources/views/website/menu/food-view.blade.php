@extends('website.master.master')
@section('title')
Food View | Chomok Restaurant
@endsection

@section('css')
@endsection

@section('body')
<!-- Food View -->
<section class="food-view-section">

  <div class="food-view-layout">

    <div class="food-view-img-col">
      <img src="assets/images/food_list/pizza/Margherita.png" alt="Margherita" class="food-view-img">
    </div>

    <div class="food-view-info-col">
      <h1 class="food-view-name">Margherita</h1>
      <p class="food-view-desc">Sauce &amp; Cheese.</p>

      <div class="food-view-block">
        <h6 class="food-view-label">Price</h6>
        <div class="menu-item-prices">
          <span class="price-pill"><input type="radio" name="view-size" class="price-pill-input" checked><em>Regular</em>TK 269</span>
          <span class="price-pill"><input type="radio" name="view-size" class="price-pill-input"><em>Medium</em>TK 379</span>
          <span class="price-pill"><input type="radio" name="view-size" class="price-pill-input"><em>Large</em>TK 515</span>
        </div>
      </div>

      <div class="food-view-block">
        <h6 class="food-view-label">Add-Ons</h6>
        <div class="addon-list">
          <label class="addon-item">
            <span class="addon-item-check"><input type="checkbox"></span>
            <span class="addon-item-name">Extra Cheese</span>
            <span class="addon-item-price">+TK 40</span>
          </label>
          <label class="addon-item">
            <span class="addon-item-check"><input type="checkbox"></span>
            <span class="addon-item-name">Mushrooms</span>
            <span class="addon-item-price">+TK 30</span>
          </label>
          <label class="addon-item">
            <span class="addon-item-check"><input type="checkbox"></span>
            <span class="addon-item-name">Jalapenos</span>
            <span class="addon-item-price">+TK 25</span>
          </label>
          <label class="addon-item">
            <span class="addon-item-check"><input type="checkbox"></span>
            <span class="addon-item-name">Olives</span>
            <span class="addon-item-price">+TK 25</span>
          </label>
          <label class="addon-item">
            <span class="addon-item-check"><input type="checkbox"></span>
            <span class="addon-item-name">Extra Sauce</span>
            <span class="addon-item-price">+TK 20</span>
          </label>
          <label class="addon-item">
            <span class="addon-item-check"><input type="checkbox"></span>
            <span class="addon-item-name">Extra Patty</span>
            <span class="addon-item-price">+TK 80</span>
          </label>
          <label class="addon-item">
            <span class="addon-item-check"><input type="checkbox"></span>
            <span class="addon-item-name">Cheese Dip</span>
            <span class="addon-item-price">+TK 35</span>
          </label>
          <label class="addon-item">
            <span class="addon-item-check"><input type="checkbox"></span>
            <span class="addon-item-name">Garlic Butter</span>
            <span class="addon-item-price">+TK 25</span>
          </label>
        </div>
      </div>

      <div class="food-view-footer">
        <div class="food-view-qty">
          <button type="button" class="qty-btn" aria-label="Decrease quantity">&minus;</button>
          <span class="qty-value">1</span>
          <button type="button" class="qty-btn" aria-label="Increase quantity">+</button>
        </div>
        <button type="button" class="btn-add-cart food-view-add-btn">Add to Cart &mdash; TK 269</button>
      </div>
    </div>

  </div>

  <!-- Suggestions -->
  <div class="food-view-suggestions">
    <div class="menu-section-head">
      <span class="badge-text">You Might Also Like</span>
      <h2 class="menu-section-title">Pair It With</h2>
    </div>

    <div class="menu-grid-2">

      <div class="food-card-v" data-category="burgers">
        <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_list/burger/Classic-Burger.png" alt="Classic Burger" class="menu-item-img"></a>
        <h3 class="menu-item-name">Classic Burger</h3>
        <div class="menu-item-prices">
          <span class="price-pill"><input type="radio" class="price-pill-input"><em>Chicken</em>TK 245</span>
          <span class="price-pill"><input type="radio" class="price-pill-input"><em>Beef</em>TK 275</span>
        </div>
        <button type="button" class="btn-add-cart">Add to Cart</button>
      </div>

      <div class="food-card-v" data-category="burgers">
        <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_list/burger/Chatgaiya-Burger.png" alt="Chatgaiya Burger" class="menu-item-img"></a>
        <h3 class="menu-item-name">Chatgaiya Burger</h3>
        <div class="menu-item-prices">
          <span class="price-pill"><input type="radio" class="price-pill-input"><em>Chicken</em>TK 385</span>
          <span class="price-pill"><input type="radio" class="price-pill-input"><em>Beef</em>TK 425</span>
        </div>
        <button type="button" class="btn-add-cart">Add to Cart</button>
      </div>

      <div class="food-card-v" data-category="pizza">
        <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_list/pizza/NY-BBQ.png" alt="NY BBQ" class="menu-item-img"></a>
        <h3 class="menu-item-name">NY BBQ</h3>
        <div class="menu-item-prices">
          <span class="price-pill"><input type="radio" class="price-pill-input"><em>Regular</em>TK 435</span>
          <span class="price-pill"><input type="radio" class="price-pill-input"><em>Medium</em>TK 515</span>
          <span class="price-pill"><input type="radio" class="price-pill-input"><em>Large</em>TK 715</span>
        </div>
        <button type="button" class="btn-add-cart">Add to Cart</button>
      </div>

      <div class="food-card-v" data-category="pasta">
        <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_list/pasta/Naga-Pasta.png" alt="Naga Pasta" class="menu-item-img"></a>
        <h3 class="menu-item-name">Naga Pasta</h3>
        <div class="menu-item-price">TK 329</div>
        <button type="button" class="btn-add-cart">Add to Cart</button>
      </div>

    </div>
  </div>

</section>

@include('website.include.cta')
@endsection


@section('scripts')

@endsection
