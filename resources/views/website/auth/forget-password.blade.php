@extends('website.master.master')
@section('title', 'Forgot Password | '.($siteSetting?->restaurant_name ?? 'Chomok Restaurant'))
@section('body')
<section class="auth-section">
  <div class="auth-card">
    <h1 class="auth-title">Forgot Password</h1>
    <p class="auth-subtext">Enter your registered email. No email will be sent; if it matches our database you can set a new password directly.</p>
    <form class="auth-form" action="{{ route('client.password.verify-email') }}" method="post">
      @csrf
      <div class="form-group"><label for="forgot-email">Email Address</label><input type="email" id="forgot-email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required></div>
      <button type="submit" class="btn-wc-hero auth-submit">Verify Email</button>
    </form>
    <p class="auth-switch"><a href="{{ route('client.login') }}" class="auth-link">Back to login</a></p>
  </div>
</section>
@endsection
