@extends('website.master.master')

@section('title')
Chomok Restaurant
@endsection

@section('css')
@endsection

@section('body')
<!-- Hero Slider -->
<section class="hero-slider">
  <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-pause="hover">

    <div class="carousel-inner">

      <div class="carousel-item active" data-bs-interval="5000">
        <img src="assets/images/slider/slider1.jpg" class="d-block w-100 slider-img" alt="Fresh Margherita Pizza">
        <div class="slide-overlay"></div>
        <div class="slider-caption">
          <div class="caption-inner">
            <span class="caption-eyebrow">Fresh From The Oven</span>
            <h2 class="caption-title">Authentic Taste,<br>Delivered Fresh</h2>
            <p class="caption-text">Hand-tossed pizzas made with fresh basil, ripe tomatoes and melted cheese, every single time.</p>
            <div class="caption-buttons">
              <a href="menu.php" class="btn btn-brand-yellow">View Menu</a>
              <a href="book.php" class="btn btn-brand-outline">Book a Table</a>
            </div>
          </div>
        </div>
      </div>

      <div class="carousel-item" data-bs-interval="5000">
        <img src="assets/images/slider/slider2.jpg" class="d-block w-100 slider-img" alt="Juicy Cheeseburger">
        <div class="slide-overlay"></div>
        <div class="slider-caption">
          <div class="caption-inner">
            <span class="caption-eyebrow">Grilled To Perfection</span>
            <h2 class="caption-title">Juicy Burgers,<br>Bold Flavors</h2>
            <p class="caption-text">Thick, juicy patties stacked high with fresh veggies and melted cheese in a toasted bun.</p>
            <div class="caption-buttons">
              <a href="menu.php" class="btn btn-brand-yellow">View Menu</a>
              <a href="book.php" class="btn btn-brand-outline">Book a Table</a>
            </div>
          </div>
        </div>
      </div>

      <div class="carousel-item" data-bs-interval="5000">
        <img src="assets/images/slider/slider3.jpg" class="d-block w-100 slider-img" alt="Crispy Chicken Tenders">
        <div class="slide-overlay"></div>
        <div class="slider-caption">
          <div class="caption-inner">
            <span class="caption-eyebrow">Crispy &amp; Golden</span>
            <h2 class="caption-title">Crispy Tenders,<br>Made To Share</h2>
            <p class="caption-text">Golden fried chicken tenders served with crispy fries and your favorite dipping sauces.</p>
            <div class="caption-buttons">
              <a href="menu.php" class="btn btn-brand-yellow">View Menu</a>
              <a href="book.php" class="btn btn-brand-outline">Book a Table</a>
            </div>
          </div>
        </div>
      </div>

    </div>

  </div>
</section>

<!-- Category Scroller -->
<section class="category-scroller">
  <div class="scroller-track">
    <div class="scroller-item"><span class="scroller-emoji">🍕</span> Pizza</div>
    <div class="scroller-item"><span class="scroller-emoji">🍔</span> Burger</div>
    <div class="scroller-item"><span class="scroller-emoji">🍗</span> Fried Chicken</div>
    <div class="scroller-item"><span class="scroller-emoji">🍛</span> Biryani</div>
    <div class="scroller-item"><span class="scroller-emoji">🍝</span> Pasta</div>
    <div class="scroller-item"><span class="scroller-emoji">🌮</span> Tacos</div>
    <div class="scroller-item"><span class="scroller-emoji">🍣</span> Sushi</div>
    <div class="scroller-item"><span class="scroller-emoji">🥗</span> Salad</div>
    <div class="scroller-item"><span class="scroller-emoji">🍰</span> Dessert</div>
    <div class="scroller-item"><span class="scroller-emoji">🥤</span> Drinks</div>

    <!-- duplicate set for a seamless loop -->
    <div class="scroller-item" aria-hidden="true"><span class="scroller-emoji">🍕</span> Pizza</div>
    <div class="scroller-item" aria-hidden="true"><span class="scroller-emoji">🍔</span> Burger</div>
    <div class="scroller-item" aria-hidden="true"><span class="scroller-emoji">🍗</span> Fried Chicken</div>
    <div class="scroller-item" aria-hidden="true"><span class="scroller-emoji">🍛</span> Biryani</div>
    <div class="scroller-item" aria-hidden="true"><span class="scroller-emoji">🍝</span> Pasta</div>
    <div class="scroller-item" aria-hidden="true"><span class="scroller-emoji">🌮</span> Tacos</div>
    <div class="scroller-item" aria-hidden="true"><span class="scroller-emoji">🍣</span> Sushi</div>
    <div class="scroller-item" aria-hidden="true"><span class="scroller-emoji">🥗</span> Salad</div>
    <div class="scroller-item" aria-hidden="true"><span class="scroller-emoji">🍰</span> Dessert</div>
    <div class="scroller-item" aria-hidden="true"><span class="scroller-emoji">🥤</span> Drinks</div>
  </div>
