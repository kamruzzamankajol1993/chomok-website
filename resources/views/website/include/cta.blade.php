@php($cta = $globalHomepageContent)
<section class="cta-section">
  <div class="cta-content">
    <div class="cta-img cta-img-left">
      <img src="{{ $cta?->hungry_left_image ? $adminAssetUrl($cta->hungry_left_image) : asset('public/website/assets/images/slider/slider3.jpg') }}" alt="Fresh food">
    </div>
    <div class="cta-text">
      <h2 class="cta-heading">{{ $cta?->hungry_line_one ?? 'Hungry?' }}<br>{{ $cta?->hungry_line_two ?? 'We Are Ready.' }}</h2>
      <p class="cta-subtext">{{ $cta?->hungry_subtext ?? 'Order now, your next meal is waiting.' }}</p>
      <a href="{{ route('menu.index') }}" class="btn-cta-book">{{ $cta?->hungry_button_text ?? 'View Menu' }}</a>
    </div>
    <div class="cta-img cta-img-right">
      <img src="{{ $cta?->hungry_right_image ? $adminAssetUrl($cta->hungry_right_image) : asset('public/website/assets/images/slider/slider2.jpg') }}" alt="Fresh food">
    </div>
  </div>
  <div class="cta-wave" aria-hidden="true"><svg viewBox="0 0 1440 100" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg"><path d="M0,100 L0,55 C240,10 480,95 720,60 C960,25 1200,85 1440,45 L1440,100 Z"></path></svg></div>
</section>
