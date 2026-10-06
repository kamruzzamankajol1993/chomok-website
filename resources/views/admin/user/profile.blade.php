@extends('admin.master.master')
@section('title', 'My Profile')
@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb-admin"><a href="{{ url('/') }}">Dashboard</a> / <span class="current">Profile</span></div>
        <h1 class="page-title">My Profile</h1>
        <p class="page-subtitle">Update your personal information, profile image and account password.</p>
    </div>
</div>

@include('admin.include.form-errors')
<form method="post" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row g-4 align-items-start">
        <div class="col-xl-4">
            <div class="admin-card profile-photo-card">
                <div class="form-panel-header centered-panel-header">
                    <div class="form-panel-icon"><i class="bi bi-person-circle"></i></div>
                    <div>
                        <h2>Profile Photo</h2>
                        <p>Use a clear square image for the best result.</p>
                    </div>
                </div>
                <label class="upload-dropzone profile-upload-zone">
                    <input type="file" name="image" accept="image/*" class="image-preview-input" data-preview="#profile-image-preview">
                    @if($user->image)
                        <img id="profile-image-preview" class="upload-preview profile-preview" src="{{ asset($user->image) }}" alt="{{ $user->name }}">
                    @else
                        <img id="profile-image-preview" class="upload-preview profile-preview" src="" style="display:none" alt="Preview">
                        <div class="profile-avatar-large">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                    @endif
                    <div class="upload-dropzone-text">
                        <i class="bi bi-cloud-arrow-up"></i>
                        <span>Choose profile image</span>
                        <small>JPG, PNG or WEBP, maximum 3MB</small>
                    </div>
                </label>
                <div class="profile-account-summary">
                    <strong>{{ $user->name }}</strong>
                    <span>{{ $user->email }}</span>
                    <span class="badge-status {{ $user->status === 'active' ? 'is-active' : 'is-blocked' }}">{{ ucfirst($user->status) }}</span>
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="admin-card form-surface-card">
                <div class="form-panel-header">
                    <div class="form-panel-icon"><i class="bi bi-person-lines-fill"></i></div>
                    <div>
                        <h2>Personal Information</h2>
                        <p>Keep your contact information accurate and up to date.</p>
                    </div>
                </div>
                <div class="form-grid-two">
                    <div class="form-group-admin">
                        <label class="form-label-admin">Full Name <span class="required">*</span></label>
                        <div class="form-control-icon-wrap"><i class="bi bi-person"></i><input type="text" name="name" class="form-control-admin" value="{{ old('name', $user->name) }}" required></div>
                    </div>
                    <div class="form-group-admin">
                        <label class="form-label-admin">Phone <span class="required">*</span></label>
                        <div class="form-control-icon-wrap"><i class="bi bi-telephone"></i><input type="text" name="phone" class="form-control-admin" value="{{ old('phone', $user->phone) }}" required></div>
                    </div>
                </div>
                <div class="form-group-admin mb-0">
                    <label class="form-label-admin">Email Address</label>
                    <div class="form-control-icon-wrap"><i class="bi bi-envelope"></i><input type="email" class="form-control-admin" value="{{ $user->email }}" readonly></div>
                    <div class="form-help">Email is used as your login identity and cannot be changed from this page.</div>
                </div>
            </div>

            <div class="admin-card form-surface-card mt-4">
                <div class="form-panel-header">
                    <div class="form-panel-icon"><i class="bi bi-shield-lock"></i></div>
                    <div>
                        <h2>Change Password</h2>
                        <p>Leave these fields blank when you do not want to change your password.</p>
                    </div>
                </div>
                <div class="form-group-admin">
                    <label class="form-label-admin">Current Password</label>
                    <div class="password-input-wrap"><input type="password" id="profile-current-password" name="current_password" class="form-control-admin"><button type="button" class="password-toggle" data-password-toggle="#profile-current-password"><i class="bi bi-eye"></i></button></div>
                </div>
                <div class="form-grid-two">
                    <div class="form-group-admin mb-0">
                        <label class="form-label-admin">New Password</label>
                        <div class="password-input-wrap"><input type="password" id="profile-new-password" name="password" class="form-control-admin"><button type="button" class="password-toggle" data-password-toggle="#profile-new-password"><i class="bi bi-eye"></i></button></div>
                    </div>
                    <div class="form-group-admin mb-0">
                        <label class="form-label-admin">Confirm New Password</label>
                        <div class="password-input-wrap"><input type="password" id="profile-confirm-password" name="password_confirmation" class="form-control-admin"><button type="button" class="password-toggle" data-password-toggle="#profile-confirm-password"><i class="bi bi-eye"></i></button></div>
                    </div>
                </div>
            </div>

            <div class="form-actions-admin profile-form-actions">
                <a href="{{ url('/') }}" class="btn-admin-outline"><i class="bi bi-arrow-left"></i> Back</a>
                <button type="submit" class="btn-admin-primary"><i class="bi bi-check-lg"></i> Update Profile</button>
            </div>
        </div>
    </div>
</form>
@endsection
