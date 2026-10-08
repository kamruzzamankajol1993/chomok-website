@extends('website.master.master')
@section('title', 'Order Successful | '.($siteSetting?->restaurant_name ?? 'Chomok Restaurant'))
@section('body')
<section class="auth-section">
  <div class="auth-card text-center">
    <h1 class="auth-title">Order Received</h1>
    <p class="auth-subtext">Thank you. Your order <strong>{{ $order->order_number }}</strong> has been submitted to {{ $order->branch?->name }}.</p>
    @if(str_contains((string) $order->note, 'FIRST ORDER OFFER APPLIED'))
      <p class="auth-subtext"><strong>🎁 First Order Buy 1 Get 1 Free has been applied.</strong> You will receive the same quantity of each ordered food free, with the same variation/size, add-ons and selected options as the paid item.</p>
    @endif
    <p class="auth-subtext">Order Type: <strong>{{ $order->order_type_label }}</strong></p>
    <p class="auth-subtext">Current status: {{ ucfirst($order->status) }}</p>
    <a href="{{ route('client.view-order', $order->id) }}" class="btn-wc-hero auth-submit">View Order</a>
  </div>
</section>
@endsection
