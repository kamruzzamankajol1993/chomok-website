@php
    $isEdit = isset($order);
    $initialItems = $isEdit ? $order->items->map(fn ($item) => [
        'menu_item_id' => $item->menu_item_id,
        'menu_item_price_id' => $item->menu_item_price_id,
        'name' => $item->item_name,
        'size_label' => $item->size_label ?: 'Regular',
        'unit_price' => (float) $item->unit_price,
        'quantity' => (int) $item->quantity,
        'note' => $item->note,
        'addon_ids' => $item->addons->pluck('addon_id')->filter()->values()->all(),
        'price_addon_ids' => $item->addons->pluck('menu_item_price_addon_id')->filter()->values()->all(),
        'addons' => $item->addons->map(fn ($addon) => [
            'id' => $addon->addon_id,
            'price_addon_id' => $addon->menu_item_price_addon_id,
            'source' => $addon->menu_item_price_addon_id ? 'variation' : 'global',
            'name' => $addon->addon_name,
            'price' => (float) $addon->price,
        ])->values()->all(),
    ])->values()->all() : [];

    $initialDiscountType = old('discount_type', $order->discount_type ?? 'fixed');
    $initialDiscountValue = old('discount_value', $isEdit ? ($order->discount_value ?? $order->discount) : 0);
    $initialCustomerName = old('customer_name', $order->customer_name ?? 'Walk-in Customer');
    $initialCustomerPhone = old('customer_phone', $order->customer_phone ?? 'N/A');
@endphp

