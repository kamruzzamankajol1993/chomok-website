@extends('admin.master.master')
@section('title','Roles')
@section('content')
<div class="page-header">
    <div><div class="breadcrumb-admin"><a href="{{ route('admin.dashboard') }}">Dashboard</a> / <span class="current">Roles</span></div><h1 class="page-title">Roles</h1><p class="page-subtitle">Create roles and assign permissions group-wise or individually.</p></div>
    @can('role.create')<a href="{{ route('admin.roles.create') }}" class="btn-admin-primary"><i class="bi bi-plus-lg"></i> Add Role</a>@endcan
</div>
<div class="admin-card">
    <form method="get" action="{{ route('admin.roles.index') }}" class="ajax-filter-form d-flex flex-wrap gap-2 mb-3" data-ajax-list-form data-target="#ajax-list-container">
        <div class="topbar-search" style="width:300px;"><i class="bi bi-search"></i><input type="search" name="search" value="{{ request('search') }}" placeholder="Search role..." data-ajax-search></div>
    </form>
    <div id="ajax-list-container" class="admin-table-loader-host" data-ajax-list-container>@include('admin.role.partials.table')</div>
</div>
@endsection
