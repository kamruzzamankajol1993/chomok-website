<div class="side-fab-stack">
  <button type="button" class="fab-btn cart-fab-btn" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas" aria-controls="cartOffcanvas" aria-label="Open cart">
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
    <span class="cart-fab-badge {{ ($globalCartCount ?? 0) < 1 ? 'd-none' : '' }}" data-cart-count data-hide-empty>{{ $globalCartCount ?? 0 }}</span>
  </button>

  <a href="{{ Auth::guard('client')->check() ? route('client.dashboard') : route('client.login') }}" class="fab-btn login-fab-btn" aria-label="{{ Auth::guard('client')->check() ? 'My account' : 'Login' }}">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
  </a>

  @if($whatsappUrl)
  <a href="{{ $whatsappUrl }}" class="fab-btn whatsapp-fab-btn" target="_blank" rel="noopener" aria-label="Chat on WhatsApp at {{ $siteSetting?->phone }}" title="WhatsApp: {{ $siteSetting?->phone }}">
    <svg width="22" height="22" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true"><path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93a7.898 7.898 0 0 0-2.327-5.607zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.25a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.558 6.558 0 0 1 1.928 4.66c-.004 3.639-2.961 6.591-6.592 6.591zm3.615-4.934c-.197-.1-1.17-.578-1.353-.646-.182-.068-.315-.1-.447.1-.132.197-.513.646-.513.646-.118.132-.237.148-.434.05-.197-.1-.833-.307-1.587-.977-.585-.52-.98-1.16-1.097-1.357-.118-.198-.013-.305.088-.404.091-.09.197-.237.296-.356.1-.119.132-.198.198-.33.065-.132.034-.248-.017-.347-.05-.1-.447-1.078-.612-1.47-.161-.387-.325-.334-.447-.34a8.95 8.95 0 0 0-.381-.007.729.729 0 0 0-.526.248c-.182.198-.691.676-.691 1.65s.708 1.916.81 2.049c.098.132 1.394 2.132 3.383 2.992.472.204.84.326 1.127.418.474.15.906.129 1.247.078.38-.057 1.17-.479 1.335-.942.164-.462.164-.858.115-.941-.05-.084-.182-.132-.38-.231z"/></svg>
  </a>
  @endif

  <button type="button" class="fab-btn scrolltop-fab-btn" id="scrollTopBtn" aria-label="Scroll to top">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 5 19 12"></polyline></svg>
  </button>
</div>
