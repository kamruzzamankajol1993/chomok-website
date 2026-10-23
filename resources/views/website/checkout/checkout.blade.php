@extends('website.master.master')
@section('title', 'Checkout | '.($siteSetting?->restaurant_name ?? 'Chomok Restaurant'))
@section('css')
<style>
  .checkout-form [hidden]{display:none!important}
  .checkout-choice-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}
  .checkout-order-type-grid{grid-template-columns:repeat(3,minmax(0,1fr))}
  .checkout-order-type-grid .checkout-choice-card{padding:14px 12px;min-height:95px}
  .checkout-order-type-grid .checkout-choice-copy strong{font-size:14px}
  .checkout-order-type-grid .checkout-choice-copy small{font-size:12px}
  .checkout-order-type-grid .checkout-choice input:focus-visible + .checkout-choice-card{outline:3px solid #d1b46b;outline-offset:2px}
  .checkout-choice{position:relative;display:block;margin:0;cursor:pointer}
  .checkout-choice input{position:absolute;opacity:0;pointer-events:none}
  .checkout-choice-card{height:100%;border:1px solid #dedede;border-radius:14px;padding:14px 15px;background:#fff;transition:.2s ease;display:flex;gap:11px;align-items:flex-start}
  .checkout-choice-dot{width:20px;height:20px;border:2px solid #aaa;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;flex:0 0 auto;margin-top:2px}
  .checkout-choice-dot:after{content:"";width:10px;height:10px;border-radius:50%;background:currentColor;transform:scale(0);transition:.18s ease}
  .checkout-choice input:checked + .checkout-choice-card{border-color:#111;box-shadow:0 0 0 1px #111}
  .checkout-choice input:checked + .checkout-choice-card .checkout-choice-dot{border-color:#111;color:#111}
  .checkout-choice input:checked + .checkout-choice-card .checkout-choice-dot:after{transform:scale(1)}
  .checkout-choice-copy{min-width:0}.checkout-choice-copy strong{display:block;font-size:15px}.checkout-choice-copy small{display:block;margin-top:3px;color:#6c757d;line-height:1.4}
  .delivery-charge-info{position:relative;overflow:hidden;border:1px solid #e6e1d6;border-radius:16px;padding:16px 18px;background:linear-gradient(135deg,#fffdf7 0%,#f8f5ec 100%);box-shadow:0 7px 20px rgba(0,0,0,.045)}
  .delivery-charge-info-title{display:flex;align-items:center;gap:8px;margin-bottom:10px;font-size:16px;font-weight:800;color:#171717}
  .delivery-charge-info-title::before{content:"";width:8px;height:8px;border-radius:50%;background:#171717;flex:0 0 auto}
  .delivery-charge-info-row{display:flex;align-items:center;justify-content:space-between;gap:16px;padding:8px 0;border-top:1px dashed #ddd6c6;color:#3c3c3c}
  .delivery-charge-info-row:first-of-type{border-top:0}
  .delivery-charge-info-row strong{color:#111;white-space:nowrap}
  .delivery-charge-info-note{display:block;margin-top:9px;color:#737373;font-size:12px;line-height:1.45}
  .order-availability-message{padding:12px 15px;border-radius:11px;border:1px solid #ecb7a9;background:#fff5f2;color:#8f2920;font-weight:650;margin-top:12px;line-height:1.5}
  .order-availability-message[hidden]{display:none!important}
  .first-order-offer{border:1px solid #111;border-radius:16px;padding:14px 16px;margin:0 0 18px;background:#fff;box-shadow:0 8px 22px rgba(0,0,0,.05)}
  .first-order-offer strong{display:block;font-size:17px;margin-bottom:3px}
  .first-order-offer span{display:block;color:#555;line-height:1.45}
  .checkout-free-line{display:inline-flex;align-items:center;align-self:flex-start;width:fit-content;max-width:100%;gap:6px;margin-top:8px;padding:7px 11px;border:1px solid #d5b84b;border-radius:10px;background:#fff4c2;color:#171717!important;opacity:1!important;font-size:13px;font-weight:800;line-height:1.35;overflow-wrap:anywhere}
  @media(max-width:767.98px){.checkout-choice-grid{grid-template-columns:1fr}.checkout-order-type-grid{grid-template-columns:1fr}.checkout-order-type-grid .checkout-choice-card{min-height:auto}}
</style>
@endsection
@section('body')
@php
  $formatCheckoutMoney = static function ($value) {
      return rtrim(rtrim(number_format((float) $value, 2, '.', ''), '0'), '.');
  };
@endphp
<section class="checkout-section">
  <div class="menu-section-head"><span class="badge-text">Almost There</span><h1 class="menu-section-title">Checkout</h1></div>

  @if($errors->any())
    <div class="order-availability-message" role="alert" style="margin-bottom:16px">{{ $errors->first() }}</div>
  @endif
  <div id="checkoutCouponPanel">
    @include('website.checkout.partials.coupon-panel', ['promotionQuote' => $promotionQuote, 'couponCode' => $couponCode, 'selectedBranchId' => $selectedBranchId, 'promotionNotice' => $promotionNotice ?? null])
  </div>

  <form class="contact-form checkout-form" action="{{ route('checkout.process') }}" method="post">
    @csrf
    <div class="checkout-layout">
      <div class="checkout-form-wrap">
        <h2 class="contact-form-title">Order Details</h2>

        <div class="form-group">
          <label>Order Type <span aria-hidden="true">*</span></label>
          <div class="checkout-choice-grid checkout-order-type-grid" role="radiogroup" aria-label="Order Type">
            @foreach(['delivery' => ['Home Delivery', 'Delivered to your address'], 'takeaway' => ['Takeaway', 'Packaged for collection'], 'pickup' => ['Pickup', 'Collect from your branch']] as $orderType => $details)
              <label class="checkout-choice">
                <input type="radio" name="order_type" value="{{ $orderType }}" @checked(old('order_type', 'delivery') === $orderType) required>
                <span class="checkout-choice-card">
                  <span class="checkout-choice-dot" aria-hidden="true"></span>
                  <span class="checkout-choice-copy"><strong>{{ $details[0] }}</strong><small>{{ $details[1] }}</small></span>
                </span>
              </label>
            @endforeach
          </div>
        </div>

        <div class="form-group">
          <label for="checkout-phone">Phone Number</label>
          <input type="tel" id="checkout-phone" name="phone" value="{{ old('phone', $client->phone) }}" placeholder="Phone Number" autocomplete="tel" required>
        </div>

        <div class="form-group" id="checkoutDeliveryAddressGroup">
          <label for="checkout-address">Delivery Address <span aria-hidden="true">*</span></label>
          <input type="text" id="checkout-address" name="address" value="{{ old('address', $client->address) }}" placeholder="House, Road, Area" autocomplete="street-address" required>
        </div>

        <div class="form-group">
          <label>Select Branch <span aria-hidden="true">*</span></label>
          <p class="form-help" style="margin:0 0 .55rem;"><strong>NB:</strong> Please select your branch carefully. Branch selection is mandatory.</p>
          <div class="checkout-choice-grid" role="radiogroup" aria-label="Select branch">
            @foreach($branches as $branch)
              <label class="checkout-choice">
                <input type="radio" name="branch_id" value="{{ $branch->id }}" @checked((string)old('branch_id', $selectedBranchId ?? '') === (string)$branch->id) required>
                <span class="checkout-choice-card">
                  <span class="checkout-choice-dot"></span>
                  <span class="checkout-choice-copy"><strong>{{ $branch->name }}</strong>@if($branch->address)<small>{{ $branch->address }}</small>@endif</span>
                </span>
              </label>
            @endforeach
          </div>
          <p id="branchOrderingStatus" class="order-availability-message" role="status" aria-live="polite" hidden></p>
        </div>

        @if($deliveryConfig['enabled'])
          <div class="form-group" id="checkoutDeliveryInfoGroup">
            <div class="delivery-charge-info" aria-label="Delivery charge information">
              <div class="delivery-charge-info-title">Delivery Charge</div>
              <div class="delivery-charge-info-row">
                <span>Within 1 km</span>
                <strong>TK {{ $formatCheckoutMoney($deliveryConfig['within']) }}</strong>
              </div>
              <div class="delivery-charge-info-row">
                <span>Beyond 1 km</span>
                <strong>TK {{ $formatCheckoutMoney($deliveryConfig['outside_base']) }} + TK {{ $formatCheckoutMoney($deliveryConfig['per_km']) }} / km</strong>
              </div>
              <small class="delivery-charge-info-note">For deliveries beyond 1 km, TK {{ $formatCheckoutMoney($deliveryConfig['per_km']) }} will be added for each additional kilometer.</small>
            </div>
          </div>
        @endif

        <h2 class="contact-form-title checkout-payment-title">Payment Method</h2>
        <div class="payment-methods">
          <label class="price-pill payment-pill checkout-cod-only"><input type="radio" name="payment_display" value="cod" class="price-pill-input" checked disabled><span id="checkoutPaymentLabel">Cash on Delivery</span></label>
        </div>
      </div>

      <div class="checkout-summary">
        <div id="checkoutSummaryContent">
          @include('website.checkout.partials.summary-content', ['cart' => $cart, 'summary' => $summary, 'deliveryConfig' => $deliveryConfig, 'promotionQuote' => $promotionQuote, 'selectedBranchId' => $selectedBranchId])
        </div>
      </div>
    </div>
  </form>
</section>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const typeInputs = document.querySelectorAll('input[name="order_type"]');
  const addressGroup = document.getElementById('checkoutDeliveryAddressGroup');
  const addressInput = document.getElementById('checkout-address');
  const deliveryInfo = document.getElementById('checkoutDeliveryInfoGroup');
  const form = document.querySelector('.checkout-form');
  const branchInputs = document.querySelectorAll('input[name="branch_id"]');
  const orderStatus = document.getElementById('branchOrderingStatus');
  const endpointBase = @json(url('/branch'));
  const promotionPreviewUrl = @json(route('checkout.promotion-preview'));
  const csrfToken = form.querySelector('input[name="_token"]')?.value || '';
  const deliveryAmount = Number(@json($deliveryConfig['enabled'] ? $deliveryConfig['within'] : 0));
  let baseAmount = Number(@json($summary['before_delivery']));
  let branchOpen = false;
  let branchStatusMessage = '';
  let promotionPreviewReady = Boolean(document.querySelector('input[name="branch_id"]:checked'));
  let promotionPreviewMessage = '';
  let statusRequest = 0;
  let promotionRequest = 0;
  let submitting = false;
  const formatMoney = number => Number(number).toFixed(2).replace(/\.?0+$/, '');

  function selectedBranch() {
    return document.querySelector('input[name="branch_id"]:checked');
  }

  function updateCouponBranchInput() {
    const input = document.getElementById('checkoutCouponBranchId');
    if (input) input.value = selectedBranch()?.value || '';
  }

  function setCouponPanelBusy(busy) {
    const panel = document.getElementById('checkoutCouponPanel');
    if (!panel) return;
    panel.querySelectorAll('button[type="submit"]').forEach(button => { button.disabled = busy; });
  }

  function renderStatus() {
    const orderButton = document.getElementById('checkoutPlaceOrder');
    const checked = selectedBranch();
    if (orderButton) {
      orderButton.disabled = !checked || !branchOpen || !promotionPreviewReady || submitting;
      if (!checked) orderButton.textContent = 'Choose a branch to order';
      else if (!promotionPreviewReady) orderButton.textContent = 'Updating offers…';
      else if (!branchOpen) orderButton.textContent = 'Ordering unavailable';
      else orderButton.textContent = 'Place Order';
    }

    const message = !checked
      ? 'Please select a branch to check ordering availability.'
      : (promotionPreviewMessage || branchStatusMessage);
    orderStatus.hidden = !message;
    orderStatus.textContent = message || '';
  }

  function syncOrderType() {
    const chosen = document.querySelector('input[name="order_type"]:checked');
    const type = chosen ? chosen.value : 'delivery';
    const isDelivery = type === 'delivery';
    const deliveryRow = document.getElementById('checkoutDeliveryFeeRow');
    const deliveryFee = document.getElementById('checkoutDeliveryFee');
    const grandTotal = document.getElementById('checkoutGrandTotal');
    const paymentLabel = document.getElementById('checkoutPaymentLabel');
    addressGroup.hidden = !isDelivery;
    addressInput.required = isDelivery;
    addressInput.disabled = !isDelivery;
    if (deliveryInfo) deliveryInfo.hidden = !isDelivery;
    if (deliveryRow) deliveryRow.hidden = !isDelivery;
    if (deliveryFee) deliveryFee.textContent = 'TK ' + formatMoney(isDelivery ? deliveryAmount : 0);
    if (grandTotal) grandTotal.textContent = 'TK ' + formatMoney(Math.ceil(Number((baseAmount + (isDelivery ? deliveryAmount : 0)).toFixed(2))));
    if (paymentLabel) paymentLabel.textContent = isDelivery ? 'Cash on Delivery' : 'Cash on Collection';
  }

  async function refreshBranchStatus() {
    const checked = selectedBranch();
    const requestNumber = ++statusRequest;
    if (!checked) {
      branchOpen = false;
      branchStatusMessage = 'Please select a branch to check ordering availability.';
      renderStatus();
      return false;
    }

    branchOpen = false;
    branchStatusMessage = 'Checking branch availability…';
    renderStatus();
    try {
      const response = await fetch(endpointBase + '/' + encodeURIComponent(checked.value) + '/order-status', {
        headers: {'Accept': 'application/json'}, credentials: 'same-origin', cache: 'no-store'
      });
      if (!response.ok) throw new Error('Status could not be checked');
      const status = await response.json();
      if (requestNumber !== statusRequest) return false;
      branchOpen = Boolean(status.can_order);
      branchStatusMessage = status.can_order ? '' : (status.message || 'Online ordering is unavailable for this branch.');
      renderStatus();
      return branchOpen;
    } catch (error) {
      if (requestNumber === statusRequest) {
        branchOpen = false;
        branchStatusMessage = 'Unable to verify branch availability. Please try again.';
        renderStatus();
      }
      return false;
    }
  }

  async function refreshPromotionPreview() {
    const checked = selectedBranch();
    const requestNumber = ++promotionRequest;
    if (!checked) {
      promotionPreviewReady = false;
      promotionPreviewMessage = '';
      renderStatus();
      return false;
    }

    promotionPreviewReady = false;
    promotionPreviewMessage = 'Updating offers for the selected branch…';
    updateCouponBranchInput();
    setCouponPanelBusy(true);
    renderStatus();

    try {
      const chosenOrderType = document.querySelector('input[name="order_type"]:checked');
      const response = await fetch(promotionPreviewUrl, {
        method: 'POST',
        credentials: 'same-origin',
        cache: 'no-store',
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({branch_id: checked.value, order_type: chosenOrderType ? chosenOrderType.value : 'delivery'})
      });
      const result = await response.json();
      if (!response.ok) throw new Error(result.message || 'Promotions could not be refreshed.');
      if (requestNumber !== promotionRequest) return false;

      const summaryContent = document.getElementById('checkoutSummaryContent');
      const couponPanel = document.getElementById('checkoutCouponPanel');
      if (summaryContent) summaryContent.innerHTML = result.summary_html;
      if (couponPanel) couponPanel.innerHTML = result.coupon_panel_html;
      baseAmount = Number(result.before_delivery || 0);
      promotionPreviewReady = true;
      promotionPreviewMessage = '';
      setCouponPanelBusy(false);
      syncOrderType();
      renderStatus();
      return true;
    } catch (error) {
      if (requestNumber === promotionRequest) {
        promotionPreviewReady = false;
        promotionPreviewMessage = error.message || 'Unable to refresh branch offers. Please reload checkout before placing your order.';
        setCouponPanelBusy(false);
        updateCouponBranchInput();
        renderStatus();
      }
      return false;
    }
  }

  typeInputs.forEach(input => input.addEventListener('change', syncOrderType));
  syncOrderType();

  branchInputs.forEach(input => input.addEventListener('change', function () {
    branchOpen = false;
    branchStatusMessage = '';
    promotionPreviewReady = false;
    promotionPreviewMessage = 'Updating offers for the selected branch…';
    updateCouponBranchInput();
    renderStatus();
    refreshBranchStatus();
    refreshPromotionPreview();
  }));

  updateCouponBranchInput();
  if (selectedBranch()) {
    refreshBranchStatus();
    refreshPromotionPreview();
  } else {
    renderStatus();
  }

  setInterval(function () {
    if (selectedBranch() && !submitting) {
      refreshBranchStatus();
      refreshPromotionPreview();
    }
  }, 60000);

  form.addEventListener('submit', async function (event) {
    if (submitting) return;
    event.preventDefault();
    if (!selectedBranch()) {
      branchOpen = false;
      branchStatusMessage = 'Please select a branch before placing your order.';
      renderStatus();
      return;
    }
    if (!promotionPreviewReady) {
      const refreshed = await refreshPromotionPreview();
      // Stop after a refresh so the customer can see any changed discount/total before confirming.
      if (!refreshed) return;
      return;
    }
    const open = await refreshBranchStatus();
    if (!open || !promotionPreviewReady) return;
    submitting = true;
    renderStatus();
    form.submit();
  });
});
</script>
@endsection
