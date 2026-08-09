@extends('website.master.master')
@section('title', 'Change Password | '.($siteSetting?->restaurant_name ?? 'Chomok Restaurant'))
@section('body')
<section class="auth-section">
  <div class="auth-card">
    <h1 class="auth-title">Set New Password</h1>
    <p class="auth-subtext">Your email was matched successfully. Set a new password below.</p>
    <form class="auth-form" action="{{ route('client.password.update.direct') }}" method="post">
      @csrf
      <div class="form-group"><label for="new-password">New Password</label><input type="password" id="new-password" name="password" placeholder="New password" required></div>
      <div class="form-group"><label for="new-password-confirm">Confirm Password</label><input type="password" id="new-password-confirm" name="password_confirmation" placeholder="Confirm new password" required></div>
      <button type="submit" class="btn-wc-hero auth-submit">Change Password</button>
    </form>
  </div>
</section>
@endsection
