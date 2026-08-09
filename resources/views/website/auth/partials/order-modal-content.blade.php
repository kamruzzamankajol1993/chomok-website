<div class="checkout-summary w-100">
  <div class="checkout-total-row"><span>Order ID</span><strong>{{ $order->order_number }}</strong></div>
  <div class="checkout-total-row"><span>Status</span><strong>{{ ucfirst($order->status) }}</strong></div>
  <div class="checkout-total-row"><span>Branch</span><strong>{{ $order->branch?->name }}</strong></div>
  <div class="checkout-total-row"><span>Placed</span><strong>{{ $order->created_at?->format('d/m/Y h:i A') }}</strong></div>
  @if($order->delivery_address)
    <div class="checkout-total-row"><span>Delivery Address</span><strong class="text-end">{{ $order->delivery_address }}</strong></div>
  @endif

  @foreach($order->items as $item)
    <div class="checkout-item">
      <div class="checkout-item-info">
        <h4>{{ $item->item_name }}</h4>
        <span>{{ $item->size_label }} × {{ $item->quantity }} @if($item->addons->isNotEmpty()) · {{ $item->addons->pluck('addon_name')->implode(', ') }} @endif</span>
      </div>
      <span class="checkout-item-price">TK {{ rtrim(rtrim(number_format((float)$item->line_total,2,'.',''),'0'),'.') }}</span>
    </div>
  @endforeach

  <div class="checkout-totals">
    <div class="checkout-total-row"><span>Subtotal</span><span>TK {{ rtrim(rtrim(number_format((float)$order->subtotal,2,'.',''),'0'),'.') }}</span></div>
    @if((float)$order->discount > 0)<div class="checkout-total-row"><span>Discount</span><span>- TK {{ rtrim(rtrim(number_format((float)$order->discount,2,'.',''),'0'),'.') }}</span></div>@endif
    @if((float)$order->tax_amount > 0)<div class="checkout-total-row"><span>{{ $order->tax_label ?: 'VAT' }}</span><span>TK {{ rtrim(rtrim(number_format((float)$order->tax_amount,2,'.',''),'0'),'.') }}</span></div>@endif
    @if((float)$order->delivery_charge > 0)<div class="checkout-total-row"><span>Delivery Fee</span><span>TK {{ rtrim(rtrim(number_format((float)$order->delivery_charge,2,'.',''),'0'),'.') }}</span></div>@endif
    <div class="checkout-total-row checkout-total-final"><span>Total</span><span>TK {{ rtrim(rtrim(number_format((float)$order->grand_total,2,'.',''),'0'),'.') }}</span></div>
  </div>
</div>
