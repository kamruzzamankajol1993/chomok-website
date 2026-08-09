@extends('website.master.master')
@section('title')
Contact Us | Chomok Restaurant
@endsection

@section('css')
@endsection

@section('body')
<!-- Page Hero -->
<section class="page-hero">
  <img src="assets/images/slider/slider1.jpg" alt="Chomok pizza" class="page-hero-img">
  <div class="slide-overlay"></div>
  <div class="page-hero-content">
    <span class="page-hero-eyebrow">We'd Love To Hear From You</span>
    <h1 class="page-hero-title">Contact Us</h1>
  </div>
</section>

<!-- Contact Info + Form -->
<section class="contact-section">
  <div class="contact-layout">

    <div class="contact-info">

      <div class="contact-info-item">
        <span class="contact-info-icon" aria-hidden="true">📍</span>
        <div>
          <h4>Our Address</h4>
          <p>394 Brothers Mansion, East Rampur, Halishahar, Chittagong.</p>
        </div>
      </div>

      <div class="contact-info-item">
        <span class="contact-info-icon" aria-hidden="true">📞</span>
        <div>
          <h4>Call Us</h4>
          <p>+880 XXX-XXXXXX</p>
        </div>
      </div>

      <div class="contact-info-item">
        <span class="contact-info-icon" aria-hidden="true">✉️</span>
        <div>
          <h4>Email Us</h4>
          <p>hello@chomok.com</p>
        </div>
      </div>

      <div class="contact-info-item">
        <span class="contact-info-icon" aria-hidden="true">🕐</span>
        <div>
          <h4>Opening Hours</h4>
          <p>Monday to Saturday, 10am &ndash; 7pm</p>
        </div>
      </div>

    </div>

    <div class="contact-form-wrap">
      <h2 class="contact-form-title">Send Us A Message</h2>
      <form class="contact-form" action="#" method="post">
        <div class="form-row">
          <div class="form-group">
            <label for="contact-name">Full Name</label>
            <input type="text" id="contact-name" name="name" placeholder="Your name">
          </div>
          <div class="form-group">
            <label for="contact-email">Email Address</label>
            <input type="email" id="contact-email" name="email" placeholder="you@example.com">
          </div>
        </div>

        <div class="form-group">
          <label for="contact-subject">Subject</label>
          <input type="text" id="contact-subject" name="subject" placeholder="What is this about?">
        </div>

        <div class="form-group">
          <label for="contact-message">Message</label>
          <textarea id="contact-message" name="message" rows="5" placeholder="Write your message..."></textarea>
        </div>

        <button type="submit" class="btn-wc-hero">Send Message</button>
      </form>
    </div>

  </div>
</section>

<!-- Map -->
<div class="contact-map">
  <iframe src="https://www.google.com/maps?q=394%20Brothers%20Mansion%2C%20East%20Rampur%2C%20Halishahar%2C%20Chittagong%2C%20Bangladesh&output=embed" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen title="Chomok Head Office location"></iframe>
</div>
@include('website.include.cta')
@endsection


@section('scripts')

@endsection
