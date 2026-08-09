@extends('website.master.master')
@section('title', 'Order Successful | '.($siteSetting?->restaurant_name ?? 'Chomok Restaurant'))
@section('body')
<section class="auth-section">
  <div class="auth-card text-center">
    <h1 class="auth-title">Order Received</h1>
    <p class="auth-subtext">Thank you. Your order <strong>{{ $order->order_number }}</strong> has been submitted to {{ $order->branch?->name }}.</p>
    <p class="auth-subtext">Current status: {{ ucfirst($order->status) }}</p>
    <a href="{{ route('client.view-order', $order->id) }}" class="btn-wc-hero auth-submit">View Order</a>
  </div>
</section>
@endsection
