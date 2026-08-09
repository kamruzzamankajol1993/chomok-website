<nav class="navbar navbar-expand-lg main-nav">
  <div class="container-fluid main-nav-inner">

    <a class="navbar-brand logo-col" href="index.php">
      <img src="assets/images/chomok-logo-white.png" alt="Chomok Restaurant" class="logo-img">
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
            aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse menu-col" id="mainNavbar">
      <ul class="navbar-nav main-menu">
        <li class="nav-item"><a class="nav-link {{ Route::is('home.index') ? 'active': '' }}" {{ Route::is('home.index') ? 'aria-current="page"' : '' }} href="{{ route('home.index') }}">Home</a></li>
        <li class="nav-item"><a class="nav-link {{ Route::is('about.index') ? 'active': '' }}" {{ Route::is('about.index') ? 'aria-current="page"' : '' }} href="{{ route('about.index') }}">About</a></li>
        <li class="nav-item"><a class="nav-link {{ Route::is('menu.index') ? 'active': '' }}" {{ Route::is('menu.index') ? 'aria-current="page"' : '' }} href="{{ route('menu.index') }}">Menu</a></li>
        <li class="nav-item"><a class="nav-link {{ Route::is('menu.index') ? 'active': '' }}" {{ Route::is('menu.index') ? 'aria-current="page"' : '' }} href="{{ route('menu.index') }}">Shop</a></li>
        <li class="nav-item"><a class="nav-link {{ Route::is('contact.index') ? 'active': '' }}" {{ Route::is('contact.index') ? 'aria-current="page"' : '' }} href="{{ route('contact.index') }}">Contact</a></li>
      </ul>
    </div>

    <div class="account-col">
      <a href="{{ route('client.login') }}" class="cart-link">Login</a>
    </div>

    <div class="cart-col">
      <a href="#" class="cart-link" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas" aria-controls="cartOffcanvas">Cart (0)</a>
    </div>

  </div>
</nav>
