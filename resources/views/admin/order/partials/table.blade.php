<div class="admin-table-wrap">
<table class="admin-table">
    <thead><tr><th><input type="checkbox" class="table-checkbox" data-select-all></th><th>Order ID</th><th>Customer</th><th>Items</th><th>Total</th><th>Payment</th><th>Branch</th><th>Source</th><th>Date</th><th>Status</th><th>Actions</th></tr></thead>
    <tbody>
    @forelse($orders as $order)
        <tr>
            <td><input type="checkbox" class="table-checkbox" value="{{ $order->id }}"></td>
            <td class="table-item-name">#{{ $order->order_number }}</td>
            <td><div class="table-item-name">{{ $order->customer_name }}</div><div class="table-item-sub">{{ $order->customer_phone }}</div></td>
            <td>{{ $order->items->pluck('item_name')->take(2)->implode(', ') }}{{ $order->items->count() > 2 ? ' +' . ($order->items->count() - 2) : '' }}</td>
            <td>TK {{ number_format((float) $order->grand_total, 2) }}</td>
            <td>{{ $order->payment_label }}</td>
            <td>{{ $order->branch?->name ?? '—' }}</td>
            <td><span class="source-badge source-{{ $order->source }}"><i class="bi {{ $order->source === 'website' ? 'bi-globe2' : 'bi-person-workspace' }}"></i> {{ ucfirst($order->source) }}</span></td>
            <td>{{ $order->created_at?->format('d/m/Y') }}</td>
            <td>
                @can('order.edit')
                    <select class="form-control-admin order-status-select" data-order-status-update data-url="{{ route('admin.orders.status', $order) }}" data-previous="{{ $order->status }}">
                        @foreach(['pending','confirmed','processing','delivered','cancelled'] as $status)<option value="{{ $status }}" @selected($order->status === $status)>{{ ucfirst($status) }}</option>@endforeach
                    </select>
                @else
                    <span class="badge-status is-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                @endcan
            </td>
            <td><div class="table-actions">
                @can('order.view')<a href="{{ route('admin.orders.show', $order) }}" class="table-action-btn" title="View Order"><i class="bi bi-eye"></i></a>@endcan
                @can('order.edit')<a href="{{ route('admin.orders.edit', $order) }}" class="table-action-btn" title="Edit Order"><i class="bi bi-pencil"></i></a>@endcan
                @can('order.delete')<form action="{{ route('admin.orders.destroy', $order) }}" method="post" class="delete-form">@csrf @method('DELETE')<button class="table-action-btn danger" title="Delete"><i class="bi bi-trash"></i></button></form>@endcan
            </div></td>
        </tr>
    @empty
        <tr><td colspan="11"><div class="empty-state"><i class="bi bi-receipt"></i><h3>No orders found</h3><p>Orders will appear here.</p></div></td></tr>
    @endforelse
    </tbody>
</table>
</div>
@include('admin.include.pagination', ['paginator' => $orders])
