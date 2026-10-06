@extends('admin.master.master')
@section('title', 'User Details')
@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb-admin"><a href="{{ route('admin.dashboard') }}">Dashboard</a> / <a href="{{ route('admin.users.index') }}">Users</a> / <span class="current">Details</span></div>
        <h1 class="page-title">User Details</h1>
        <p class="page-subtitle">Review profile, branch assignment, role and account access.</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('admin.users.index') }}" class="btn-admin-outline"><i class="bi bi-arrow-left"></i> Back</a>
        @can('user.edit')<a href="{{ route('admin.users.edit', $user) }}" class="btn-admin-primary"><i class="bi bi-pencil"></i> Edit User</a>@endcan
    </div>
</div>

<div class="row g-4 align-items-start">
    <div class="col-xl-4">
        <div class="admin-card user-show-profile-card">
            @if($user->image)
                <img src="{{ asset($user->image) }}" class="user-show-avatar" alt="{{ $user->name }}">
            @else
                <div class="user-show-avatar avatar-fallback">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
            @endif
            <h2>{{ $user->name }}</h2>
            <p>{{ $user->email }}</p>
            <div class="user-show-badges">
                <span class="badge-status {{ $user->status === 'active' ? 'is-active' : 'is-blocked' }}">{{ ucfirst($user->status) }}</span>
                <span class="badge-status {{ $user->canSeeAllBranches() ? 'is-processing' : 'is-pending' }}">{{ $user->canSeeAllBranches() ? 'All Branches' : 'Own Branch' }}</span>
            </div>
            <div class="profile-meta-list">
                <div><span>Phone</span><strong>{{ $user->phone ?: 'Not provided' }}</strong></div>
                <div><span>Joined</span><strong>{{ $user->created_at?->format('d/m/Y') }}</strong></div>
                <div><span>Last Updated</span><strong>{{ $user->updated_at?->format('d/m/Y') }}</strong></div>
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        <div class="admin-card user-detail-card">
            <div class="form-panel-header">
                <div class="form-panel-icon"><i class="bi bi-diagram-3"></i></div>
                <div><h2>Assignment & Access</h2><p>Branch, role and permission summary for this account.</p></div>
            </div>
            <div class="detail-grid">
                <div class="detail-item"><span>Branch</span><strong>{{ $user->branch?->name ?? 'Not assigned' }}</strong><small>{{ $user->branch?->code ?: 'No branch code' }}</small></div>
                <div class="detail-item"><span>Assigned Role</span><strong>{{ $user->getRoleNames()->join(', ') ?: 'No role assigned' }}</strong><small>Controls feature-level access</small></div>
                <div class="detail-item"><span>Data Scope</span><strong>{{ $user->canSeeAllBranches() ? 'All Branches' : 'Assigned Branch Only' }}</strong><small>{{ $user->canSeeAllBranches() ? 'Can view cross-branch data' : 'Branch-scoped data access' }}</small></div>
                <div class="detail-item"><span>Account Status</span><strong>{{ ucfirst($user->status) }}</strong><small>{{ $user->status === 'active' ? 'Login is currently allowed' : 'Login is currently blocked' }}</small></div>
            </div>
        </div>

        <div class="admin-card user-detail-card mt-4">
            <div class="form-panel-header">
                <div class="form-panel-icon"><i class="bi bi-shield-check"></i></div>
                <div><h2>Effective Permissions</h2><p>Permissions inherited from the assigned role.</p></div>
            </div>
            <div class="permission-chip-list">
                @forelse($user->getAllPermissions()->sortBy('name') as $permission)
                    <span class="permission-chip"><i class="bi bi-check2"></i>{{ $permission->name }}</span>
                @empty
                    <div class="empty-inline-state">No permissions are assigned to this user.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
