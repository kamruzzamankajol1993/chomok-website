<div class="side-fab-stack">
  <button type="button" class="fab-btn cart-fab-btn" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas" aria-controls="cartOffcanvas" aria-label="Open cart">
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
    <span class="cart-fab-badge {{ ($globalCartCount ?? 0) < 1 ? 'd-none' : '' }}" data-cart-count data-hide-empty>{{ $globalCartCount ?? 0 }}</span>
  </button>

  <a href="{{ Auth::guard('client')->check() ? route('client.dashboard') : route('client.login') }}" class="fab-btn login-fab-btn" aria-label="{{ Auth::guard('client')->check() ? 'My account' : 'Login' }}">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
  </a>

  <a href="https://wa.me/" class="fab-btn whatsapp-fab-btn" target="_blank" rel="noopener" aria-label="Chat on WhatsApp">
    <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2 22l5.25-1.38c1.45.79 3.08 1.21 4.79 1.21h.01c5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2"></path></svg>
  </a>

  <button type="button" class="fab-btn scrolltop-fab-btn" id="scrollTopBtn" aria-label="Scroll to top">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 5 19 12"></polyline></svg>
  </button>
</div>
