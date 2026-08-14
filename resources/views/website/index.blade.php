@extends('website.master.master')

@section('title', ($siteSetting?->restaurant_name ?? 'Chomok').' Restaurant')
@section('meta_description', $siteSeoDescription)

@section('body')
<!-- Hero Slider -->
<section class="hero-slider">
  <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-pause="hover">
    <div class="carousel-inner">
      @forelse($slides as $slide)
        <div class="carousel-item {{ $loop->first ? 'active' : '' }}" data-bs-interval="5000">
          <img src="{{ $slide->image ? $adminAssetUrl($slide->image) : asset('public/website/assets/images/slider/slider1.jpg') }}" class="d-block w-100 slider-img" alt="{{ trim(($slide->title_line_1 ?? '').' '.($slide->title_line_2 ?? '')) }}">
          <div class="slide-overlay"></div>
          <div class="slider-caption">
            <div class="caption-inner">
              @if($slide->eyebrow_text)<span class="caption-eyebrow">{{ $slide->eyebrow_text }}</span>@endif
              <h2 class="caption-title">{{ $slide->title_line_1 }}@if($slide->title_line_2)<br>{{ $slide->title_line_2 }}@endif</h2>
              @if($slide->subtext)<p class="caption-text">{{ $slide->subtext }}</p>@endif
              <div class="caption-buttons">
                @if($slide->button_1_text)<a href="{{ $siteLinkUrl($slide->button_1_link, route('menu.index')) }}" class="btn btn-brand-yellow">{{ $slide->button_1_text }}</a>@endif
                @if($slide->button_2_text)<a href="{{ $siteLinkUrl($slide->button_2_link, route('contact.index')) }}" class="btn btn-brand-outline">{{ $slide->button_2_text }}</a>@endif
              </div>
            </div>
          </div>
        </div>
      @empty
        <div class="carousel-item active" data-bs-interval="5000">
          <img src="{{ asset('public/website/assets/images/slider/slider1.jpg') }}" class="d-block w-100 slider-img" alt="Chomok Restaurant">
          <div class="slide-overlay"></div>
        </div>
      @endforelse
    </div>
  </div>
</section>