</section>

<!-- About Section -->
<section class="about-section">
  <div class="about-container">

    <div class="about-badge">
      <span class="badge-icon" aria-hidden="true">
        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6">
          <path d="M6 2v8a2 2 0 0 0 2 2v10M6 2a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2M9 2v10M18 2c-2 0-3.5 2-3.5 5s1 4 1.5 4v11" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </span>
      <span class="badge-text">Taste Of World Class Food</span>
    </div>

    <h2 class="about-heading">
      <span class="heading-row">
        <img src="assets/images/slider/slider2.jpg" class="heading-img pill-img" alt="Guests enjoying a meal">
        <span class="heading-word">Experience</span>
        <img src="assets/images/slider/slider1.jpg" class="heading-img circle-img" alt="Signature Chomok dish">
      </span>
      <span class="heading-row heading-row-2">Culinary Excellence</span>
    </h2>

    <div class="about-footer-row">
      <div class="about-text-col">
        <p class="about-text">A good restaurant is like a vacation, it transports you, and it becomes a lot more than just about the food.</p>
        <a href="menu.php" class="btn-about-cta">
          Our Delicious Item
          <span class="cta-arrow" aria-hidden="true">&#8594;</span>
        </a>
      </div>

      <div class="about-trust-col">
        <svg class="trust-arrow" viewBox="0 0 90 70" width="70" height="55" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true">
          <path d="M4 8c8 0 30 2 34 24s-16 24-8 6 26-20 36-16" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="M52 12l14 10-16 6" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>

        <div class="trust-avatars">
          <span class="trust-avatar avatar-1">🧑</span>
          <span class="trust-avatar avatar-2">👩</span>
          <span class="trust-avatar avatar-3">👨</span>
        </div>

        <div class="trust-count">
          <strong>10K+</strong>
          <span>Trusted by Families</span>
        </div>
      </div>
    </div>

  </div>
</section>

