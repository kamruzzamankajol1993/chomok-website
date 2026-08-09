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
    <p class="auth-subtext">Sign up to order faster and keep track of your Chomok cravings.</p>

    <form class="auth-form" action="#" method="post">
      <div class="form-group">
        <label for="reg-name">Full Name</label>
        <input type="text" id="reg-name" name="name" placeholder="Your full name">
      </div>

      <div class="form-group">
        <label for="reg-email">Email Address</label>
        <input type="email" id="reg-email" name="email" placeholder="you@example.com">
      </div>

      <div class="form-group">
        <label for="reg-phone">Phone Number</label>
        <input type="tel" id="reg-phone" name="phone" placeholder="+880 XXX-XXXXXX">
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="reg-password">Password</label>
          <input type="password" id="reg-password" name="password" placeholder="Create a password">
        </div>
        <div class="form-group">
          <label for="reg-confirm-password">Confirm Password</label>
          <input type="password" id="reg-confirm-password" name="confirm_password" placeholder="Re-enter password">
        </div>
      </div>

      <label class="auth-terms">
        <input type="checkbox" name="terms">
        I agree to the <a href="terms.php" class="auth-link">Terms of Service</a> and <a href="privacy.php" class="auth-link">Privacy Policy</a>.
      </label>

      <button type="submit" class="btn-wc-hero auth-submit">Create Account</button>
    </form>

    <p class="auth-switch">Already have an account? <a href="login.php" class="auth-link">Log in</a></p>
  </div>
</section>
@endsection


@section('scripts')

@endsection
