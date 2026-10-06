@include('admin.include.form-errors')
<div class="admin-card form-surface-card">
    <div class="form-panel-header">
        <div class="form-panel-icon"><i class="bi bi-person-badge"></i></div>
        <div><h2>Role Information</h2><p>Name the role and select the permissions it should receive.</p></div>
    </div>
    <div class="form-group-admin"><label class="form-label-admin">Role Name <span class="required">*</span></label><div class="form-control-icon-wrap"><i class="bi bi-tag"></i><input type="text" name="name" class="form-control-admin" value="{{ old('name',$role->name ?? '') }}" required></div></div>
    <div class="permission-toolbar">
        <label class="permission-select-all"><input type="checkbox" id="select-all-permissions"> <strong>Select All Permissions</strong></label>
        <span class="form-help">Select all, choose a complete group, or select individual permissions.</span>
    </div>
    <div class="permission-groups">
        @forelse($permissionGroups as $group => $permissions)
        <section class="permission-group-card" data-permission-group>
            <div class="permission-group-header"><label><input type="checkbox" class="group-permission-toggle"> <strong>{{ $group }}</strong></label><span>{{ $permissions->count() }} permission(s)</span></div>
            <div class="permission-grid">
                @foreach($permissions as $permission)
                <label class="permission-item"><input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="permission-checkbox" @checked(in_array($permission->id, old('permissions',$selectedPermissions ?? [])))><span>{{ $permission->name }}</span></label>
                @endforeach
            </div>
        </section>
        @empty<div class="empty-state"><p>No permissions are available.</p></div>@endforelse
    </div>
    <div class="form-actions-admin"><a href="{{ route('admin.roles.index') }}" class="btn-admin-outline"><i class="bi bi-arrow-left"></i> Cancel</a><button type="submit" class="btn-admin-primary"><i class="bi bi-check-lg"></i> {{ $submitLabel }}</button></div>
</div>
