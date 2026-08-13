@foreach($items as $item)
  @php($firstPrice = $item->prices->first())
  <div class="food-card-v" data-category="{{ $item->category?->slug }}" data-menu-card>
    <a href="{{ route('menu.show', $item) }}" class="food-card-v-img">
      @if($item->mainImage?->image)
        <img src="{{ $adminAssetUrl($item->mainImage->image) }}" alt="{{ $item->name }}" class="menu-item-img">
      @else
        <img src="{{ asset('public/website/assets/images/food-placeholder.jpg') }}" alt="{{ $item->name }}" class="menu-item-img">
      @endif
    </a>
    <h3 class="menu-item-name">{{ $item->name }}</h3>
    <div class="menu-item-prices">
      @foreach($item->prices as $price)
        <label class="price-pill">
          <input type="radio" name="menu_price_{{ $item->id }}" value="{{ $price->id }}" data-price-for="{{ $item->id }}" class="price-pill-input" @checked($loop->first)>
          <em>{{ $price->size_label ?: 'Regular' }}</em>TK {{ rtrim(rtrim(number_format((float)$price->effective_price, 2, '.', ''), '0'), '.') }}
        </label>
      @endforeach
    </div>
    <button type="button" class="btn-add-cart" data-add-cart data-menu-item-id="{{ $item->id }}" data-default-price-id="{{ $firstPrice?->id }}" data-has-addons="{{ ($item->addons->isNotEmpty() || $item->prices->contains(fn ($price) => $price->variationAddons->isNotEmpty())) ? '1' : '0' }}" data-detail-url="{{ route('menu.show', $item) }}">Add to Cart</button>
  </div>
@endforeach
