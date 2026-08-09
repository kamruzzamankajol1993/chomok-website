<nav class="navbar navbar-expand-lg main-nav">
  <div class="container-fluid main-nav-inner">
    <a class="navbar-brand logo-col" href="{{ route('home.index') }}">
      @if($siteSetting?->logo)
        <img src="{{ $adminAssetUrl($siteSetting->logo) }}" alt="{{ $siteSetting->restaurant_name ?? 'Chomok Restaurant' }}" class="logo-img">
      @else
        <img src="{{ asset('public/website/assets/images/chomok-logo-white.png') }}" alt="Chomok Restaurant" class="logo-img">
      @endif
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
            aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse menu-col" id="mainNavbar">
      <ul class="navbar-nav main-menu">
        <li class="nav-item"><a class="nav-link {{ Route::is('home.index') ? 'active': '' }}" href="{{ route('home.index') }}">Home</a></li>
        <li class="nav-item"><a class="nav-link {{ Route::is('about.index') ? 'active': '' }}" href="{{ route('about.index') }}">About</a></li>
        <li class="nav-item"><a class="nav-link {{ Route::is('menu.*') ? 'active': '' }}" href="{{ route('menu.index') }}">Menu</a></li>
        <li class="nav-item"><a class="nav-link {{ Route::is('branch.index') ? 'active': '' }}" href="{{ route('branch.index') }}">Shop</a></li>
        <li class="nav-item"><a class="nav-link {{ Route::is('contact.index') ? 'active': '' }}" href="{{ route('contact.index') }}">Contact</a></li>
      </ul>
    </div>

    <div class="account-col">
      @if(Auth::guard('client')->check())
        <a href="{{ route('client.dashboard') }}" class="cart-link">My Account</a>
      @else
        <a href="{{ route('client.login') }}" class="cart-link">Login</a>
      @endif
    </div>

    <div class="cart-col">
      <a href="#" class="cart-link" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas" aria-controls="cartOffcanvas">Cart (<span data-cart-count>{{ $globalCartCount ?? 0 }}</span>)</a>
    </div>
  </div>
</nav>
