@extends('website.master.master')
@section('title', 'Dashboard | '.($siteSetting?->restaurant_name ?? 'Chomok Restaurant'))

@section('body')
@php
  $statusClass = fn (string $status) => match ($status) {
    'delivered' => 'is-delivered',
    'cancelled' => 'is-cancelled',
    default => 'is-processing',
  };
@endphp
<!-- User Dashboard -->
<section class="dashboard-section">
  <div class="dashboard-layout">

    <aside class="dashboard-sidebar">
      <div class="dashboard-user">
        <div class="dashboard-avatar" aria-hidden="true">{{ strtoupper(mb_substr($client->name, 0, 1)) }}</div>
        <h3 class="dashboard-username">{{ $client->name }}</h3>
        <p class="dashboard-email">{{ $client->email }}</p>
      </div>

      <ul class="dashboard-nav" role="tablist">
        <li>
          <button type="button" class="dashboard-nav-link active" id="overview-tab" data-bs-toggle="pill" data-bs-target="#overview-pane" role="tab" aria-controls="overview-pane" aria-selected="true">Overview</button>
        </li>
        <li>
          <button type="button" class="dashboard-nav-link" id="orders-tab" data-bs-toggle="pill" data-bs-target="#orders-pane" role="tab" aria-controls="orders-pane" aria-selected="false">Order History</button>
        </li>
        <li>
          <button type="button" class="dashboard-nav-link" id="account-tab" data-bs-toggle="pill" data-bs-target="#account-pane" role="tab" aria-controls="account-pane" aria-selected="false">Account Information</button>
        </li>
      </ul>

      <a href="{{ route('logout') }}" class="btn-logout" onclick="event.preventDefault(); document.getElementById('clientLogoutForm').submit();">Logout</a>
      <form id="clientLogoutForm" action="{{ route('logout') }}" method="post" class="d-none">@csrf</form>
    </aside>

    <div class="dashboard-content">
      <div class="tab-content">

        <!-- Overview -->
        <div class="tab-pane fade show active" id="overview-pane" role="tabpanel" aria-labelledby="overview-tab">
          <div class="dashboard-stats">
            <div class="dashboard-stat-card">
              <span class="stat-number">{{ $summary['total'] }}</span>
              <span class="stat-label">Total Orders</span>
            </div>
            <div class="dashboard-stat-card">
              <span class="stat-number">{{ $summary['pending'] }}</span>
              <span class="stat-label">Active Orders</span>
            </div>
            <div class="dashboard-stat-card">
              <span class="stat-number">TK {{ number_format($summary['spent'], 0) }}</span>
              <span class="stat-label">Total Spent</span>
            </div>
          </div>

          <div class="dashboard-section-header">
            <h2 class="dashboard-section-title">Recent Orders</h2>
            <button type="button" class="dashboard-view-all-btn" id="viewAllOrdersBtn">View All Orders</button>
          </div>

          <div class="orders-table-wrap">
            <table class="orders-table">
              <thead>
                <tr><th>Order ID</th><th>Date</th><th>Items</th><th>Total</th><th>Status</th></tr>
              </thead>
              <tbody>
                @forelse($orders->take(3) as $order)
                  <tr>
                    <td>{{ $order->order_number }}</td>
                    <td>{{ $order->created_at?->format('d M Y') }}</td>
                    <td>
                      @foreach($order->items->take(2) as $item)
                        <div class="mb-1">
                          <strong>{{ $item->item_name }}</strong>
                          @if($item->addons->isNotEmpty())
                            @foreach($item->addons as $addon)
                              @php($addonDescription = $addon->description ?: $addon->menuItemPriceAddon?->description ?: $addon->addon?->description)
                              <small class="d-block">+ {{ $addon->addon_name }}@if(filled($addonDescription)) — <span class="text-muted">{{ $addonDescription }}</span>@endif</small>
                            @endforeach
                          @endif
                        </div>
                      @endforeach
                      @if($order->items->count() > 2)<small>+{{ $order->items->count() - 2 }} more item(s)</small>@endif
                    </td>
                    <td>TK {{ number_format((float)$order->grand_total, 0) }}</td>
                    <td><span class="order-status {{ $statusClass($order->status) }}">{{ ucfirst($order->status) }}</span></td>
                  </tr>
                @empty
                  <tr><td colspan="5">No orders found.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

        <!-- Order History -->
        <div class="tab-pane fade" id="orders-pane" role="tabpanel" aria-labelledby="orders-tab">
          <h2 class="dashboard-section-title">Order History</h2>
          <div class="orders-table-wrap">
            <table class="orders-table">
              <thead>
                <tr><th>Order ID</th><th>Date</th><th>Items</th><th>Total</th><th>Status</th><th></th></tr>
              </thead>
              <tbody>
                @forelse($orders as $order)
                  <tr>
                    <td>{{ $order->order_number }}</td>
                    <td>{{ $order->created_at?->format('d M Y') }}</td>
                    <td>
                      @foreach($order->items->take(2) as $item)
                        <div class="mb-1">
                          <strong>{{ $item->item_name }}</strong>
                          @if($item->addons->isNotEmpty())
                            @foreach($item->addons as $addon)
                              @php($addonDescription = $addon->description ?: $addon->menuItemPriceAddon?->description ?: $addon->addon?->description)
                              <small class="d-block">+ {{ $addon->addon_name }}@if(filled($addonDescription)) — <span class="text-muted">{{ $addonDescription }}</span>@endif</small>
                            @endforeach
                          @endif
                        </div>
                      @endforeach
                      @if($order->items->count() > 2)<small>+{{ $order->items->count() - 2 }} more item(s)</small>@endif
                    </td>
                    <td>TK {{ number_format((float)$order->grand_total, 0) }}</td>
                    <td><span class="order-status {{ $statusClass($order->status) }}">{{ ucfirst($order->status) }}</span></td>
                    <td><button type="button" class="order-view-btn" data-order-view data-order-url="{{ route('client.view-order', $order->id) }}">View</button></td>
                  </tr>
                @empty
                  <tr><td colspan="6">No orders found.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
          @if($orders->hasPages())<div class="mt-3">{{ $orders->links() }}</div>@endif
        </div>

        <!-- Account Information -->
        <div class="tab-pane fade" id="account-pane" role="tabpanel" aria-labelledby="account-tab">
          <h2 class="dashboard-section-title">Account Information</h2>
          <form class="contact-form dashboard-account-form" action="{{ route('client.update-profile') }}" method="post">
            @csrf
            <div class="form-row">
              <div class="form-group"><label for="account-name">Full Name</label><input type="text" id="account-name" name="name" value="{{ old('name', $client->name) }}" required></div>
              <div class="form-group"><label for="account-email">Email Address</label><input type="email" id="account-email" name="email" value="{{ old('email', $client->email) }}" required></div>
            </div>
            <div class="form-row">
              <div class="form-group"><label for="account-phone">Phone Number</label><input type="tel" id="account-phone" name="phone" value="{{ old('phone', $client->phone) }}" required></div>
              <div class="form-group"><label for="account-address">Delivery Address</label><input type="text" id="account-address" name="address" value="{{ old('address', $client->address) }}"></div>
            </div>
            <button type="submit" class="btn-wc-hero">Save Changes</button>
          </form>
        </div>

      </div>
    </div>
  </div>
