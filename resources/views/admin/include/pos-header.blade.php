<header class="pos-app-header">
    <div class="pos-app-brand">
        <span class="pos-app-brand-icon">
            @if($restaurantSetting?->icon)
                <img src="{{ asset($restaurantSetting->icon) }}" alt="{{ $restaurantSetting->restaurant_name }}">
            @elseif($restaurantSetting?->logo)
                <img src="{{ asset($restaurantSetting->logo) }}" alt="{{ $restaurantSetting->restaurant_name }}">
            @else
                <i class="bi bi-shop"></i>
            @endif
        </span>
        <div>
            <strong>{{ $restaurantSetting?->restaurant_name ?? config('app.name', 'Restaurant') }}</strong>
            <small>Point of Sale</small>
        </div>
    </div>

    <nav class="pos-app-nav" aria-label="POS navigation">
        <a href="{{ route('admin.dashboard') }}" class="pos-nav-link"><i class="bi bi-grid"></i><span>Dashboard</span></a>
        @can('order.view')
            <a href="{{ route('admin.orders.index') }}" class="pos-nav-link"><i class="bi bi-receipt"></i><span>Order List</span></a>
        @endcan
        @can('client.view')
            <a href="{{ route('admin.clients.index') }}" class="pos-nav-link"><i class="bi bi-people"></i><span>Client List</span></a>
        @endcan
        <form action="{{ route('logout') }}" method="post" class="m-0">
            @csrf
            <button type="submit" class="pos-nav-link pos-nav-logout"><i class="bi bi-box-arrow-right"></i><span>Logout</span></button>
        </form>
    </nav>
</header>
