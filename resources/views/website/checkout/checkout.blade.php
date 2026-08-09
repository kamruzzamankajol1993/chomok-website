@extends('website.master.master')
@section('title', 'Checkout | '.($siteSetting?->restaurant_name ?? 'Chomok Restaurant'))
@section('body')
<section class="checkout-section">
  <div class="menu-section-head"><span class="badge-text">Almost There</span><h1 class="menu-section-title">Checkout</h1></div>

  <form class="contact-form checkout-form" action="{{ route('checkout.process') }}" method="post">
    @csrf
    <div class="checkout-layout">
      <div class="checkout-form-wrap">
        <h2 class="contact-form-title">Delivery Details</h2>
        <div class="form-row">
          <div class="form-group"><label for="checkout-name">Full Name</label><input type="text" id="checkout-name" name="name" value="{{ old('name',$client->name) }}" placeholder="Your name" required></div>
          <div class="form-group"><label for="checkout-phone">Phone Number</label><input type="tel" id="checkout-phone" name="phone" value="{{ old('phone',$client->phone) }}" placeholder="+880 XXX-XXXXXX" required></div>
        </div>
        <div class="form-group"><label for="checkout-address">Delivery Address</label><input type="text" id="checkout-address" name="address" value="{{ old('address',$client->address) }}" placeholder="House, Road, Area" required></div>
        <div class="form-row">
          <div class="form-group"><label for="checkout-city">City</label><input type="text" id="checkout-city" name="city" value="{{ old('city') }}" placeholder="Chittagong"></div>
          <div class="form-group"><label for="checkout-postcode">Postal Code</label><input type="text" id="checkout-postcode" name="postcode" value="{{ old('postcode') }}" placeholder="4000"></div>
        </div>
        <div class="form-group">
          <label for="checkout-branch">Select Branch</label>
          <select id="checkout-branch" name="branch_id" required>
            @foreach($branches as $branch)<option value="{{ $branch->id }}" @selected((string)old('branch_id',$client->branch_id)===(string)$branch->id)>{{ $branch->name }}{{ $branch->address ? ' — '.$branch->address : '' }}</option>@endforeach
          </select>
        </div>
        <div class="form-group"><label for="checkout-notes">Delivery Notes (Optional)</label><textarea id="checkout-notes" name="notes" rows="3" placeholder="E.g. Ring the bell, leave at the gate...">{{ old('notes') }}</textarea></div>

        <h2 class="contact-form-title checkout-payment-title">Payment Method</h2>
        <div class="payment-methods">
          <label class="price-pill payment-pill"><input type="radio" name="payment" value="cod" class="price-pill-input" @checked(old('payment','cod')==='cod')>Cash on Delivery</label>
          <label class="price-pill payment-pill"><input type="radio" name="payment" value="bkash" class="price-pill-input" @checked(old('payment')==='bkash')>bKash</label>
          <label class="price-pill payment-pill"><input type="radio" name="payment" value="nagad" class="price-pill-input" @checked(old('payment')==='nagad')>Nagad</label>
          <label class="price-pill payment-pill"><input type="radio" name="payment" value="card" class="price-pill-input" @checked(old('payment')==='card')>Card</label>
        </div>
      </div>

      <div class="checkout-summary">
        <h2 class="contact-form-title">Order Summary</h2>
        @foreach($cart as $row)
          <div class="checkout-item">
            @if(!empty($row['image']))<img src="{{ $adminAssetUrl($row['image']) }}" alt="{{ $row['name'] }}" class="checkout-item-img">@endif
            <div class="checkout-item-info"><h4>{{ $row['name'] }}</h4><span>{{ $row['size_label'] }} &times; {{ $row['quantity'] }} @if(!empty($row['addons'])) · {{ collect($row['addons'])->pluck('name')->implode(', ') }} @endif</span></div>
            <span class="checkout-item-price">TK {{ rtrim(rtrim(number_format((float)$row['line_total'],2,'.',''),'0'),'.') }}</span>
          </div>
        @endforeach
        <div class="checkout-totals">
          <div class="checkout-total-row"><span>Subtotal</span><span>TK {{ rtrim(rtrim(number_format($summary['subtotal'],2,'.',''),'0'),'.') }}</span></div>
          @if($summary['tax'] > 0)<div class="checkout-total-row"><span>{{ $summary['tax_label'] }}</span><span>TK {{ rtrim(rtrim(number_format($summary['tax'],2,'.',''),'0'),'.') }}</span></div>@endif
          <div class="checkout-total-row"><span>Delivery Fee</span><span>TK {{ rtrim(rtrim(number_format($summary['delivery'],2,'.',''),'0'),'.') }}</span></div>
          <div class="checkout-total-row checkout-total-final"><span>Total</span><span>TK {{ rtrim(rtrim(number_format($summary['grand'],2,'.',''),'0'),'.') }}</span></div>
        </div>
        <button type="submit" class="btn-wc-hero checkout-place-order">Place Order</button>
      </div>
    </div>
  </form>
</section>
@endsection
