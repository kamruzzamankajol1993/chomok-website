@extends('website.master.master')
@section('title')
Menu | Chomok Restaurant
@endsection

@section('css')
@endsection

@section('body')
<!-- Menu Page -->
<section class="menu-page-section">

  <div class="menu-section-head">
    <span class="badge-text">Full Menu</span>
    <h1 class="menu-section-title">Browse Our Menu</h1>
  </div>

  <div class="menu-page-layout">

    <!-- Category Sidebar -->
    <aside class="menu-sidebar">
      <ul class="menu-category-list">
        <li><button type="button" class="menu-cat-btn active" data-filter="all">All</button></li>
        <li><button type="button" class="menu-cat-btn" data-filter="pizza">Pizza</button></li>
        <li><button type="button" class="menu-cat-btn" data-filter="burgers">Burgers</button></li>
        <li><button type="button" class="menu-cat-btn" data-filter="pasta">Pasta</button></li>
        <li><button type="button" class="menu-cat-btn" data-filter="broast-chicken">Broast Chicken</button></li>
        <li><button type="button" class="menu-cat-btn" data-filter="broast-meals">Broast Meals</button></li>
        <li><button type="button" class="menu-cat-btn" data-filter="rice-meals">Rice Meals</button></li>
        <li><button type="button" class="menu-cat-btn" data-filter="rice-bowls">Rice Bowls</button></li>
        <li><button type="button" class="menu-cat-btn" data-filter="meatbox">Meatbox</button></li>
        <li><button type="button" class="menu-cat-btn" data-filter="combo">Combo</button></li>
        <li><button type="button" class="menu-cat-btn" data-filter="sides">Sides</button></li>
        <li><button type="button" class="menu-cat-btn" data-filter="dessert">Shakes &amp; Dessert</button></li>
      </ul>
    </aside>

    <!-- Category Offcanvas (opens from the right via the floating Category button, on desktop and mobile) -->
    <div class="offcanvas offcanvas-end category-offcanvas" tabindex="-1" id="categoryOffcanvas" aria-labelledby="categoryOffcanvasLabel">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="categoryOffcanvasLabel">Categories</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <ul class="menu-category-list category-offcanvas-list">
          <li><button type="button" class="menu-cat-btn active" data-filter="all">All</button></li>
          <li><button type="button" class="menu-cat-btn" data-filter="pizza">Pizza</button></li>
          <li><button type="button" class="menu-cat-btn" data-filter="burgers">Burgers</button></li>
          <li><button type="button" class="menu-cat-btn" data-filter="pasta">Pasta</button></li>
          <li><button type="button" class="menu-cat-btn" data-filter="broast-chicken">Broast Chicken</button></li>
          <li><button type="button" class="menu-cat-btn" data-filter="broast-meals">Broast Meals</button></li>
          <li><button type="button" class="menu-cat-btn" data-filter="rice-meals">Rice Meals</button></li>
          <li><button type="button" class="menu-cat-btn" data-filter="rice-bowls">Rice Bowls</button></li>
          <li><button type="button" class="menu-cat-btn" data-filter="meatbox">Meatbox</button></li>
          <li><button type="button" class="menu-cat-btn" data-filter="combo">Combo</button></li>
          <li><button type="button" class="menu-cat-btn" data-filter="sides">Sides</button></li>
          <li><button type="button" class="menu-cat-btn" data-filter="dessert">Shakes &amp; Dessert</button></li>
        </ul>
      </div>
    </div>

    <!-- Food Grid -->
    <div class="menu-page-content">
      <div class="menu-grid-2">

        <!-- Pizza -->
        <div class="food-card-v" data-category="pizza">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_list/pizza/Margherita.png" alt="Margherita" class="menu-item-img"></a>
          <h3 class="menu-item-name">Margherita</h3>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Regular</em>TK 269</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Medium</em>TK 379</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Large</em>TK 515</span>
          </div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="pizza">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_list/pizza/Veg-&-Tez.png" alt="Veg &amp; Tez" class="menu-item-img"></a>
          <h3 class="menu-item-name">Veg &amp; Tez</h3>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Regular</em>TK 315</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Medium</em>TK 425</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Large</em>TK 629</span>
          </div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="pizza">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_list/pizza/Beef-Maximus.png" alt="Beef Maximus" class="menu-item-img"></a>
          <h3 class="menu-item-name">Beef Maximus</h3>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Regular</em>TK 425</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Medium</em>TK 525</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Large</em>TK 649</span>
          </div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="pizza">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_list/pizza/Spicy-Chicken.png" alt="Spicy Chicken" class="menu-item-img"></a>
          <h3 class="menu-item-name">Spicy Chicken</h3>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Regular</em>TK 385</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Medium</em>TK 535</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Large</em>TK 645</span>
          </div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="pizza">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_list/pizza/Classic-Chicken.png" alt="Classic Chicken" class="menu-item-img"></a>
          <h3 class="menu-item-name">Classic Chicken</h3>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Regular</em>TK 435</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Medium</em>TK 535</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Large</em>TK 689</span>
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

        <div class="food-card-v" data-category="pizza">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_list/pizza/Peri-Peri-Chicken.png" alt="Peri Peri Chicken" class="menu-item-img"></a>
          <h3 class="menu-item-name">Peri Peri Chicken</h3>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Regular</em>TK 435</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Medium</em>TK 535</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Large</em>TK 729</span>
          </div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="pizza">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_list/pizza/Favorite-Feast.png" alt="Favorite Feast" class="menu-item-img"></a>
          <h3 class="menu-item-name">Favorite Feast</h3>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Regular</em>TK 435</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Medium</em>TK 545</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Large</em>TK 729</span>
          </div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="pizza">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_list/pizza/Pizza-Americano.png" alt="Pizza Americano" class="menu-item-img"></a>
          <h3 class="menu-item-name">Pizza Americano</h3>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Regular</em>TK 485</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Medium</em>TK 645</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Large</em>TK 749</span>
          </div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="pizza">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_list/pizza/Barbecue-Chicken.png" alt="Barbecue Chicken" class="menu-item-img"></a>
          <h3 class="menu-item-name">Barbecue Chicken</h3>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Regular</em>TK 465</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Medium</em>TK 585</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Large</em>TK 815</span>
          </div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="pizza">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_list/pizza/Kabab-Fantasy.png" alt="Kabab Fantasy" class="menu-item-img"></a>
          <h3 class="menu-item-name">Kabab Fantasy</h3>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Regular</em>TK 495</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Medium</em>TK 649</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Large</em>TK 849</span>
          </div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="pizza">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_list/pizza/Pizza-Supreme.png" alt="Pizza Supreme" class="menu-item-img"></a>
          <h3 class="menu-item-name">Pizza Supreme</h3>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Regular</em>TK 549</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Medium</em>TK 699</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Large</em>TK 849</span>
          </div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="pizza">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_list/pizza/Chomok's-Special.png" alt="Chomok's Special" class="menu-item-img"></a>
          <h3 class="menu-item-name">Chomok's Special</h3>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Regular</em>TK 435</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Medium</em>TK 599</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Large</em>TK 865</span>
          </div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="pizza">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_list/pizza/Pizza-Meatzza.png" alt="Pizza Meatzza" class="menu-item-img"></a>
          <h3 class="menu-item-name">Pizza Meatzza</h3>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Regular</em>TK 445</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Medium</em>TK 659</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Large</em>TK 849</span>
          </div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="pizza">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_list/pizza/Meatlovers.png" alt="Meatlovers" class="menu-item-img"></a>
          <h3 class="menu-item-name">Meatlovers</h3>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Regular</em>TK 549</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Medium</em>TK 699</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Large</em>TK 989</span>
          </div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <!-- Burgers -->
        <div class="food-card-v" data-category="burgers">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_list/burger/Double-Trouble.png" alt="Double Trouble" class="menu-item-img"></a>
          <h3 class="menu-item-name">Double Trouble</h3>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Chicken</em>TK 515</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Beef</em>TK 549</span>
          </div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="burgers">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_list/burger/Mojar-Zinger.png" alt="Mojar Zinger" class="menu-item-img"></a>
          <h3 class="menu-item-name">Mojar Zinger</h3>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Chicken</em>TK 275</span>
          </div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="burgers">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_list/burger/Ultimate-Smashed.png" alt="Ultimate Smashed" class="menu-item-img"></a>
          <h3 class="menu-item-name">Ultimate Smashed</h3>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Single</em>TK 435</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Double</em>TK 715</span>
          </div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="burgers">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_list/burger/Bacon-Cheddar-Smashed.png" alt="Bacon Cheddar Smashed" class="menu-item-img"></a>
          <h3 class="menu-item-name">Bacon Cheddar Smashed</h3>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Single</em>TK 535</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Double</em>TK 769</span>
          </div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

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
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_list/burger/Barbecue-Burger.png" alt="Barbecue Burger" class="menu-item-img"></a>
          <h3 class="menu-item-name">Barbecue Burger</h3>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Chicken</em>TK 275</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Beef</em>TK 289</span>
          </div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="burgers">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_list/burger/All-Day-Breakfast.png" alt="All Day Breakfast" class="menu-item-img"></a>
          <h3 class="menu-item-name">All Day Breakfast</h3>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Chicken</em>TK 385</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Beef</em>TK 385</span>
          </div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="burgers">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_list/burger/Cheese-Blast.png" alt="Cheese Blast" class="menu-item-img"></a>
          <h3 class="menu-item-name">Cheese Blast</h3>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Chicken</em>TK 399</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Beef</em>TK 459</span>
          </div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="burgers">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_list/burger/Chick-Supremeo.png" alt="Chick Supremeo" class="menu-item-img"></a>
          <h3 class="menu-item-name">Chick Supremeo</h3>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Chicken</em>TK 385</span>
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

        <div class="food-card-v" data-category="burgers">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_list/burger/Chorizo-Beef.png" alt="Chorizo Beef" class="menu-item-img"></a>
          <h3 class="menu-item-name">Chorizo Beef</h3>
          <div class="menu-item-price">TK 465</div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="burgers">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_list/burger/Beef-Joe.png" alt="Beef Joe" class="menu-item-img"></a>
          <h3 class="menu-item-name">Beef Joe</h3>
          <div class="menu-item-price">TK 485</div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="burgers">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_list/burger/Fish-O-Filet.png" alt="Fish-O-Filet" class="menu-item-img"></a>
          <h3 class="menu-item-name">Fish-O-Filet</h3>
          <div class="menu-item-price">TK 379</div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="burgers">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_list/burger/Make-Your-Meal.png" alt="Make Your Meal" class="menu-item-img"></a>
          <h3 class="menu-item-name">Make Your Meal</h3>
          <div class="menu-item-price">TK 99</div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <!-- Pasta -->
        <div class="food-card-v" data-category="pasta">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_list/pasta/Arabita-Chicken-Pasta.png" alt="Arabita Chicken Pasta" class="menu-item-img"></a>
          <h3 class="menu-item-name">Arabita Chicken Pasta</h3>
          <div class="menu-item-price">TK 249</div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="pasta">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_list/pasta/Pasta-Basta.png" alt="Pasta Basta" class="menu-item-img"></a>
          <h3 class="menu-item-name">Pasta Basta</h3>
          <div class="menu-item-price">TK 275</div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="pasta">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_list/pasta/Naga-Pasta.png" alt="Naga Pasta" class="menu-item-img"></a>
          <h3 class="menu-item-name">Naga Pasta</h3>
          <div class="menu-item-price">TK 329</div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="pasta">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_list/pasta/Creamy-Fussili.png" alt="Creamy Fussili" class="menu-item-img"></a>
          <h3 class="menu-item-name">Creamy Fussili</h3>
          <div class="menu-item-price">TK 315</div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="pasta">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_list/pasta/Chilli-Mac-&-Cheese.png" alt="Chilli Mac &amp; Cheese" class="menu-item-img"></a>
          <h3 class="menu-item-name">Chilli Mac &amp; Cheese</h3>
          <div class="menu-item-price">TK 385</div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <!-- Broast Chicken -->
        <div class="food-card-v" data-category="broast-chicken">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_image.png" alt="Broast Chicken" class="menu-item-img"></a>
          <h3 class="menu-item-name">Broast Chicken</h3>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>2 Pcs</em>TK 285</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>4 Pcs</em>TK 549</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>8 Pcs</em>TK 1,099</span>
          </div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="broast-chicken">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_image.png" alt="20 Pcs Bucket" class="menu-item-img"></a>
          <h3 class="menu-item-name">20 Pcs Bucket</h3>
          <div class="menu-item-price">TK 1,099</div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="broast-chicken">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_image.png" alt="Coleslaw" class="menu-item-img"></a>
          <h3 class="menu-item-name">Coleslaw</h3>
          <div class="menu-item-price">TK 85</div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <!-- Broast Meals -->
        <div class="food-card-v" data-category="broast-meals">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_image.png" alt="Broast Meal - 1" class="menu-item-img"></a>
          <h3 class="menu-item-name">Broast Meal - 1</h3>
          <div class="menu-item-price">TK 385</div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="broast-meals">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_image.png" alt="Broast Meal - 2" class="menu-item-img"></a>
          <h3 class="menu-item-name">Broast Meal - 2</h3>
          <div class="menu-item-price">TK 499</div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="broast-meals">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_image.png" alt="Broast Meal - 3" class="menu-item-img"></a>
          <h3 class="menu-item-name">Broast Meal - 3</h3>
          <div class="menu-item-price">TK 439</div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="broast-meals">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_image.png" alt="Broast Meal - 4" class="menu-item-img"></a>
          <h3 class="menu-item-name">Broast Meal - 4</h3>
          <div class="menu-item-price">TK 385</div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="broast-meals">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_image.png" alt="Broast Meal - 5" class="menu-item-img"></a>
          <h3 class="menu-item-name">Broast Meal - 5</h3>
          <div class="menu-item-price">TK 385</div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="broast-meals">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_image.png" alt="Broast Meal - 6" class="menu-item-img"></a>
          <h3 class="menu-item-name">Broast Meal - 6</h3>
          <div class="menu-item-price">TK 329</div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="broast-meals">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_image.png" alt="Broast Meal - 7" class="menu-item-img"></a>
          <h3 class="menu-item-name">Broast Meal - 7</h3>
          <div class="menu-item-price">TK 329</div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <!-- Rice Meals -->
        <div class="food-card-v" data-category="rice-meals">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_image.png" alt="Munchurian Chicken" class="menu-item-img"></a>
          <h3 class="menu-item-name">Munchurian Chicken</h3>
          <div class="menu-item-price">TK 329</div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="rice-meals">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_image.png" alt="BBQ Chicken Meal" class="menu-item-img"></a>
          <h3 class="menu-item-name">BBQ Chicken Meal</h3>
          <div class="menu-item-price">TK 385</div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="rice-meals">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_image.png" alt="Chicken Drums" class="menu-item-img"></a>
          <h3 class="menu-item-name">Chicken Drums</h3>
          <div class="menu-item-price">TK 385</div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="rice-meals">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_image.png" alt="Bangkok Chicken" class="menu-item-img"></a>
          <h3 class="menu-item-name">Bangkok Chicken</h3>
          <div class="menu-item-price">TK 419</div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="rice-meals">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_image.png" alt="Peri-Peri Chicken" class="menu-item-img"></a>
          <h3 class="menu-item-name">Peri-Peri Chicken</h3>
          <div class="menu-item-price">TK 425</div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="rice-meals">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_image.png" alt="Stuffed Mushroom Chicken" class="menu-item-img"></a>
          <h3 class="menu-item-name">Stuffed Mushroom Chicken</h3>
          <div class="menu-item-price">TK 475</div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <!-- Rice Bowls -->
        <div class="food-card-v" data-category="rice-bowls">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_image.png" alt="BBQ Rice Bowl" class="menu-item-img"></a>
          <h3 class="menu-item-name">BBQ Rice Bowl</h3>
          <div class="menu-item-price">TK 275</div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="rice-bowls">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_image.png" alt="Achari Rice Bowl" class="menu-item-img"></a>
          <h3 class="menu-item-name">Achari Rice Bowl</h3>
          <div class="menu-item-price">TK 275</div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="rice-bowls">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_image.png" alt="Gyro Chicken Rice Bowl" class="menu-item-img"></a>
          <h3 class="menu-item-name">Gyro Chicken Rice Bowl</h3>
          <div class="menu-item-price">TK 385</div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <!-- Meatbox -->
        <div class="food-card-v" data-category="meatbox">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_image.png" alt="Classic Meatbox" class="menu-item-img"></a>
          <h3 class="menu-item-name">Classic Meatbox</h3>
          <div class="menu-item-price">TK 329</div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="meatbox">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_image.png" alt="Naga Meatbox" class="menu-item-img"></a>
          <h3 class="menu-item-name">Naga Meatbox</h3>
          <div class="menu-item-price">TK 349</div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="meatbox">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_image.png" alt="Twist Box" class="menu-item-img"></a>
          <h3 class="menu-item-name">Twist Box</h3>
          <div class="menu-item-price">TK 385</div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <!-- Combo -->
        <div class="food-card-v" data-category="combo">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_image.png" alt="Classic Box" class="menu-item-img"></a>
          <h3 class="menu-item-name">Classic Box</h3>
          <div class="menu-item-price">TK 499</div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="combo">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_image.png" alt="Pizza Lovers" class="menu-item-img"></a>
          <h3 class="menu-item-name">Pizza Lovers</h3>
          <div class="menu-item-price">TK 549</div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="combo">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_image.png" alt="Mama Treat" class="menu-item-img"></a>
          <h3 class="menu-item-name">Mama Treat</h3>
          <div class="menu-item-price">TK 1,199</div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <!-- Sides -->
        <div class="food-card-v" data-category="sides">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_image.png" alt="Stuffed Garlic Bread" class="menu-item-img"></a>
          <h3 class="menu-item-name">Stuffed Garlic Bread</h3>
          <div class="menu-item-price">TK 275</div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="sides">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_image.png" alt="Fish Strips" class="menu-item-img"></a>
          <h3 class="menu-item-name">Fish Strips</h3>
          <div class="menu-item-price">TK 259</div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="sides">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_image.png" alt="Chicken Tender" class="menu-item-img"></a>
          <h3 class="menu-item-name">Chicken Tender</h3>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>3 Pcs</em>TK 169</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>6 Pcs</em>TK 299</span>
          </div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="sides">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_image.png" alt="Naga Wings (6 Pcs)" class="menu-item-img"></a>
          <h3 class="menu-item-name">Naga Wings (6 Pcs)</h3>
          <div class="menu-item-price">TK 259</div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="sides">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_image.png" alt="BBQ Spicy Wings (6 Pcs)" class="menu-item-img"></a>
          <h3 class="menu-item-name">BBQ Spicy Wings (6 Pcs)</h3>
          <div class="menu-item-price">TK 259</div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="sides">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_image.png" alt="Peri-Peri Wings (6 Pcs)" class="menu-item-img"></a>
          <h3 class="menu-item-name">Peri-Peri Wings (6 Pcs)</h3>
          <div class="menu-item-price">TK 259</div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="sides">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_image.png" alt="Cheese Ball (4 Pcs)" class="menu-item-img"></a>
          <h3 class="menu-item-name">Cheese Ball (4 Pcs)</h3>
          <div class="menu-item-price">TK 209</div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="sides">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_image.png" alt="Fries" class="menu-item-img"></a>
          <h3 class="menu-item-name">Fries</h3>
          <div class="menu-item-price">TK 149</div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="sides">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_image.png" alt="Beef Tacos (2 Pcs)" class="menu-item-img"></a>
          <h3 class="menu-item-name">Beef Tacos (2 Pcs)</h3>
          <div class="menu-item-price">TK 359</div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="sides">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_image.png" alt="Peri-Peri Tacos (2 Pcs)" class="menu-item-img"></a>
          <h3 class="menu-item-name">Peri-Peri Tacos (2 Pcs)</h3>
          <div class="menu-item-price">TK 329</div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <!-- Shakes & Dessert -->
        <div class="food-card-v" data-category="dessert">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_image.png" alt="Lemon Krusher" class="menu-item-img"></a>
          <h3 class="menu-item-name">Lemon Krusher</h3>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Regular</em>TK 109</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Large</em>TK 139</span>
          </div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="dessert">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_image.png" alt="Chocolate Krusher" class="menu-item-img"></a>
          <h3 class="menu-item-name">Chocolate Krusher</h3>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Regular</em>TK 145</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Large</em>TK 219</span>
          </div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="dessert">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_image.png" alt="Strawberry" class="menu-item-img"></a>
          <h3 class="menu-item-name">Strawberry</h3>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Regular</em>TK 145</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Large</em>TK 219</span>
          </div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="dessert">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_image.png" alt="Peanut Butter" class="menu-item-img"></a>
          <h3 class="menu-item-name">Peanut Butter</h3>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Regular</em>TK 199</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Large</em>TK 249</span>
          </div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="dessert">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_image.png" alt="Goa Lemonade" class="menu-item-img"></a>
          <h3 class="menu-item-name">Goa Lemonade</h3>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Regular</em>TK 149</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Large</em>TK 219</span>
          </div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="dessert">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_image.png" alt="Chocolate Lava" class="menu-item-img"></a>
          <h3 class="menu-item-name">Chocolate Lava</h3>
          <div class="menu-item-price">TK 165</div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="dessert">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_image.png" alt="Water" class="menu-item-img"></a>
          <h3 class="menu-item-name">Water</h3>
          <div class="menu-item-price">At MRP</div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

        <div class="food-card-v" data-category="dessert">
          <a href="food-view.php" class="food-card-v-img"><img src="assets/images/food_image.png" alt="Beverage (Fountain)" class="menu-item-img"></a>
          <h3 class="menu-item-name">Beverage (Fountain)</h3>
          <div class="menu-item-price">TK 65</div>
          <button type="button" class="btn-add-cart">Add to Cart</button>
        </div>

      </div>
    </div>

  </div>
</section>

@include('website.include.cta')
@endsection


@section('scripts')

@endsection
