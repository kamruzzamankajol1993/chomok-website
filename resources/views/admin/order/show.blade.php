@extends('admin.master.master')
@section('title', 'Order '.$order->order_number)
@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb-admin"><a href="{{ route('admin.dashboard') }}">Dashboard</a> / <a href="{{ route('admin.orders.index') }}">Orders</a> / <span class="current">#{{ $order->order_number }}</span></div>
        <h1 class="page-title">Order #{{ $order->order_number }} <span class="badge-status is-{{ $order->status }}">{{ ucfirst($order->status) }}</span></h1>
        <p class="page-subtitle">Placed on {{ $order->created_at?->format('d/m/Y h:i A') }} at {{ $order->branch?->name }}.</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('admin.orders.index') }}" class="btn-admin-outline"><i class="bi bi-arrow-left"></i> Back to Orders</a>
        @can('order.edit')<a href="{{ route('admin.orders.edit', $order) }}" class="btn-admin-outline"><i class="bi bi-pencil"></i> Edit</a>@endcan
        <div class="dropdown">
            <button class="btn-admin-primary dropdown-toggle" data-bs-toggle="dropdown"><i class="bi bi-printer"></i> Print Invoice</button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" target="_blank" href="{{ route('admin.orders.invoice.a4', $order) }}"><i class="bi bi-file-earmark-text me-2"></i>A4 PDF Invoice</a></li>
                <li><a class="dropdown-item" target="_blank" href="{{ route('admin.orders.invoice.80mm', $order) }}"><i class="bi bi-receipt-cutoff me-2"></i>80mm POS PDF</a></li>
            </ul>
        </div>
    </div>
</div>

@if($order->source === 'website' && $order->status === 'pending')
    <div class="admin-alert admin-alert-warning d-flex align-items-center justify-content-between gap-3 mb-3">
        <div><i class="bi bi-globe2 me-2"></i>This order was received from the website and is waiting for confirmation.</div>
        @can('order.edit')
            <form action="{{ route('admin.orders.confirm', $order) }}" method="post">@csrf<button class="btn-admin-primary"><i class="bi bi-check2-circle"></i> Confirm Order</button></form>
        @endcan
    </div>
@endif

