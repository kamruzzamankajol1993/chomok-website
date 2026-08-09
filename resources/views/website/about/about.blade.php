@extends('website.master.master')
@section('title', ($content->meta_title ?: 'About').' | '.($siteSetting?->restaurant_name ?? 'Chomok Restaurant'))
@section('meta_description', $content->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($content->story_description ?: $siteSeoDescription), 155))

@section('css')
@endsection

@section('body')
<!-- Our Story Hero -->
<section class="story-hero-section">

  <h1 class="story-hero-heading">
    {{ $content->story_heading_prefix ?: 'Homemade' }}
    <img src="{{ $content->story_heading_image ? $adminAssetUrl($content->story_heading_image) : asset('public/website/assets/images/food_list/pasta/Naga-Pasta.png') }}" alt="Chomok food" class="story-hero-heading-img">
    {!! nl2br(e($content->story_heading_suffix ?: "And\nHearty Feasts")) !!}
  </h1>

  <div class="story-hero-row">

    <div class="story-hero-photo story-hero-photo-left">
      <img src="{{ $content->story_left_image ? $adminAssetUrl($content->story_left_image) : asset('public/website/assets/images/food_list/pizza/Beef-Maximus.png') }}" alt="Chomok food">
    </div>

    <div class="story-hero-body">
      <span class="story-hero-index">{{ $content->story_index ?: '01' }}</span>

      <div class="story-hero-divider">
        <a href="{{ $siteLinkUrl($content->story_button_link, route('menu.index')) }}" class="story-hero-cta">{!! nl2br(e($content->story_button_text ?: 'View Menu')) !!}</a>
      </div>

      <p class="story-hero-text">{{ $content->story_description }}</p>
    </div>

    <div class="story-hero-trust">
      <div class="story-hero-trust-top">
        <div class="story-hero-avatars">
          <span>{{ $content->story_avatar_1 ?: 'M' }}</span><span>{{ $content->story_avatar_2 ?: 'F' }}</span><span>{{ $content->story_avatar_3 ?: 'T' }}</span>
        </div>
        <span class="story-hero-trust-label">{{ $content->story_trust_label }}</span>
      </div>

      <div class="story-hero-photo story-hero-photo-right">
        <img src="{{ $content->story_right_image ? $adminAssetUrl($content->story_right_image) : asset('public/website/assets/images/food_list/burger/Chatgaiya-Burger.png') }}" alt="Chomok food">
      </div>
    </div>

  </div>

</section>

<!-- Mission & Vision -->
<section class="mv-section">
  <div class="mv-grid">

    <div class="mv-mission">
      <span class="mv-eyebrow">{{ $content->mission_label ?: 'Mission' }}</span>
      <p class="mv-text mv-text-lg">{{ $content->mission_text }}</p>
    </div>

    <div class="mv-photo mv-photo-main">
      <img src="{{ $content->mission_main_image ? $adminAssetUrl($content->mission_main_image) : asset('public/website/assets/images/about/interior-main.jpg') }}" alt="Chomok dining space">
    </div>

    <div class="mv-photos-duo">
      <div class="mv-photo">
        <img src="{{ $content->mission_secondary_image_1 ? $adminAssetUrl($content->mission_secondary_image_1) : asset('public/website/assets/images/about/interior-lights.jpg') }}" alt="Chomok kitchen">
      </div>
      <div class="mv-photo">
        <img src="{{ $content->mission_secondary_image_2 ? $adminAssetUrl($content->mission_secondary_image_2) : asset('public/website/assets/images/about/interior-window.jpg') }}" alt="Chomok dining area">
      </div>
    </div>

    <div class="mv-vision">
      <span class="mv-eyebrow">{{ $content->vision_label ?: 'Vision' }}</span>
      <div class="mv-vision-row">
        <span class="mv-arrow-icon" aria-hidden="true">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M7 17 17 7M9 7h8v8"></path>
          </svg>
        </span>
        <p class="mv-text">{{ $content->vision_text }}</p>
      </div>

      <svg class="mv-shell" viewBox="0 0 320 170" aria-hidden="true">
        <g fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M28 90 C18 84 18 72 28 66 C40 58 54 64 54 78 C54 92 38 96 30 88"></path>
          <path d="M40 80 C90 40 180 30 300 46"></path>
          <path d="M40 96 C100 132 200 140 296 108"></path>
          <path d="M300 46 C306 58 292 66 300 78 C310 90 292 98 296 108"></path>
          <path d="M46 84 L286 60"></path>
          <path d="M46 86 L282 78"></path>
          <path d="M46 88 L280 96"></path>
          <path d="M48 90 L270 112"></path>
        </g>
      </svg>
    </div>

  </div>
