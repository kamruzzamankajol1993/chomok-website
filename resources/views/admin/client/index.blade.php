@extends('admin.master.master')

@section('title', 'Clients')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb-admin"><a href="{{ route('admin.dashboard') }}">Dashboard</a> / <span class="current">Clients</span></div>
        <h1 class="page-title">Clients</h1>
        <p class="page-subtitle">{{ number_format($totalClients) }} registered customers.</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('admin.clients.export') }}" class="btn-admin-outline"><i class="bi bi-download"></i> Export CSV</a>
        @can('client.create')
            <a href="{{ route('admin.clients.create') }}" class="btn-admin-primary"><i class="bi bi-plus-lg"></i> Add Client</a>
        @endcan
    </div>
</div>

<div class="admin-card">
    <form action="{{ route('admin.clients.index') }}" method="get" class="client-filter-row ajax-filter-form" data-ajax-list-form data-target="#ajax-client-list">
        <div class="topbar-search client-filter-search">
            <i class="bi bi-search"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search clients by name, email or phone..." autocomplete="off" data-ajax-search>
        </div>
        @if(auth()->user()->canSeeAllBranches())
            <select name="branch_id" class="form-control-admin" data-ajax-filter>
                <option value="">All Branches</option>
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}" @selected((string) request('branch_id') === (string) $branch->id)>{{ $branch->name }}</option>
                @endforeach
            </select>
        @endif
        <select name="status" class="form-control-admin" data-ajax-filter>
            <option value="">All Status</option>
            <option value="active" @selected(request('status') === 'active')>Active</option>
            <option value="blocked" @selected(request('status') === 'blocked')>Blocked</option>
        </select>
    </form>

    <div id="ajax-client-list" data-ajax-list-container>
        @include('admin.client.partials.table')
    </div>
</div>
@endsection
