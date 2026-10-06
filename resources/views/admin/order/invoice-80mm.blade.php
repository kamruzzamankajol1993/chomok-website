<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt {{ $order->order_number }}</title>
    <style>
        body { font-family: dejavusans, sans-serif; color: #000; font-size: 8.7px; line-height: 1.35; margin: 0; padding: 0; }
        .center { text-align: center; }
        .logo { max-width: 34mm; max-height: 15mm; }
        .name { margin: 0 0 2px; font-size: 13px; }
        .dash { border-top: 1px dashed #000; height: 1px; margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 1.5px 0; vertical-align: top; }
        .right { text-align: right; }
        .label { width: 34%; }
        .item { padding: 4px 0; }
        .item-name { font-weight: bold; }
        .small { font-size: 7.7px; color: #222; }
        .grand td { border-top: 1px dashed #000; padding-top: 5px; font-size: 10.5px; font-weight: bold; }
        .footer { margin-top: 7px; text-align: center; }
    </style>
</head>
<body>
<div class="center">
    @if($restaurantSetting?->show_logo_invoice && $invoiceLogo)
        <img class="logo" src="{{ $invoiceLogo }}" alt="Logo"><br>
    @else
        <h2 class="name">{{ $restaurantSetting?->restaurant_name ?? 'Restaurant' }}</h2>
    @endif
    @if($order->branch?->name)<strong>{{ $order->branch->name }}</strong><br>@endif
    @if($order->branch?->address){{ $order->branch->address }}<br>@endif
    @if($order->branch?->phone){{ $order->branch->phone }}<br>@endif
    @if($restaurantSetting?->tax_registration_number){{ $restaurantSetting->tax_registration_number }}@endif
</div>
<div class="dash"></div>
<table>
    <tr><td class="label">Invoice</td><td class="right"><strong>{{ $order->order_number }}</strong></td></tr>
    <tr><td>Date</td><td class="right">{{ $order->created_at?->format('d/m/Y h:i A') }}</td></tr>
    <tr><td>Customer</td><td class="right">{{ $order->customer_name }}</td></tr>
    @if($order->customer_phone)<tr><td>Phone</td><td class="right">{{ $order->customer_phone }}</td></tr>@endif
    <tr><td>Type</td><td class="right">{{ $order->order_type_label }}</td></tr>
</table>
<div class="dash"></div>

@foreach($order->items as $item)
    <div class="item">
        <div class="item-name">{{ $item->item_name }} ({{ $item->size_label ?: 'Regular' }})</div>
        @if($item->addons->isNotEmpty())
            @foreach($item->addons as $addon)
                @php($addonDescription = $addon->description ?: $addon->menuItemPriceAddon?->description ?: $addon->addon?->description)
                <div class="small">+ {{ $addon->addon_name }}@if(filled($addonDescription)) — {{ $addonDescription }}@endif</div>
            @endforeach
        @endif
        @if($item->note)<div class="small">{{ $item->note }}</div>@endif
        <table><tr><td>{{ $item->quantity }} x {{ number_format((float) $item->unit_price, 2) }}</td><td class="right">{{ number_format((float) $item->line_total, 2) }}</td></tr></table>
    </div>
@endforeach

<div class="dash"></div>
<table>
    <tr><td>Subtotal</td><td class="right">{{ number_format((float) $order->subtotal, 2) }}</td></tr>
    @if((float) $order->discount > 0)<tr><td>Discount</td><td class="right">-{{ number_format((float) $order->discount, 2) }}</td></tr>@endif
    @if((float) $order->service_charge_amount > 0)<tr><td>Service ({{ (float) $order->service_charge_rate }}%)</td><td class="right">{{ number_format((float) $order->service_charge_amount, 2) }}</td></tr>@endif
    @if((float) $order->tax_amount > 0)<tr><td>{{ $order->tax_label }} ({{ (float) $order->tax_rate }}%)</td><td class="right">{{ number_format((float) $order->tax_amount, 2) }}</td></tr>@endif
    @if((float) $order->delivery_charge > 0)<tr><td>Delivery</td><td class="right">{{ number_format((float) $order->delivery_charge, 2) }}</td></tr>@endif
    <tr class="grand"><td>TOTAL</td><td class="right">TK {{ number_format((float) $order->grand_total, 2) }}</td></tr>
    <tr><td>Payment</td><td class="right">{{ $order->payment_label }}</td></tr>
    @if((float) $order->paid_amount > 0)<tr><td>Paid</td><td class="right">{{ number_format((float) $order->paid_amount, 2) }}</td></tr>@endif
    @if((float) $order->due_amount > 0)<tr><td>Due</td><td class="right">{{ number_format((float) $order->due_amount, 2) }}</td></tr>@endif
</table>
@if($order->payment_reference || $order->split_mfs_reference || $order->split_bank_reference)
    <div class="dash"></div>
    <table>
        @if($order->payment_reference)<tr><td>Reference</td><td class="right">{{ $order->payment_reference }}</td></tr>@endif
        @if($order->split_mfs_reference)<tr><td>MFS Ref</td><td class="right">{{ $order->split_mfs_reference }}</td></tr>@endif
        @if($order->split_bank_reference)<tr><td>Card/Bank Ref</td><td class="right">{{ $order->split_bank_reference }}</td></tr>@endif
    </table>
@endif
<div class="dash"></div>
<div class="footer">{{ $restaurantSetting?->invoice_footer_note ?: 'Thank you!' }}</div>
</body>
</html>
