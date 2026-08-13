@extends('website.master.master')
@section('title')
Verify Email | Chomok Restaurant
@endsection

@section('body')
<section class="auth-section">
  <div class="auth-card">
    <h1 class="auth-title">Verify Your Email</h1>
    <p class="auth-subtext">
      We sent a 6-digit verification code to <strong>{{ $email }}</strong>. Enter the code below to complete your registration.
    </p>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
      <div class="alert alert-danger">
        {{ $errors->first() }}
      </div>
    @endif

    <form class="auth-form" action="{{ route('client.register.verify.submit') }}" method="post">
      @csrf
      <div class="form-group">
        <label for="registration-otp">Verification Code</label>
        <input
          type="text"
          id="registration-otp"
          name="otp"
          value="{{ old('otp') }}"
          inputmode="numeric"
          autocomplete="one-time-code"
          pattern="[0-9]{6}"
          maxlength="6"
          placeholder="Enter 6-digit code"
          required
          autofocus
        >
      </div>

      <button type="submit" class="btn-wc-hero auth-submit">Verify & Create Account</button>
    </form>

    <form action="{{ route('client.register.resend') }}" method="post" style="margin-top: 14px;">
      @csrf
      <button type="submit" class="btn-add-cart" style="width: 100%;">Resend Code</button>
    </form>

    <p class="auth-switch">Wrong email? <a href="{{ route('client.register') }}" class="auth-link">Start registration again</a></p>
  </div>
</section>
@endsection
