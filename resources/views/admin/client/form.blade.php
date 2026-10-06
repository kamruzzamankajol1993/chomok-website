@php($isEdit = isset($client))
<div class="page-header">
    <div>
        <div class="breadcrumb-admin"><a href="{{ route('admin.dashboard') }}">Dashboard</a> / <a href="{{ route('admin.clients.index') }}">Clients</a> / <span class="current">{{ $isEdit ? 'Edit' : 'Create' }}</span></div>
        <h1 class="page-title">{{ $isEdit ? 'Edit Client' : 'Create Client' }}</h1>
        <p class="page-subtitle">Client branch is assigned automatically from the logged-in user.</p>
    </div>
    <a href="{{ route('admin.clients.index') }}" class="btn-admin-outline"><i class="bi bi-arrow-left"></i> Back to Clients</a>
</div>

<form action="{{ $isEdit ? route('admin.clients.update', $client) : route('admin.clients.store') }}" method="post" data-form-loader data-loader-text="Saving client...">
    @csrf
    @if($isEdit) @method('PUT') @endif
    <div class="admin-card form-surface-card form-loader-surface">
        <div class="form-panel-header">
            <div class="form-panel-icon"><i class="bi bi-person-lines-fill"></i></div>
            <div><h2>Client Information</h2><p>Contact details, login access and account status.</p></div>
        </div>

        @include('admin.include.form-errors')

        <div class="form-grid-two">
            <div class="form-group-admin">
                <label class="form-label-admin">Full Name <span class="required">*</span></label>
                <input type="text" name="name" class="form-control-admin" value="{{ old('name', $client->name ?? '') }}" required>
            </div>
            <div class="form-group-admin">
                <label class="form-label-admin">Phone <span class="required">*</span></label>
                <input type="text" name="phone" class="form-control-admin" value="{{ old('phone', $client->phone ?? '') }}" required>
            </div>
            <div class="form-group-admin">
                <label class="form-label-admin">Email</label>
                <input type="email" name="email" class="form-control-admin" value="{{ old('email', $client->email ?? '') }}">
            </div>
            <div class="form-group-admin">
                <label class="form-label-admin">Status <span class="required">*</span></label>
                <select name="status" class="form-control-admin" required>
                    <option value="active" @selected(old('status', $client->status ?? 'active') === 'active')>Active</option>
                    <option value="blocked" @selected(old('status', $client->status ?? 'active') === 'blocked')>Blocked</option>
                </select>
            </div>
        </div>

        <div class="form-group-admin">
            <label class="form-label-admin">Address</label>
            <textarea name="address" rows="3" class="form-control-admin">{{ old('address', $client->address ?? '') }}</textarea>
        </div>
        <div class="form-group-admin">
            <label class="form-label-admin">Internal Note</label>
            <textarea name="notes" rows="3" class="form-control-admin">{{ old('notes', $client->notes ?? '') }}</textarea>
        </div>

        <div class="switch-row d-flex align-items-center justify-content-between gap-3 mb-3">
            <div><div class="fw-bold">Allow Website Login</div><div class="form-help">Enable this client to sign in when the website login is connected.</div></div>
            <label class="form-switch-admin"><input type="checkbox" name="can_login" value="1" id="clientCanLogin" @checked(old('can_login', $client->can_login ?? false))><span class="switch-track"></span></label>
        </div>

        <div id="clientPasswordFields" class="form-grid-two">
            <div class="form-group-admin">
                <label class="form-label-admin">Password {{ $isEdit ? '(leave blank to keep current)' : '' }}</label>
                <input type="password" name="password" class="form-control-admin" autocomplete="new-password">
            </div>
            <div class="form-group-admin">
                <label class="form-label-admin">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control-admin" autocomplete="new-password">
            </div>
        </div>

        <div class="form-actions-admin">
            <a href="{{ route('admin.clients.index') }}" class="btn-admin-outline">Cancel</a>
            <button type="submit" class="btn-admin-primary"><i class="bi bi-check-lg"></i> {{ $isEdit ? 'Update Client' : 'Save Client' }}</button>
        </div>
    </div>
</form>

@push('scripts')
<script>
(function () {
    var toggle = document.getElementById('clientCanLogin');
    var fields = document.getElementById('clientPasswordFields');
    function sync() { fields.style.display = toggle.checked ? 'grid' : 'none'; }
    toggle.addEventListener('change', sync); sync();
})();
</script>
@endpush
