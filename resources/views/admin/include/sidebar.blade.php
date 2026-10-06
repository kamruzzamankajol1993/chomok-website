<aside class="admin-sidebar">
    <div class="sidebar-brand">
        @if($restaurantSetting?->logo)
            <img src="{{ asset($restaurantSetting->logo) }}" alt="Logo" class="sidebar-brand-image">
        @else
            <div class="sidebar-brand-logo">{{ strtoupper(substr($restaurantSetting?->restaurant_name ?? 'C', 0, 1)) }}</div>
        @endif
        <div class="sidebar-brand-text">{{ $restaurantSetting?->restaurant_name ?? 'Chomok' }}<br><small>Admin Panel</small></div>
    </div>

    <ul class="sidebar-nav">
        @can('dashboard.view')
            <li><a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a></li>
        @endcan

        @if(auth()->user()->canAny(['category.view', 'subcategory.view', 'menu-item.view', 'addon.view']))
            <li class="sidebar-nav-label">Catalog</li>
            @can('category.view')
                <li><a href="{{ route('admin.categories.index') }}" class="sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"><i class="bi bi-tags-fill"></i> Categories</a></li>
            @endcan
            @can('subcategory.view')
                <li><a href="{{ route('admin.subcategories.index') }}" class="sidebar-link {{ request()->routeIs('admin.subcategories.*') ? 'active' : '' }}"><i class="bi bi-diagram-3-fill"></i> Subcategories</a></li>
            @endcan
            @can('menu-item.view')
                <li><a href="{{ route('admin.menu-items.index') }}" class="sidebar-link {{ request()->routeIs('admin.menu-items.*') ? 'active' : '' }}"><i class="bi bi-egg-fried"></i> Menu Items</a></li>
            @endcan
            @can('addon.view')
                <li><a href="{{ route('admin.addons.index') }}" class="sidebar-link {{ request()->routeIs('admin.addons.*') ? 'active' : '' }}"><i class="bi bi-magic"></i> Add-Ons</a></li>
            @endcan
        @endif

        @if(auth()->user()->canAny(['order.view', 'client.view']))
            <li class="sidebar-nav-label">Sales</li>
            @can('order.view')
                <li><a href="{{ route('admin.orders.index') }}" class="sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"><i class="bi bi-receipt"></i> Orders <span class="badge-count d-none" data-website-order-count>0</span></a></li>
            @endcan
            @can('client.view')
                <li><a href="{{ route('admin.clients.index') }}" class="sidebar-link {{ request()->routeIs('admin.clients.*') ? 'active' : '' }}"><i class="bi bi-people-fill"></i> Clients</a></li>
            @endcan
        @endif

        @if(auth()->user()->canAny(['branch.view', 'user.view']))
            <li class="sidebar-nav-label">Management</li>
            @can('branch.view')
                <li><a href="{{ route('admin.branches.index') }}" class="sidebar-link {{ request()->routeIs('admin.branches.*') ? 'active' : '' }}"><i class="bi bi-shop"></i> Branches</a></li>
            @endcan
            @can('user.view')
                <li><a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"><i class="bi bi-people-fill"></i> Users</a></li>
            @endcan
        @endif

        @if(auth()->user()->canAny(['role.view', 'permission.view']))
            <li class="sidebar-nav-label">Access Control</li>
            @can('role.view')
                <li><a href="{{ route('admin.roles.index') }}" class="sidebar-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}"><i class="bi bi-person-badge-fill"></i> Roles</a></li>
            @endcan
            @can('permission.view')
                <li><a href="{{ route('admin.permissions.index') }}" class="sidebar-link {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}"><i class="bi bi-shield-lock-fill"></i> Permissions</a></li>
            @endcan
        @endif


        @if(auth()->user()->canAny(['website-content.view', 'contact-query.view']))
            <li class="sidebar-nav-label">Website Content</li>
            @can('website-content.view')
                <li><a href="{{ route('admin.website.home.index') }}" class="sidebar-link {{ request()->routeIs('admin.website.home.*') ? 'active' : '' }}"><i class="bi bi-layout-text-window"></i> Homepage</a></li>
                <li><a href="{{ route('admin.website.about.edit') }}" class="sidebar-link {{ request()->routeIs('admin.website.about.*') ? 'active' : '' }}"><i class="bi bi-file-text"></i> About Page</a></li>
                <li><a href="{{ route('admin.website.shop.edit') }}" class="sidebar-link {{ request()->routeIs('admin.website.shop.*') ? 'active' : '' }}"><i class="bi bi-shop"></i> Shop Page</a></li>
                <li><a href="{{ route('admin.website.contact.edit') }}" class="sidebar-link {{ request()->routeIs('admin.website.contact.*') ? 'active' : '' }}"><i class="bi bi-telephone"></i> Contact Page</a></li>
                <li><a href="{{ route('admin.website.content.edit') }}" class="sidebar-link {{ request()->routeIs('admin.website.content.*') ? 'active' : '' }}"><i class="bi bi-file-earmark-richtext"></i> Website Content</a></li>
            @endcan
            @can('contact-query.view')
                <li><a href="{{ route('admin.contact-queries.index') }}" class="sidebar-link {{ request()->routeIs('admin.contact-queries.*') ? 'active' : '' }}"><i class="bi bi-envelope"></i> Contact Queries @if(($contactQueryNewCount ?? 0) > 0)<span class="badge-count">{{ ($contactQueryNewCount ?? 0) > 99 ? '99+' : $contactQueryNewCount }}</span>@endif</a></li>
            @endcan
        @endif

        <li class="sidebar-nav-label">Account</li>
        @can('setting.view')
            <li><a href="{{ route('admin.settings.edit') }}" class="sidebar-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}"><i class="bi bi-gear-fill"></i> Settings</a></li>
        @endcan
        <li>
            <form action="{{ route('logout') }}" method="post">
                @csrf
                <button type="submit" class="sidebar-link sidebar-logout-button"><i class="bi bi-box-arrow-right"></i> Logout</button>
            </form>
        </li>
    </ul>
</aside>