</section>

<!-- Services Highlight -->
<section class="services-highlight-section">

  <svg class="services-leaf services-leaf-top" viewBox="0 0 140 140" aria-hidden="true">
    <g fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
      <path d="M130 10 C 90 10 40 40 15 90"></path>
      <path d="M40 35 C 55 45 60 60 55 75"></path>
      <path d="M60 22 C 75 30 82 45 78 60"></path>
      <path d="M82 12 C 96 20 102 34 98 48"></path>
    </g>
  </svg>

  <svg class="services-leaf services-leaf-bottom" viewBox="0 0 140 140" aria-hidden="true">
    <g fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
      <path d="M10 130 C 50 130 100 100 125 50"></path>
      <path d="M100 105 C 85 95 80 80 85 65"></path>
      <path d="M80 118 C 65 110 58 95 62 80"></path>
      <path d="M58 128 C 44 120 38 106 42 92"></path>
    </g>
  </svg>

  <div class="services-highlight-inner">

    <div class="services-highlight-content">
      <h2 class="services-highlight-heading">{{ $content->services_heading_line_1 }}<br><strong>{{ $content->services_heading_line_2 }}</strong></h2>
      <p class="services-highlight-text">{{ $content->services_description }}</p>
      <a href="{{ $siteLinkUrl($content->services_button_link, route('menu.index')) }}" class="btn-wc-hero">{{ $content->services_button_text ?: 'Order Now' }}</a>
    </div>

    <div class="services-highlight-grid">

      <div class="service-card">
        <span class="service-card-icon" aria-hidden="true">
          <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
            <rect x="5" y="2.5" width="14" height="19" rx="2.5"></rect>
            <path d="M9 6.5h6M9 17h2"></path>
            <path d="m8.5 12 2 2 4-4"></path>
          </svg>
        </span>
        <h3 class="service-card-title">{{ $content->service_1_title }}</h3>
        <p class="service-card-text">{{ $content->service_1_text }}</p>
      </div>

      <div class="service-card">
        <span class="service-card-icon" aria-hidden="true">
          <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 16.5V8a1.5 1.5 0 0 1 1.5-1.5h9L18 10h2.5A1.5 1.5 0 0 1 22 11.5v5a1 1 0 0 1-1 1h-1.5"></path>
            <path d="M12.5 6.5v4h5.8"></path>
            <path d="M3 13h9.5"></path>
            <circle cx="7.5" cy="17.5" r="2"></circle>
            <circle cx="17" cy="17.5" r="2"></circle>
          </svg>
        </span>
        <h3 class="service-card-title">{{ $content->service_2_title }}</h3>
        <p class="service-card-text">{{ $content->service_2_text }}</p>
      </div>

      <div class="service-card">
        <span class="service-card-icon" aria-hidden="true">
          <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
            <path d="M2.5 3h2.2l2.6 12.4a1.8 1.8 0 0 0 1.8 1.4h8.1a1.8 1.8 0 0 0 1.76-1.42L20.7 8H6"></path>
            <circle cx="9.5" cy="20" r="1.4"></circle>
            <circle cx="17" cy="20" r="1.4"></circle>
          </svg>
        </span>
        <h3 class="service-card-title">{{ $content->service_3_title }}</h3>
        <p class="service-card-text">{{ $content->service_3_text }}</p>
      </div>

      <div class="service-card">
        <span class="service-card-icon" aria-hidden="true">
          <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 2.5c-4 0-7 3.1-7 7 0 5.2 7 12 7 12s7-6.8 7-12c0-3.9-3-7-7-7Z"></path>
            <circle cx="12" cy="9.5" r="2.6"></circle>
          </svg>
        </span>
        <h3 class="service-card-title">{{ $content->service_4_title }}</h3>
        <p class="service-card-text">{{ $content->service_4_text }}</p>
      </div>

    </div>

  </div>

