@extends('website.master.master')
@section('title', 'Terms and Conditions | '.($siteSetting?->restaurant_name ?? 'Chomok Restaurant'))
@section('body')
<section class="auth-section"><div class="auth-card" style="max-width:1000px"><h1 class="auth-title">Terms and Conditions</h1><div class="policy-content">{!! $content ?: '<p>Terms and conditions will be updated soon.</p>' !!}</div></div></section>
@endsection
