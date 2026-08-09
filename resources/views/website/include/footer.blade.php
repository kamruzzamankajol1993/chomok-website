<!-- Footer -->
<footer class="site-footer">
  <div class="footer-inner">

    <div class="footer-brand">
      <h3 class="footer-logo">{{ $siteSetting?->restaurant_name ?? 'Chomok' }}</h3>
      <div class="footer-address">
        <span class="footer-address-icon" aria-hidden="true">📍</span>
        <div>
          <h4>Address</h4>
          <p>{{ $siteSetting?->address ?? 'Chattogram, Bangladesh' }}</p>
        </div>
      </div>
    </div>

    <div class="footer-col">
      <h4>Company</h4>
      <ul>
        <li><a href="{{ route('about.index') }}">About Us</a></li>
      </ul>
    </div>

    <div class="footer-col">
      <h4>Support</h4>
      <ul>
        <li><a href="{{ route('contact.index') }}">Contact Us</a></li>
        <li><a href="{{ route('extra.delivery-policy') }}">Delivery Info</a></li>
      </ul>
    </div>

    <div class="footer-col">
      <h4>Legal</h4>
      <ul>
        <li><a href="{{ route('extra.privacy-policy') }}">Privacy Policy</a></li>
        <li><a href="{{ route('extra.terms-and-conditions') }}">Terms of Service</a></li>
        <li><a href="{{ route('extra.refund-policy') }}">Refund Policy</a></li>
      </ul>
    </div>

  </div>

  <div class="footer-bottom">
    <p>&copy; {{ date('Y') }} {{ $siteSetting?->restaurant_name ?? 'Chomok' }}. All rights reserved.</p>
    <div class="footer-bottom-links">
      <a href="{{ route('extra.privacy-policy') }}">Privacy Policy</a>
      <a href="{{ route('extra.terms-and-conditions') }}">Terms of Service</a>
      <a href="{{ route('extra.refund-policy') }}">Refund Policy</a>
    </div>
  </div>
</footer>