</section>

<div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="orderDetailsModalLabel">Order Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="orderDetailsModalBody">
        <div class="text-center py-5">Loading order details...</div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script>
document.getElementById('viewAllOrdersBtn')?.addEventListener('click', function () {
  document.getElementById('orders-tab')?.click();
});

(function () {
  const modalEl = document.getElementById('orderDetailsModal');
  const modalBody = document.getElementById('orderDetailsModalBody');
  const modalTitle = document.getElementById('orderDetailsModalLabel');
  const modal = modalEl ? bootstrap.Modal.getOrCreateInstance(modalEl) : null;

  document.addEventListener('click', async function (event) {
    const button = event.target.closest('[data-order-view]');
    if (!button || !modal || !modalBody) return;
    event.preventDefault();

    modalTitle.textContent = 'Order Details';
    modalBody.innerHTML = '<div class="text-center py-5">Loading order details...</div>';
    modal.show();

    try {
      const response = await fetch(button.dataset.orderUrl, {
        headers: {'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest'},
        cache: 'no-store'
      });
      if (response.status === 401 || response.status === 419) {
        window.location.href = @json(route('client.login'));
        return;
      }
      if (!response.ok) throw new Error('Unable to load order details.');
      const data = await response.json();
      modalTitle.textContent = data.title || 'Order Details';
      modalBody.innerHTML = data.html || '<div class="text-center py-5">Order details are unavailable.</div>';
    } catch (error) {
      modalBody.innerHTML = '<div class="text-center py-5 text-danger">'+error.message+'</div>';
    }
  });
})();
</script>
@endsection
