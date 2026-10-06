@extends('admin.master.auth')
@section('title', 'Forgot Password')
@section('content')
<div class="admin-login-page">
    <div class="admin-login-card">
        <div class="admin-login-logo"><i class="bi bi-shield-lock"></i></div>
        <h1 class="admin-login-title">Forgot Password</h1>
        <p class="admin-login-subtext">Enter your account email. The system will verify it directly from the database.</p>
        <form class="admin-login-form" action="{{ route('password.verify-email') }}" method="post">
            @csrf
            <div class="form-group-admin">
                <label class="form-label-admin" for="reset-email">Email Address</label>
                <input type="email" id="reset-email" name="email" class="form-control-admin @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus>
                @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn-admin-primary admin-login-submit"><i class="bi bi-search"></i> Verify Account</button>
            <a href="{{ route('login') }}" class="auth-back-link"><i class="bi bi-arrow-left"></i> Back to login</a>
        </form>
    </div>
</div>
@endsection
