@include('admin.include.form-errors')
<div class="row g-4 align-items-start">
    <div class="col-xl-8">
        <div class="admin-card form-surface-card">
            <div class="form-panel-header">
                <div class="form-panel-icon"><i class="bi bi-person-lines-fill"></i></div>
                <div>
                    <h2>Account Information</h2>
                    <p>Enter the user's contact and login information.</p>
                </div>
            </div>

            <div class="form-grid-two">
                <div class="form-group-admin">
                    <label class="form-label-admin">Full Name <span class="required">*</span></label>
                    <div class="form-control-icon-wrap"><i class="bi bi-person"></i><input type="text" name="name" class="form-control-admin" value="{{ old('name',$user->name ?? '') }}" required></div>
                </div>
                <div class="form-group-admin">
                    <label class="form-label-admin">Phone <span class="required">*</span></label>
                    <div class="form-control-icon-wrap"><i class="bi bi-telephone"></i><input type="text" name="phone" class="form-control-admin" value="{{ old('phone',$user->phone ?? '') }}" required></div>
                </div>
            </div>

            <div class="form-group-admin">
                <label class="form-label-admin">Email Address <span class="required">*</span></label>
                <div class="form-control-icon-wrap"><i class="bi bi-envelope"></i><input type="email" name="email" class="form-control-admin" value="{{ old('email',$user->email ?? '') }}" required></div>
            </div>

            <div class="form-grid-two">
                <div class="form-group-admin">
                    <label class="form-label-admin">Password {{ isset($user) ? '' : '*' }}</label>
                    <div class="password-input-wrap"><input type="password" id="user-password" name="password" class="form-control-admin" {{ isset($user)?'':'required' }}><button type="button" class="password-toggle" data-password-toggle="#user-password"><i class="bi bi-eye"></i></button></div>
                    @isset($user)<div class="form-help">Leave blank to keep the current password.</div>@endisset
                </div>
                <div class="form-group-admin">
                    <label class="form-label-admin">Confirm Password {{ isset($user) ? '' : '*' }}</label>
                    <div class="password-input-wrap"><input type="password" id="user-password-confirm" name="password_confirmation" class="form-control-admin" {{ isset($user)?'':'required' }}><button type="button" class="password-toggle" data-password-toggle="#user-password-confirm"><i class="bi bi-eye"></i></button></div>
                </div>
            </div>
        </div>

        <div class="admin-card form-surface-card mt-4 choices-overflow-card">
            <div class="form-panel-header">
                <div class="form-panel-icon"><i class="bi bi-diagram-3"></i></div>
                <div>
                    <h2>Role & Branch Access</h2>
                    <p>Assign the user's role, branch and data visibility.</p>
                </div>
            </div>

            <div class="form-grid-two">
                <div class="form-group-admin">
                    <label class="form-label-admin">Branch</label>
                    <select name="branch_id" class="form-control-admin choices-select">
                        <option value="">Select Branch</option>
                        @foreach($branches as $branch)<option value="{{ $branch->id }}" @selected((string)old('branch_id',$user->branch_id ?? '')===(string)$branch->id)>{{ $branch->name }}</option>@endforeach
                    </select>
                </div>
                <div class="form-group-admin">
                    <label class="form-label-admin">Role <span class="required">*</span></label>
                    <select name="role" class="form-control-admin choices-select" required>
                        <option value="">Select Role</option>
                        @foreach($roles as $role)<option value="{{ $role->name }}" @selected(old('role',isset($user)?$user->getRoleNames()->first():'')===$role->name)>{{ $role->name }}</option>@endforeach
                    </select>
                </div>
            </div>

            <div class="form-grid-two align-items-stretch">
                <div class="form-group-admin mb-0">
                    <label class="form-label-admin">Account Status</label>
                    <select name="status" class="form-control-admin choices-select">
                        <option value="active" @selected(old('status',$user->status ?? 'active')==='active')>Active</option>
                        <option value="inactive" @selected(old('status',$user->status ?? 'active')==='inactive')>Inactive</option>
                    </select>
                </div>
                @if($canSeeAllBranches ?? auth()->user()->canSeeAllBranches())
                    <div class="form-group-admin d-flex align-items-center justify-content-between switch-row mb-0">
                        <div><label class="form-label-admin mb-0">All Branch Access</label><div class="form-help">Allow this user to view data from every branch.</div></div>
                        <label class="form-switch-admin"><input type="checkbox" name="all_branch_access" value="1" @checked(old('all_branch_access',$user->all_branch_access ?? false))><span class="switch-track"></span></label>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="admin-card profile-photo-card sticky-side-card">
            <div class="form-panel-header centered-panel-header">
                <div class="form-panel-icon"><i class="bi bi-image"></i></div>
                <div><h2>Profile Image</h2><p>Preview the image before saving.</p></div>
            </div>
            <label class="upload-dropzone image-upload-zone">
                <input type="file" name="image" accept="image/*" class="image-preview-input" data-preview="#user-image-preview">
                <img id="user-image-preview" class="upload-preview profile-preview" src="{{ isset($user) && $user->image ? asset($user->image) : '' }}" @if(!isset($user) || !$user->image) style="display:none" @endif alt="Preview">
                @if(!isset($user) || !$user->image)<div class="profile-avatar-large"><i class="bi bi-person"></i></div>@endif
                <div class="upload-dropzone-text"><i class="bi bi-cloud-arrow-up"></i><span>Click to upload image</span><small>JPG, PNG or WEBP, maximum 3MB</small></div>
            </label>
        </div>
    </div>
</div>
<div class="form-actions-admin"><a href="{{ route('admin.users.index') }}" class="btn-admin-outline"><i class="bi bi-arrow-left"></i> Cancel</a><button type="submit" class="btn-admin-primary"><i class="bi bi-check-lg"></i> {{ $submitLabel }}</button></div>
