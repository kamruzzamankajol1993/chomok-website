<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="description" content="@yield('meta_description', $siteSeoDescription)">
  <meta name="robots" content="index,follow">
  <meta property="og:site_name" content="{{ $siteSetting?->restaurant_name ?? 'Chomok' }}">
  <meta property="og:title" content="@yield('title', ($siteSetting?->restaurant_name ?? 'Chomok').' Restaurant')">
  <meta property="og:description" content="@yield('meta_description', $siteSeoDescription)">
  <title>@yield('title', ($siteSetting?->restaurant_name ?? 'Chomok').' Restaurant')</title>

  @if($siteSetting?->icon)
    <link rel="icon" href="{{ $adminAssetUrl($siteSetting->icon) }}">
  @endif
  <link rel="stylesheet" href="{{ asset('public/website/assets/vendor/bootstrap/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('public/website/assets/css/style.css') }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  @yield('css')
</head>
<body>

<header class="site-header">
  <div class="top-bar">
    <div class="container-fluid">
      <div class="row gx-0">
        <div class="col-lg-6 top-bar-item top-bar-left">
          <span>
            @if($siteSetting?->opening_time && $siteSetting?->closing_time)
              Open {{ \Carbon\Carbon::createFromFormat('H:i:s', $siteSetting->opening_time)->format('g:i A') }} to {{ \Carbon\Carbon::createFromFormat('H:i:s', $siteSetting->closing_time)->format('g:i A') }}
            @else
              Fresh food, prepared with care
            @endif
          </span>
        </div>
        <div class="col-lg-6 top-bar-item top-bar-right">
          <span>Our Location: {{ \Illuminate\Support\Str::limit($siteSetting?->address ?? 'Chattogram, Bangladesh', 70) }}</span>
        </div>
      </div>
    </div>
  </div>
</header>

@include('website.include.header')
@include('website.include.floating-sidebar')
@include('website.include.cart')
@include('website.menu.partials.addon-modal')

@yield('body')

@include('website.include.footer')