<div id="posOrderApp"
     class="pos-real-app"
     data-menu-url="{{ route('admin.orders.pos.menu-items') }}"
     data-client-url="{{ route('admin.orders.clients.search') }}"
     data-client-store-url="{{ route('admin.orders.clients.quick-store') }}"
     data-submit-url="{{ $isEdit ? route('admin.orders.update', $order) : route('admin.orders.store') }}"
     data-submit-method="{{ $isEdit ? 'PUT' : 'POST' }}"
     data-tax-rate="{{ (float) $setting->tax_rate }}"
     data-tax-label="{{ $setting->tax_label ?: 'VAT' }}"
     data-service-rate="{{ (float) $setting->service_charge }}"
     data-is-edit="{{ $isEdit ? '1' : '0' }}"
     data-initial-paid="{{ (float) old('paid_amount', $order->paid_amount ?? 0) }}">

    <div class="pos-screen-heading">
        <div>
            <span class="pos-screen-kicker">{{ $isEdit ? 'Update Transaction' : 'New Transaction' }}</span>
            <h1>{{ $isEdit ? 'Edit Order '.$order->order_number : 'Restaurant POS' }}</h1>
        </div>
        <div class="pos-heading-actions">
            <span class="source-badge source-{{ $isEdit ? $order->source : 'admin' }}">{{ ucfirst($isEdit ? $order->source : 'admin') }}</span>
            <a href="{{ route('admin.orders.index') }}" class="btn-admin-outline"><i class="bi bi-arrow-left"></i> Back</a>
        </div>
    </div>

    <div class="pos-real-layout">
        <section class="pos-catalog-area">
            <div class="pos-toolbar-card">
                <div class="pos-toolbar-grid">
                    <div class="form-group-admin mb-0 pos-branch-field">
                        <label class="form-label-admin">Branch</label>
                        @if(auth()->user()->canSeeAllBranches())
                            <select id="posBranch" class="form-control-admin">
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" @selected((int) old('branch_id', $order->branch_id ?? $selectedBranchId) === (int) $branch->id)>{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        @else
                            <input type="hidden" id="posBranch" value="{{ $selectedBranchId }}">
                            <div class="pos-static-field">{{ $branches->firstWhere('id', $selectedBranchId)?->name ?? auth()->user()->branch?->name }}</div>
                        @endif
                    </div>
                    <div class="form-group-admin mb-0">
                        <label class="form-label-admin">Category</label>
                        <select id="posCategory" class="form-control-admin">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group-admin mb-0 pos-search-field">
                        <label class="form-label-admin">Search Food</label>
                        <div class="pos-search-box"><i class="bi bi-search"></i><input type="text" id="posItemSearch" placeholder="Type item name..."></div>
                    </div>
                </div>
            </div>

            <div class="pos-products-shell">
                <div class="pos-section-heading">
                    <div><h2>Menu Items</h2><p>Click an item to choose size and add-ons.</p></div>
                    <span class="pos-count-pill" id="posProductCount">0 items</span>
                </div>
                <div id="posProducts" class="pos-product-grid">
                    <div class="pos-panel-loader"><span class="ajax-table-loader-spinner"></span><span>Loading menu items...</span></div>
                </div>
            </div>
        </section>

        <aside class="pos-checkout-area">
            <div class="pos-checkout-scroll">
                <section class="pos-checkout-block">
                    <div class="pos-block-title"><span><i class="bi bi-person"></i> Client</span></div>
                    <div class="pos-client-picker-row">
                        <select id="posClient" class="form-control-admin">
                            <option value="">Walk-in / Guest Customer</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}"
                                        data-name="{{ $client->name }}"
                                        data-phone="{{ $client->phone }}"
                                        data-email="{{ $client->email }}"
                                        data-address="{{ $client->address }}"
                                        @selected((int) old('client_id', $order->client_id ?? 0) === (int) $client->id)>
                                    {{ $client->name }} — {{ $client->phone }}
                                </option>
                            @endforeach
                        </select>
                        <button type="button" class="pos-add-client-btn" data-bs-toggle="modal" data-bs-target="#posClientModal" title="Add client"><i class="bi bi-plus-lg"></i></button>
                    </div>
                    <div class="pos-selected-client" id="selectedClientCard">
                        <span class="pos-client-avatar"><i class="bi bi-person"></i></span>
                        <div><strong id="selectedClientName">{{ $initialCustomerName }}</strong><small id="selectedClientMeta">{{ $initialCustomerPhone }}</small></div>
                    </div>
                    <input type="hidden" id="customerName" value="{{ $initialCustomerName }}">
                    <input type="hidden" id="customerPhone" value="{{ $initialCustomerPhone }}">
                    <input type="hidden" id="customerEmail" value="{{ old('customer_email', $order->customer_email ?? '') }}">
                    <input type="hidden" id="customerAddress" value="{{ old('customer_address', $order->customer_address ?? '') }}">
                </section>

                <section class="pos-checkout-block pos-cart-block">
                    <div class="pos-block-title">
                        <span><i class="bi bi-cart3"></i> Cart <b id="posCartCount">0</b></span>
                        <div class="pos-cart-actions" id="posCartBulkTools">
                            <label><input type="checkbox" id="selectAllCartItems"> All</label>
                            <button type="button" id="deleteSelectedCartItems" class="pos-cart-delete-selected"><i class="bi bi-trash"></i> Selected</button>
                        </div>
                    </div>
                    <div id="posCartItems" class="pos-cart-items"><div class="pos-cart-empty"><i class="bi bi-basket2"></i><span>No items added yet.</span></div></div>
                </section>

                <section class="pos-checkout-block">
                    <div class="pos-block-title"><span><i class="bi bi-sliders"></i> Order Details</span></div>
                    <div class="pos-compact-grid">
                        <div class="form-group-admin"><label class="form-label-admin">Order Type</label><select id="orderType" class="form-control-admin"><option value="dine_in" @selected(old('order_type', $order->order_type ?? 'dine_in') === 'dine_in')>Dine In</option><option value="delivery" @selected(old('order_type', $order->order_type ?? '') === 'delivery')>Delivery</option></select></div>
                        <div class="form-group-admin"><label class="form-label-admin">Status</label><select id="orderStatus" class="form-control-admin">@foreach(['pending','confirmed','processing','delivered','cancelled'] as $status)<option value="{{ $status }}" @selected(old('status', $order->status ?? 'pending') === $status)>{{ ucfirst($status) }}</option>@endforeach</select></div>
                    </div>
                    <div id="deliveryFields">
                        <div class="form-group-admin"><label class="form-label-admin">Delivery Address <span class="required">*</span></label><textarea id="deliveryAddress" class="form-control-admin" rows="2">{{ old('delivery_address', $order->delivery_address ?? '') }}</textarea></div>
                        <div class="form-group-admin"><label class="form-label-admin">Delivery Charge</label><input id="deliveryCharge" type="number" class="form-control-admin" value="{{ old('delivery_charge', $order->delivery_charge ?? 0) }}" min="0" step="0.01"></div>
                    </div>
                    <div class="form-group-admin">
                        <label class="form-label-admin">Discount Type</label>
                        <div class="pos-discount-radios">
                            <label><input type="radio" name="discount_type" value="fixed" @checked($initialDiscountType === 'fixed')> Fixed Amount</label>
                            <label><input type="radio" name="discount_type" value="percentage" @checked($initialDiscountType === 'percentage')> Percentage</label>
                        </div>
                    </div>
                    <div class="form-group-admin"><label class="form-label-admin" id="discountValueLabel">Discount Amount</label><div class="pos-input-affix"><span id="discountValueAffix">TK</span><input id="discountValue" type="number" class="form-control-admin" value="{{ $initialDiscountValue }}" min="0" step="0.01"></div></div>
                    <div class="form-group-admin mb-0"><label class="form-label-admin">Order Note</label><textarea id="orderNote" class="form-control-admin" rows="2" placeholder="Kitchen or delivery note...">{{ old('note', $order->note ?? '') }}</textarea></div>
                </section>

                <section class="pos-checkout-block">
                    <div class="pos-block-title"><span><i class="bi bi-wallet2"></i> Payment</span></div>
                    <div class="form-group-admin"><label class="form-label-admin">Payment Type</label><select id="paymentType" class="form-control-admin"><option value="cash" @selected(old('payment_type', $order->payment_type ?? 'cash') === 'cash')>Cash</option><option value="cash_on_delivery" @selected(old('payment_type', $order->payment_type ?? '') === 'cash_on_delivery')>Cash on Delivery</option><option value="mfs" @selected(old('payment_type', $order->payment_type ?? '') === 'mfs')>MFS</option><option value="bank" @selected(old('payment_type', $order->payment_type ?? '') === 'bank')>Bank</option><option value="split" @selected(old('payment_type', $order->payment_type ?? '') === 'split')>Split</option></select></div>
                    <div id="singleReferenceField" class="form-group-admin"><label class="form-label-admin">Reference Number <span class="required">*</span></label><input id="paymentReference" class="form-control-admin" value="{{ old('payment_reference', $order->payment_reference ?? '') }}"></div>
                    <div id="splitPaymentFields" class="split-payment-grid">
                        <div class="form-group-admin"><label class="form-label-admin">Cash</label><input id="splitCash" type="number" class="form-control-admin" value="{{ old('split_cash', $order->split_cash ?? 0) }}" min="0" step="0.01"></div>
                        <div class="form-group-admin"><label class="form-label-admin">MFS</label><input id="splitMfs" type="number" class="form-control-admin" value="{{ old('split_mfs', $order->split_mfs ?? 0) }}" min="0" step="0.01"></div>
                        <div class="form-group-admin"><label class="form-label-admin">Bank</label><input id="splitBank" type="number" class="form-control-admin" value="{{ old('split_bank', $order->split_bank ?? 0) }}" min="0" step="0.01"></div>
                        <div class="form-group-admin"><label class="form-label-admin">MFS Reference</label><input id="splitMfsReference" class="form-control-admin" value="{{ old('split_mfs_reference', $order->split_mfs_reference ?? '') }}"></div>
                        <div class="form-group-admin"><label class="form-label-admin">Bank Reference</label><input id="splitBankReference" class="form-control-admin" value="{{ old('split_bank_reference', $order->split_bank_reference ?? '') }}"></div>
                    </div>
                    <div class="pos-compact-grid pos-payment-amounts">
                        <div class="form-group-admin"><label class="form-label-admin">Pay Amount</label><input id="paidAmount" type="number" class="form-control-admin" value="{{ old('paid_amount', $order->paid_amount ?? 0) }}" min="0" step="0.01"></div>
                        <div class="form-group-admin"><label class="form-label-admin">Due Amount</label><input id="dueAmount" type="number" class="form-control-admin" value="{{ old('due_amount', $order->due_amount ?? 0) }}" readonly></div>
                    </div>
                </section>
            </div>

            <div class="pos-payment-summary">
                <div class="pos-summary-row"><span>Subtotal</span><strong id="summarySubtotal">TK 0.00</strong></div>
                <div class="pos-summary-row" id="summaryDiscountRow"><span id="summaryDiscountLabel">Discount</span><strong id="summaryDiscount">- TK 0.00</strong></div>
                <div class="pos-summary-row" id="summaryServiceRow"><span id="summaryServiceLabel">Service Charge</span><strong id="summaryService">TK 0.00</strong></div>
                <div class="pos-summary-row" id="summaryTaxRow"><span id="summaryTaxLabel">VAT</span><strong id="summaryTax">TK 0.00</strong></div>
                <div class="pos-summary-row" id="summaryDeliveryRow"><span>Delivery Charge</span><strong id="summaryDelivery">TK 0.00</strong></div>
                <div class="pos-summary-row pos-summary-total"><span>Grand Total</span><strong id="summaryGrandTotal">TK 0.00</strong></div>
                <div class="pos-summary-row pos-summary-paid"><span>Paid</span><strong id="summaryPaid">TK 0.00</strong></div>
                <div class="pos-summary-row pos-summary-due"><span>Due</span><strong id="summaryDue">TK 0.00</strong></div>
                <button type="button" class="pos-complete-order-btn" id="saveOrderBtn"><i class="bi bi-check2-circle"></i> {{ $isEdit ? 'Update Order' : 'Complete Order' }}</button>
            </div>
        </aside>
    </div>