</section>

<!-- Customer Reviews -->
<section class="reviews-section">

  <span class="reviews-quote-deco" aria-hidden="true">&#8220;</span>

  <div class="reviews-head">
    <span class="reviews-eyebrow">{{ $content->reviews_eyebrow }}</span>
    <h2 class="reviews-heading">{{ $content->reviews_heading_line_1 }}<br><strong>{{ $content->reviews_heading_line_2 }}</strong></h2>
    <p class="reviews-subtext">{{ $content->reviews_subtext }}</p>
    <div class="reviews-stat">
      <span class="reviews-stars" aria-hidden="true">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.5l2.9 6.3 6.9.7-5.2 4.7 1.5 6.8L12 17.6l-6.1 3.4 1.5-6.8L2.2 9.5l6.9-.7L12 2.5z"></path></svg>
        <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.5l2.9 6.3 6.9.7-5.2 4.7 1.5 6.8L12 17.6l-6.1 3.4 1.5-6.8L2.2 9.5l6.9-.7L12 2.5z"></path></svg>
        <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.5l2.9 6.3 6.9.7-5.2 4.7 1.5 6.8L12 17.6l-6.1 3.4 1.5-6.8L2.2 9.5l6.9-.7L12 2.5z"></path></svg>
        <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.5l2.9 6.3 6.9.7-5.2 4.7 1.5 6.8L12 17.6l-6.1 3.4 1.5-6.8L2.2 9.5l6.9-.7L12 2.5z"></path></svg>
        <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.5l2.9 6.3 6.9.7-5.2 4.7 1.5 6.8L12 17.6l-6.1 3.4 1.5-6.8L2.2 9.5l6.9-.7L12 2.5z"></path></svg>
      </span>
      {{ $content->reviews_summary }}
    </div>
  </div>

  <div class="reviews-grid">

    <div class="service-card review-card">
      <span class="review-stars" aria-label="Rated {{ (int)$content->review_1_rating }} out of 5">
        @if((int)$content->review_1_rating >= 1)<svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.5l2.9 6.3 6.9.7-5.2 4.7 1.5 6.8L12 17.6l-6.1 3.4 1.5-6.8L2.2 9.5l6.9-.7L12 2.5z"></path></svg>@endif
        @if((int)$content->review_1_rating >= 2)<svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.5l2.9 6.3 6.9.7-5.2 4.7 1.5 6.8L12 17.6l-6.1 3.4 1.5-6.8L2.2 9.5l6.9-.7L12 2.5z"></path></svg>@endif
        @if((int)$content->review_1_rating >= 3)<svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.5l2.9 6.3 6.9.7-5.2 4.7 1.5 6.8L12 17.6l-6.1 3.4 1.5-6.8L2.2 9.5l6.9-.7L12 2.5z"></path></svg>@endif
        @if((int)$content->review_1_rating >= 4)<svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.5l2.9 6.3 6.9.7-5.2 4.7 1.5 6.8L12 17.6l-6.1 3.4 1.5-6.8L2.2 9.5l6.9-.7L12 2.5z"></path></svg>@endif
        @if((int)$content->review_1_rating >= 5)<svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.5l2.9 6.3 6.9.7-5.2 4.7 1.5 6.8L12 17.6l-6.1 3.4 1.5-6.8L2.2 9.5l6.9-.7L12 2.5z"></path></svg>@endif
      </span>
      <p class="review-quote">{{ $content->review_1_quote }}</p>
      <div class="review-author">
        <span class="review-avatar">{{ $content->review_1_initial }}</span>
        <div>
          <div class="review-author-name">{{ $content->review_1_name }}</div>
          <div class="review-author-role">{{ $content->review_1_role }}</div>
        </div>
      </div>
    </div>

    <div class="service-card review-card">
      <span class="review-stars" aria-label="Rated {{ (int)$content->review_2_rating }} out of 5">
        @if((int)$content->review_2_rating >= 1)<svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.5l2.9 6.3 6.9.7-5.2 4.7 1.5 6.8L12 17.6l-6.1 3.4 1.5-6.8L2.2 9.5l6.9-.7L12 2.5z"></path></svg>@endif
        @if((int)$content->review_2_rating >= 2)<svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.5l2.9 6.3 6.9.7-5.2 4.7 1.5 6.8L12 17.6l-6.1 3.4 1.5-6.8L2.2 9.5l6.9-.7L12 2.5z"></path></svg>@endif
        @if((int)$content->review_2_rating >= 3)<svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.5l2.9 6.3 6.9.7-5.2 4.7 1.5 6.8L12 17.6l-6.1 3.4 1.5-6.8L2.2 9.5l6.9-.7L12 2.5z"></path></svg>@endif
        @if((int)$content->review_2_rating >= 4)<svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.5l2.9 6.3 6.9.7-5.2 4.7 1.5 6.8L12 17.6l-6.1 3.4 1.5-6.8L2.2 9.5l6.9-.7L12 2.5z"></path></svg>@endif
        @if((int)$content->review_2_rating >= 5)<svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.5l2.9 6.3 6.9.7-5.2 4.7 1.5 6.8L12 17.6l-6.1 3.4 1.5-6.8L2.2 9.5l6.9-.7L12 2.5z"></path></svg>@endif
      </span>
      <p class="review-quote">{{ $content->review_2_quote }}</p>
      <div class="review-author">
        <span class="review-avatar">{{ $content->review_2_initial }}</span>
        <div>
          <div class="review-author-name">{{ $content->review_2_name }}</div>
          <div class="review-author-role">{{ $content->review_2_role }}</div>
        </div>
      </div>
    </div>

    <div class="service-card review-card">
      <span class="review-stars" aria-label="Rated {{ (int)$content->review_3_rating }} out of 5">
        @if((int)$content->review_3_rating >= 1)<svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.5l2.9 6.3 6.9.7-5.2 4.7 1.5 6.8L12 17.6l-6.1 3.4 1.5-6.8L2.2 9.5l6.9-.7L12 2.5z"></path></svg>@endif
        @if((int)$content->review_3_rating >= 2)<svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.5l2.9 6.3 6.9.7-5.2 4.7 1.5 6.8L12 17.6l-6.1 3.4 1.5-6.8L2.2 9.5l6.9-.7L12 2.5z"></path></svg>@endif
        @if((int)$content->review_3_rating >= 3)<svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.5l2.9 6.3 6.9.7-5.2 4.7 1.5 6.8L12 17.6l-6.1 3.4 1.5-6.8L2.2 9.5l6.9-.7L12 2.5z"></path></svg>@endif
        @if((int)$content->review_3_rating >= 4)<svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.5l2.9 6.3 6.9.7-5.2 4.7 1.5 6.8L12 17.6l-6.1 3.4 1.5-6.8L2.2 9.5l6.9-.7L12 2.5z"></path></svg>@endif
        @if((int)$content->review_3_rating >= 5)<svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.5l2.9 6.3 6.9.7-5.2 4.7 1.5 6.8L12 17.6l-6.1 3.4 1.5-6.8L2.2 9.5l6.9-.7L12 2.5z"></path></svg>@endif
      </span>
      <p class="review-quote">{{ $content->review_3_quote }}</p>
      <div class="review-author">
        <span class="review-avatar">{{ $content->review_3_initial }}</span>
        <div>
          <div class="review-author-name">{{ $content->review_3_name }}</div>
          <div class="review-author-role">{{ $content->review_3_role }}</div>
        </div>
      </div>
    </div>

  </div>

</section>
@endsection


@section('scripts')

@endsection
