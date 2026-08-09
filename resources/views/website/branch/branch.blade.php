@extends('website.master.master')
@section('title')
Outlets | Chomok Restaurant
@endsection

@section('css')
@endsection

@section('body')
<!-- Page Hero -->
<section class="page-hero">
  <img src="assets/images/food-placeholder.jpg" alt="Chomok store" class="page-hero-img">
  <div class="slide-overlay"></div>
  <div class="page-hero-content">
    <span class="page-hero-eyebrow">Dine In &amp; Takeaway</span>
    <h1 class="page-hero-title">Visit Our Shops</h1>
  </div>
</section>

<!-- Intro -->
<div class="shop-intro">
  <span class="badge-text">Find Us</span>
  <h2 class="menu-section-title">A Chomok Near You</h2>
  <p class="shop-intro-text">
    From our very first home kitchen in Halishahar to storefronts across Chittagong and now Rajshahi, every
    Chomok location serves the same fresh, home-style food we started with. Drop by, place a takeaway order,
    or just come say hello.
  </p>
</div>

<!-- Shop List -->
<section class="shop-list-section">

  <div class="shop-row">
    <div class="shop-row-img">
      <img src="assets/images/about/interior-main.jpg" alt="Head Office">
    </div>
    <div class="shop-row-info">
      <h3 class="shop-row-name">Head Office</h3>
      <div class="shop-row-detail"><span class="shop-row-icon" aria-hidden="true">📍</span> 394 Brothers Mansion, East Rampur, Halishahar, Chittagong.</div>
      <div class="shop-row-detail"><span class="shop-row-icon" aria-hidden="true">📞</span> +880 XXX-XXXXXX</div>
      <a href="https://www.google.com/maps?q=394%20Brothers%20Mansion%2C%20East%20Rampur%2C%20Halishahar%2C%20Chittagong%2C%20Bangladesh" target="_blank" rel="noopener" class="shop-row-map-link">
        <span class="shop-row-icon" aria-hidden="true">🗺️</span> View on Google Maps
      </a>
    </div>
  </div>

  <div class="shop-row">
    <div class="shop-row-img">
      <img src="assets/images/about/interior-main.jpg" alt="1st Outlet">
    </div>
    <div class="shop-row-info">
      <h3 class="shop-row-name">1st Outlet</h3>
      <div class="shop-row-detail"><span class="shop-row-icon" aria-hidden="true">📍</span> 2nd Floor Avenue Center, CDA Avenue, GEC Circle, Chittagong; Bangladesh.</div>
      <div class="shop-row-detail"><span class="shop-row-icon" aria-hidden="true">📞</span> +880 XXX-XXXXXX</div>
      <a href="https://www.google.com/maps?q=2nd%20Floor%20Avenue%20Center%2C%20CDA%20Avenue%2C%20GEC%20Circle%2C%20Chittagong%2C%20Bangladesh" target="_blank" rel="noopener" class="shop-row-map-link">
        <span class="shop-row-icon" aria-hidden="true">🗺️</span> View on Google Maps
      </a>
    </div>
  </div>

  <div class="shop-row">
    <div class="shop-row-img">
      <img src="assets/images/about/interior-main.jpg" alt="2nd Outlet">
    </div>
    <div class="shop-row-info">
      <h3 class="shop-row-name">2nd Outlet</h3>
      <div class="shop-row-detail"><span class="shop-row-icon" aria-hidden="true">📍</span> 448/500, Arakan Street, Samsurnahar Villa, Olekha Masjid Circle, Chawkbazar, Panchlaish PS; Chittagong-4211; Bangladesh.</div>
      <div class="shop-row-detail"><span class="shop-row-icon" aria-hidden="true">📞</span> +880 XXX-XXXXXX</div>
      <a href="https://www.google.com/maps?q=448%2F500%2C%20Arakan%20Street%2C%20Samsurnahar%20Villa%2C%20Olekha%20Masjid%20Circle%2C%20Chawkbazar%2C%20Panchlaish%2C%20Chittagong%204211%2C%20Bangladesh" target="_blank" rel="noopener" class="shop-row-map-link">
        <span class="shop-row-icon" aria-hidden="true">🗺️</span> View on Google Maps
      </a>
    </div>
  </div>

  <div class="shop-row">
    <div class="shop-row-img">
      <img src="assets/images/about/interior-main.jpg" alt="Cloud Kitchen">
    </div>
    <div class="shop-row-info">
      <h3 class="shop-row-name">Cloud Kitchen</h3>
      <div class="shop-row-detail"><span class="shop-row-icon" aria-hidden="true">📍</span> 394 Brothers Mansion, East Rampur, Halishahar, Chittagong.</div>
      <div class="shop-row-detail"><span class="shop-row-icon" aria-hidden="true">📞</span> +880 XXX-XXXXXX</div>
      <a href="https://www.google.com/maps?q=394%20Brothers%20Mansion%2C%20East%20Rampur%2C%20Halishahar%2C%20Chittagong%2C%20Bangladesh" target="_blank" rel="noopener" class="shop-row-map-link">
        <span class="shop-row-icon" aria-hidden="true">🗺️</span> View on Google Maps
      </a>
    </div>
  </div>

  <div class="shop-row">
    <div class="shop-row-img">
      <img src="assets/images/about/interior-main.jpg" alt="Ware House">
    </div>
    <div class="shop-row-info">
      <h3 class="shop-row-name">Ware House</h3>
      <div class="shop-row-detail"><span class="shop-row-icon" aria-hidden="true">📍</span> 394 Brothers Mansion, East Rampur, Halishahar, Chittagong.</div>
      <div class="shop-row-detail"><span class="shop-row-icon" aria-hidden="true">📞</span> +880 XXX-XXXXXX</div>
      <a href="https://www.google.com/maps?q=394%20Brothers%20Mansion%2C%20East%20Rampur%2C%20Halishahar%2C%20Chittagong%2C%20Bangladesh" target="_blank" rel="noopener" class="shop-row-map-link">
        <span class="shop-row-icon" aria-hidden="true">🗺️</span> View on Google Maps
      </a>
    </div>
  </div>

  <div class="shop-row">
    <div class="shop-row-img">
      <img src="assets/images/about/interior-main.jpg" alt="Chomok Rajshahi">
    </div>
    <div class="shop-row-info">
      <h3 class="shop-row-name">Chomok Rajshahi</h3>
      <div class="shop-row-detail"><span class="shop-row-icon" aria-hidden="true">📍</span> 3rd Floor, M.R.M. Mobile Market (Opposite New Market), Saheb Bazar, Boalia, Rajshahi-6100, Bangladesh.</div>
      <div class="shop-row-detail"><span class="shop-row-icon" aria-hidden="true">📞</span> +880 XXX-XXXXXX</div>
      <a href="https://www.google.com/maps?q=M.R.M.%20Mobile%20Market%2C%20Saheb%20Bazar%2C%20Boalia%2C%20Rajshahi%206100%2C%20Bangladesh" target="_blank" rel="noopener" class="shop-row-map-link">
        <span class="shop-row-icon" aria-hidden="true">🗺️</span> View on Google Maps
      </a>
    </div>
  </div>

</section>

@include('website.include.cta')
@endsection


@section('scripts')

@endsection
