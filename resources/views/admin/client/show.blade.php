@extends('admin.master.master')
@section('title', 'Client Profile')
@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb-admin"><a href="{{ route('admin.dashboard') }}">Dashboard</a> / <a href="{{ route('admin.clients.index') }}">Clients</a> / <span class="current">{{ $client->name }}</span></div>
        <h1 class="page-title">Client Profile <span class="badge-status {{ $client->status === 'active' ? 'is-active' : 'is-blocked' }}">{{ ucfirst($client->status) }}</span></h1>
        <p class="page-subtitle">Client since {{ $client->created_at?->format('d/m/Y') }}.</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('admin.clients.index') }}" class="btn-admin-outline"><i class="bi bi-arrow-left"></i> Back to Clients</a>
        @can('client.edit')
            <form action="{{ route('admin.clients.status', $client) }}" method="post">@csrf @method('PATCH')
                <button type="submit" class="btn-admin-outline"><i class="bi bi-{{ $client->status === 'active' ? 'slash-circle' : 'check-circle' }}"></i> {{ $client->status === 'active' ? 'Block Client' : 'Activate Client' }}</button>
            </form>
            <a href="{{ route('admin.clients.edit', $client) }}" class="btn-admin-primary"><i class="bi bi-pencil"></i> Edit Client</a>
        @endcan
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="admin-card mb-3">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="client-profile-avatar">{{ strtoupper(substr($client->name, 0, 1)) }}</div>
                <div><div class="fw-bold" style="font-size:1.1rem;">{{ $client->name }}</div><div class="table-item-sub">Customer ID: #{{ $client->code }}</div></div>
            </div>
            <div class="order-detail-row"><span>Email</span><span>{{ $client->email ?: '—' }}</span></div>
            <div class="order-detail-row"><span>Phone</span><span>{{ $client->phone }}</span></div>
            <div class="order-detail-row"><span>Address</span><span>{{ $client->address ?: '—' }}</span></div>
            <div class="order-detail-row"><span>Preferred Branch</span><span>{{ $client->branch?->name ?? '—' }}</span></div>
            <div class="order-detail-row"><span>Joined</span><span>{{ $client->created_at?->format('d/m/Y') }}</span></div>
            <div class="order-detail-row"><span>Total Spent</span><span>TK {{ number_format($stats['spent'], 2) }}</span></div>
            <div class="order-detail-row"><span>Website Login</span><span>{{ $client->can_login ? 'Allowed' : 'Not Allowed' }}</span></div>
        </div>

        @can('client.edit')
        <form action="{{ route('admin.clients.notes', $client) }}" method="post" class="admin-card" data-form-loader data-loader-text="Saving note...">
            @csrf @method('PUT')
            <div class="admin-card-header"><h2 class="admin-card-title">Internal Notes</h2></div>
            <textarea name="notes" class="form-control-admin" rows="4" placeholder="Add an internal note about this client...">{{ old('notes', $client->notes) }}</textarea>
            <button type="submit" class="btn-admin-primary w-100 justify-content-center mt-3"><i class="bi bi-check-lg"></i> Save Note</button>
        </form>
        @endcan
    </div>

    <div class="col-lg-8">
        <div class="stats-row client-stats-row">
            <div class="stat-card"><div class="stat-icon bg-yellow"><i class="bi bi-receipt"></i></div><div><div class="stat-value">{{ $stats['orders'] }}</div><div class="stat-label">Orders</div></div></div>
            <div class="stat-card"><div class="stat-icon bg-green"><i class="bi bi-check-circle"></i></div><div><div class="stat-value">{{ $stats['delivered'] }}</div><div class="stat-label">Delivered</div></div></div>
            <div class="stat-card"><div class="stat-icon bg-blue"><i class="bi bi-cash-stack"></i></div><div><div class="stat-value">TK {{ number_format($stats['spent'], 0) }}</div><div class="stat-label">Total Spent</div></div></div>
        </div>

        <div class="admin-card">
            <div class="admin-card-header"><h2 class="admin-card-title">Order History</h2></div>
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead><tr><th>Order ID</th><th>Items</th><th>Total</th><th>Payment</th><th>Date</th><th>Status</th><th></th></tr></thead>
                    <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="table-item-name">#{{ $order->order_number }}</td>
                            <td>{{ $order->items()->pluck('item_name')->take(2)->implode(', ') }}</td>
                            <td>TK {{ number_format((float) $order->grand_total, 2) }}</td>
                            <td>{{ $order->payment_label }}</td>
                            <td>{{ $order->created_at?->format('d/m/Y') }}</td>
                            <td><span class="badge-status is-{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
                            <td><a href="{{ route('admin.orders.show', $order) }}" class="table-action-btn" title="View Order"><i class="bi bi-eye"></i></a></td>
                        </tr>
                    @empty
                        <tr><td colspan="7"><div class="empty-state"><i class="bi bi-receipt"></i><h3>No orders found</h3></div></td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            @include('admin.include.pagination', ['paginator' => $orders])
        </div>
    </div>
</div>
@endsection
