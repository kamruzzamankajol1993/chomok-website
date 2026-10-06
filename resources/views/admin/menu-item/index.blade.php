@extends('admin.master.master')

@section('title', 'Menu Items')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb-admin"><a href="{{ route('admin.dashboard') }}">Dashboard</a> / <span class="current">Menu Items</span></div>
        <h1 class="page-title">Menu Items</h1>
        <p class="page-subtitle">{{ $itemCount }} items across {{ $categoryCount }} categories.</p>
    </div>
    @can('menu-item.create')
        <a href="{{ route('admin.menu-items.create') }}" class="btn-admin-primary"><i class="bi bi-plus-lg"></i> Add New Item</a>
    @endcan
</div>

<form
    id="menuItemFilterForm"
    action="{{ route('admin.menu-items.index') }}"
    method="get"
    data-ajax-list-form
    data-target="#ajax-list-container"
></form>

<div class="admin-card menu-item-filter-section">
    <div class="menu-item-filter-heading">
        <div class="menu-item-filter-icon"><i class="bi bi-funnel"></i></div>
        <div>
            <h2>Filter Menu Items</h2>
            <p>Filter the menu list by category and current status.</p>
        </div>
    </div>

    <div class="menu-item-filter-row">
        <div class="menu-item-filter-field">
            <label class="form-label-admin" for="menu-item-category-filter">Category</label>
            <select
                id="menu-item-category-filter"
                name="category_id"
                form="menuItemFilterForm"
                class="form-control-admin catalog-filter-select"
                data-ajax-filter
            >
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ (string) request('category_id') === (string) $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="menu-item-filter-field">
            <label class="form-label-admin" for="menu-item-status-filter">Status</label>
            <select
                id="menu-item-status-filter"
                name="status"
                form="menuItemFilterForm"
                class="form-control-admin catalog-status-select"
                data-ajax-filter
            >
                <option value="">All Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
    </div>
</div>

<div class="admin-card">
    <div class="catalog-list-toolbar menu-item-list-toolbar">
        <div class="topbar-search catalog-table-search menu-item-table-search">
            <i class="bi bi-search"></i>
            <input
                type="text"
                name="search"
                form="menuItemFilterForm"
                value="{{ request('search') }}"
                placeholder="Search menu items..."
                autocomplete="off"
                data-ajax-search
            >
        </div>

        @if(auth()->user()->canAny(['menu-item.edit', 'menu-item.delete']))
            @include('admin.include.bulk-actions', [
                'formId' => 'menuItemBulkForm',
                'action' => route('admin.menu-items.bulk-action'),
                'canEdit' => auth()->user()->can('menu-item.edit'),
                'canDelete' => auth()->user()->can('menu-item.delete'),
            ])
        @endif
    </div>

    <div id="ajax-list-container">
        @include('admin.menu-item.partials.table')
    </div>
</div>
@endsection
