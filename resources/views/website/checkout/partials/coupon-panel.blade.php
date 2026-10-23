@if(filled($promotionNotice ?? null))
  <div class="order-availability-message" role="status" style="margin-bottom:16px">{{ $promotionNotice }}</div>
@endif
@if(($promotionQuote['coupons_enabled'] ?? false))
  <div class="first-order-offer" style="margin-bottom:18px">
    @if(!empty($couponCode) && !empty($promotionQuote['coupon']))
      <strong>Coupon {{ $promotionQuote['coupon']->code }} applied</strong>
      <span>Discount: TK {{ rtrim(rtrim(number_format((float) $promotionQuote['coupon_discount'], 2, '.', ''), '0'), '.') }}. BOGO will not apply while this coupon is active.</span>
      <form action="{{ route('checkout.coupon.remove') }}" method="post" style="margin-top:10px">@csrf<button type="submit" class="btn-wc-hero" style="width:auto;padding:9px 14px">Remove Coupon</button></form>
    @else
      <strong>Have a coupon code?</strong>
      <form action="{{ route('checkout.coupon.apply') }}" method="post" class="d-flex gap-2 flex-wrap" style="margin-top:10px">@csrf
        <input type="text" name="coupon_code" value="{{ old('coupon_code') }}" maxlength="80" placeholder="Enter coupon code" required style="flex:1;min-width:180px">
        <input type="hidden" name="branch_id" id="checkoutCouponBranchId" value="{{ $selectedBranchId ?? '' }}">
        <button type="submit" class="btn-wc-hero" style="width:auto;padding:9px 14px">Apply Coupon</button>
      </form>
      <small class="d-block mt-2">Select a branch before applying a coupon. Branch-specific offers and coupons are checked against the selected branch.</small>
    @endif
  </div>
@endif
