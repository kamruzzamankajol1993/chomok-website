@extends('website.master.master')
@section('title', $menuItem->name.' | '.($siteSetting?->restaurant_name ?? 'Chomok Restaurant'))
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($menuItem->description ?: $siteSeoDescription), 155))

@section('body')
@php
  $requestedPriceId = (int) request()->query('price_id', 0);
  $selectedPrice = $menuItem->prices->first(fn ($price) => (int) $price->id === $requestedPriceId)
      ?? $menuItem->prices->first();
  $selectedPriceId = (int) ($selectedPrice?->id ?? 0);
@endphp
<!-- Food View -->
<section class="food-view-section">

  <div class="food-view-layout" data-menu-card>

    <div class="food-view-img-col">
      @php($mainImage = $menuItem->images->first())
      <img src="{{ $mainImage?->image ? $adminAssetUrl($mainImage->image) : asset('public/website/assets/images/food-placeholder.jpg') }}" alt="{{ $menuItem->name }}" class="food-view-img">
    </div>

    <div class="food-view-info-col">
      <h1 class="food-view-name">{{ $menuItem->name }}</h1>
      <p class="food-view-desc">{{ $menuItem->description }}</p>

      <div class="food-view-block">
        <h6 class="food-view-label">Price</h6>
        <div class="menu-item-prices">
          @foreach($menuItem->prices as $price)
            <label class="price-pill">
              <input type="radio" name="view-size" value="{{ $price->id }}" data-price-for="{{ $menuItem->id }}" class="price-pill-input" @checked((int) $selectedPriceId === (int) $price->id)>
              <em>{{ $price->size_label ?: 'Regular' }}</em>TK {{ rtrim(rtrim(number_format((float)$price->effective_price, 2, '.', ''), '0'), '.') }}
            </label>
          @endforeach
        </div>

        @if($menuItem->prices->contains(fn ($price) => $price->variationAddons->isNotEmpty()))
          @foreach($menuItem->prices as $price)
            @if($price->variationAddons->isNotEmpty())
              <div data-food-variation-addon-group data-price-id="{{ $price->id }}" class="{{ (int) $selectedPriceId === (int) $price->id ? '' : 'd-none' }}">
                <h6 class="food-view-label">{{ $price->size_label ?: 'Regular' }} Add-Ons</h6>
                <div class="addon-list">
                  @foreach($price->variationAddons as $variationAddon)
                    <label class="addon-item">
                      <span class="addon-item-check"><input type="checkbox" name="price_addon_ids[]" value="{{ $variationAddon->id }}"></span>
                      <span class="addon-item-name">{{ $variationAddon->name }}@if(filled($variationAddon->description))<small class="d-block text-muted">{{ $variationAddon->description }}</small>@endif</span>
                      <span class="addon-item-price">+TK {{ rtrim(rtrim(number_format((float)$variationAddon->price, 2, '.', ''), '0'), '.') }}</span>
                    </label>
                  @endforeach
                </div>
              </div>
            @endif
          @endforeach
        @endif
      </div>

      @if($menuItem->addons->isNotEmpty())
        <div class="food-view-block">
          <h6 class="food-view-label">Add-Ons</h6>
          <div class="addon-list">
            @foreach($menuItem->addons as $addon)
              <label class="addon-item">
                <span class="addon-item-check"><input type="checkbox" name="addon_ids[]" value="{{ $addon->id }}"></span>
                <span class="addon-item-name">{{ $addon->name }}@if(filled($addon->description))<small class="d-block text-muted">{{ $addon->description }}</small>@endif</span>
                <span class="addon-item-price">+TK {{ rtrim(rtrim(number_format((float)$addon->price, 2, '.', ''), '0'), '.') }}</span>
              </label>
            @endforeach
          </div>
        </div>
      @endif


      <div class="food-view-footer">
        <div class="food-view-qty" data-food-qty-wrap>
          <button type="button" class="qty-btn" data-food-qty="-1" aria-label="Decrease quantity">&minus;</button>
          <span class="qty-value" data-food-qty-value>1</span>
          <button type="button" class="qty-btn" data-food-qty="1" aria-label="Increase quantity">+</button>
        </div>
        <button type="button" class="btn-add-cart food-view-add-btn"
          data-add-cart
          data-menu-item-id="{{ $menuItem->id }}"
          data-default-price-id="{{ $selectedPriceId }}"
          data-has-addons="{{ ($menuItem->addons->isNotEmpty() || $menuItem->prices->contains(fn ($price) => $price->variationAddons->isNotEmpty())) ? '1' : '0' }}"
          data-detail-add-cart="1"
          data-quantity-source="[data-food-qty-value]">Add to Cart</button>
      </div>
    </div>

  </div>

  @if($suggestions->isNotEmpty())
    <!-- Suggestions -->
    <div class="food-view-suggestions">
      <div class="menu-section-head">
        <span class="badge-text">You Might Also Like</span>
        <h2 class="menu-section-title">Pair It With</h2>
      </div>
      <div class="menu-grid-2">
        @include('website.menu.partials.cards', ['items' => $suggestions])
      </div>
    </div>
  @endif

</section>

@include('website.include.cta')
@endsection

@section('scripts')
@endsection
