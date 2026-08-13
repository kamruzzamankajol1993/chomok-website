@extends('website.master.master')
@section('title', 'Order '.$order->order_number.' | '.($siteSetting?->restaurant_name ?? 'Chomok'))
@section('body')
<section class="dashboard-section">
  <div class="menu-section-head"><span class="badge-text">Order Details</span><h1 class="menu-section-title">{{ $order->order_number }}</h1></div>
  <div class="checkout-summary mx-auto" style="max-width:850px">
    <div class="checkout-total-row"><span>Status</span><strong>{{ ucfirst($order->status) }}</strong></div>
    <div class="checkout-total-row"><span>Branch</span><strong>{{ $order->branch?->name }}</strong></div>
    <div class="checkout-total-row"><span>Placed</span><strong>{{ $order->created_at?->format('d/m/Y h:i A') }}</strong></div>
    @foreach($order->items as $item)
      <div class="checkout-item">
        <div class="checkout-item-info">
          <h4>{{ $item->item_name }}</h4>
          <span>{{ $item->size_label }} × {{ $item->quantity }}</span>
          @if($item->addons->isNotEmpty())
            <div class="mt-1">
            @foreach($item->addons as $addon)
              @php($addonDescription = $addon->description ?: $addon->menuItemPriceAddon?->description ?: $addon->addon?->description)
              <small class="d-block"><strong>{{ $addon->addon_name }}</strong>@if(filled($addonDescription))<span class="d-block text-muted">{{ $addonDescription }}</span>@endif</small>
            @endforeach
            </div>
          @endif
        </div>
        <span class="checkout-item-price">TK {{ rtrim(rtrim(number_format((float)$item->line_total,2,'.',''),'0'),'.') }}</span>
      </div>
    @endforeach
    <div class="checkout-totals"><div class="checkout-total-row checkout-total-final"><span>Total</span><span>TK {{ rtrim(rtrim(number_format((float)$order->grand_total,2,'.',''),'0'),'.') }}</span></div></div>
    <a href="{{ route('client.dashboard') }}" class="btn-wc-hero">Back to Dashboard</a>
  </div>
</section>
@endsection
