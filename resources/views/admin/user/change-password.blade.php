@extends('admin.master.master')
@section('title', 'Change Password')
@section('content')
<div class="page-header"><div><div class="breadcrumb-admin"><a href="{{ route('admin.dashboard') }}">Dashboard</a> / <span class="current">Change Password</span></div><h1 class="page-title">Change Password</h1><p class="page-subtitle">Update the password for your signed-in account.</p></div></div>
<div class="admin-card form-card-narrow">
    @include('admin.include.form-errors')
    <form method="post" action="{{ route('admin.password.update') }}">@csrf @method('PUT')
        @foreach([['current_password','Current Password'],['password','New Password'],['password_confirmation','Confirm New Password']] as [$name,$label])
        <div class="form-group-admin"><label class="form-label-admin" for="{{ $name }}">{{ $label }}</label><div class="password-input-wrap"><input type="password" id="{{ $name }}" name="{{ $name }}" class="form-control-admin" required><button type="button" class="password-toggle" data-password-toggle="#{{ $name }}"><i class="bi bi-eye"></i></button></div></div>
        @endforeach
        <div class="form-actions-admin"><button class="btn-admin-primary" type="submit"><i class="bi bi-check-lg"></i> Update Password</button></div>
    </form>
</div>
@endsection
