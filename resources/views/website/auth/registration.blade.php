@extends('website.master.master')
@section('title')
Registration | Chomok Restaurant
@endsection

@section('css')
@endsection

@section('body')
<!-- Register -->
<section class="auth-section">
  <div class="auth-card">
    <h1 class="auth-title">Create Your Account</h1>
    <p class="auth-subtext">Sign up to order faster. We will verify your email with a 6-digit code before creating your account.</p>

    <form class="auth-form" action="{{ route('client.register.submit') }}" method="post">
      @csrf
      <div class="form-group">
        <label for="reg-name">Full Name</label>
        <input type="text" id="reg-name" name="name" value="{{ old('name') }}" placeholder="Your full name" required>
      </div>

      <div class="form-group">
        <label for="reg-email">Email Address</label>
        <input type="email" id="reg-email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required>
      </div>

      <div class="form-group">
        <label for="reg-phone">Phone Number</label>
        <input type="tel" id="reg-phone" name="phone" value="{{ old('phone') }}" placeholder="+880 XXX-XXXXXX" required>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="reg-password">Password</label>
          <input type="password" id="reg-password" name="password" placeholder="Create a password" required>
        </div>
        <div class="form-group">
          <label for="reg-confirm-password">Confirm Password</label>
          <input type="password" id="reg-confirm-password" name="password_confirmation" placeholder="Re-enter password" required>
        </div>
      </div>

      <label class="auth-terms">
        <input type="checkbox" name="terms" value="1" required>
        I agree to the <a href="{{ route('extra.terms-and-conditions') }}" class="auth-link">Terms of Service</a> and <a href="{{ route('extra.privacy-policy') }}" class="auth-link">Privacy Policy</a>.
      </label>

      <button type="submit" class="btn-wc-hero auth-submit">Send Verification Code</button>
    </form>

    <p class="auth-switch">Already have an account? <a href="{{ route('client.login') }}" class="auth-link">Log in</a></p>
  </div>
</section>
@endsection


@section('scripts')

@endsection