<!-- Menu / Product List -->
<section class="menu-section" id="menu">

  <div class="menu-section-head">
    <span class="badge-text">Our Menu</span>
    <h2 class="menu-section-title">What We're Serving</h2>
  </div>

  <!-- Pizza Menu -->
  <div class="menu-category menu-cat-pizza">
    <div class="menu-category-inner">
      <h3 class="menu-cat-title">Pizza Menu</h3>

      <h4 class="menu-subtitle">1. Regular Pizza</h4>
      <div class="menu-grid">
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_list/pizza/Margherita.png" alt="Margherita" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Margherita</h5>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Regular</em>TK 269</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Medium</em>TK 379</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Large</em>TK 515</span>
          </div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_list/pizza/Veg-&amp;-Tez.png" alt="Veg &amp; Tez" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Veg &amp; Tez</h5>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Regular</em>TK 315</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Medium</em>TK 425</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Large</em>TK 629</span>
          </div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_list/pizza/Beef-Maximus.png" alt="Beef Maximus" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Beef Maximus</h5>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Regular</em>TK 425</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Medium</em>TK 525</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Large</em>TK 649</span>
          </div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_list/pizza/Spicy-Chicken.png" alt="Spicy Chicken" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Spicy Chicken</h5>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Regular</em>TK 385</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Medium</em>TK 535</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Large</em>TK 645</span>
          </div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_list/pizza/Classic-Chicken.png" alt="Classic Chicken" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Classic Chicken</h5>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Regular</em>TK 435</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Medium</em>TK 535</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Large</em>TK 689</span>
          </div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
      </div>

      <h4 class="menu-subtitle">2. Loaded Pizza</h4>
      <div class="menu-grid">
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_list/pizza/NY-BBQ.png" alt="NY BBQ" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">NY BBQ</h5>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Regular</em>TK 435</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Medium</em>TK 515</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Large</em>TK 715</span>
          </div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_list/pizza/Peri-Peri-Chicken.png" alt="Peri Peri Chicken" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Peri Peri Chicken</h5>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Regular</em>TK 435</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Medium</em>TK 535</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Large</em>TK 729</span>
          </div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_list/pizza/Favorite-Feast.png" alt="Favorite Feast" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Favorite Feast</h5>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Regular</em>TK 435</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Medium</em>TK 545</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Large</em>TK 729</span>
          </div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_list/pizza/Pizza-Americano.png" alt="Pizza Americano" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Pizza Americano</h5>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Regular</em>TK 485</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Medium</em>TK 645</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Large</em>TK 749</span>
          </div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_list/pizza/Barbecue-Chicken.png" alt="Barbecue Chicken" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Barbecue Chicken</h5>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Regular</em>TK 465</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Medium</em>TK 585</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Large</em>TK 815</span>
          </div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
      </div>

      <h4 class="menu-subtitle">3. Overloaded Pizza</h4>
      <div class="menu-grid">
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_list/pizza/Kabab-Fantasy.png" alt="Kabab Fantasy" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Kabab Fantasy</h5>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Regular</em>TK 495</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Medium</em>TK 649</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Large</em>TK 849</span>
          </div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_list/pizza/Pizza-Supreme.png" alt="Pizza Supreme" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Pizza Supreme</h5>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Regular</em>TK 549</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Medium</em>TK 699</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Large</em>TK 849</span>
          </div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_list/pizza/Chomok's-Special.png" alt="Chomok's Special" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Chomok's Special</h5>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Regular</em>TK 435</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Medium</em>TK 599</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Large</em>TK 865</span>
          </div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_list/pizza/Pizza-Meatzza.png" alt="Pizza Meatzza" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Pizza Meatzza</h5>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Regular</em>TK 445</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Medium</em>TK 659</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Large</em>TK 849</span>
          </div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_list/pizza/Meatlovers.png" alt="Meatlovers" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Meatlovers</h5>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Regular</em>TK 549</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Medium</em>TK 699</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Large</em>TK 989</span>
          </div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
      </div>

    </div>
  </div>

  <!-- Burgers Menu -->
  <div class="menu-category menu-cat-burgers">
    <div class="menu-category-inner">
      <h3 class="menu-cat-title">Burgers Menu</h3>

      <h4 class="menu-subtitle">1. New Burgers</h4>
      <div class="menu-grid">
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_list/burger/Double-Trouble.png" alt="Double Trouble" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Double Trouble</h5>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Chicken</em>TK 515</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Beef</em>TK 549</span>
          </div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_list/burger/Mojar-Zinger.png" alt="Mojar Zinger" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Mojar Zinger</h5>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Chicken</em>TK 275</span>
          </div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
      </div>

      <h4 class="menu-subtitle">2. Smashed Burgers</h4>
      <div class="menu-grid">
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_list/burger/Ultimate-Smashed.png" alt="Ultimate Smashed" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Ultimate Smashed</h5>
          <p class="menu-item-desc">Smashed Patty, Cheese, Caramelized Mushroom, Jalapeno</p>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Single (1 Patty, 1 Cheese)</em>TK 435</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Double (2 Patties, 2 Cheese)</em>TK 715</span>
          </div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_list/burger/Bacon-Cheddar-Smashed.png" alt="Bacon Cheddar Smashed" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Bacon Cheddar Smashed</h5>
          <p class="menu-item-desc">Smashed Patty, Cheese, Egg, Bacon, Caramelized Onion</p>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Single (1 Patty, 1 Cheese)</em>TK 535</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Double (2 Patties, 2 Cheese)</em>TK 769</span>
          </div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
      </div>

      <h4 class="menu-subtitle">3. Signature Burgers</h4>
      <div class="menu-grid">
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_list/burger/Classic-Burger.png" alt="Classic Burger" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Classic Burger</h5>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Chicken</em>TK 245</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Beef</em>TK 275</span>
          </div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_list/burger/Barbecue-Burger.png" alt="Barbecue Burger" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Barbecue Burger</h5>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Chicken</em>TK 275</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Beef</em>TK 289</span>
          </div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_list/burger/All-Day-Breakfast.png" alt="All Day Breakfast" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">All Day Breakfast</h5>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Chicken</em>TK 385</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Beef</em>TK 385</span>
          </div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_list/burger/Cheese-Blast.png" alt="Cheese Blast" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Cheese Blast</h5>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Chicken</em>TK 399</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Beef</em>TK 459</span>
          </div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_list/burger/Chick-Supremeo.png" alt="Chick Supremeo" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Chick Supremeo</h5>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Chicken</em>TK 385</span>
          </div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_list/burger/Chatgaiya-Burger.png" alt="Chatgaiya Burger" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Chatgaiya Burger</h5>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Chicken</em>TK 385</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Beef</em>TK 425</span>
          </div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_list/burger/Chorizo-Beef.png" alt="Chorizo Beef" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Chorizo Beef</h5>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Beef</em>TK 465</span>
          </div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_list/burger/Beef-Joe.png" alt="Beef Joe" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Beef Joe</h5>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Beef</em>TK 485</span>
          </div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_list/burger/Fish-O-Filet.png" alt="Fish-O-Filet" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Fish-O-Filet</h5>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Fish</em>TK 379</span>
          </div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
      </div>

      <h4 class="menu-subtitle">4. Burger Combo Deal</h4>
      <div class="menu-grid">
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_list/burger/Make-Your-Meal.png" alt="Make Your Meal" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Make Your Meal</h5>
          <p class="menu-item-desc">Add Half Fries &amp; Soft Drinks to any burger</p>
          <div class="menu-item-price">TK 99</div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
      </div>

    </div>
  </div>

  <!-- Pasta Menu -->
  <div class="menu-category menu-cat-pasta">
    <div class="menu-category-inner">
      <h3 class="menu-cat-title">Pasta Menu</h3>
      <div class="menu-grid">
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_list/pasta/Arabita-Chicken-Pasta.png" alt="Arabita Chicken Pasta" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Arabita Chicken Pasta</h5>
          <div class="menu-item-price">TK 249</div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_list/pasta/Pasta-Basta.png" alt="Pasta Basta" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Pasta Basta</h5>
          <div class="menu-item-price">TK 275</div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_list/pasta/Naga-Pasta.png" alt="Naga Pasta" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Naga Pasta</h5>
          <div class="menu-item-price">TK 329</div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_list/pasta/Creamy-Fussili.png" alt="Creamy Fussili" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Creamy Fussili</h5>
          <div class="menu-item-price">TK 315</div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_list/pasta/Chilli-Mac-&amp;-Cheese.png" alt="Chilli Mac &amp; Cheese" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Chilli Mac &amp; Cheese</h5>
          <div class="menu-item-price">TK 385</div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Broast Chicken -->
  <div class="menu-category menu-cat-broast-chicken">
    <div class="menu-category-inner">
      <h3 class="menu-cat-title">Broast Chicken</h3>
      <div class="menu-grid">
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_image.png" alt="Food photo" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Broast Chicken</h5>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>2 Pcs</em>TK 285</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>4 Pcs</em>TK 549</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>8 Pcs</em>TK 1,099</span>
          </div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_image.png" alt="Food photo" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">20 Pcs Bucket</h5>
          <p class="menu-item-desc">4 Pcs Broast, 8 Pcs Strips, 8 Wings</p>
          <div class="menu-item-price">TK 1,099</div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_image.png" alt="Food photo" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Coleslaw</h5>
          <div class="menu-item-price">TK 85</div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Broast Meals -->
  <div class="menu-category menu-cat-broast-meals">
    <div class="menu-category-inner">
      <h3 class="menu-cat-title">Broast Meals</h3>
      <div class="menu-grid">
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_image.png" alt="Food photo" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Broast Meal - 1</h5>
          <p class="menu-item-desc">1 Pc Fried Chicken, Fried Rice, Vegetables, 1 Soft Drink</p>
          <div class="menu-item-price">TK 385</div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_image.png" alt="Food photo" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Broast Meal - 2</h5>
          <p class="menu-item-desc">2 Pcs Chicken, 1 Fries, 1 Soft Drink</p>
          <div class="menu-item-price">TK 499</div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_image.png" alt="Food photo" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Broast Meal - 3</h5>
          <p class="menu-item-desc">Fried Rice, 1 Broast Chicken, Soup, Vegetables, 1 Soft Drink</p>
          <div class="menu-item-price">TK 439</div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_image.png" alt="Food photo" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Broast Meal - 4</h5>
          <p class="menu-item-desc">Crispy Honey Chicken Salad, Fried Rice, 1 Soft Drink</p>
          <div class="menu-item-price">TK 385</div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_image.png" alt="Food photo" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Broast Meal - 5</h5>
          <p class="menu-item-desc">2 Fried Chicken, Coleslaw, Bun, 1 Soft Drink</p>
          <div class="menu-item-price">TK 385</div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_image.png" alt="Food photo" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Broast Meal - 6</h5>
          <p class="menu-item-desc">Fried Rice, 3 Fried Prawns, Vegetables, 1 Soft Drink</p>
          <div class="menu-item-price">TK 329</div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_image.png" alt="Food photo" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Broast Meal - 7</h5>
          <p class="menu-item-desc">Fried Rice, Chicken Masala, Vegetables, 1 Soft Drink</p>
          <div class="menu-item-price">TK 329</div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Rice Meals -->
  <div class="menu-category menu-cat-rice-meals">
    <div class="menu-category-inner">
      <h3 class="menu-cat-title">Rice Meals</h3>
      <div class="menu-grid">
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_image.png" alt="Food photo" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Munchurian Chicken</h5>
          <p class="menu-item-desc">Manchurian Chicken, Fried Rice, Vegetables, 2 Spring Roll</p>
          <div class="menu-item-price">TK 329</div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_image.png" alt="Food photo" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">BBQ Chicken Meal</h5>
          <p class="menu-item-desc">Quarter BBQ Chicken, Sauteed Vegetables, Fried Rice</p>
          <div class="menu-item-price">TK 385</div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_image.png" alt="Food photo" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Chicken Drums</h5>
          <p class="menu-item-desc">2 Chicken Drums, Chinese Vegetables, Fried Rice, Salads</p>
          <div class="menu-item-price">TK 385</div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_image.png" alt="Food photo" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Bangkok Chicken</h5>
          <p class="menu-item-desc">2 Pcs Bangkok Fried Chicken, 2 Spring Roll, Chicken Chilli, Vegetables, Fried Rice</p>
          <div class="menu-item-price">TK 419</div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_image.png" alt="Food photo" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Peri-Peri Chicken</h5>
          <p class="menu-item-desc">Quarter Peri-Peri Chicken, Sauteed Vegetables, Fried Rice</p>
          <div class="menu-item-price">TK 425</div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_image.png" alt="Food photo" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Stuffed Mushroom Chicken</h5>
          <p class="menu-item-desc">Creamy Cheese Mushroom Stuffed with Chicken, Served with Sauteed Vegetables &amp; Rice (Achari, Naga, or Fried Rice)</p>
          <div class="menu-item-price">TK 475</div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Rice Bowls -->
  <div class="menu-category menu-cat-rice-bowls">
    <div class="menu-category-inner">
      <h3 class="menu-cat-title">Rice Bowls</h3>
      <div class="menu-grid">
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_image.png" alt="Food photo" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">BBQ Rice Bowl</h5>
          <p class="menu-item-desc">BBQ Chicken, Egg, Fried Rice</p>
          <div class="menu-item-price">TK 275</div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_image.png" alt="Food photo" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Achari Rice Bowl</h5>
          <p class="menu-item-desc">Achari Rice, Chicken Popcorn, Egg &amp; Yogurt Sauce</p>
          <div class="menu-item-price">TK 275</div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_image.png" alt="Food photo" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Gyro Chicken Rice Bowl</h5>
          <p class="menu-item-desc">Gyro Chicken Served with Achari/Naga Rice, Fries/Vegetables, Yogurt Sauce</p>
          <div class="menu-item-price">TK 385</div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Meatbox -->
  <div class="menu-category menu-cat-meatbox">
    <div class="menu-category-inner">
      <h3 class="menu-cat-title">Meatbox</h3>
      <div class="menu-grid">
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_image.png" alt="Food photo" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Classic Meatbox</h5>
          <p class="menu-item-desc">Chicken, Sausage, Fries &amp; Sauce</p>
          <div class="menu-item-price">TK 329</div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_image.png" alt="Food photo" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Naga Meatbox</h5>
          <p class="menu-item-desc">Chicken, Chicken Krunch, Fries &amp; Sauce</p>
          <div class="menu-item-price">TK 349</div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_image.png" alt="Food photo" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Twist Box</h5>
          <p class="menu-item-desc">Chicken, Chicken Meat Ball, Chicken Krunch, Fries, Sauce, Vegetables &amp; Cheese Ball</p>
          <div class="menu-item-price">TK 385</div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Combo -->
  <div class="menu-category menu-cat-combo">
    <div class="menu-category-inner">
      <h3 class="menu-cat-title">Combo</h3>
      <div class="menu-grid">
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_image.png" alt="Food photo" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Classic Box</h5>
          <p class="menu-item-desc">1 Classic Burger + 1 Pcs Chicken Tender + Fries + 2 Pcs Wings (Any Flavour of Your Choice) + 250ml Soft Drink</p>
          <div class="menu-item-price">TK 499</div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_image.png" alt="Food photo" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Pizza Lovers</h5>
          <p class="menu-item-desc">1 Regular Ny Barbecue + Fries + 2 Soft Drinks</p>
          <div class="menu-item-price">TK 549</div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_image.png" alt="Food photo" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Mama Treat</h5>
          <p class="menu-item-desc">1 Regular Pizza, 8 Pcs Chicken Strips, 8 Pcs Wings, 4 Soft Drinks</p>
          <div class="menu-item-price">TK 1,199</div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Sides -->
  <div class="menu-category menu-cat-sides">
    <div class="menu-category-inner">
      <h3 class="menu-cat-title">Sides</h3>
      <div class="menu-grid">
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_image.png" alt="Food photo" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Stuffed Garlic Bread</h5>
          <div class="menu-item-price">TK 275</div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_image.png" alt="Food photo" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Fish Strips</h5>
          <div class="menu-item-price">TK 259</div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_image.png" alt="Food photo" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Chicken Tender</h5>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>3 Pcs</em>TK 169</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>6 Pcs</em>TK 299</span>
          </div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_image.png" alt="Food photo" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Naga Wings (6 Pcs)</h5>
          <div class="menu-item-price">TK 259</div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_image.png" alt="Food photo" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">BBQ Spicy Wings (6 Pcs)</h5>
          <div class="menu-item-price">TK 259</div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_image.png" alt="Food photo" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Peri-Peri Wings (6 Pcs)</h5>
          <div class="menu-item-price">TK 259</div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_image.png" alt="Food photo" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Cheese Ball (4 Pcs)</h5>
          <div class="menu-item-price">TK 209</div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_image.png" alt="Food photo" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Fries</h5>
          <p class="menu-item-desc">Imported Fries Tossed with 5 Spices</p>
          <div class="menu-item-price">TK 149</div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_image.png" alt="Food photo" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Beef Tacos (2 Pcs)</h5>
          <p class="menu-item-desc">Filler Cheese, Beef, Capsicum, Onion</p>
          <div class="menu-item-price">TK 359</div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_image.png" alt="Food photo" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Peri-Peri Tacos (2 Pcs)</h5>
          <p class="menu-item-desc">Filler Cheese, Capsicum, Onion, Chicken &amp; Peri-Peri Sauce</p>
          <div class="menu-item-price">TK 329</div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Shakes & Dessert -->
  <div class="menu-category menu-cat-dessert">
    <div class="menu-category-inner">
      <h3 class="menu-cat-title">Shakes &amp; Dessert</h3>
      <div class="menu-grid">
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_image.png" alt="Food photo" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Lemon Krusher</h5>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Regular</em>TK 109</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Large</em>TK 139</span>
          </div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_image.png" alt="Food photo" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Chocolate Krusher</h5>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Regular</em>TK 145</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Large</em>TK 219</span>
          </div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_image.png" alt="Food photo" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Strawberry</h5>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Regular</em>TK 145</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Large</em>TK 219</span>
          </div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_image.png" alt="Food photo" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Peanut Butter</h5>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Regular</em>TK 199</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Large</em>TK 249</span>
          </div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_image.png" alt="Food photo" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Goa Lemonade</h5>
          <div class="menu-item-prices">
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Regular</em>TK 149</span>
            <span class="price-pill"><input type="radio" class="price-pill-input"><em>Large</em>TK 219</span>
          </div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_image.png" alt="Food photo" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Chocolate Lava</h5>
          <div class="menu-item-price">TK 165</div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_image.png" alt="Food photo" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Water</h5>
          <div class="menu-item-price">At MRP</div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
        <div class="menu-item">
          <a href="food-view.php" class="menu-item-photo">
            <img src="assets/images/food_image.png" alt="Food photo" class="menu-item-img">
          </a>
          <div class="menu-item-banner">
          <h5 class="menu-item-name">Beverage (Fountain)</h5>
          <div class="menu-item-price">TK 65</div>
          <div class="menu-item-actions">
            <button type="button" class="btn-add-cart">Add to Cart</button>
          </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</section>

