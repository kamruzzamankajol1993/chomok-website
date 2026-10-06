@extends('admin.master.master')
@section('title','Permissions')
@section('content')
<div class="page-header">
    <div><div class="breadcrumb-admin"><a href="{{ route('admin.dashboard') }}">Dashboard</a> / <span class="current">Permissions</span></div><h1 class="page-title">Permissions</h1><p class="page-subtitle">Permissions are organized by the new group column.</p></div>
    @can('permission.create')<a href="{{ route('admin.permissions.create') }}" class="btn-admin-primary"><i class="bi bi-plus-lg"></i> Add Permission Group</a>@endcan
</div>
<div class="admin-card">
<form method="get" action="{{ route('admin.permissions.index') }}" class="ajax-filter-form d-flex flex-wrap gap-2 mb-3" data-ajax-list-form data-target="#ajax-list-container"><div class="topbar-search" style="width:320px;"><i class="bi bi-search"></i><input type="search" name="search" value="{{ request('search') }}" placeholder="Search permission or group..." data-ajax-search></div></form>
<div id="ajax-list-container" class="admin-table-loader-host" data-ajax-list-container>@include('admin.permission.partials.table')</div>
</div>
@endsection
