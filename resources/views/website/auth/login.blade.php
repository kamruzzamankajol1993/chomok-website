@extends('website.master.master')
@section('title')
Login | Chomok Restaurant
@endsection

@section('css')
@endsection

@section('body')
<!-- Login -->
<section class="auth-section">
  <div class="auth-card">
    <h1 class="auth-title">Welcome Back</h1>
    <p class="auth-subtext">Log in to track your orders and manage your account.</p>

    <form class="auth-form" action="#" method="post">
      <div class="form-group">
        <label for="login-email">Email Address</label>
        <input type="email" id="login-email" name="email" placeholder="you@example.com">
      </div>

      <div class="form-group">
        <label for="login-password">Password</label>
        <input type="password" id="login-password" name="password" placeholder="Enter your password">
      </div>

      <div class="auth-form-row">
        <label class="auth-checkbox">
          <input type="checkbox" name="remember">
          Remember me
        </label>
        <a href="#" class="auth-link">Forgot password?</a>
      </div>

      <button type="submit" class="btn-wc-hero auth-submit">Log In</button>
    </form>

    <p class="auth-switch">Don't have an account? <a href="register.php" class="auth-link">Create one</a></p>
  </div>
</section>
@endsection


@section('scripts')

@endsection