</div>

<div class="modal fade" id="posItemModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered"><div class="modal-content admin-modal-content"><div class="modal-header"><div><h5 class="modal-title" id="modalItemName">Add Item</h5><div class="table-item-sub" id="modalItemCategory"></div></div><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body">
        <div class="form-group-admin"><label class="form-label-admin">Price / Size</label><select id="modalItemPrice" class="form-control-admin"></select></div>
        <div class="form-group-admin"><label class="form-label-admin">Global Add-Ons</label><div id="modalGlobalAddons" class="pos-addon-list"></div></div>
        <div class="form-group-admin"><label class="form-label-admin">Variation Add-Ons</label><div id="modalPriceAddons" class="pos-addon-list"></div></div>
        <div class="form-group-admin"><label class="form-label-admin">Quantity</label><input id="modalItemQuantity" type="number" class="form-control-admin" min="1" value="1"></div>
        <div class="form-group-admin mb-0"><label class="form-label-admin">Item Note</label><textarea id="modalItemNote" class="form-control-admin" rows="2" placeholder="Special instruction..."></textarea></div>
    </div><div class="modal-footer"><button type="button" class="btn-admin-outline" data-bs-dismiss="modal">Cancel</button><button type="button" class="btn-admin-primary" id="addModalItemBtn"><i class="bi bi-plus-lg"></i> Add to Cart</button></div></div></div>
</div>

