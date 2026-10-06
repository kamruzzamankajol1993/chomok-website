@extends('admin.master.auth')
@section('title', 'Admin Login')
@section('content')
<div class="admin-login-page">
    <div class="admin-login-card">
        @if($restaurantSetting?->logo)
            <img src="{{ asset($restaurantSetting->logo) }}" alt="Logo" class="admin-login-logo-image">
        @else
            <div class="admin-login-logo">{{ strtoupper(substr($restaurantSetting?->restaurant_name ?? 'C', 0, 1)) }}</div>
        @endif
        <h1 class="admin-login-title">{{ $restaurantSetting?->restaurant_name ?? 'Chomok' }} Admin</h1>
        <p class="admin-login-subtext">Sign in to manage your restaurant.</p>

        <form class="admin-login-form" action="{{ route('login.submit') }}" method="post">
            @csrf
            <div class="form-group-admin">
                <label class="form-label-admin" for="login-email">Email Address</label>
                <input type="email" id="login-email" name="email" class="form-control-admin @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="admin@chomok.com" required autofocus>
                @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="form-group-admin">
                <label class="form-label-admin" for="login-password">Password</label>
                <div class="password-input-wrap">
                    <input type="password" id="login-password" name="password" class="form-control-admin" placeholder="Enter your password" required>
                    <button type="button" class="password-toggle" data-password-toggle="#login-password" aria-label="Show password"><i class="bi bi-eye"></i></button>
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-between mb-3" style="font-size:.82rem;">
                <label class="form-check-admin"><input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember me</label>
                <a href="{{ route('password.request') }}" class="admin-link">Forgot password?</a>
            </div>

            <button type="submit" class="btn-admin-primary admin-login-submit"><i class="bi bi-box-arrow-in-right"></i> Sign In</button>
        </form>
    </div>
</div>
@endsection
