<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $order->order_number }}</title>
    <style>
        body { font-family: dejavusans, sans-serif; color: #111; font-size: 11px; }
        table { border-collapse: collapse; width: 100%; }
        .header td { vertical-align: top; padding-bottom: 12px; border-bottom: 2px solid #111; }
        .logo { max-width: 115px; max-height: 60px; }
        .restaurant-name { margin: 0 0 6px; font-size: 19px; }
        .invoice-title { margin: 0; font-size: 24px; text-align: right; }
        .meta { line-height: 1.6; }
        .align-right { text-align: right; }
        .info { margin-top: 18px; }
        .info td { width: 50%; vertical-align: top; padding: 0 12px 14px 0; }
        .info h3 { margin: 0 0 6px; font-size: 12px; }
        .info p { margin: 2px 0; line-height: 1.45; }
        .items { margin-top: 6px; }
        .items th { background: #f3f3f3; text-transform: uppercase; font-size: 9px; border: 1px solid #ddd; padding: 8px; }
        .items td { border: 1px solid #ddd; padding: 8px; vertical-align: top; }
        .item-note { color: #555; font-size: 9px; line-height: 1.4; }
        .totals { width: 42%; margin-left: 58%; margin-top: 16px; }
        .totals td { padding: 5px 0; }
        .totals .value { text-align: right; }
        .grand td { border-top: 2px solid #111; padding-top: 8px; font-size: 14px; font-weight: bold; }
        .footer { margin-top: 32px; text-align: center; color: #555; }
    </style>
</head>
<body>
<table class="header">
    <tr>
        <td style="width:55%">
            @if($restaurantSetting?->show_logo_invoice && $invoiceLogo)
                <img class="logo" src="{{ $invoiceLogo }}" alt="Logo">
            @else
                <h2 class="restaurant-name">{{ $restaurantSetting?->restaurant_name ?? 'Restaurant' }}</h2>
            @endif
            <div class="meta">
                @if($order->branch?->name)<strong>{{ $order->branch->name }}</strong><br>@endif
                @if($order->branch?->address){{ $order->branch->address }}<br>@endif
                @if($order->branch?->phone){{ $order->branch->phone }}<br>@endif
                @if($restaurantSetting?->tax_registration_number){{ $restaurantSetting->tax_registration_number }}@endif
            </div>
        </td>
        <td style="width:45%" class="align-right">
            <h1 class="invoice-title">INVOICE</h1>
            <div class="meta">
                <strong>#{{ $order->order_number }}</strong><br>
                {{ $order->created_at?->format('d/m/Y h:i A') }}<br>
                {{ $order->order_type_label }}
            </div>
        </td>
    </tr>
</table>

<table class="info">
    <tr>
        <td>
            <h3>BILL TO</h3>
            <p><strong>{{ $order->customer_name }}</strong></p>
            @if($order->customer_phone)<p>{{ $order->customer_phone }}</p>@endif
            @if($order->customer_email)<p>{{ $order->customer_email }}</p>@endif
            @if($order->delivery_address ?: $order->customer_address)<p>{{ $order->delivery_address ?: $order->customer_address }}</p>@endif
        </td>
        <td>
            <h3>PAYMENT</h3>
            <p>{{ $order->payment_label }}</p>
            @if($order->payment_reference)<p>Reference: {{ $order->payment_reference }}</p>@endif
            @if($order->split_mfs_reference)<p>MFS Ref: {{ $order->split_mfs_reference }}</p>@endif
            @if($order->split_bank_reference)<p>Card/Bank Ref: {{ $order->split_bank_reference }}</p>@endif
            <p>Status: {{ ucfirst($order->status) }}</p>
            <p>Source: {{ ucfirst($order->source) }}</p>
        </td>
    </tr>
</table>

<table class="items">
    <thead>
    <tr>
        <th style="width:55%;text-align:left">Item</th>
        <th style="width:10%">Qty</th>
        <th style="width:17%;text-align:right">Price</th>
        <th style="width:18%;text-align:right">Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach($order->items as $item)
        <tr>
            <td>
                <strong>{{ $item->item_name }}</strong>
                <div class="item-note">
                    {{ $item->size_label ?: 'Regular' }}
                    @if($item->addons->isNotEmpty())
                        @foreach($item->addons as $addon)
                            @php($addonDescription = $addon->description ?: $addon->menuItemPriceAddon?->description ?: $addon->addon?->description)
                            <br><span class="small">+ {{ $addon->addon_name }}@if(filled($addonDescription)) — {{ $addonDescription }}@endif</span>
                        @endforeach
                    @endif
                    @if($item->note)<br>{{ $item->note }}@endif
                </div>
            </td>
            <td style="text-align:center">{{ $item->quantity }}</td>
            <td class="align-right">{{ number_format((float) $item->unit_price, 2) }}</td>
            <td class="align-right">{{ number_format((float) $item->line_total, 2) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<table class="totals">
    <tr><td>Subtotal</td><td class="value">TK {{ number_format((float) $order->subtotal, 2) }}</td></tr>
    @if((float) $order->discount > 0)<tr><td>Discount</td><td class="value">- TK {{ number_format((float) $order->discount, 2) }}</td></tr>@endif
    @if((float) $order->service_charge_amount > 0)<tr><td>Service Charge ({{ (float) $order->service_charge_rate }}%)</td><td class="value">TK {{ number_format((float) $order->service_charge_amount, 2) }}</td></tr>@endif
    @if((float) $order->tax_amount > 0)<tr><td>{{ $order->tax_label }} ({{ (float) $order->tax_rate }}%)</td><td class="value">TK {{ number_format((float) $order->tax_amount, 2) }}</td></tr>@endif
    @if((float) $order->delivery_charge > 0)<tr><td>Delivery Charge</td><td class="value">TK {{ number_format((float) $order->delivery_charge, 2) }}</td></tr>@endif
    <tr class="grand"><td>Grand Total</td><td class="value">TK {{ number_format((float) $order->grand_total, 2) }}</td></tr>
    @if((float) $order->paid_amount > 0)<tr><td>Paid</td><td class="value">TK {{ number_format((float) $order->paid_amount, 2) }}</td></tr>@endif
    @if((float) $order->due_amount > 0)<tr><td>Due</td><td class="value">TK {{ number_format((float) $order->due_amount, 2) }}</td></tr>@endif
</table>

<div class="footer">{{ $restaurantSetting?->invoice_footer_note ?: 'Thank you for your order.' }}</div>
</body>
</html>
