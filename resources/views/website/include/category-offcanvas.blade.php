<div class="offcanvas offcanvas-end category-offcanvas global-category-offcanvas" tabindex="-1" id="categoryOffcanvas" aria-labelledby="categoryOffcanvasLabel">
  <div class="offcanvas-header category-offcanvas-header">
    <a href="{{ route('home.index') }}" class="category-offcanvas-logo" id="categoryOffcanvasLabel">
      @if($siteSetting?->logo)
        <img src="{{ $adminAssetUrl($siteSetting->logo) }}" alt="{{ $siteSetting->restaurant_name ?? 'Chomok Restaurant' }}">
      @else
        <img src="{{ asset('public/website/assets/images/chomok-logo-white.png') }}" alt="Chomok Restaurant">
      @endif
    </a>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close categories"></button>
  </div>
  <div class="offcanvas-body">
    <h5 class="category-offcanvas-title">Categories</h5>
    <ul class="menu-category-list category-offcanvas-list">
      @if(Route::is('menu.index'))
        <li><button type="button" class="menu-cat-btn {{ (($category ?? request('category', 'all')) === 'all') ? 'active' : '' }}" data-menu-filter="all">All</button></li>
        @foreach($siteCategories as $cat)
          <li>
            <button type="button" class="menu-cat-btn {{ (($category ?? request('category', 'all')) === $cat->slug) ? 'active' : '' }}" data-menu-filter="{{ $cat->slug }}">
              @if($cat->image)<img src="{{ $adminAssetUrl($cat->image) }}" alt="" class="category-offcanvas-thumb">@endif
              <span>{{ $cat->name }}</span>
            </button>
          </li>
        @endforeach
      @else
        <li><a href="{{ route('menu.index') }}" class="menu-cat-btn category-offcanvas-link">All</a></li>
        @foreach($siteCategories as $cat)
          <li>
            <a href="{{ route('menu.index', ['category' => $cat->slug]) }}" class="menu-cat-btn category-offcanvas-link">
              @if($cat->image)<img src="{{ $adminAssetUrl($cat->image) }}" alt="" class="category-offcanvas-thumb">@endif
              <span>{{ $cat->name }}</span>
            </a>
          </li>
        @endforeach
      @endif
    </ul>
  </div>
</div>
