@extends('website.master.master')
@section('title', ($content->meta_title ?: 'Shop').' | '.($siteSetting?->restaurant_name ?? 'Chomok Restaurant'))
@section('meta_description', $content->meta_description ?: ('Find '.($siteSetting?->restaurant_name ?? 'Chomok').' branches, addresses and Google Maps locations.'))
@section('body')
<section class="page-hero">
  @php($heroBranch = $branches->first(fn($branch) => $branch->image))
  <img src="{{ $content->hero_image ? $adminAssetUrl($content->hero_image) : ($heroBranch?->image ? $adminAssetUrl($heroBranch->image) : asset('public/website/assets/images/food-placeholder.jpg')) }}" alt="{{ $content->hero_title ?: 'Chomok store' }}" class="page-hero-img">
  <div class="slide-overlay"></div><div class="page-hero-content"><span class="page-hero-eyebrow">{{ $content->hero_eyebrow_text ?: 'Dine In & Takeaway' }}</span><h1 class="page-hero-title">{{ $content->hero_title ?: 'Visit Our Shops' }}</h1></div>
</section>
<div class="shop-intro"><span class="badge-text">{{ $content->intro_badge_text ?: 'Find Us' }}</span><h2 class="menu-section-title">{{ $content->intro_heading ?: 'A Chomok Near You' }}</h2><p class="shop-intro-text">{{ $content->intro_text }}</p></div>
<section class="shop-list-section">
  @foreach($branches as $branch)
    <div class="shop-row">
      <div class="shop-row-img"><img src="{{ $branch->image ? $adminAssetUrl($branch->image) : asset('public/website/assets/images/food-placeholder.jpg') }}" alt="{{ $branch->name }}"></div>
      <div class="shop-row-info">
        <h3 class="shop-row-name">{{ $branch->name }}</h3>
        <div class="shop-row-detail"><span class="shop-row-icon" aria-hidden="true">📍</span> {{ $branch->address }}</div>
        @if($branch->phone)<div class="shop-row-detail"><span class="shop-row-icon" aria-hidden="true">📞</span> {{ $branch->phone }}</div>@endif
        <a href="{{ $branch->google_map_link ?: 'https://www.google.com/maps?q='.urlencode($branch->address) }}" target="_blank" rel="noopener" class="shop-row-map-link"><span class="shop-row-icon" aria-hidden="true">🗺️</span> {{ $content->map_link_text ?: 'View on Google Maps' }}</a>
      </div>
    </div>
  @endforeach
</section>
@include('website.include.cta')
@endsection
