@extends('admin.master.auth')
@section('title', 'Reset Password')
@section('content')
<div class="admin-login-page">
    <div class="admin-login-card">
        <div class="admin-login-logo"><i class="bi bi-key"></i></div>
        <h1 class="admin-login-title">Set New Password</h1>
        <p class="admin-login-subtext">Account verified: <strong>{{ $email }}</strong></p>
        <form class="admin-login-form" action="{{ route('password.update.direct') }}" method="post">
            @csrf
            <div class="form-group-admin">
                <label class="form-label-admin" for="new-password">New Password</label>
                <div class="password-input-wrap">
                    <input type="password" id="new-password" name="password" class="form-control-admin" required>
                    <button type="button" class="password-toggle" data-password-toggle="#new-password"><i class="bi bi-eye"></i></button>
                </div>
            </div>
            <div class="form-group-admin">
                <label class="form-label-admin" for="confirm-password">Confirm Password</label>
                <div class="password-input-wrap">
                    <input type="password" id="confirm-password" name="password_confirmation" class="form-control-admin" required>
                    <button type="button" class="password-toggle" data-password-toggle="#confirm-password"><i class="bi bi-eye"></i></button>
                </div>
            </div>
            <button type="submit" class="btn-admin-primary admin-login-submit"><i class="bi bi-check-lg"></i> Change Password</button>
        </form>
    </div>
</div>
@endsection
