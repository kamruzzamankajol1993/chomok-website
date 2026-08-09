@extends('website.master.master')
@section('title', 'Refund Policy | '.($siteSetting?->restaurant_name ?? 'Chomok Restaurant'))
@section('body')
<section class="auth-section"><div class="auth-card" style="max-width:1000px"><h1 class="auth-title">Refund Policy</h1><div class="policy-content">{!! $content ?: '<p>Refund policy content will be updated soon.</p>' !!}</div></div></section>
@endsection