<!-- Promo Banner Grid -->
<section class="banner-section">
  <div class="banner-grid">

    <div class="banner-card banner-fried-rice">
      <div class="banner-text">
        <h3 class="banner-title">Fried Rice</h3>
        <p class="banner-desc">Delicious fried rice packed with flavor and freshness.</p>
        <div class="banner-price">$11.50</div>
        <a href="index.php#menu" class="btn-buy-now is-yellow">Buy Now</a>
      </div>
      <div class="banner-photo">
        <img src="assets/images/food-placeholder.jpg" alt="Fried Rice">
      </div>
    </div>

    <div class="banner-card banner-pizza">
      <div class="banner-text">
        <h3 class="banner-title">Pizza</h3>
        <p class="banner-desc">Delicious fried rice packed with flavor and freshness.</p>
        <div class="banner-price">$12.50</div>
        <a href="index.php#menu" class="btn-buy-now is-white">Buy Now</a>
      </div>
      <div class="banner-photo">
        <img src="assets/images/slider/slider1.jpg" alt="Pizza">
      </div>
    </div>

    <div class="banner-card banner-burger banner-card--img-left">
      <div class="banner-photo">
        <img src="assets/images/slider/slider2.jpg" alt="Burger">
      </div>
      <div class="banner-text">
        <h3 class="banner-title">Burger</h3>
        <p class="banner-desc">Delicious fried rice packed with flavor and freshness.</p>
        <div class="banner-price">$11.50</div>
        <a href="index.php#menu" class="btn-buy-now is-yellow">Buy Now</a>
      </div>
    </div>

    <div class="banner-card banner-pasta">
      <div class="banner-text">
        <h3 class="banner-title">Pasta</h3>
        <p class="banner-desc">Delicious fried rice packed with flavor and freshness.</p>
        <div class="banner-price">$11.50</div>
        <a href="index.php#menu" class="btn-buy-now is-dark">Buy Now</a>
      </div>
      <div class="banner-photo">
        <img src="assets/images/food-placeholder.jpg" alt="Pasta">
      </div>
    </div>

    <div class="banner-card banner-fried-chicken">
      <div class="banner-text">
        <h3 class="banner-title">Fried Chicken</h3>
        <p class="banner-desc">Delicious fried rice packed with flavor and freshness.</p>
        <div class="banner-price">$14.50</div>
        <a href="index.php#menu" class="btn-buy-now is-yellow">Buy Now</a>
      </div>
      <div class="banner-photo">
        <img src="assets/images/slider/slider3.jpg" alt="Fried Chicken">
      </div>
    </div>

  </div>
</section>

@include('website.include.outlets')

@include('website.include.cta')

@endsection

@section('scripts')
@endsection