<div class="row g-3">
    <div class="col-lg-8">
        <div class="admin-card mb-3">
            <div class="admin-card-header"><h2 class="admin-card-title">Order Items</h2></div>
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead><tr><th>Item</th><th>Add-Ons</th><th>Qty</th><th>Unit Price</th><th>Subtotal</th></tr></thead>
                    <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td><div class="table-item-name">{{ $item->item_name }}</div><div class="table-item-sub">{{ $item->size_label ?: 'Regular' }}{{ $item->note ? ' · '.$item->note : '' }}</div></td>
                            <td>
                                @forelse($item->addons as $addon)
                                    @php($addonDescription = $addon->description ?: $addon->menuItemPriceAddon?->description ?: $addon->addon?->description)
                                    <span class="order-addon-chip">{{ $addon->addon_name }} (+TK {{ number_format((float) $addon->price, 2) }})@if(filled($addonDescription))<small class="d-block mt-1">{{ $addonDescription }}</small>@endif</span>
                                @empty — @endforelse
                            </td>
                            <td>{{ $item->quantity }}</td>
                            <td>TK {{ number_format((float) $item->unit_price, 2) }}</td>
                            <td class="fw-bold">TK {{ number_format((float) $item->line_total, 2) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="order-total-summary">
                <div class="order-detail-row"><span>Subtotal</span><strong>TK {{ number_format((float) $order->subtotal, 2) }}</strong></div>
                @if((float) $order->discount > 0)<div class="order-detail-row"><span>Discount</span><strong>- TK {{ number_format((float) $order->discount, 2) }}</strong></div>@endif
                @if((float) $order->service_charge_amount > 0)<div class="order-detail-row"><span>Service Charge({{ rtrim(rtrim(number_format((float) $order->service_charge_rate, 2), '0'), '.') }}%)</span><strong>TK {{ number_format((float) $order->service_charge_amount, 2) }}</strong></div>@endif
                @if((float) $order->tax_amount > 0)<div class="order-detail-row"><span>{{ $order->tax_label }}({{ rtrim(rtrim(number_format((float) $order->tax_rate, 2), '0'), '.') }}%)</span><strong>TK {{ number_format((float) $order->tax_amount, 2) }}</strong></div>@endif
                @if((float) $order->delivery_charge > 0)<div class="order-detail-row"><span>Delivery Charge</span><strong>TK {{ number_format((float) $order->delivery_charge, 2) }}</strong></div>@endif
                <div class="order-detail-row order-grand-row"><span>Grand Total</span><strong>TK {{ number_format((float) $order->grand_total, 2) }}</strong></div>
                @if((float) $order->due_amount > 0)<div class="order-detail-row"><span>Due</span><strong class="text-danger">TK {{ number_format((float) $order->due_amount, 2) }}</strong></div>@endif
            </div>
        </div>

        @if($order->note)
            <div class="admin-card"><div class="admin-card-header"><h2 class="admin-card-title">Order Note</h2></div><p class="mb-0">{{ $order->note }}</p></div>
        @endif
    </div>

    <div class="col-lg-4">
        <div class="admin-card mb-3">
            <div class="admin-card-header"><h2 class="admin-card-title">Customer Information</h2></div>
            <div class="order-detail-row"><span>Name</span><span>{{ $order->customer_name }}</span></div>
            <div class="order-detail-row"><span>Phone</span><span>{{ $order->customer_phone }}</span></div>
            <div class="order-detail-row"><span>Email</span><span>{{ $order->customer_email ?: '—' }}</span></div>
            <div class="order-detail-row"><span>Address</span><span>{{ $order->customer_address ?: '—' }}</span></div>
            @if($order->order_type === 'delivery')<div class="order-detail-row"><span>Delivery Address</span><span>{{ $order->delivery_address }}</span></div>@endif
            @if($order->client)<div class="mt-3"><a href="{{ route('admin.clients.show', $order->client) }}" class="admin-card-link">View Client Profile <i class="bi bi-arrow-right"></i></a></div>@endif
        </div>

        <div class="admin-card mb-3">
            <div class="admin-card-header"><h2 class="admin-card-title">Branch &amp; Payment</h2></div>
            <div class="order-detail-row"><span>Branch</span><span>{{ $order->branch?->name }}</span></div>
            <div class="order-detail-row"><span>Order Type</span><span>{{ $order->order_type_label }}</span></div>
            <div class="order-detail-row"><span>Payment Method</span><span>{{ $order->payment_label }}</span></div>
            @if($order->payment_reference)<div class="order-detail-row"><span>Reference</span><span>{{ $order->payment_reference }}</span></div>@endif
            @if($order->payment_type === 'split')
                <div class="order-detail-row"><span>Cash</span><span>TK {{ number_format((float) $order->split_cash, 2) }}</span></div>
                <div class="order-detail-row"><span>MFS</span><span>TK {{ number_format((float) $order->split_mfs, 2) }}</span></div>
                <div class="order-detail-row"><span>Bank</span><span>TK {{ number_format((float) $order->split_bank, 2) }}</span></div>
                @if($order->split_mfs_reference)<div class="order-detail-row"><span>MFS Reference</span><span>{{ $order->split_mfs_reference }}</span></div>@endif
                @if($order->split_bank_reference)<div class="order-detail-row"><span>Bank Reference</span><span>{{ $order->split_bank_reference }}</span></div>@endif
            @endif
        </div>

        <div class="admin-card">
            <div class="admin-card-header"><h2 class="admin-card-title">Order Information</h2></div>
            <div class="order-detail-row"><span>Source</span><span class="source-badge source-{{ $order->source }}">{{ ucfirst($order->source) }}</span></div>
            <div class="order-detail-row"><span>Created By</span><span>{{ $order->creator?->name ?? 'Website' }}</span></div>
            <div class="order-detail-row"><span>Confirmed At</span><span>{{ $order->confirmed_at?->format('d/m/Y h:i A') ?? '—' }}</span></div>
            @can('order.edit')
            <div class="form-group-admin mt-3 mb-0">
                <label class="form-label-admin">Order Status</label>
                <select class="form-control-admin" data-order-status-update data-url="{{ route('admin.orders.status', $order) }}" data-previous="{{ $order->status }}">
                    @foreach(['pending','confirmed','processing','delivered','cancelled'] as $status)<option value="{{ $status }}" @selected($order->status === $status)>{{ ucfirst($status) }}</option>@endforeach
                </select>
            </div>
            @endcan
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('change', function (event) {
    var select = event.target.closest('[data-order-status-update]'); if (!select) return;
    var previous = select.dataset.previous; select.disabled = true;
    fetch(select.dataset.url,{method:'PATCH',headers:{'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,'Accept':'application/json','Content-Type':'application/json'},body:JSON.stringify({status:select.value})})
      .then(function(r){if(!r.ok)throw new Error('Could not update status.');return r.json()})
      .then(function(data){select.dataset.previous=select.value;Swal.fire({icon:'success',title:data.message,timer:1200,showConfirmButton:false})})
      .catch(function(e){select.value=previous;Swal.fire({icon:'error',title:e.message})})
      .finally(function(){select.disabled=false});
});
</script>
@endpush