<script src="{{ asset('public/website/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('public/website/assets/js/script.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
(function () {
  'use strict';
  const token = document.querySelector('meta[name="csrf-token"]')?.content || '';
  const cartOffcanvasEl = document.getElementById('cartOffcanvas');
  const cartOffcanvas = cartOffcanvasEl ? bootstrap.Offcanvas.getOrCreateInstance(cartOffcanvasEl) : null;
  const addonModalEl = document.getElementById('addonSelectionModal');
  const addonModal = addonModalEl ? bootstrap.Modal.getOrCreateInstance(addonModalEl) : null;
  const clientAuthenticated = @json(Auth::guard('client')->check());

  function money(value) { return Number(value || 0).toFixed(2).replace(/\.00$/, ''); }
  function updateCartUi(data) {
    if (typeof data.html === 'string') {
      const wrap = document.getElementById('cartContentWrap');
      if (wrap) wrap.innerHTML = data.html;
    }
    document.querySelectorAll('[data-cart-count]').forEach(el => {
      el.textContent = Number(data.count || 0);
      el.classList.toggle('d-none', Number(data.count || 0) < 1 && el.hasAttribute('data-hide-empty'));
    });
  }
  async function jsonFetch(url, options = {}) {
    const headers = Object.assign({
      'Accept': 'application/json',
      'X-Requested-With': 'XMLHttpRequest',
      'X-CSRF-TOKEN': token,
      'Content-Type': 'application/json'
    }, options.headers || {});
    const response = await fetch(url, Object.assign({}, options, {headers}));
    let data = {};
    try { data = await response.json(); } catch (e) {}
    if (clientAuthenticated && (response.status === 401 || response.status === 419)) {
      window.location.href = @json(route('client.login'));
      throw new Error('Your session has expired.');
    }
    if (!response.ok) {
      const validation = data.errors ? Object.values(data.errors).flat()[0] : null;
      throw new Error(validation || data.message || 'Request failed.');
    }
    return data;
  }
  async function refreshCart() {
    try {
      const response = await fetch(@json(route('cart.index')), {headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'}, cache:'no-store'});
      if (response.ok) updateCartUi(await response.json());
    } catch (e) {}
  }
  async function addCart(payload, openCart = true) {
    const data = await jsonFetch(@json(route('cart.add')), {method:'POST', body: JSON.stringify(payload)});
    updateCartUi(data);
    if (addonModalEl?.classList.contains('show')) addonModal?.hide();
    if (openCart) cartOffcanvas?.show();
    return data;
  }

  window.ChomokCart = { refresh: refreshCart, add: addCart };

  document.addEventListener('click', async function (event) {
    const addButton = event.target.closest('[data-add-cart]');
    if (addButton) {
      event.preventDefault();
      const itemId = Number(addButton.dataset.menuItemId);
      const hasAddons = addButton.dataset.hasAddons === '1';
      const card = addButton.closest('[data-menu-card]') || document;
      const selected = card.querySelector(`input[data-price-for="${itemId}"]:checked`);
      const priceId = Number(selected?.value || addButton.dataset.defaultPriceId || 0);
      const quantitySource = addButton.dataset.quantitySource ? document.querySelector(addButton.dataset.quantitySource) : null;
      const requestedQuantity = Math.max(1, Number(quantitySource?.textContent || 1));
      if (!priceId) {
        Swal.fire({icon:'warning', title:'Select a size', text:'Please choose a price/size first.'});
        return;
      }
      if (hasAddons) {
        try {
          const response = await fetch(addButton.dataset.configureUrl, {headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'}, cache:'no-store'});
          if (!response.ok) throw new Error('Unable to load add-ons.');
          const data = await response.json();
          document.getElementById('addonSelectionBody').innerHTML = data.html;
          const modalPrice = document.querySelector(`#addonSelectionBody input[name="menu_item_price_id"][value="${priceId}"]`);
          if (modalPrice) modalPrice.checked = true;
          const modalQtyValue = document.querySelector('#addonSelectionBody [data-modal-qty-value]');
          const modalQtyInput = document.querySelector('#addonSelectionBody [data-modal-qty-input]');
          if (modalQtyValue) modalQtyValue.textContent = requestedQuantity;
          if (modalQtyInput) modalQtyInput.value = requestedQuantity;
          addonModal?.show();
        } catch (error) {
          Swal.fire({icon:'error', title:'Unable to continue', text:error.message});
        }
      } else {
        try { await addCart({menu_item_id:itemId, menu_item_price_id:priceId, quantity:requestedQuantity, addon_ids:[]}); }
        catch (error) { Swal.fire({icon:'error', title:'Cart error', text:error.message}); }
      }
      return;
    }


    const foodQtyButton = event.target.closest('[data-food-qty]');
    if (foodQtyButton) {
      const wrap = foodQtyButton.closest('[data-food-qty-wrap]');
      const value = wrap?.querySelector('[data-food-qty-value]');
      if (!value) return;
      value.textContent = Math.min(99, Math.max(1, Number(value.textContent || 1) + Number(foodQtyButton.dataset.foodQty || 0)));
      return;
    }

    const modalQtyButton = event.target.closest('[data-modal-qty]');
    if (modalQtyButton) {
      const wrap = modalQtyButton.closest('[data-modal-qty-wrap]');
      const value = wrap?.querySelector('[data-modal-qty-value]');
      const input = wrap?.querySelector('[data-modal-qty-input]');
      if (!value || !input) return;
      const next = Math.min(99, Math.max(1, Number(value.textContent || 1) + Number(modalQtyButton.dataset.modalQty || 0)));
      value.textContent = next;
      input.value = next;
      return;
    }

    const qtyButton = event.target.closest('[data-cart-qty]');
    if (qtyButton) {
      const key = qtyButton.dataset.key;
      const current = Number(qtyButton.dataset.quantity || 1);
      const next = Math.max(1, current + Number(qtyButton.dataset.delta || 0));
      try {
        const data = await jsonFetch(@json(route('cart.update')), {method:'POST', body:JSON.stringify({key, quantity:next})});
        updateCartUi(data);
      } catch (error) { Swal.fire({icon:'error', title:'Cart error', text:error.message}); }
      return;
    }

    const removeButton = event.target.closest('[data-cart-remove]');
    if (removeButton) {
      const result = await Swal.fire({
        icon:'warning',
        title:'Remove this item?',
        text:'This item will be removed from your cart.',
        showCancelButton:true,
        confirmButtonText:'Yes, remove it',
        cancelButtonText:'Keep item',
        confirmButtonColor:'#dc3545'
      });
      if (!result.isConfirmed) return;
      try {
        const data = await jsonFetch(@json(route('cart.remove')), {method:'POST', body:JSON.stringify({key:removeButton.dataset.key})});
        updateCartUi(data);
        Swal.fire({icon:'success', title:'Removed', text:'The item was removed from your cart.', timer:1200, showConfirmButton:false});
      } catch (error) { Swal.fire({icon:'error', title:'Cart error', text:error.message}); }
      return;
    }

    const clearButton = event.target.closest('[data-cart-clear]');
    if (clearButton) {
      const result = await Swal.fire({
        icon:'warning',
        title:'Clear your cart?',
        text:'All items in your cart will be removed.',
        showCancelButton:true,
        confirmButtonText:'Yes, clear cart',
        cancelButtonText:'Keep cart',
        confirmButtonColor:'#dc3545'
      });
      if (!result.isConfirmed) return;
      try {
        const data = await jsonFetch(@json(route('cart.clear')), {method:'POST', body:'{}'});
        updateCartUi(data);
        Swal.fire({icon:'success', title:'Cart cleared', timer:1200, showConfirmButton:false});
      } catch (error) { Swal.fire({icon:'error', title:'Cart error', text:error.message}); }
    }
  });

  document.addEventListener('submit', async function (event) {
    const form = event.target.closest('#addonSelectionForm');
    if (!form) return;
    event.preventDefault();
    const formData = new FormData(form);
    const payload = {
      menu_item_id: Number(formData.get('menu_item_id')),
      menu_item_price_id: Number(formData.get('menu_item_price_id')),
      quantity: Number(formData.get('quantity') || 1),
      addon_ids: formData.getAll('addon_ids[]').map(Number)
    };
    try { await addCart(payload); }
    catch (error) { Swal.fire({icon:'error', title:'Cart error', text:error.message}); }
  });

  document.addEventListener('DOMContentLoaded', function () {
    flatpickr('.date-picker', {dateFormat:'d/m/Y', allowInput:true});
    refreshCart();

    @if(session('success'))
      Swal.fire({icon:'success', title:'Success', text:@json(session('success')), timer:2200, showConfirmButton:false});
    @endif
    @if(session('error'))
      Swal.fire({icon:'error', title:'Notice', text:@json(session('error'))});
    @endif
    @if($errors->any())
      Swal.fire({icon:'error', title:'Please check the form', html:@json(implode('<br>', $errors->all()))});
    @endif
  });

  @if(Auth::guard('client')->check())
  window.setInterval(async function () {
    try {
      const response = await fetch(@json(route('client.session-status')), {headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'}, cache:'no-store'});
      if (response.status === 401) window.location.href = @json(route('client.login'));
    } catch (e) {}
  }, 60000);
  @endif
})();
</script>
@yield('scripts')
</body>
</html>
