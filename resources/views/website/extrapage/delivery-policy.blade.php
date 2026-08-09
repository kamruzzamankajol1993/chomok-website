@extends('website.master.master')
@section('title', 'Delivery Info | '.($siteSetting?->restaurant_name ?? 'Chomok Restaurant'))
@section('body')
<section class="auth-section"><div class="auth-card" style="max-width:1000px"><h1 class="auth-title">Delivery Info</h1><div class="policy-content">{!! $content ?: '<p>Delivery information will be updated soon.</p>' !!}</div></div></section>
@endsection
