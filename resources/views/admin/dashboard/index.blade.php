@extends('admin.master.master')
@section('title', 'Dashboard')
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Welcome back, {{ auth()->user()->name }}</h1>
        <p class="page-subtitle">Here is what is happening with {{ $restaurantSetting?->restaurant_name ?? 'your restaurant' }} today.</p>
    </div>
    @can('menu-item.create')<a href="{{ route('admin.menu-items.create') }}" class="btn-admin-primary"><i class="bi bi-plus-lg"></i> Add New Item</a>@endcan
</div>

<div class="stats-row">
    <div class="stat-card">
        <div class="stat-icon bg-yellow"><i class="bi bi-receipt"></i></div>
        <div>
            <div class="stat-value">{{ number_format($totalOrders) }}</div>
            <div class="stat-label">Total Orders</div>
            <div class="stat-trend {{ $orderTrend >= 0 ? 'up' : 'down' }}"><i class="bi bi-arrow-{{ $orderTrend >= 0 ? 'up' : 'down' }}-short"></i>{{ number_format(abs($orderTrend), 1) }}% this month</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-green"><i class="bi bi-cash-stack"></i></div>
        <div>
            <div class="stat-value">TK {{ number_format($totalRevenue, 0) }}</div>
            <div class="stat-label">Total Revenue</div>
            <div class="stat-trend {{ $revenueTrend >= 0 ? 'up' : 'down' }}"><i class="bi bi-arrow-{{ $revenueTrend >= 0 ? 'up' : 'down' }}-short"></i>{{ number_format(abs($revenueTrend), 1) }}% this month</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-blue"><i class="bi bi-egg-fried"></i></div>
        <div>
            <div class="stat-value">{{ number_format($menuItemCount) }}</div>
            <div class="stat-label">Menu Items</div>
            <div class="stat-trend up"><i class="bi bi-plus-lg"></i>{{ number_format($newMenuItemsThisWeek) }} new this week</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-red"><i class="bi bi-people-fill"></i></div>
        <div>
            <div class="stat-value">{{ number_format($registeredUsers) }}</div>
            <div class="stat-label">Registered Users</div>
            <div class="stat-trend up"><i class="bi bi-plus-lg"></i>{{ number_format($newUsersThisMonth) }} new this month</div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="admin-card">
            <div class="admin-card-header">
                <h2 class="admin-card-title">Recent Orders</h2>
                @can('order.view')<a href="{{ route('admin.orders.index') }}" class="admin-card-link">View all</a>@endcan
            </div>
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead><tr><th>Order ID</th><th>Customer</th><th>Items</th><th>Total</th><th>Status</th></tr></thead>
                    <tbody>
                    @forelse($recentOrders as $order)
                        <tr>
                            <td>@can('order.view')<a href="{{ route('admin.orders.show', $order) }}" class="text-decoration-none">#{{ $order->order_number }}</a>@else #{{ $order->order_number }} @endcan</td>
                            <td><div>{{ $order->customer_name }}</div>@if($order->branch)<small class="text-muted">{{ $order->branch->name }}</small>@endif</td>
                            <td>{{ $order->items->take(2)->map(fn($item) => $item->item_name.($item->quantity > 1 ? ' ×'.$item->quantity : ''))->join(', ') }}@if($order->items->count() > 2) <span class="text-muted">+{{ $order->items->count() - 2 }}</span>@endif</td>
                            <td>TK {{ number_format((float) $order->grand_total, 0) }}</td>
                            <td><span class="badge-status is-{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">No orders found yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="admin-card">
            <div class="admin-card-header"><h2 class="admin-card-title">Top Categories</h2></div>
            @forelse($topCategories as $category)
                <div class="stat-bar-row">
                    <div class="stat-bar-label"><span>{{ $category['name'] }}</span><span>{{ $category['percent'] }}%</span></div>
                    <div class="stat-bar-track"><div class="stat-bar-fill" style="width:{{ $category['percent'] }}%"></div></div>
                    <div class="small text-muted mt-1">{{ number_format($category['quantity']) }} item{{ $category['quantity'] === 1 ? '' : 's' }} sold</div>
                </div>
            @empty
                <div class="text-center text-muted py-4">No category sales data yet.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
