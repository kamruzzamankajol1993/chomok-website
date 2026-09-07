<!-- Office & Outlets -->
<section class="outlets-section">

  <div class="menu-section-head">
    <span class="badge-text">Find Us</span>
    <h2 class="menu-section-title">Our Current Office &amp; Outlets</h2>
  </div>

  <div class="outlets-grid">
    @forelse($siteBranches as $branch)
      @php
        $mapIframe = trim((string) $branch->map_iframe);
        $hasDirectIframe = $mapIframe !== '' && preg_match('~<iframe\\b[^>]*>.*?</iframe>~is', $mapIframe);
        $mapUrl = (!$hasDirectIframe && filter_var($mapIframe, FILTER_VALIDATE_URL)) ? $mapIframe : null;
        $fallbackMapSrc = $mapUrl ?: 'https://www.google.com/maps?q='.urlencode($branch->address).'&output=embed';
      @endphp
      <div class="outlet-card">
        <div class="outlet-map">
          @if($hasDirectIframe)
            {!! $mapIframe !!}
          @else
            <iframe src="{{ $fallbackMapSrc }}" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen title="{{ $branch->name }} location"></iframe>
          @endif
        </div>
        <div class="outlet-info">
          <h3 class="outlet-name">{{ $branch->name }}</h3>
          <p class="outlet-address">{{ $branch->address }}</p>
        </div>
      </div>
    @empty
      <div class="outlet-card">
        <div class="outlet-info"><p class="outlet-address">Outlet information will be available soon.</p></div>
      </div>
    @endforelse
  </div>
</section>