<div class="modal fade" id="posClientModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg"><div class="modal-content admin-modal-content">
        <div class="modal-header"><div><h5 class="modal-title">Add New Client</h5><div class="table-item-sub">The client will be assigned to the selected POS branch.</div></div><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <form id="posClientCreateForm">
            <div class="modal-body">
                <div class="form-grid-two">
                    <div class="form-group-admin"><label class="form-label-admin">Name <span class="required">*</span></label><input name="name" class="form-control-admin" required></div>
                    <div class="form-group-admin"><label class="form-label-admin">Phone <span class="required">*</span></label><input name="phone" class="form-control-admin" required></div>
                    <div class="form-group-admin"><label class="form-label-admin">Email</label><input name="email" type="email" class="form-control-admin"></div>
                    <div class="form-group-admin"><label class="form-label-admin">Address</label><input name="address" class="form-control-admin"></div>
                </div>
                <div class="form-group-admin"><label class="form-label-admin">Notes</label><textarea name="notes" class="form-control-admin" rows="2"></textarea></div>
                <label class="form-check-admin mb-3"><input type="checkbox" name="can_login" value="1" id="quickClientCanLogin"> Allow this client to log in</label>
                <div id="quickClientPasswordFields" class="form-grid-two d-none">
                    <div class="form-group-admin"><label class="form-label-admin">Password <span class="required">*</span></label><input name="password" type="password" class="form-control-admin" minlength="8"></div>
                    <div class="form-group-admin"><label class="form-label-admin">Confirm Password <span class="required">*</span></label><input name="password_confirmation" type="password" class="form-control-admin" minlength="8"></div>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn-admin-outline" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn-admin-primary" id="quickClientSaveBtn"><i class="bi bi-person-plus"></i> Add Client</button></div>
        </form>
    </div></div>
</div>

