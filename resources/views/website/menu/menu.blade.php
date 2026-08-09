@extends('website.master.master')
@section('title', 'Menu | '.($siteSetting?->restaurant_name ?? 'Chomok Restaurant'))
@section('meta_description', 'Browse the latest menu and order online from '.($siteSetting?->restaurant_name ?? 'Chomok').'.')

@section('body')
<section class="menu-page-section">
  <div class="menu-section-head"><span class="badge-text">Full Menu</span><h1 class="menu-section-title">Browse Our Menu</h1></div>
  <div class="menu-page-layout">
    <aside class="menu-sidebar">
      <ul class="menu-category-list">
        <li><button type="button" class="menu-cat-btn {{ $category === 'all' ? 'active' : '' }}" data-menu-filter="all">All</button></li>
        @foreach($categories as $cat)
          <li><button type="button" class="menu-cat-btn {{ $category === $cat->slug ? 'active' : '' }}" data-menu-filter="{{ $cat->slug }}">{{ $cat->name }}</button></li>
        @endforeach
      </ul>
    </aside>

    <div class="offcanvas offcanvas-end category-offcanvas" tabindex="-1" id="categoryOffcanvas" aria-labelledby="categoryOffcanvasLabel">
      <div class="offcanvas-header"><h5 class="offcanvas-title" id="categoryOffcanvasLabel">Categories</h5><button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button></div>
      <div class="offcanvas-body">
        <ul class="menu-category-list category-offcanvas-list">
          <li><button type="button" class="menu-cat-btn {{ $category === 'all' ? 'active' : '' }}" data-menu-filter="all">All</button></li>
          @foreach($categories as $cat)
            <li><button type="button" class="menu-cat-btn {{ $category === $cat->slug ? 'active' : '' }}" data-menu-filter="{{ $cat->slug }}">{{ $cat->name }}</button></li>
          @endforeach
        </ul>
      </div>
    </div>

    <div class="menu-page-content">
      <div class="menu-grid-2" id="ajaxMenuGrid">
        @include('website.menu.partials.cards', ['items' => $items])
      </div>
      <div id="menuAjaxLoader" class="text-center py-4 d-none">Loading...</div>
      <div id="menuScrollSentinel" data-next-page="{{ $items->hasMorePages() ? $items->currentPage()+1 : '' }}"></div>
      <div id="menuEmpty" class="text-center py-5 {{ $items->count() ? 'd-none' : '' }}">No food item found.</div>
    </div>
  </div>
</section>
@include('website.include.cta')
@endsection

@section('scripts')
<script>
(function () {
  'use strict';
  const grid = document.getElementById('ajaxMenuGrid');
  const loader = document.getElementById('menuAjaxLoader');
  const sentinel = document.getElementById('menuScrollSentinel');
  const empty = document.getElementById('menuEmpty');
  let category = @json($category ?: 'all');
  let loading = false;

  async function loadPage(page, replace) {
    if (loading || !page) return;
    loading = true; loader.classList.remove('d-none');
    try {
      const url = new URL(@json(route('menu.index')), window.location.origin);
      url.searchParams.set('category', category);
      url.searchParams.set('page', page);
      const response = await fetch(url.toString(), {headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'}, cache:'no-store'});
      if (!response.ok) throw new Error('Unable to load menu.');
      const data = await response.json();
      if (replace) grid.innerHTML = data.html; else grid.insertAdjacentHTML('beforeend', data.html);
      sentinel.dataset.nextPage = data.next_page || '';
      empty.classList.toggle('d-none', grid.children.length > 0);
      if (replace) {
        const clean = new URL(@json(route('menu.index')), window.location.origin);
        if (category !== 'all') clean.searchParams.set('category', category);
        history.replaceState({}, '', clean.toString());
      }
    } catch (error) {
      Swal.fire({icon:'error', title:'Menu error', text:error.message});
    } finally { loading = false; loader.classList.add('d-none'); }
  }

  document.addEventListener('click', function (event) {
    const button = event.target.closest('[data-menu-filter]');
    if (!button) return;
    category = button.dataset.menuFilter || 'all';
    document.querySelectorAll('[data-menu-filter]').forEach(btn => btn.classList.toggle('active', btn.dataset.menuFilter === category));
    sentinel.dataset.nextPage = '';
    loadPage(1, true);
    const offcanvas = document.getElementById('categoryOffcanvas');
    if (offcanvas?.classList.contains('show')) bootstrap.Offcanvas.getInstance(offcanvas)?.hide();
  });

  const observer = new IntersectionObserver(entries => {
    if (entries[0].isIntersecting && sentinel.dataset.nextPage) loadPage(Number(sentinel.dataset.nextPage), false);
  }, {rootMargin:'350px'});
  observer.observe(sentinel);
})();
</script>
@endsection
