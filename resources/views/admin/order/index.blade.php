@extends('admin.master.master')
@section('title', 'Orders')
@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb-admin"><a href="{{ route('admin.dashboard') }}">Dashboard</a> / <span class="current">Orders</span></div>
        <h1 class="page-title">Orders</h1>
        <p class="page-subtitle">Track and manage incoming customer orders.</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('admin.orders.export') }}" class="btn-admin-outline"><i class="bi bi-download"></i> Export CSV</a>
        @can('order.create')
        {{-- <a href="{{ route('admin.orders.create') }}" class="btn-admin-primary">
            <i class="bi bi-plus-lg"></i> Create Order</a> --}}
            @endcan
    </div>
</div>

<div class="stats-row">
    <div class="stat-card"><div class="stat-icon bg-yellow"><i class="bi bi-hourglass-split"></i></div><div><div class="stat-value">{{ $stats['pending'] }}</div><div class="stat-label">Pending</div></div></div>
    <div class="stat-card"><div class="stat-icon bg-blue"><i class="bi bi-arrow-repeat"></i></div><div><div class="stat-value">{{ $stats['processing'] }}</div><div class="stat-label">Processing</div></div></div>
    <div class="stat-card"><div class="stat-icon bg-green"><i class="bi bi-check-circle"></i></div><div><div class="stat-value">{{ $stats['delivered'] }}</div><div class="stat-label">Delivered</div></div></div>
    <div class="stat-card"><div class="stat-icon bg-red"><i class="bi bi-x-circle"></i></div><div><div class="stat-value">{{ $stats['cancelled'] }}</div><div class="stat-label">Cancelled</div></div></div>
</div>

<div class="admin-card">
    <form action="{{ route('admin.orders.index') }}" method="get" class="order-filter-form ajax-filter-form" data-ajax-list-form data-target="#ajax-order-list">
        <div class="order-status-tabs" role="group" aria-label="Order status filter">
            @foreach(['' => 'All Orders', 'pending' => 'Pending', 'confirmed' => 'Confirmed', 'processing' => 'Processing', 'delivered' => 'Delivered', 'cancelled' => 'Cancelled'] as $value => $label)
                <button type="button" class="{{ request('status', '') === $value ? 'btn-admin-primary' : 'btn-admin-outline' }} order-status-filter" data-status="{{ $value }}">{{ $label }}</button>
            @endforeach
            <input type="hidden" name="status" value="{{ request('status') }}" id="orderStatusFilterInput">
        </div>

        <div class="order-list-toolbar">
            <div class="topbar-search order-list-search"><i class="bi bi-search"></i><input type="text" name="search" value="{{ request('search') }}" placeholder="Search order, customer, phone or item..." data-ajax-search></div>
            @if(auth()->user()->canSeeAllBranches())
                <select name="branch_id" class="form-control-admin" data-ajax-filter>
                    <option value="">All Branches</option>
                    @foreach($branches as $branch)<option value="{{ $branch->id }}" @selected((string) request('branch_id') === (string) $branch->id)>{{ $branch->name }}</option>@endforeach
                </select>
            @endif
            <select name="source" class="form-control-admin" data-ajax-filter>
                <option value="">All Sources</option>
                <option value="admin" @selected(request('source') === 'admin')>Admin</option>
                <option value="website" @selected(request('source') === 'website')>Website</option>
            </select>
        </div>
    </form>

    <div id="ajax-order-list" data-ajax-list-container>@include('admin.order.partials.table')</div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    document.addEventListener('click', function (event) {
        var button = event.target.closest('.order-status-filter');
        if (!button) return;
        var form = button.closest('[data-ajax-list-form]');
        form.querySelector('#orderStatusFilterInput').value = button.dataset.status;
        form.querySelectorAll('.order-status-filter').forEach(function (item) {
            item.classList.toggle('btn-admin-primary', item === button);
            item.classList.toggle('btn-admin-outline', item !== button);
        });
        form.querySelector('[data-ajax-filter]')?.dispatchEvent(new Event('change', { bubbles: true }));
        if (!form.querySelector('[data-ajax-filter]')) form.requestSubmit();
    });

    document.addEventListener('change', function (event) {
        var select = event.target.closest('[data-order-status-update]');
        if (!select) return;
        var previous = select.dataset.previous;
        select.disabled = true;
        fetch(select.dataset.url, {
            method: 'PATCH',
            headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json', 'Content-Type': 'application/json'},
            body: JSON.stringify({status: select.value})
        }).then(function (response) { return response.ok ? response.json() : Promise.reject(new Error('Could not update status.')); })
          .then(function (data) { select.dataset.previous = select.value; Swal.fire({icon:'success', title:data.message, timer:1200, showConfirmButton:false}); })
          .catch(function (error) { select.value = previous; Swal.fire({icon:'error', title:error.message}); })
          .finally(function () { select.disabled = false; });
    });
})();
</script>
@endpush
