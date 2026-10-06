@extends('admin.master.master')
@section('title', 'Users')
@section('content')
<div class="page-header">
    <div><div class="breadcrumb-admin"><a href="{{ route('admin.dashboard') }}">Dashboard</a> / <span class="current">Users</span></div><h1 class="page-title">Users</h1><p class="page-subtitle">Manage admin users, branch access and assigned roles.</p></div>
    @can('user.create')<a href="{{ route('admin.users.create') }}" class="btn-admin-primary"><i class="bi bi-person-plus"></i> Add User</a>@endcan
</div>
<div class="admin-card">
    <form method="get" action="{{ route('admin.users.index') }}" class="ajax-filter-form user-filter-toolbar mb-3" data-ajax-list-form data-target="#ajax-list-container">
        <div class="topbar-search user-filter-search"><i class="bi bi-search"></i><input type="search" name="search" value="{{ request('search') }}" placeholder="Search name, email or phone..." data-ajax-search></div>
        @if(auth()->user()->canSeeAllBranches())
            <div class="user-filter-control user-filter-branch">
                <select name="branch_id" class="form-control-admin choices-select" data-ajax-filter><option value="">All Branches</option>@foreach($branches as $branch)<option value="{{ $branch->id }}" @selected((string)request('branch_id')===(string)$branch->id)>{{ $branch->name }}</option>@endforeach</select>
            </div>
        @endif
        <div class="user-filter-control user-filter-status">
            <select name="status" class="form-control-admin choices-select" data-ajax-filter><option value="">All Status</option><option value="active" @selected(request('status')==='active')>Active</option><option value="inactive" @selected(request('status')==='inactive')>Inactive</option></select>
        </div>
    </form>
    <div id="ajax-list-container" class="admin-table-loader-host" data-ajax-list-container>@include('admin.user.partials.table')</div>
</div>
@endsection
