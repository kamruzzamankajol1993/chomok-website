<header class="admin-topbar">
    <div class="d-flex align-items-center gap-3">
        <button type="button" class="sidebar-toggle-btn" aria-label="Toggle sidebar"><i class="bi bi-list"></i></button>
        <div class="topbar-global-search d-none d-md-block position-relative" data-global-search-wrap>
            <div class="topbar-search">
                <i class="bi bi-search"></i>
                <input type="search" placeholder="Search orders, items, clients..." autocomplete="off" data-global-search data-search-url="{{ route('admin.search') }}">
                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true" data-global-search-loader></span>
            </div>
            <div class="global-search-results d-none" data-global-search-results></div>
        </div>
    </div>

    <div class="topbar-right">
        @can('setting.edit')
            <form action="{{ route('admin.clear-cache') }}" method="post" class="clear-cache-form m-0">
                @csrf
                <button type="submit" class="topbar-cache-btn" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Clear application cache">
                    <i class="bi bi-arrow-clockwise"></i>
                    <span>Clear Cache</span>
                </button>
            </form>
        @endcan

        @can('order.view')
            <button type="button" class="topbar-icon-btn" aria-label="Website order notifications" data-order-notification-bell>
                <i class="bi bi-bell-fill"></i>
                <span class="topbar-order-count d-none" data-website-order-count>0</span>
            </button>
        @else
            <button type="button" class="topbar-icon-btn" aria-label="Notifications"><i class="bi bi-bell-fill"></i></button>
        @endcan
        <div class="dropdown">
            <div class="topbar-profile" data-bs-toggle="dropdown" aria-expanded="false">
                @if(auth()->user()->image)
                    <img src="{{ asset(auth()->user()->image) }}" alt="{{ auth()->user()->name }}" class="topbar-profile-avatar profile-image">
                @else
                    <div class="topbar-profile-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                @endif
                <div>
                    <div class="topbar-profile-name">{{ auth()->user()->name }}</div>
                    <div class="topbar-profile-role">{{ auth()->user()->getRoleNames()->first() ?? 'User' }}</div>
                </div>
                <i class="bi bi-chevron-down" style="font-size:.75rem;color:var(--admin-text-muted);"></i>
            </div>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><span class="dropdown-item-text small text-muted">{{ auth()->user()->email }}</span></li>
                <li><a class="dropdown-item" href="{{ route('admin.profile.edit') }}"><i class="bi bi-person-circle me-2"></i>Profile</a></li>
                @can('setting.view')
                    <li><a class="dropdown-item" href="{{ route('admin.settings.edit') }}"><i class="bi bi-gear me-2"></i>Settings</a></li>
                @endcan
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="{{ route('logout') }}" method="post">@csrf
                        <button class="dropdown-item" type="submit"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>

<style>
.topbar-global-search{width:min(420px,38vw)}
.topbar-global-search .topbar-search{width:100%}
.global-search-results{position:absolute;top:calc(100% + 8px);left:0;width:min(520px,80vw);max-height:430px;overflow-y:auto;background:#fff;border:1px solid rgba(15,23,42,.12);border-radius:12px;box-shadow:0 18px 45px rgba(15,23,42,.16);z-index:1080;padding:6px}
.global-search-item{display:flex;align-items:center;gap:10px;padding:10px 11px;border-radius:9px;color:var(--admin-text,#1f2937);text-decoration:none}
.global-search-item:hover{background:#f6f7f9;color:var(--admin-text,#1f2937)}
.global-search-icon{width:34px;height:34px;display:flex;align-items:center;justify-content:center;border-radius:9px;background:#f1f3f5;flex:0 0 auto}
.global-search-copy{min-width:0}.global-search-title{font-weight:600;font-size:.88rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.global-search-meta{font-size:.75rem;color:var(--admin-text-muted,#6b7280);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.global-search-type{font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:var(--admin-text-muted,#6b7280)}
.global-search-empty{padding:20px 14px;text-align:center;color:var(--admin-text-muted,#6b7280);font-size:.85rem}
</style>

<script>
(function () {
    var wrap = document.querySelector('[data-global-search-wrap]');
    if (!wrap) return;
    var input = wrap.querySelector('[data-global-search]');
    var panel = wrap.querySelector('[data-global-search-results]');
    var loader = wrap.querySelector('[data-global-search-loader]');
    var timer = null, controller = null;

    function escapeHtml(value) {
        return String(value == null ? '' : value).replace(/[&<>'"]/g, function (char) {
            return {'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#039;','"':'&quot;'}[char];
        });
    }

    function hide() { panel.classList.add('d-none'); panel.innerHTML = ''; }
    function showEmpty(text) { panel.innerHTML = '<div class="global-search-empty">'+escapeHtml(text)+'</div>'; panel.classList.remove('d-none'); }

    function render(items) {
        if (!items.length) { showEmpty('No matching records found.'); return; }
        panel.innerHTML = items.map(function (item) {
            return '<a class="global-search-item" href="'+escapeHtml(item.url)+'">'
                + '<span class="global-search-icon"><i class="bi '+escapeHtml(item.icon || 'bi-search')+'"></i></span>'
                + '<span class="global-search-copy flex-grow-1"><span class="global-search-type">'+escapeHtml(item.type)+'</span><span class="global-search-title d-block">'+escapeHtml(item.title)+'</span><span class="global-search-meta d-block">'+escapeHtml(item.subtitle || '')+'</span></span>'
                + '<i class="bi bi-chevron-right small text-muted"></i></a>';
        }).join('');
        panel.classList.remove('d-none');
    }

    input.addEventListener('input', function () {
        clearTimeout(timer);
        var q = input.value.trim();
        if (q.length < 2) { if (controller) controller.abort(); hide(); return; }
        timer = setTimeout(function () {
            if (controller) controller.abort();
            controller = new AbortController();
            loader.classList.remove('d-none');
            fetch(input.dataset.searchUrl + '?q=' + encodeURIComponent(q), {headers:{'Accept':'application/json'}, signal: controller.signal})
                .then(function (response) { if (!response.ok) throw new Error('Search failed'); return response.json(); })
                .then(function (data) { render(Array.isArray(data.results) ? data.results : []); })
                .catch(function (error) { if (error.name !== 'AbortError') showEmpty('Search could not be loaded.'); })
                .finally(function () { loader.classList.add('d-none'); });
        }, 250);
    });

    input.addEventListener('focus', function () { if (input.value.trim().length >= 2 && panel.innerHTML) panel.classList.remove('d-none'); });
    document.addEventListener('click', function (event) { if (!wrap.contains(event.target)) hide(); });
    document.addEventListener('keydown', function (event) { if (event.key === 'Escape') { hide(); input.blur(); } });
})();
</script>