@push('scripts')
<script>
(function () {
    'use strict';

    var root = document.getElementById('posOrderApp');
    if (!root) return;

    var token = document.querySelector('meta[name="csrf-token"]').content;
    var taxRate = parseFloat(root.dataset.taxRate) || 0;
    var taxLabel = root.dataset.taxLabel || 'VAT';
    var serviceRate = parseFloat(root.dataset.serviceRate) || 0;
    var isEdit = root.dataset.isEdit === '1';
    var state = {
        menuItems: [],
        cart: @json($initialItems),
        activeItem: null,
        paidTouched: isEdit,
        currentTotal: 0
    };
    var itemModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('posItemModal'));
    var clientModalElement = document.getElementById('posClientModal');
    var clientModal = clientModalElement ? bootstrap.Modal.getOrCreateInstance(clientModalElement) : null;
    var branch = document.getElementById('posBranch');
    var category = document.getElementById('posCategory');
    var search = document.getElementById('posItemSearch');
    var money = function (value) { return 'TK ' + (parseFloat(value) || 0).toFixed(2); };

    function branchId() { return branch.value; }
    function effectivePrice(price) { return parseFloat(price.effective_price ?? price.discount_price ?? price.price) || 0; }
    function escapeHtml(value) { var div = document.createElement('div'); div.textContent = value == null ? '' : String(value); return div.innerHTML; }
    function formatRate(value) { return Number(value).toFixed(2).replace(/\.00$/, '').replace(/(\.\d)0$/, '$1'); }
    function selectedDiscountType() { return document.querySelector('input[name="discount_type"]:checked')?.value || 'fixed'; }

    function loadProducts() {
        var url = new URL(root.dataset.menuUrl, window.location.origin);
        url.searchParams.set('branch_id', branchId());
        if (category.value) url.searchParams.set('category_id', category.value);
        if (search.value.trim()) url.searchParams.set('search', search.value.trim());
        document.getElementById('posProducts').innerHTML = '<div class="pos-panel-loader"><span class="ajax-table-loader-spinner"></span><span>Loading menu items...</span></div>';
        fetch(url, {headers: {'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest'}})
            .then(function (response) { if (!response.ok) throw new Error('Unable to load menu items.'); return response.json(); })
            .then(function (data) { state.menuItems = data.items || []; renderProducts(); })
            .catch(function (error) { document.getElementById('posProducts').innerHTML = '<div class="empty-state"><i class="bi bi-exclamation-circle"></i><h3>' + escapeHtml(error.message) + '</h3></div>'; });
    }

    function renderProducts() {
        var host = document.getElementById('posProducts');
        document.getElementById('posProductCount').textContent = state.menuItems.length + ' items';
        if (!state.menuItems.length) {
            host.innerHTML = '<div class="empty-state"><i class="bi bi-egg-fried"></i><h3>No menu items found</h3></div>';
            return;
        }
        host.innerHTML = state.menuItems.map(function (item) {
            var prices = item.prices || [];
            var min = prices.length ? Math.min.apply(null, prices.map(effectivePrice)) : 0;
            return '<button type="button" class="pos-product-card" data-menu-id="' + item.id + '">'
                + (item.image ? '<img src="' + item.image + '" alt="' + escapeHtml(item.name) + '">' : '<div class="pos-product-placeholder"><i class="bi bi-egg-fried"></i></div>')
                + '<div class="pos-product-body"><span class="pos-product-category">' + escapeHtml(item.category || 'Menu') + '</span><strong>' + escapeHtml(item.name) + '</strong><b>From ' + money(min) + '</b></div></button>';
        }).join('');
    }

    function openItem(item) {
        if (!item || !item.prices || !item.prices.length) {
            Swal.fire({icon: 'warning', title: 'No price is configured for this item'});
            return;
        }
        state.activeItem = item;
        document.getElementById('modalItemName').textContent = item.name;
        document.getElementById('modalItemCategory').textContent = [item.category, item.subcategory].filter(Boolean).join(' / ');
        document.getElementById('modalItemPrice').innerHTML = item.prices.map(function (price) {
            var regular = parseFloat(price.price) || 0;
            var effective = effectivePrice(price);
            return '<option value="' + price.id + '" data-price="' + effective + '" data-label="' + escapeHtml(price.size_label || 'Regular') + '">' + escapeHtml(price.size_label || 'Regular') + ' — ' + money(effective) + (effective < regular ? ' (Regular ' + money(regular) + ')' : '') + '</option>';
        }).join('');
        var globalAddons = document.getElementById('modalGlobalAddons');
        globalAddons.innerHTML = item.addons && item.addons.length
            ? item.addons.map(function (addon) { return '<label class="form-check-admin"><input type="checkbox" class="global-addon-checkbox" value="' + addon.id + '" data-name="' + escapeHtml(addon.name) + '" data-price="' + addon.price + '"> ' + escapeHtml(addon.name) + ' <span>+' + money(addon.price) + '</span></label>'; }).join('')
            : '<span class="form-help">No global add-ons available.</span>';
        renderModalPriceAddons();
        document.getElementById('modalItemQuantity').value = 1;
        document.getElementById('modalItemNote').value = '';
        itemModal.show();
    }

    function renderModalPriceAddons() {
        var host = document.getElementById('modalPriceAddons');
        var priceSelect = document.getElementById('modalItemPrice');
        if (!host || !priceSelect || !state.activeItem) return;
        var selectedPrice = (state.activeItem.prices || []).find(function (price) {
            return parseInt(price.id) === parseInt(priceSelect.value);
        });
        var variationAddons = selectedPrice && selectedPrice.variation_addons ? selectedPrice.variation_addons : [];
        host.innerHTML = variationAddons.length
            ? variationAddons.map(function (addon) { return '<label class="form-check-admin"><input type="checkbox" class="variation-addon-checkbox" value="' + addon.id + '" data-name="' + escapeHtml(addon.name) + '" data-price="' + addon.price + '"> ' + escapeHtml(addon.name) + ' <span>+' + money(addon.price) + '</span></label>'; }).join('')
            : '<span class="form-help">No add-ons for this size.</span>';
    }

    function addActiveItem() {
        var priceSelect = document.getElementById('modalItemPrice');
        var option = priceSelect.options[priceSelect.selectedIndex];
        if (!state.activeItem || !option) return;
        var globalAddons = Array.from(document.querySelectorAll('#modalGlobalAddons input:checked')).map(function (checkbox) {
            return {id: parseInt(checkbox.value), price_addon_id: null, source: 'global', name: checkbox.dataset.name, price: parseFloat(checkbox.dataset.price) || 0};
        });
        var variationAddons = Array.from(document.querySelectorAll('#modalPriceAddons input:checked')).map(function (checkbox) {
            return {id: null, price_addon_id: parseInt(checkbox.value), source: 'variation', name: checkbox.dataset.name, price: parseFloat(checkbox.dataset.price) || 0};
        });
        var addons = globalAddons.concat(variationAddons);
        var quantity = Math.max(parseInt(document.getElementById('modalItemQuantity').value) || 1, 1);
        var note = document.getElementById('modalItemNote').value.trim();
        var addonIds = globalAddons.map(function (addon) { return addon.id; }).sort(function (a, b) { return a - b; });
        var priceAddonIds = variationAddons.map(function (addon) { return addon.price_addon_id; }).sort(function (a, b) { return a - b; });
        var existing = state.cart.find(function (line) {
            return parseInt(line.menu_item_price_id) === parseInt(priceSelect.value)
                && JSON.stringify((line.addon_ids || []).slice().sort(function (a, b) { return a - b; })) === JSON.stringify(addonIds)
                && JSON.stringify((line.price_addon_ids || []).slice().sort(function (a, b) { return a - b; })) === JSON.stringify(priceAddonIds)
                && (line.note || '') === note;
        });
        if (existing) {
            existing.quantity += quantity;
        } else {
            state.cart.push({
                menu_item_id: state.activeItem.id,
                menu_item_price_id: parseInt(priceSelect.value),
                name: state.activeItem.name,
                size_label: option.dataset.label || 'Regular',
                unit_price: parseFloat(option.dataset.price) || 0,
                quantity: quantity,
                note: note,
                addon_ids: addonIds,
                price_addon_ids: priceAddonIds,
                addons: addons
            });
        }
        itemModal.hide();
        renderCart();
    }

    function renderCart() {
        var host = document.getElementById('posCartItems');
        var totalQuantity = state.cart.reduce(function (sum, item) { return sum + item.quantity; }, 0);
        document.getElementById('posCartCount').textContent = totalQuantity;
        document.getElementById('posCartBulkTools').classList.toggle('d-none', !state.cart.length);
        document.getElementById('selectAllCartItems').checked = false;
        if (!state.cart.length) {
            host.innerHTML = '<div class="pos-cart-empty"><i class="bi bi-basket2"></i><span>No items added yet.</span></div>';
            calculate();
            return;
        }
        host.innerHTML = state.cart.map(function (item, index) {
            var addonTotal = (item.addons || []).reduce(function (sum, addon) { return sum + parseFloat(addon.price || 0); }, 0);
            var addonText = item.addons && item.addons.length ? ' · ' + item.addons.map(function (addon) { return escapeHtml(addon.name); }).join(', ') : '';
            return '<div class="pos-cart-item">'
                + '<div class="pos-cart-select"><input type="checkbox" class="cart-line-checkbox" value="' + index + '" aria-label="Select cart item"></div>'
                + '<div class="pos-cart-item-content"><div class="pos-cart-item-top"><div><strong>' + escapeHtml(item.name) + '</strong><span>' + escapeHtml(item.size_label || 'Regular') + addonText + '</span></div><button type="button" class="pos-single-delete" data-cart-remove="' + index + '" title="Delete"><i class="bi bi-trash"></i></button></div>'
                + '<div class="pos-cart-item-bottom"><div class="pos-qty"><button type="button" data-cart-minus="' + index + '">−</button><span>' + item.quantity + '</span><button type="button" data-cart-plus="' + index + '">+</button></div><div class="pos-cart-line-price"><small>' + money(item.unit_price + addonTotal) + ' × ' + item.quantity + '</small><strong>' + money((item.unit_price + addonTotal) * item.quantity) + '</strong></div></div></div></div>';
        }).join('');
        calculate();
    }

    function discountData(subtotal) {
        var type = selectedDiscountType();
        var value = Math.max(parseFloat(document.getElementById('discountValue').value) || 0, 0);
        if (type === 'percentage') {
            value = Math.min(value, 100);
            return {type: type, value: value, amount: subtotal * (value / 100)};
        }
        return {type: type, value: value, amount: Math.min(value, subtotal)};
    }

    function calculate() {
        var subtotal = state.cart.reduce(function (sum, item) {
            var addons = (item.addons || []).reduce(function (addonSum, addon) { return addonSum + (parseFloat(addon.price) || 0); }, 0);
            return sum + (item.unit_price + addons) * item.quantity;
        }, 0);
        var discount = discountData(subtotal);
        var net = Math.max(subtotal - discount.amount, 0);
        var dineIn = document.getElementById('orderType').value === 'dine_in';
        var service = dineIn ? net * (serviceRate / 100) : 0;
        var tax = (net + service) * (taxRate / 100);
        var delivery = dineIn ? 0 : Math.max(parseFloat(document.getElementById('deliveryCharge').value) || 0, 0);
        var rawTotal = Math.max(net + service + tax + delivery, 0);
        var total = Math.ceil(Number(rawTotal.toFixed(2)));
        state.currentTotal = total;

        var paymentType = document.getElementById('paymentType').value;
        var paidInput = document.getElementById('paidAmount');
        var paid;
        if (paymentType === 'split') {
            paid = ['splitCash', 'splitMfs', 'splitBank'].reduce(function (sum, id) { return sum + (parseFloat(document.getElementById(id).value) || 0); }, 0);
            paidInput.value = Math.min(paid, total).toFixed(2);
        } else {
            if (!state.paidTouched) {
                paidInput.value = paymentType === 'cash_on_delivery' ? '0.00' : total.toFixed(2);
            }
            paid = Math.min(Math.max(parseFloat(paidInput.value) || 0, 0), total);
        }
        var due = Math.max(total - paid, 0);
        document.getElementById('dueAmount').value = due.toFixed(2);

        document.getElementById('summarySubtotal').textContent = money(subtotal);
        document.getElementById('summaryDiscount').textContent = '- ' + money(discount.amount);
        document.getElementById('summaryDiscountLabel').textContent = discount.type === 'percentage' ? 'Discount (' + formatRate(discount.value) + '%)' : 'Discount';
        document.getElementById('summaryService').textContent = money(service);
        document.getElementById('summaryServiceLabel').textContent = 'Service Charge (' + formatRate(serviceRate) + '%)';
        document.getElementById('summaryTax').textContent = money(tax);
        document.getElementById('summaryTaxLabel').textContent = taxLabel + ' (' + formatRate(taxRate) + '%)';
        document.getElementById('summaryDelivery').textContent = money(delivery);
        document.getElementById('summaryGrandTotal').textContent = money(total);
        document.getElementById('summaryPaid').textContent = money(paid);
        document.getElementById('summaryDue').textContent = money(due);
        document.getElementById('summaryDiscountRow').style.display = discount.amount > 0 ? 'flex' : 'none';
        document.getElementById('summaryServiceRow').style.display = service > 0 ? 'flex' : 'none';
        document.getElementById('summaryTaxRow').style.display = tax > 0 ? 'flex' : 'none';
        document.getElementById('summaryDeliveryRow').style.display = delivery > 0 ? 'flex' : 'none';
        return total;
    }

    function syncDiscountType() {
        var percentage = selectedDiscountType() === 'percentage';
        document.getElementById('discountValueLabel').textContent = percentage ? 'Discount Percentage' : 'Discount Amount';
        document.getElementById('discountValueAffix').textContent = percentage ? '%' : 'TK';
        document.getElementById('discountValue').max = percentage ? '100' : '';
        if (percentage && parseFloat(document.getElementById('discountValue').value) > 100) document.getElementById('discountValue').value = 100;
        calculate();
    }

    function syncOrderType() {
        var delivery = document.getElementById('orderType').value === 'delivery';
        document.getElementById('deliveryFields').style.display = delivery ? 'block' : 'none';
        if (!delivery) document.getElementById('deliveryCharge').value = 0;
        calculate();
    }

    function syncPayment(resetPaid) {
        var type = document.getElementById('paymentType').value;
        document.getElementById('singleReferenceField').style.display = ['mfs', 'bank'].includes(type) ? 'block' : 'none';
        document.getElementById('splitPaymentFields').style.display = type === 'split' ? 'grid' : 'none';
        document.getElementById('paidAmount').readOnly = type === 'split';
        if (resetPaid) state.paidTouched = false;
        calculate();
    }

    function updateSelectedClient(option, preserveGuest) {
        if (option && option.value) {
            document.getElementById('customerName').value = option.dataset.name || '';
            document.getElementById('customerPhone').value = option.dataset.phone || '';
            document.getElementById('customerEmail').value = option.dataset.email || '';
            document.getElementById('customerAddress').value = option.dataset.address || '';
        } else if (!preserveGuest) {
            document.getElementById('customerName').value = 'Walk-in Customer';
            document.getElementById('customerPhone').value = 'N/A';
            document.getElementById('customerEmail').value = '';
            document.getElementById('customerAddress').value = '';
        }
        document.getElementById('selectedClientName').textContent = document.getElementById('customerName').value || 'Walk-in Customer';
        var meta = [document.getElementById('customerPhone').value, document.getElementById('customerEmail').value].filter(Boolean).join(' · ');
        document.getElementById('selectedClientMeta').textContent = meta || 'Guest checkout';
    }

    function loadClients(selectedId) {
        var url = new URL(root.dataset.clientUrl, window.location.origin);
        url.searchParams.set('branch_id', branchId());
        return fetch(url, {headers: {'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest'}})
            .then(function (response) { if (!response.ok) throw new Error('Unable to load clients.'); return response.json(); })
            .then(function (data) {
                var select = document.getElementById('posClient');
                select.innerHTML = '<option value="">Walk-in / Guest Customer</option>' + (data.clients || []).map(function (client) {
                    return '<option value="' + client.id + '" data-name="' + escapeHtml(client.name) + '" data-phone="' + escapeHtml(client.phone) + '" data-email="' + escapeHtml(client.email || '') + '" data-address="' + escapeHtml(client.address || '') + '">' + escapeHtml(client.name) + ' — ' + escapeHtml(client.phone) + '</option>';
                }).join('');
                if (selectedId) select.value = String(selectedId);
                updateSelectedClient(select.options[select.selectedIndex], !selectedId && isEdit);
            });
    }

    function payload() {
        var discount = discountData(state.cart.reduce(function (sum, item) {
            return sum + (item.unit_price + (item.addons || []).reduce(function (s, addon) { return s + (parseFloat(addon.price) || 0); }, 0)) * item.quantity;
        }, 0));
        return {
            branch_id: parseInt(branchId()),
            client_id: document.getElementById('posClient').value || null,
            customer_name: document.getElementById('customerName').value.trim(),
            customer_phone: document.getElementById('customerPhone').value.trim(),
            customer_email: document.getElementById('customerEmail').value.trim() || null,
            customer_address: document.getElementById('customerAddress').value.trim() || null,
            order_type: document.getElementById('orderType').value,
            delivery_address: document.getElementById('deliveryAddress').value.trim() || null,
            delivery_charge: parseFloat(document.getElementById('deliveryCharge').value) || 0,
            discount_type: discount.type,
            discount_value: discount.value,
            payment_type: document.getElementById('paymentType').value,
            payment_reference: document.getElementById('paymentReference').value.trim() || null,
            split_cash: parseFloat(document.getElementById('splitCash').value) || 0,
            split_mfs: parseFloat(document.getElementById('splitMfs').value) || 0,
            split_bank: parseFloat(document.getElementById('splitBank').value) || 0,
            split_mfs_reference: document.getElementById('splitMfsReference').value.trim() || null,
            split_bank_reference: document.getElementById('splitBankReference').value.trim() || null,
            paid_amount: parseFloat(document.getElementById('paidAmount').value) || 0,
            status: document.getElementById('orderStatus').value,
            note: document.getElementById('orderNote').value.trim() || null,
            items: state.cart.map(function (item) {
                return {menu_item_id: item.menu_item_id, menu_item_price_id: item.menu_item_price_id, quantity: item.quantity, addon_ids: item.addon_ids || [], price_addon_ids: item.price_addon_ids || [], note: item.note || null};
            })
        };
    }

    function saveOrder() {
        var button = document.getElementById('saveOrderBtn');
        if (!state.cart.length) {
            Swal.fire({icon: 'warning', title: 'Add at least one food item'});
            return;
        }
        button.disabled = true;
        button.innerHTML = '<span class="ajax-table-loader-spinner pos-button-spinner"></span> Saving...';
        fetch(root.dataset.submitUrl, {
            method: root.dataset.submitMethod,
            headers: {'X-CSRF-TOKEN': token, 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest'},
            body: JSON.stringify(payload())
        })
            .then(async function (response) {
                var data = await response.json();
                if (!response.ok) {
                    var message = data.message || 'Validation failed.';
                    if (data.errors) message = Object.values(data.errors).flat().join('\n');
                    throw new Error(message);
                }
                return data;
            })
            .then(function (data) { Swal.fire({icon: 'success', title: data.message, showConfirmButton: false, timer: 1000}).then(function () { window.location.href = data.redirect; }); })
            .catch(function (error) { Swal.fire({icon: 'error', title: 'Could not save order', text: error.message}); })
            .finally(function () { button.disabled = false; button.innerHTML = '<i class="bi bi-check2-circle"></i> {{ $isEdit ? 'Update Order' : 'Complete Order' }}'; });
    }

    document.getElementById('posProducts').addEventListener('click', function (event) {
        var card = event.target.closest('[data-menu-id]');
        if (card) openItem(state.menuItems.find(function (item) { return item.id === parseInt(card.dataset.menuId); }));
    });
    document.getElementById('modalItemPrice').addEventListener('change', renderModalPriceAddons);
    document.getElementById('addModalItemBtn').addEventListener('click', addActiveItem);
    document.getElementById('posCartItems').addEventListener('click', function (event) {
        var remove = event.target.closest('[data-cart-remove]');
        var minus = event.target.closest('[data-cart-minus]');
        var plus = event.target.closest('[data-cart-plus]');
        if (remove) state.cart.splice(parseInt(remove.dataset.cartRemove), 1);
        else if (minus) { var minusIndex = parseInt(minus.dataset.cartMinus); state.cart[minusIndex].quantity = Math.max(1, state.cart[minusIndex].quantity - 1); }
        else if (plus) state.cart[parseInt(plus.dataset.cartPlus)].quantity++;
        if (remove || minus || plus) renderCart();
    });
    document.getElementById('selectAllCartItems').addEventListener('change', function () {
        document.querySelectorAll('.cart-line-checkbox').forEach(function (checkbox) { checkbox.checked = document.getElementById('selectAllCartItems').checked; });
    });
    document.getElementById('deleteSelectedCartItems').addEventListener('click', function () {
        var indexes = Array.from(document.querySelectorAll('.cart-line-checkbox:checked')).map(function (checkbox) { return parseInt(checkbox.value); }).sort(function (a, b) { return b - a; });
        if (!indexes.length) { Swal.fire({icon: 'warning', title: 'Select cart items first'}); return; }
        Swal.fire({icon: 'warning', title: 'Delete selected cart items?', showCancelButton: true, confirmButtonText: 'Delete'}).then(function (result) {
            if (!result.isConfirmed) return;
            indexes.forEach(function (index) { state.cart.splice(index, 1); });
            renderCart();
        });
    });
    document.getElementById('posClient').addEventListener('change', function () { updateSelectedClient(this.options[this.selectedIndex], false); });
    if (branch.tagName === 'SELECT') branch.addEventListener('change', function () { state.cart = []; renderCart(); loadProducts(); loadClients(); });
    category.addEventListener('change', loadProducts);
    var searchTimer;
    search.addEventListener('input', function () { clearTimeout(searchTimer); searchTimer = setTimeout(loadProducts, 250); });
    document.querySelectorAll('input[name="discount_type"]').forEach(function (radio) { radio.addEventListener('change', syncDiscountType); });
    ['discountValue', 'deliveryCharge'].forEach(function (id) { document.getElementById(id).addEventListener('input', calculate); });
    ['splitCash', 'splitMfs', 'splitBank'].forEach(function (id) { document.getElementById(id).addEventListener('input', calculate); });
    document.getElementById('paidAmount').addEventListener('input', function () { state.paidTouched = true; calculate(); });
    document.getElementById('orderType').addEventListener('change', syncOrderType);
    document.getElementById('paymentType').addEventListener('change', function () { syncPayment(true); });
    document.getElementById('saveOrderBtn').addEventListener('click', saveOrder);

    if (clientModalElement) {
        document.getElementById('quickClientCanLogin').addEventListener('change', function () {
            var fields = document.getElementById('quickClientPasswordFields');
            fields.classList.toggle('d-none', !this.checked);
            fields.querySelectorAll('input').forEach(function (input) { input.required = document.getElementById('quickClientCanLogin').checked; });
        });
        document.getElementById('posClientCreateForm').addEventListener('submit', function (event) {
            event.preventDefault();
            var form = event.currentTarget;
            var button = document.getElementById('quickClientSaveBtn');
            var data = Object.fromEntries(new FormData(form).entries());
            data.branch_id = parseInt(branchId());
            data.can_login = document.getElementById('quickClientCanLogin').checked ? 1 : 0;
            button.disabled = true;
            button.innerHTML = '<span class="ajax-table-loader-spinner pos-button-spinner"></span> Saving...';
            fetch(root.dataset.clientStoreUrl, {
                method: 'POST',
                headers: {'X-CSRF-TOKEN': token, 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest'},
                body: JSON.stringify(data)
            })
                .then(async function (response) {
                    var responseData = await response.json();
                    if (!response.ok) {
                        var message = responseData.message || 'Could not add client.';
                        if (responseData.errors) message = Object.values(responseData.errors).flat().join('\n');
                        throw new Error(message);
                    }
                    return responseData;
                })
                .then(function (responseData) {
                    var client = responseData.client;
                    var select = document.getElementById('posClient');
                    var option = new Option(client.name + ' — ' + client.phone, client.id, true, true);
                    option.dataset.name = client.name;
                    option.dataset.phone = client.phone;
                    option.dataset.email = client.email || '';
                    option.dataset.address = client.address || '';
                    select.appendChild(option);
                    updateSelectedClient(option, false);
                    form.reset();
                    document.getElementById('quickClientPasswordFields').classList.add('d-none');
                    document.getElementById('quickClientPasswordFields').querySelectorAll('input').forEach(function (input) { input.required = false; });
                    clientModal.hide();
                    Swal.fire({icon: 'success', title: responseData.message, showConfirmButton: false, timer: 900});
                })
                .catch(function (error) { Swal.fire({icon: 'error', title: 'Could not add client', text: error.message}); })
                .finally(function () { button.disabled = false; button.innerHTML = '<i class="bi bi-person-plus"></i> Add Client'; });
        });
    }

    updateSelectedClient(document.getElementById('posClient').options[document.getElementById('posClient').selectedIndex], isEdit && !document.getElementById('posClient').value);
    syncDiscountType();
    syncOrderType();
    syncPayment(false);
    renderCart();
    loadProducts();
})();
</script>
@endpush
