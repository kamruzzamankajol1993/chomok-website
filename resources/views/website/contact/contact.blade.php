@extends('website.master.master')
@section('title', ($content->meta_title ?: 'Contact Us').' | '.($siteSetting?->restaurant_name ?? 'Chomok Restaurant'))
@section('meta_description', $content->meta_description ?: ('Contact '.($siteSetting?->restaurant_name ?? 'Chomok').' for location, phone, opening hours and support.'))
@section('body')
<section class="page-hero">
  <img src="{{ $content->hero_image ? $adminAssetUrl($content->hero_image) : asset('public/website/assets/images/slider/slider1.jpg') }}" alt="{{ $content->hero_title }}" class="page-hero-img">
  <div class="slide-overlay"></div>
  <div class="page-hero-content"><span class="page-hero-eyebrow">{{ $content->hero_eyebrow_text }}</span><h1 class="page-hero-title">{{ $content->hero_title }}</h1></div>
</section>
<section class="contact-section">
  <div class="contact-layout">
    <div class="contact-info">
      <div class="contact-info-item"><span class="contact-info-icon">{{ $content->address_icon ?: '📍' }}</span><div><h4>{{ $content->address_heading ?: 'Our Address' }}</h4><p>{{ $content->address }}</p></div></div>
      <div class="contact-info-item"><span class="contact-info-icon">{{ $content->phone_icon ?: '📞' }}</span><div><h4>{{ $content->phone_heading ?: 'Call Us' }}</h4><p>{{ $content->phone_number }}</p></div></div>
      <div class="contact-info-item"><span class="contact-info-icon">{{ $content->email_icon ?: '✉️' }}</span><div><h4>{{ $content->email_heading ?: 'Email Us' }}</h4><p>{{ $content->email_address }}</p></div></div>
      <div class="contact-info-item"><span class="contact-info-icon">{{ $content->hours_icon ?: '🕐' }}</span><div><h4>{{ $content->hours_heading ?: 'Opening Hours' }}</h4><p>{{ $content->opening_hours }}</p></div></div>
    </div>
    <div class="contact-form-wrap">
      <h2 class="contact-form-title">{{ $content->form_heading ?: 'Send Us A Message' }}</h2>
      <form class="contact-form" action="{{ route('contact.store') }}" method="post">
        @csrf
        <div class="form-row"><div class="form-group"><label for="contact-name">{{ $content->name_label ?: 'Full Name' }}</label><input type="text" id="contact-name" name="name" value="{{ old('name') }}" placeholder="{{ $content->name_placeholder ?: 'Your name' }}" required></div><div class="form-group"><label for="contact-email">{{ $content->email_label ?: 'Email Address' }}</label><input type="email" id="contact-email" name="email" value="{{ old('email') }}" placeholder="{{ $content->email_placeholder ?: 'you@example.com' }}" required></div></div>
        <div class="form-group"><label for="contact-subject">{{ $content->subject_label ?: 'Subject' }}</label><input type="text" id="contact-subject" name="subject" value="{{ old('subject') }}" placeholder="{{ $content->subject_placeholder ?: 'What is this about?' }}" required></div>
        <div class="form-group"><label for="contact-message">{{ $content->message_label ?: 'Message' }}</label><textarea id="contact-message" name="message" rows="5" placeholder="{{ $content->message_placeholder ?: 'Write your message...' }}" required>{{ old('message') }}</textarea></div>
        <button type="submit" class="btn-wc-hero">{{ $content->submit_button_text ?: 'Send Message' }}</button>
      </form>
    </div>
  </div>
</section>
<div class="contact-map"><iframe src="{{ $content->map_embed_url ?: 'https://www.google.com/maps?q='.urlencode($content->map_address ?: $content->address).'&output=embed' }}" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen title="Location map"></iframe></div>
@include('website.include.cta')
@endsection