<!-- Category Scroller -->
<section class="category-scroller">
  @php
    $categoryEmojis = ['pizza'=>'🍕','burgers'=>'🍔','fried-chicken'=>'🍗','rice-biryani'=>'🍛','pasta-noodles'=>'🍝','sandwiches'=>'🥪','salads'=>'🥗','desserts'=>'🍰','beverages'=>'🥤','combos'=>'🍽️'];
  @endphp
  <div class="scroller-track">
    @foreach($categories as $category)
      <a href="{{ route('menu.index', ['category' => $category->slug]) }}" class="scroller-item"><span class="scroller-emoji">{{ $categoryEmojis[$category->slug] ?? '🍴' }}</span> {{ $category->name }}</a>
    @endforeach
    @foreach($categories as $category)
      <a href="{{ route('menu.index', ['category' => $category->slug]) }}" class="scroller-item" aria-hidden="true"><span class="scroller-emoji">{{ $categoryEmojis[$category->slug] ?? '🍴' }}</span> {{ $category->name }}</a>
    @endforeach
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
      <span class="badge-text">{{ $content->about_badge_text ?: 'Taste Of World Class Food' }}</span>
    </div>

    <h2 class="about-heading">
      <span class="heading-row">
        <img src="{{ $content->about_pill_image ? $adminAssetUrl($content->about_pill_image) : asset('public/website/assets/images/slider/slider2.jpg') }}" class="heading-img pill-img" alt="Guests enjoying a meal">
        <span class="heading-word">{{ $content->about_heading_line_1 ?: 'Experience' }}</span>
        <img src="{{ $content->about_circle_image ? $adminAssetUrl($content->about_circle_image) : asset('public/website/assets/images/slider/slider1.jpg') }}" class="heading-img circle-img" alt="Signature Chomok dish">
      </span>
      <span class="heading-row heading-row-2">{{ $content->about_heading_line_2 ?: 'Culinary Excellence' }}</span>
    </h2>

    <div class="about-footer-row">
      <div class="about-text-col">
        <p class="about-text">{{ $content->about_paragraph_text }}</p>
        <a href="{{ route('menu.index') }}" class="btn-about-cta">
          {{ $content->about_button_text ?: 'Our Delicious Item' }}
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
          @php
            $trust = trim((string) $content->about_trust_badge);
            $trustNumber = str_contains($trust, ' ') ? str($trust)->before(' ')->toString() : ($trust ?: '10K+');
            $trustText = str_contains($trust, ' ') ? str($trust)->after(' ')->toString() : 'Trusted by Families';
          @endphp
          <strong>{{ $trustNumber }}</strong>
          <span>{{ $trustText }}</span>
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

  @php
    $menuClassMap = [
      'burgers'=>'burgers','pizza'=>'pizza','fried-chicken'=>'broast-chicken','rice-biryani'=>'rice-meals',
      'pasta-noodles'=>'pasta','sandwiches'=>'sides','salads'=>'sides','desserts'=>'dessert','beverages'=>'dessert','combos'=>'combo'
    ];
  @endphp
  @foreach($categories as $category)
    @php
      // Match the design hierarchy: category first, then numbered subcategory groups.
      // Items without a subcategory remain directly under their category.
      $menuGroups = $category->subcategories
        ->values()
        ->map(fn ($subcategory, $index) => [
          'title' => ($index + 1).'. '.$subcategory->name,
          'items' => $subcategory->menuItems,
        ]);

      if ($category->menuItems->isNotEmpty()) {
        $menuGroups->push([
          'title' => null,
          'items' => $category->menuItems,
        ]);
      }
    @endphp

    <div class="menu-category menu-cat-{{ $menuClassMap[$category->slug] ?? $category->slug }}">
      <div class="menu-category-inner">
        <h3 class="menu-cat-title">{{ $category->name }} Menu</h3>

        @foreach($menuGroups as $group)
          @if($group['title'])
            <h4 class="menu-subtitle">{{ $group['title'] }}</h4>
          @endif

          <div class="menu-grid">
            @foreach($group['items'] as $item)
              @php($firstPrice = $item->prices->first())
              <div class="menu-item" data-menu-card>
                <a href="{{ route('menu.show', $item) }}" class="menu-item-photo">
                  <img src="{{ $item->mainImage?->image ? $adminAssetUrl($item->mainImage->image) : asset('public/website/assets/images/food-placeholder.jpg') }}" alt="{{ $item->name }}" class="menu-item-img">
                </a>
                <div class="menu-item-banner">
                  <h5 class="menu-item-name">{{ $item->name }}</h5>
                  <div class="menu-item-prices">
                    @foreach($item->prices as $price)
                      <label class="price-pill">
                        <input type="radio" name="home_price_{{ $item->id }}" value="{{ $price->id }}" data-price-for="{{ $item->id }}" class="price-pill-input" @checked($loop->first)>
                        <em>{{ $price->size_label ?: 'Regular' }}</em>TK {{ rtrim(rtrim(number_format((float)$price->effective_price, 2, '.', ''), '0'), '.') }}
                      </label>
                    @endforeach
                  </div>
                  <div class="menu-item-actions">
                    <button type="button" class="btn-add-cart" data-add-cart data-menu-item-id="{{ $item->id }}" data-default-price-id="{{ $firstPrice?->id }}" data-has-addons="{{ ($item->addons->isNotEmpty() || $item->prices->contains(fn ($price) => $price->variationAddons->isNotEmpty())) ? '1' : '0' }}" data-detail-url="{{ route('menu.show', $item) }}">Add to Cart</button>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        @endforeach
      </div>
    </div>
  @endforeach
</section>

<!-- Promo Banner Grid: intentionally image-only, exactly five slots -->
<section class="banner-section">
  <div class="banner-grid">
    @foreach($promoCards as $promo)
      @php($bannerClass = match((int)$promo->banner_slot){1=>'banner-fried-rice',2=>'banner-pizza',3=>'banner-burger banner-card--img-left',4=>'banner-pasta',default=>'banner-fried-chicken'})
      <a class="banner-card {{ $bannerClass }}" href="{{ $siteLinkUrl($promo->link, route('menu.index')) }}" aria-label="Open promotion {{ $promo->banner_slot }}">
        @if($promo->image)
          <img src="{{ $adminAssetUrl($promo->image) }}" alt="Promotion {{ $promo->banner_slot }}" style="width:100%;height:100%;object-fit:cover;display:block;">
        @endif
      </a>
    @endforeach
  </div>
</section>

@include('website.include.outlets')
@include('website.include.cta')
@endsection

@section('scripts')
@endsection
