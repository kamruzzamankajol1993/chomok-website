@extends('admin.master.master')
@section('title', 'Branches')
@section('content')
<div class="page-header">
    <div><div class="breadcrumb-admin"><a href="{{ route('admin.dashboard') }}">Dashboard</a> / <span class="current">Branches</span></div><h1 class="page-title">Branches</h1><p class="page-subtitle">Manage every location that can receive orders.</p></div>
    @can('branch.create')@if(auth()->user()->canSeeAllBranches())<a href="{{ route('admin.branches.create') }}" class="btn-admin-primary"><i class="bi bi-plus-lg"></i> Add Branch</a>@endif @endcan
</div>

<div class="admin-card">
    <form method="get" action="{{ route('admin.branches.index') }}" class="ajax-filter-form d-flex flex-wrap gap-2 mb-3" data-ajax-list-form data-target="#ajax-list-container">
        <div class="topbar-search" style="width:300px;"><i class="bi bi-search"></i><input type="search" name="search" value="{{ request('search') }}" placeholder="Search branch, code, phone..." data-ajax-search></div>
        <select name="status" class="form-control-admin choices-select" style="width:170px;" data-ajax-filter>
            <option value="">All Status</option><option value="active" @selected(request('status')==='active')>Active</option><option value="inactive" @selected(request('status')==='inactive')>Inactive</option>
        </select>
    </form>
    <div id="ajax-list-container" class="admin-table-loader-host" data-ajax-list-container>@include('admin.branch.partials.table')</div>
</div>
@endsection
