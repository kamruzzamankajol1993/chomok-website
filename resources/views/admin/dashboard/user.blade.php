@extends('admin.master.master')
@section('title', 'Dashboard')
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Welcome back, {{ auth()->user()->name }}</h1>
        <p class="page-subtitle">
            @if(auth()->user()->canSeeAllBranches())
                Here is your order summary.
            @else
                Here is the latest order summary for {{ auth()->user()->branch?->name ?? 'your branch' }}.
            @endif
        </p>
    </div>
</div>

<div class="stats-row">
    <div class="stat-card">
        <div class="stat-icon bg-yellow"><i class="bi bi-receipt"></i></div>
        <div>
            <div class="stat-value">{{ number_format($totalOrders) }}</div>
            <div class="stat-label">Total Orders</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon bg-yellow"><i class="bi bi-hourglass-split"></i></div>
        <div>
            <div class="stat-value">{{ number_format($totalPending) }}</div>
            <div class="stat-label">Total Pending</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon bg-green"><i class="bi bi-check-circle"></i></div>
        <div>
            <div class="stat-value">{{ number_format($totalConfirmed) }}</div>
            <div class="stat-label">Total Confirmed</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon bg-red"><i class="bi bi-x-circle"></i></div>
        <div>
            <div class="stat-value">{{ number_format($totalCancelled) }}</div>
            <div class="stat-label">Total Cancelled</div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-12">
        <div class="admin-card">
            <div class="admin-card-header">
                <h2 class="admin-card-title">Latest 5 Orders</h2>
                @can('order.view')
                    <a href="{{ route('admin.orders.index') }}" class="admin-card-link">View all</a>
                @endcan
            </div>

            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($recentOrders as $order)
                        <tr>
                            <td>
                                @can('order.view')
                                    <a href="{{ route('admin.orders.show', $order) }}" class="text-decoration-none">#{{ $order->order_number }}</a>
                                @else
                                    #{{ $order->order_number }}
                                @endcan
                            </td>
                            <td>
                                <div>{{ $order->customer_name }}</div>
                                @if($order->branch)
                                    <small class="text-muted">{{ $order->branch->name }}</small>
                                @endif
                            </td>
                            <td>
                                {{ $order->items->take(2)->map(fn($item) => $item->item_name.($item->quantity > 1 ? ' ×'.$item->quantity : ''))->join(', ') }}
                                @if($order->items->count() > 2)
                                    <span class="text-muted">+{{ $order->items->count() - 2 }}</span>
                                @endif
                            </td>
                            <td>TK {{ number_format((float) $order->grand_total, 0) }}</td>
                            <td><span class="badge-status is-{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No orders found yet.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
