@extends('admin.master.master')

@section('title', $menuItem->name)

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb-admin"><a href="{{ route('admin.dashboard') }}">Dashboard</a> / <a href="{{ route('admin.menu-items.index') }}">Menu Items</a> / <span class="current">View Item</span></div>
        <h1 class="page-title">{{ $menuItem->name }}</h1>
        <p class="page-subtitle">Complete menu item information, pricing and availability.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.menu-items.index') }}" class="btn-admin-outline"><i class="bi bi-arrow-left"></i> Back</a>
        @can('menu-item.edit')
            <a href="{{ route('admin.menu-items.edit', $menuItem) }}" class="btn-admin-primary"><i class="bi bi-pencil"></i> Edit Item</a>
        @endcan
    </div>
</div>

<div class="menu-item-show-layout">
    <div class="form-page-main">
        <div class="admin-card mb-3">
            <h2 class="form-section-title">Photos</h2>
            <p class="form-section-hint">The highlighted photo is used as the main image across the website.</p>
            <div class="menu-show-gallery">
                @foreach($menuItem->images as $image)
                    <div class="menu-show-image {{ $image->is_main ? 'is-main' : '' }}">
                        <img src="{{ asset($image->image) }}" alt="{{ $menuItem->name }}">
                        @if($image->is_main)<span>Main Image</span>@endif
                    </div>
                @endforeach
            </div>
        </div>

        <div class="admin-card mb-3">
            <h2 class="form-section-title">Basic Information</h2>
            <div class="detail-grid-admin">
                <div><span>Item Name</span><strong>{{ $menuItem->name }}</strong></div>
                <div><span>Category</span><strong>{{ $menuItem->category?->name ?? '—' }}</strong></div>
                <div><span>Subcategory</span><strong>{{ $menuItem->subcategory?->name ?? '—' }}</strong></div>
                <div><span>Status</span><strong><span class="badge-status {{ $menuItem->is_active ? 'is-active' : 'is-inactive' }}">{{ $menuItem->is_active ? 'Active' : 'Inactive' }}</span></strong></div>
                <div class="detail-grid-wide"><span>Description</span><strong>{{ $menuItem->description ?: 'No description added.' }}</strong></div>
            </div>
        </div>

        <div class="admin-card">
            <h2 class="form-section-title">Pricing</h2>
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead><tr><th>Size</th><th>Regular Price</th><th>Discount Price</th><th>Variation Add-Ons</th></tr></thead>
                    <tbody>
                        @foreach($menuItem->prices as $price)
                            <tr>
                                <td>{{ $price->size_label ?: 'Regular' }}</td>
                                <td>TK {{ number_format((float) $price->price, ((float) $price->price == floor((float) $price->price)) ? 0 : 2) }}</td>
                                <td>
                                    @if($price->discount_price !== null && (float) $price->discount_price > 0)
                                        <span class="badge-status is-active">TK {{ number_format((float) $price->discount_price, 2) }}</span>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>
                                    @forelse($price->variationAddons as $variationAddon)
                                        <div class="mb-2">
                                            <span class="badge-status is-active me-1">{{ $variationAddon->name }} +TK {{ number_format((float) $variationAddon->price, ((float) $variationAddon->price == floor((float) $variationAddon->price)) ? 0 : 2) }}</span>
                                            @if(filled($variationAddon->description))
                                                <div class="form-help mt-1">{{ $variationAddon->description }}</div>
                                            @endif
                                        </div>
                                    @empty
                                        —
                                    @endforelse
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="form-page-side">
        <div class="admin-card mb-3">
            <h2 class="form-section-title">Add-Ons</h2>
            <div class="catalog-chip-list">
                @forelse($menuItem->addons as $addon)
                    <span>{{ $addon->name }} <b>+TK {{ number_format((float) $addon->price, ((float) $addon->price == floor((float) $addon->price)) ? 0 : 2) }}</b></span>
                @empty
                    <p class="form-section-hint mb-0">No add-ons assigned.</p>
                @endforelse
            </div>
        </div>

        <div class="admin-card mb-3">
            <h2 class="form-section-title">Available At Branches</h2>
            <div class="catalog-chip-list">
                @forelse($menuItem->branches as $branch)
                    <span><i class="bi bi-shop"></i> {{ $branch->name }}</span>
                @empty
                    <p class="form-section-hint mb-0">No branch assigned.</p>
                @endforelse
            </div>
        </div>

        <div class="admin-card">
            <h2 class="form-section-title">Record Information</h2>
            <div class="detail-stack-admin">
                <div><span>Created</span><strong>{{ $menuItem->created_at?->format('d/m/Y') }}</strong></div>
                <div><span>Last Updated</span><strong>{{ $menuItem->updated_at?->format('d/m/Y') }}</strong></div>
            </div>
        </div>
    </div>
</div>
@endsection
